<?php

namespace Elab\Lite\System;

use Elab\Lite\Engine;
use Elab\Lite\Helpers\Inflector;
use Elab\Lite\Helpers\Arr;
use Elab\Lite\Helpers\FrontendHelper;
use Elab\Lite\Services\Database;
use Elab\Lite\Services\Response;


class Frontend extends Application
{

    public $root_page_id = false;
    public $page = false;
    public $pages = array(); // visi puslapiai kurie yra path'e (url'e)
    public $path = false;
    public $lang_path = true;
    public $lang_key = false; // pvz.: lt, en, ru ...
    public $title = false;
    public $home_url = false;
    public $domain = false;
    public $project_name = false;
    public $page_path = array();
    public $page_layout = 'default.tpl';
    public $content_layout = 'default.tpl';
    public $content_type = 'content.tpl';
    public $view_cache = array();

    public function get_title()
    {
        if ($this->title) {
            return $this->title;
        } elseif (isset($this->page['header_title']) && $this->page['header_title']) {
            return $this->page['header_title'];
        } else {
            return $this->page['title'] ?: $this->page['name'];
        }
    }

    public function set_title($title)
    {
        $this->title = $title;
    }

    public function init_session()
    {
        session_name(Repository::$config['frontend_session_name']);
        session_start();
    }

    /**
     * Ivairios konfiguracijos
     *
     */
    public function init()
    {
        parent::init();
        $this->init_frontend_config();
        $this->init_photos();
    }

    public function init_frontend_config()
    {
        $this->config = Engine::get_config('frontend');
        Repository::$frontend = $this;
        Repository::$frontend_config = &$this->config;
    }

    public function init_photos()
    {
        $rez = Database::query("SELECT entity_name, foreign_key, container_name, f.* FROM lite_photo_containers fc JOIN lite_photos f ON f.gallery_id=fc.id ORDER BY position");
        while ($row = mysqli_fetch_array($rez, MYSQLI_ASSOC)) {
            $photo = array_slice($row, 3);
            $lang = empty($row['language']) ? "default" : $row['language'];
            Repository::$photos[$row['entity_name']][$row['foreign_key']][$row['container_name']][$lang][] = $photo;
        }
    }

    /**
     * Ivairus paruosiamieji veiksmai
     *
     */
    public function prepare()
    {
        $this->auth();

        $this->req_trailing_slash();
        $this->domain = PROJECT_DOMAIN;
        $this->project_name = Repository::$config['project_name'];

        // ishparsinam url
        $this->path = Engine::read_path_info();

        //Reload configs with project selected
        Engine::load_db_config();

        $langs = $this->get_e('languages')->find_elements();
        $langs_by_key = array_column($langs, null, 'language');
        $langs_by_id = array_column($langs, null, 'id');
        if ($this->path && isset($langs_by_key[$this->path[0]])) {
            $lang = $langs_by_key[$this->path[0]];
            $this->home_url = PROJECT_URL . $lang['language'] . '/';
            $depth = 1;
        }  else {
            $this->home_url = PROJECT_URL;
            $depth = 0;
            $lang = reset($langs_by_key);
        }
        $this->lang_key = $lang['language'];
        Translator::$language = $lang['language'];

        // pradinis puslapis
        $this->root_page_id = 0;

        $pages_controller = $this->get_e('pages');

        if (empty($this->path[$depth])) {
            $page = $pages_controller->find_element("`parent`=0");
            $this->pages[] = $page;
            $depth++;
        } else {
            // randam aktyvu puslapi (pagal url)
            $page = false;
            while (isset($this->path[$depth]) && ($current_page = $pages_controller->find_by_url($this->path[$depth], false, 'translate'))) {
                $this->pages[] = $current_page;
                $page = $current_page;
                $depth++;
            }
        }
        if ($page['type'] == 'index') {
            $this->canonical = $this->home_url;
        }
        if (!$page) {
            $this->not_found();
            return;
        }

        foreach ($this->pages as $item) {
            $this->add_page_path($item['name'], $pages_controller->get_full_url($item['id']));
        }

        // papildomas formatavimas aktyviam puslapiui
        $page['depth'] = $depth;
        if (!isset(Repository::$config['pages'][$page['type']])) {
            $page['type'] = 'content';
        }

        // puslapiu tipu sarasas (kad butu galima nesunkiai pagal tipa prieiti prie atitinkamo tos kalbos puslapio url)
        $this->page_types = $this->get_page_types($this->root_page_id);

        $this->set_content_type($page['type'] . ".tpl");
        if (!empty($page['title'])) {
            $this->page_title = $page['title'];
        }

        $pages_controller->format($page, 'detailed');
        $this->page = $page;
    }

