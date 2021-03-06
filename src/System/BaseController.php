<?php

namespace Elab\Lite\System;

use Elab\Lite\Helpers\Inflector;
use Elab\Lite\Engine;

/**
 *
 * @author kran
 * @author core
 */
abstract class BaseController
{

    /**
     * Application objektas (Frontend, Backend, Repository, ...)
     * @var Application
     */
    public $app;

    /**
     * ApplicationController
     * @var ApplicationController
     */
    public $app_controller;
    public $parent_object = null;
    public $config = false;
    public $last_error = false;
    /**
     * Parametrai, kurie bus paimami is $_GET ir irasomi i controller'io konfiguracija.
     * Raktas - lauko pavadinimas, reiksme - saugijimo budas (session arba temporary).
     * Pvz.: 'page_id' => 'session'
     *
     */
    public $get_params = array();
    private $name = false;

    public function __construct($name, $parent = null, $config = array())
    {
        $this->set_name($name);
        $this->set_parent_object($parent);
        $this->configure_controller($config);
        $this->uniqid = uniqid(); // reikalinga debuginimui, kai tikrinam ar objektas keshuojamas.
    }

    public function set_parent_object($parent)
    {
        $this->parent_object = $parent;
        if (is_a($parent, \Elab\Lite\System\ApplicationController::class)) {
            $this->app_controller = $parent;
            $this->app = $parent->app;
        } elseif (is_a($parent, \Elab\Lite\System\Application::class)) {
            $this->app = $parent;
        } elseif (is_a($parent, \Elab\Lite\System\EntityController::class)) {
            $this->app = $parent->app;
            $this->app_controller = $parent->app_controller;
        }
    }

    protected function configure_controller($config = array())
    {
        $path = $this->get_config_path();
        $this->config = Engine::get_config($path);

        //jei tokia yra, pajungiam konfiguracija is tevinio objekto
        if (!empty($this->parent_object->config[$parent_config_name = $this->get_name() . '_config'])) {
            $this->config = array_merge($this->config, $this->parent_object->config[$parent_config_name]);
        }
        $this->config['id'] = $this->get_name();
        if (is_array($config)) {
            $this->config = array_merge($this->config, $config);
        }

        // jeigu toks yra, pasetinam viewui konfig??racij?? (mums gali prisireikti pa??i?? ??vairiausi?? konfig??racij?? view'e
        // i?? runtim'e sukurt?? objekt??, o standartinis get_config toolsas mums gr????ina konfig??racijas tik i?? failo, db
        // sesijos, kartais to nepakanka - reikia pvz konfig??racijos, kuri ateina i?? t??vinio objekto). Beje, ?? view'??
        // paduodama pirmoji sukurta objekto konfig??racija - laikoma, kad ji "tikroji" - vyriausio objekto
        // konfig??racija, nes v??liau gali ateiti ??iuk??li??, sukurt?? kuriant vaikinius elementus
        if (isset(Repository::$smarty) && is_object(Repository::$smarty) && ($this->get_controller_type() == 'entity') && empty(Repository::$smarty->tpl_vars[$config_name = $this->get_name() . '_config'])) {
            Repository::$smarty->assign($config_name, $this->config);
        }
    }

    /**
     * turi gra??inti kontrollerio konfig??racijos keli??, bendr?? visiems ??manomiems b??dams: failas, db, sesija
     *
     */
    public function get_config_path()
    {
        return "controllers/{$this->get_controller_type()}/{$this->get_name()}";
    }

    /**
     * gra??ina kontrolerio tip??: entity, backend, frontend, etc
     *
     */
    abstract public function get_controller_type();

    public function get_name()
    {
        return $this->name;
    }

    protected function set_name($name)
    {
        $this->name = $name;
    }

    /* Static funkcijos* */

    /**
     * I?? $_GET uzkrauna kontrolerio konfiguracija.
     *
     */
    public function load_get_params($params = false)
    {
        if (!$params) {
            $params = $this->get_params;
        }
        foreach ($params as $key => $mode) {
            if (isset($_GET[$key])) {
                if ($_GET[$key] !== '') {
                    $this->add_config($key, htmlspecialchars($_GET[$key], ENT_QUOTES), $mode);
                } else {
                    $this->add_config($key, null, $mode);
                }
                $val = $_GET[$key] !== '' ?: null;
            }
        }
    }

    public function add_config($key, $value, $mode = 'temporary')
    {
        if ($mode == 'session') {
            Engine::set_session_var('config/' . $this->get_config_path() . ($key ? "/$key" : ''), $value);
        } elseif ($mode == 'db') {
            Engine::set_dbconfig_var($this->get_config_path() . ($key ? "/$key" : ''), $value);
        }
        $var = &$this->config;
        if ($key) {
            $keys = explode("/", $key);
            $last_key = array_pop($keys);
            foreach ($keys as $k) {
                if (!isset($var[$k]) || !is_array($var[$k])) {
                    $var[$k] = null;
                }
                $var = &$var[$k];
            }
            if ($value !== null) {
                $var[$last_key] = $value;
            } else {
                unset($var[$last_key]);
            }
        } else {
            $var = $value;
        }
    }

    public function __toString()
    {
        $name = get_class($this);
        $name = preg_replace('/^Project/', '', $name);
        $name = Inflector::camel2id($name);
        return $name;
    }
}
