<?php

namespace Elab\Lite\System;

//engine modes yra naudojama iskirti kelis pagrindines klases darbo rezimus
use Elab\Lite\Helpers\File;
use Elab\Lite\Helpers\Inflector;
use Elab\Lite\Services\Smarty\Modifiers;
use Elab\Lite\Engine;
use Elab\Lite\Services\Database;

define('ENGINE_MODE_DEFAULT', 99); //standartinis rezimas - bus reikalinga krauti smarty, mail, etc, etc
define('ENGINE_MODE_SIMPLE', 0); //supaprastinstas rezimas, kai nereikia smarty, mail, etc, tereikia config, funkciju

abstract class Application
{
    public $page_layout = '';
    public $content_layout = 'empty.tpl';
    public $content_type = '';
    public $js = array();
    public $css = array();
    public $less = array();
    public $supported_content_types = array(
        'application/xhtml+xml', 'text/html',
        //'application/atom+xml',
        'application/javascript',
        'application/json'
    );
    public $supported_languages = array(
        'lt' => true,
        'en' => true,
        'ru' => true,
    );
    public $engine_mode = ENGINE_MODE_DEFAULT;
    public $_cache = array();
    public $error_handler = null;
    public $project = false;
    public $lang_key = false;
    public $default_language = false;
    /**
     * uzsikrovimo laikai (testavimui)
     * @var array()
     */
    private $_loadtimes = array();
    private $_loadtime_stack = array();
    /**
     * template'as, kuri atvaizduosim kviesdami view() metoda.
     * @var string
     */
    private $current_template = '';

    public function __construct($mode = ENGINE_MODE_DEFAULT)
    {
        $this->engine_mode = $mode;
        Repository::$app = $this;
    }

    public function get_e($name, $config = array())
    {
        return $this->load_entity_controller($name, $config);
    }

    public function load_entity_controller($name, $config = array())
    {
        $obj = Engine::load_entity_controller($name, $this, $config);
        return $obj;
    }

    public function read_cache($name, $timeout = CACHE_TIMEOUT)
    {
        if (!CACHE_ENABLED || isset($_GET['reset_cache'])) {
            return false;
        }
        $file = "cache/cache/$name";
        if (file_exists($file) && filemtime($file) > (time() - $timeout)) {
            return unserialize(file_get_contents($file));
        }
        return false;
    }

    public function write_cache($name, $data)
    {
        if (CACHE_ENABLED) {
            file_put_contents("cache/cache/$name", serialize($data));
        }
    }

    public function get_current_template()
    {
        return $this->current_template;
    }

    public function set_current_template($name)
    {
        $this->current_template = $name;
    }

    public function load_helper($name = false)
    {
        $class_path = explode('-', Inflector::slug($name . ' helper', '-'));
        $classes = array();
        for ($i = 0; $i < count($class_path); $i++) {
            $class = Inflector::camelize(implode('-', array_slice($class_path, $i)));
            foreach (Engine::$namespaces as $namespace) {
                $classes[] = $namespace . 'Helpers\\' . $class;
            }
            foreach (Engine::$namespaces as $namespace) {
                $classes[] = $namespace . 'System\\' . $class;
            }
        }
        foreach ($classes as $class) {
            if (class_exists($class)) {
                $existing_class = $class;
                break;
            }
        }
        $obj = new $existing_class();
        return $obj;
    }

    public function run()
    {
        $this->track_loadtime_start('run');
        $this->track_loadtime('init'); // $this->init();
        $this->track_loadtime('prepare'); // $this->prepare();
        $this->track_loadtime('logic'); // $this->logic();
        $this->track_loadtime('before_render'); // $this->before_render();
        $this->track_loadtime('render'); // $this->render();
        $this->track_loadtime('clean_up'); // $this->clean_up();
        $this->track_loadtime_stop();

        if (isset($_GET['load_times'])) {
            echo($this->loadtime_info());
        }
    }

