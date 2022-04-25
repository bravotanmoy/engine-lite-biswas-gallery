<?php

namespace Elab\Lite;

use Elab\Lite\System\Repository;
use Elab\Lite\Helpers\Inflector;
use Elab\Lite\Services\Database;

class Engine
{
    public static $namespaces = [
        "lite" => "Elab\\Lite\\"
    ];

    public static $db_config = false;

    public static function run()
    {
        $_SERVER['REQUEST_URI'] = preg_replace('@^/cache/galleries/(.*)$@', '/res/tr_galleries/$1', $_SERVER['REQUEST_URI']);

        $path_info = self::read_path_info();

        // jeigu kreipesi i neegzistuojanti faila egzistuojanciame kataloge, grazinam 404
        if (!empty($path_info[0]) && file_exists($path_info[0]) && is_dir($path_info[0])) {
            header("HTTP/1.0 404 Not Found");
            die(
                "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">".
                "<html><head>".
                "<title>404 Not Found</title>".
                "</head><body>".
                "<h1>Not Found</h1>".
                "<p>The requested URL was not found on this server.</p>".
                "</body></html>"
            );
        }

        //		spl_autoload_register('Engine::autoloader');

        switch (@$path_info[0]) {
            case 'res':
                $resource = new System\Resource();
                $resource->run();
                break;

            default:
                $frontend = new System\Frontend();
                $frontend->run();
        }
    }

    public static function add_message($path, $type, $msg, $append = false)
    {
        $msg_before = '';
        if (!is_array($msg) && $append && !empty($_SESSION[$path]['messages'][$type])) {
            $msg_before = $_SESSION[$path]['messages'][$type] . " ";
        }
        $_SESSION[$path]['messages'][$type] = $msg_before ? $msg_before . $msg : $msg;
    }

    public static function load_controller($controller_type, $name = null, $parent = null, $config = array())
    {
        if (empty($name)) {
            $controllerPath = str_replace(self::$namespaces, "", $controller_type);
        } else {
            if ($controller_type == 'entity') {
                $controllerName = Inflector::camelize($name);
            } else {
                $controllerName = Inflector::camelize("$name $controller_type controller");
            }
            $controllerPath = "Controllers\\" . ucfirst($controller_type) . "\\" . $controllerName;
        }
        if (class_exists(self::$namespaces['lite'] . $controllerPath)) {
            $existing_class = self::$namespaces['lite'] . $controllerPath;
        } else {
            $existing_class = self::$namespaces['lite'] . "System\\" . Inflector::camelize(" $controller_type controller");
        }
        try {
            $obj = new $existing_class($name, $parent, $config);
        } catch (Exception $e) {
            $obj = null;
        }
        return $obj;
    }

    public static function load_entity_controller($name, $parent = null, $config = array())
    {
        $obj = self::load_controller('entity', $name, $parent, $config);
        return $obj;
    }

    public static function get_array_var($path, &$array)
    {
        $path = explode('/', $path);
        $config = &$array;
        foreach ($path as $key) {
            if ($key || is_numeric($key)) {
                if (!is_array($config) || !isset($config[$key])) {
                    return false;
                }
                $config = &$config[$key];
            }
        }
        return $config;
    }

    public static function get_session_var($path)
    {
        return self::get_array_var($path, $_SESSION);
    }

    public static function set_dbconfig_var($path, $value)
    {
        // isvalom senas konfiguracijas
        Database::query("DELETE FROM lite_settings WHERE (`path` LIKE '$path' OR `path` LIKE '$path/%')");
        if (!empty($value) && is_array($value)) {
            foreach ($value as $k => $v) {
                self::set_dbconfig_var("$path/$k", $v);
            }
        } elseif ($value==='') {
            self::unset_dbconfig_var($path);
            return Repository::$db->affected_rows;
        } else {
            // isvalom "trumpesniu keliu" reiksmes. Pvz.: jei buvo 'config/name', tai jis nebereikalingas jei norim issaugoti 'config/name/first_name'
            Database::query("DELETE FROM lite_settings WHERE '$path' LIKE CONCAT(`path`, '/%')");
            Database::query("INSERT INTO lite_settings (`path`, `value`) VALUES ('$path', '$value')");
            if (self::$db_config === false) {
                self::$db_config = array();
            }
            self::add_to_array($path, $value, self::$db_config);
            return Repository::$db->affected_rows;
        }
    }

    /**
     * Ištrina DB konfigūracijos elementą.
     * @param $path
     * @return unknown_type
     */
    public static function unset_dbconfig_var($path)
    {
        Database::query("DELETE FROM lite_settings WHERE `path`='$path'");
    }