    public function auth()
    {
        $auth_config = Engine::get_config('auth');
        if (!$auth_config) {
            return;
        }
        foreach ($auth_config as $pattern => $users) {
            if ($pattern == $_SERVER['SERVER_NAME'] || @preg_match($pattern, $_SERVER['SERVER_NAME'])) {
                $user = @$_SERVER['PHP_AUTH_USER'];
                if (!$user || !isset($users[$user]) || $users[$user] != $_SERVER['PHP_AUTH_PW']) {
                    header('WWW-Authenticate: Basic realm="Restricted area"');
                    header('HTTP/1.0 401 Unauthorized');
                    echo 'Forbidden.';
                    exit;
                }
                break;
            }
        }
    }

    public function req_trailing_slash()
    {
        if (empty($_GET) && !preg_match('@/$@', FULL_URL)) {
            $url = rtrim(FULL_URL_TRUNC, '/') . '/';
            Response::redirect($url, 301);
        }
    }

    public function not_found()
    {
        $this->set_page_not_found();
    }

    public function set_page_not_found()
    {
        $this->not_found = true;
        $this->add_page_path(t('Puslapis nerastas'));
        $this->set_title(t('Puslapis nerastas'));
        // Jeigu puslapis dar nepradetas atvaizduoti (nebuvo kvieciamas frontend->render()).
        $this->set_content_type('pages/not_found.tpl');
        $this->set_content_layout('plain.tpl');
        // Jeigu puslapis atvaizduojamas per frontend->view()
        $this->set_current_template('frontend/content_types/pages/not_found.tpl');
        $this->page = false;
        //pasetinam 404 klaidos kodą, kad puslapis toliau nebūtų indeksuojamas
        header('HTTP/1.0 404 Not Found');
        Repository::$smarty->assign('backlink', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : false);
    }

    public function add_page_path($title, $url = false)
    {
        $this->page_path[] = array('title' => $title, 'url' => $url);
    }

    public function get_page_types($parent, &$page_list = array(), &$pages = array())
    {
        if (!$page_list && !$pages) {
            $cache_name = "page_types_{$this->domain}_{$this->lang_key}_{$parent}";
            if ($result = $this->read_cache($cache_name)) {
                return $result;
            }
        } else {
            $cache_name = false;
        }
        $pages_controller = $this->load_entity_controller('pages');

        if (empty($page_list)) {
            $page_list = $pages_controller->find_elements("", array("translate"));
        }
        if (!is_array($parent)) {
            $parent = array(
                'id' => $parent,
                'full_url' => $this->home_url,
            );
        }
        if (empty($parent['full_url'])) {
            $parent['full_url'] = $pages_controller->get_full_url($parent['id']);
        }
        foreach ($page_list as $page) {
            if ($page['parent'] == $parent['id']) {
                $page['full_url'] = $parent['full_url'] . $page['url'] . '/';
                if (empty($pages[$page['type']])) {
                    $pages[$page['type']] = $page;
                }
                if (!empty($page['alias']) && empty($pages['alias_' . $page['alias']])) {
                    $pages['alias_' . $page['alias']] = $page;
                }
                $this->get_page_types($page, $page_list, $pages);
                if (($page['type'] == 'link' || $page['type'] == 'external_link') && $page['link_to']) {
                    $pages[$page['type']]['full_url'] = $page['link_to'];
                    if ($page['alias']) {
                        $pages['alias_' . $page['alias']]['full_url'] = $page['link_to'];
                    }
                }
            }
        }
        if ($cache_name) {
            $this->write_cache($cache_name, $pages);
        }
        return $pages;
    }

    /**
     * Apraso svetaines elgsena (veiksmus). Reagavimas i post'a, get'a ir pan.
     *
     */
    public function logic()
    {
        if (!empty($this->page['restricted']) && empty($_SESSION['user'])) {
            Repository::$smarty->assign('forbidden_action', t('Peržiūrėti šį puslapį'));
            $this->set_content_type('pages/forbidden');
        } elseif (empty($this->not_found)) {
            $type = $this->page['type'];
            $fc_name = @Repository::$config['pages'][$type]['controller'] ?: $type;
            $fc = $this->load_frontend_controller($fc_name);
            if (@$fc->config['content_layout']) {
                $this->set_content_layout($fc->config['content_layout']);
            }
            $fc->run();
        }
    }

    public function load_frontend_controller($name, $cache = true)
    {
        if ($cache && isset($this->_cache[$name])) {
            $obj = $this->_cache[$name];
        } else {
            $obj = Engine::load_controller('frontend', $name, $this);
            $obj->global_path = $this->path;
            $this->_cache[$name] = $obj;
        }
        return $obj;
    }