    public function track_loadtime_start($track_id)
    {
        $info = array(
            'id' => $track_id,
            'uniqid' => uniqid(),
            'start' => microtime(true),
            'memory_peak_before' => memory_get_peak_usage(),
            'level' => count($this->_loadtime_stack),
            'mysql_total_time_before' => Repository::$mysql_debug_total_time,
            'mysql_total_queries_before' => Repository::$mysql_debug_total_queries,
        );
        $this->_loadtime_stack[] = $info;
        $this->_loadtimes[$info['uniqid']] = $info;
    }

    public function track_loadtime($method, $track_id = null)
    {
        $this->track_loadtime_start($track_id ?: $method);
        call_user_func_array(array($this, $method), array_slice(func_get_args(), 2));
        $this->track_loadtime_stop($track_id);
    }

    public function track_loadtime_stop()
    {
        global $mysql_debug_total_queries;
        global $mysql_debug_total_time;
        $info = array_pop($this->_loadtime_stack);
        $info['loadtime'] = microtime(true) - $info['start'];
        $info['memory_peak'] = memory_get_peak_usage();
        $info['mysql_total_time'] = Repository::$mysql_debug_total_time;
        $info['mysql_total_queries'] = Repository::$mysql_debug_total_queries;
        $this->_loadtimes[$info['uniqid']] = $info;
    }

    public function loadtime_info($mode = 'html')
    {
        $result = '';
        $rows = array();
        //$rows[] =
        $header = array('level', 'task', 'loadtime', 'sql queries', 'sql time', 'memory peak', 'memory peak diff');
        $html = '';
        foreach ($this->_loadtimes as $k => $info) {
            $row = array(
                'level' => $info['level'],
                'task' => str_repeat('*', $info['level']) . ' ' . $info['id'],
                'loadtime' => round($info['loadtime'], 4),
                'sql queries' => $info['mysql_total_queries'] - $info['mysql_total_queries_before'],
                'sql time' => round($info['mysql_total_time'] - $info['mysql_total_time_before'], 4),
                'memory peak' => File::human_file_size($info['memory_peak']),
                'memory peak diff' => File::human_file_size($info['memory_peak'] - $info['memory_peak_before']),
            );
            if ($row['loadtime'] >= 1) {
                $timeclass = "1000";
            } elseif ($row['loadtime'] >= 0.5) {
                $timeclass = "500";
            } elseif ($row['loadtime'] >= 0.2) {
                $timeclass = '200';
            } elseif ($row['loadtime'] >= 0.1) {
                $timeclass = '100';
            } else {
                $timeclass = '0';
            }
            $rows[] = $row;
            $html .= "<tr class='timeclass$timeclass'><td>" . implode('</td><td>', $row) . "</td></tr>";
        }
        $header = '<th>' . implode('</th><th>', $header) . '</th>';
        $html = '<table class="loadtime_info" border="1" cellpadding="4" style="font-family: monospace;"><tr>' . $header . '</tr>' . $html . '</table>';
        return $mode == 'html' ? $html : $rows;
    }

    public function init()
    {
        $this->init_config();

        date_default_timezone_set(TIMEZONE);
        mb_internal_encoding(CHARSET);

        if ($this->engine_mode > ENGINE_MODE_SIMPLE) {
            $this->init_session();
            $this->init_db();
            $this->init_smarty();
            Repository::$version = Engine::get_config_from_file('version', true);
            $this->init_enabled_modules();
            $this->init_page_types();

            if (empty($_SESSION['token'])) {
                $_SESSION['token'] = uniqid();
            }
            Repository::$config['token'] = $_SESSION['token'];
        }
    }

    public function init_config()
    {
        Repository::$config = Engine::get_config_from_file('engine', true);
    }

    public function init_session()
    {
    }

    /**
     * Inicializuoja duomenų bazę. Jei db konfigūracija nepaduota, ima standartinę projekto db konfigūraciją.
     * @param $config - db konfigūracinis masyvas:
     * array(
     *    'dbhost' => 'hostas',
     *    'dbuser' => 'db_vartotojo_vardas',
     *    'dbpass' => 'db_slaptažodis',
     *    'dbname' => 'db_vardas',
     * )
     * @return unknown_type
     */
    public function init_db($config = false)
    {
        if (empty($config)) {
            $config = Engine::get_config_from_file('db', false);
        }

        $db = new \mysqli($config['dbhost'], $config['dbuser'], $config['dbpass'], $config['dbname']);
        if (!$db) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        } else {
            Repository::$db = $db;
        }