    public static function add_to_array($path, $value, &$array)
    {
        $path = explode('/', $path);
        $last_key = array_pop($path);
        foreach ($path as $key) {
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = null;
            }
            $array = &$array[$key];
        }
        if ($value !== null) {
            $array[$last_key] = $value;
        } else {
            unset($array[$last_key]);
        }
    }

    public static function set_session_var($path, $value)
    {
        self::add_to_array($path, $value, $_SESSION);
        return true;
    }

    /**
     * Grąžina pilną objekto konfigūraciją pagal kelią
     *
     * @param $path 		- kelias, pvz: controllers/backend/news
     * @param $force_reload - perkrauti užkešuotą konfigūraciją?
     * @return unknown_type
     */
    public static function get_config($path, $force_reload = false)
    {
        $result = false;
        $configs = array(
            self::get_config_from_file($path),
            self::get_config_from_db($path, $force_reload),
            self::get_config_from_session($path)
        );
        // Is sesijos ir db gali grizti ne tik masyvas, o ir paprastas elementas (jeigu kelias paskutinis, pvz.: entity/news/page_size
        foreach ($configs as $c) {
            if (!empty($c) || is_numeric($c)) {
                if (is_array($c) && is_array($result)) {
                    $result = array_replace_recursive($c, $result);
                } else {
                    $result = $c;
                }
            }
        }
        return $result;
    }

    /**
     * Gražina konfigūraciją, tuo atveju jei rezultatas yra ne masyvas, įdeda į masyvą
     * @param $path
     * @return unknown_type
     */
    public static function get_config_array($path)
    {
        $result = self::get_config($path);
        if (empty($result)) {
            return array();
        } elseif (!is_array($result)) {
            return array($result);
        }
        return $result;
    }

    public static function get_config_from_exact_file($path)
    {
        $paths = explode('/', $path);
        $param_path = '';
        while (($file_path = implode('/', $paths)) && ($file_path != 'config')) {
            $file_path = $file_path . '.cfg.php';
            if (file_exists($file_path)) {
                include $file_path;
                if (isset($config)) {
                    return self::get_array_var($param_path, $config);
                } else {
                    // blogas konfiguracinis failas
                    return null;
                }
            }
            $last_path = array_pop($paths);
            $param_path = $param_path ? "$last_path/$param_path" : $last_path;
        }
        return null;
    }

    /**
     * perskaito ir gražina kontrolerio konfigūraciją ir failo
     *
     */
    public static function get_config_from_file($path, $merge = true)
    {
        return self::get_config_from_exact_file("config/$path");
    }

    public static function load_db_config()
    {
        $db_config = array();
        $rez = Database::query('SELECT * FROM lite_settings ORDER BY path');
        while ($row = mysqli_fetch_array($rez, MYSQLI_ASSOC)) {
            self::add_to_array($row['path'], $row['value'], $db_config);
        }
        self::$db_config = $db_config;
    }

    /**
     * perskaito ir gražina kontrolerio konfigūraciją iš db
     *
     * @param $path			- objekto, kurio konfigūracijos prašoma, kelias
     * @param $force_reload	- perkrauti užkešuotą konfigūraciją?
     * @return unknown_type
     */
    public static function get_config_from_db($path, $force_reload = false)
    {
        if (self::$db_config === false || $force_reload) {
            self::load_db_config();
        }
        return self::get_array_var($path, self::$db_config);
    }

    /**
     * perskaito ir gražina kontrolerio konfigūraciją iš sesijos
     *
     * @return unknown
     */
    public static function get_config_from_session($path)
    {
        return self::get_session_var("config/$path");
    }

    /**
     * Randa kelio parametrus is puslapio adreso / $_GET'o / $_SERVER['PATH_INFO'] ir pan.
     *
     * @return unknown
     */
    public static function read_path_info()
    {
        $path = array();
        if (!empty($_SERVER['REQUEST_URI'])) {
            $req = $_SERVER['REQUEST_URI'];
            $dir = dirname($_SERVER['SCRIPT_NAME']);
            if (strpos($req, $dir) === 0) {
                $req = substr($req, strlen($dir));
            }
            $req = preg_replace('/\?.*$/', '', $req);
            $req = urldecode($req);
            $req = trim($req, '/');
            if ($req) {
                $path = explode('/', $req);
                $path = array_filter($path);
            }
        }
        foreach ($path as &$val) {
            $val = htmlspecialchars($val, ENT_QUOTES);
        }
        return $path;
    }

    // TODO: kazkaip pergalvoti ka daryti su default'ine filtru konfiguracija

    public static function get_filter_page($params = array())
    {
        return array_merge(array(
            'title' => t('Puslapis (kategorija)'),
            'input_type' => 'select',
            'filter_type' => 'field',
            'condition_type' => 'equal',
            'function' => 'page', //prepare_filter_ , process_filter_
            'field_name' => 'page',
        ), $params);
    }

    public static function get_filter_tags($params = array())
    {
        return array_merge(array(
            'title' => t('Žymės'),
            'input_type' => 'checkboxes',
            'filter_type' => 'custom',
            'function' => 'tags',
        ), $params);
    }

    public static function get_filter_keywords($params = array())
    {
        return array_merge(array(
            'title' => t('Paieškos žodžiai'),
            'input_type' => 'input',
            'filter_type' => 'custom',
            'function' => 'keywords',
            'fields' => array('name', 'description'),
        ), $params);
    }

    public static function get_filter_date($params = array())
    {
        return array_merge(array(
            'title' => t('Data'),
            'input_type' => 'input',
            'filter_type' => 'field',
            'operator' => 'LIKE',
        ), $params);
    }
}