    public function before_render()
    {
        // perduodam duomenis i view'a
        Repository::$smarty->assign(array(
            'config' => array(
                'frontend' => &$this->config,
                'engine' => &Repository::$config,
            ),
            'content_layout' => $this->get_content_layout(),
            'content_type' => $this->get_content_type(),
            'page_path' => $this->get_page_path(),
            'version' => &Repository::$version,
            'h' => new FrontendHelper(),
        ));
        Repository::$smarty->assignByRef('frontend', $this);
    }

    public function get_page_path()
    {
        return $this->page_path;
    }

    public function display_content_type()
    {
        $content_type = $this->get_content_type();
        $param_arr = $this->content_type_params;
        array_unshift($param_arr, $content_type);
        return call_user_func_array(array($this, 'view'), $param_arr);
    }

    /**
     * Isveda i ekrana nurodyta template'a, pries tai paruosus reikiamus duomenis.
     * Pvz, view'e (template'e) turim: {$fronted->view('news/latest_news', 10)}
     * Tada kvieciamas NewsFrontendController->latest_news(10), ir atvaizduojamas 'views/frontend/content_types/news/latest_news.tpl' view'as.
     *
     * @param unknown_type $template
     */
    public function view($template)
    {
        $cache_name = false;
        if (@$this->view_cache[$template]) {
            $cache_name = Inflector::slug("$template-{$this->domain}-{$this->lang_key}");
            if ($result = $this->read_cache($cache_name, $this->view_cache[$template])) {
                return $result;
            }
        }
        $this->track_loadtime_start("view: $template");
        $t0 = microtime(true);
        $mode = error_reporting(@ERROR_REPORTING_MODE);
        Repository::$smarty->caching = false;
        $template = preg_replace('/\.tpl$/', '', $template);
        $this->set_current_template("frontend/content_types/$template.tpl");

        $result = false;
        if (preg_match('@/@', $template)) {
            $path = explode('/', $template);
            $fc_name = $path[0];
            $fc_action = $path[1];
            if (@$path[2]) {
                $fc_action .= "_" . $path[2];
            }
            //			list ($fc_name, $fc_action) = explode('/', $template);
            $params = array();
            if (func_num_args() >= 2) {
                $params = array_slice(func_get_args(), 1);
            }
            Repository::$view_module = $fc_name;
            $fc = $this->load_frontend_controller($fc_name);
            if (method_exists($fc, $fc_action)) {
                $p1 = @$params[0] ?: '';
                if (is_array($p1)) {
                    $p1 = 'array';
                }
                if (is_object($p1)) {
                    $p1 = 'object';
                }
                $this->track_loadtime_start("PHP: " . preg_replace('/(.*)\/(.*)(\.tpl)?/', "$1->$2($p1)", $template));
                $result = call_user_func_array(array($fc, $fc_action), $params);
                $this->track_loadtime_stop();
            }/* else {
              debug ("method not found: $fc_name->$fc_action");
              } */
        }
        // debug ($this->get_current_template()); return;
        if ($tpl = $this->get_current_template()) {
            $this->track_loadtime_start("VIEW: $template");
            $result = $this->fetch($tpl);
            $this->track_loadtime_stop();
        }
        Repository::$view_module = false;
        error_reporting($mode);
        $this->track_loadtime_stop();
        if ($cache_name) {
            $this->write_cache($cache_name, $result);
        }
        return $result;
    }

    public function render()
    {
        if (!empty($_GET['display'])) {

            // koki adresa rodyti adress bar'e? (be ?display=...)
            $query_string = http_build_query(Arr::blacklistKeys($_GET, 'display'));
            header('X-AJAXNAV-URL: ' . FULL_URL_TRUNC . ($query_string ? "?$query_string" : ''));

            // URL pvz: ...?display=content_types/news/detailed.tpl&args[0]=jonas&args[1]=petras
            $args = !empty($_GET['args']) ? $_GET['args'] : array();
            $path = explode('/', $_GET['display']);
            if ($path[0] == 'content_types') {
                // bus atvaizduojamas template'as 'views/frontend/content_types/news/detailed.tpl' ir kvieciamas NewsFrontendController->detailed('jonas', 'petras')
                array_unshift($args, implode('/', array_slice($path, 1)));
                echo call_user_func_array(array($this, 'view'), $args);
            } elseif ($path[0] == 'helpers') {
                echo call_user_func_array(array($this->load_helper($path[1]), $path[2]), $args);
            } else {
                $this->display('frontend/' . $_GET['display']);
            }
        } else {
            $this->display('frontend/page_layouts/' . $this->get_page_layout());
        }
    }

    public function get_element($entity, $id, $field = 'id', $formatting = false)
    {
        return $this->load_entity_controller($entity)->get_element($id, $field, $formatting);
    }
}