        $charset = defined('SQL_CHARSET') ? SQL_CHARSET : 'utf8';
        Database::query("SET NAMES $charset");

        //	if (PROJECT_MODE == 'development') {
        Database::query("SET SESSION sql_mode='STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION'");
        //	}
        $this->db = $db;
        Repository::$db = $this->db;
    }

    public function init_smarty()
    {
        $smarty = new \Smarty();
        $smarty->setTemplateDir('./');
        $smarty->setCompileDir('cache/smarty/');
        $smarty->setCacheDir('cache/smarty/');
        $smarty->error_reporting = E_ALL ^ E_NOTICE;
        Repository::$smarty = $smarty;
        $this->register_smarty_plugins();
    }

    public function register_smarty_plugins()
    {
        Modifiers::load();
        if (PROJECT_MODE == 'development') {
            Repository::$smarty->force_compile = true;
        }
    }

    /**
     * Užkrauna "įjungtų" modulių sąrašą ir padeda jį į Repository
     *
     * @return unknown_type
     */
    public function init_enabled_modules()
    {
        $module_list = Engine::get_config_from_file('modules', false);
        $enabled_modules = array();
        foreach ($module_list as $module) {
            $enabled_modules[$module] = true;
        }
        Repository::$enabled_modules = $enabled_modules;
    }

    public function init_page_types()
    {
        $page_type_list = Engine::get_config_from_file('pages', true);
        /* jeigu puslapis yra priskirtas prie modulio ir tas modulis yra išjungtas,
         * vadinasi toks puslapio tipas nėra reikalingas - išmetam.
         * + išmetam puslapį, kuriame yra parametras disabled */
        foreach ($page_type_list as $key => $page_type) {
            $module = @$page_type['module'] === true ? $key : (@$page_type['module'] ?: false);
            if ((!empty($page_type['disabled'])) || ($module && empty(Repository::$enabled_modules[$module]))) {
                unset($page_type_list[$key]);
            }
        }
        Repository::$config['pages'] = $page_type_list;
    }

    public function prepare()
    {
    }

    public function logic()
    {
    }

    public function before_render()
    {
    }

    public function render()
    {
    }

    public function fetch($template_path)
    {
        $tpl = Helper::get_view_path($template_path);
        return $tpl ? Repository::$smarty->fetch($tpl) : "";
    }

    public function display($template_path)
    {
        $tpl = Helper::get_view_path($template_path);
        Repository::$smarty->display($tpl);
    }

    public function get_page_layout()
    {
        $layout = $this->page_layout;
        if (!preg_match('/\.tpl$/', $layout)) {
            $layout .= '.tpl';
        }
        return $layout;
    }

    public function set_page_layout($name)
    {
        $this->page_layout = $name;
    }

    public function get_content_layout()
    {
        $layout = $this->content_layout;
        if (!preg_match('/\.tpl$/', $layout)) {
            $layout .= '.tpl';
        }
        return $layout;
    }

    public function set_content_layout($name)
    {
        $this->content_layout = $name;
    }

    public function get_content_type()
    {
        $layout = $this->content_type;
        if (!preg_match('/\.tpl$/', $layout)) {
            $layout .= '.tpl';
        }
        return $layout;
    }

    public function set_content_type($name)
    {
        $this->content_type = $name;
        $this->content_type_params = array_slice(func_get_args(), 1);
    }

    public function clean_exit($message = null)
    {
        $this->clean_up();
        exit($message);
    }

    public function clean_up()
    {
        $this->close_db_connection();
    }

    /**
     * Švariai baigiam darbą su db: uždarom connectioną, etc, etc.
     * @return unknown_type
     */
    public function close_db_connection()
    {
        if (!empty($this->db)) {
            $this->db->close();
        }
    }
}
