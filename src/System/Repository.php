<?php

namespace Elab\Lite\System;

/**
 * DIRECTLY ACCESSING STATIC VARIABLES ARE DEPRECATED
 * USE GET FUNCTIONS INSTEAD
 */
/**
 * Klasė - sandėlys, kurioje statiniuose laukuose yra saugomi visoje sistemoje reikalingi objektai.
 * @author kran
 * @package core
 *
 * TODO: pasidomėti Registry design pattern, greičiausiai jis čia tiktų
 */
final class Repository
{

    /**
     * Čia saugoma sistemos konfigūracija
     */
    public static $config = array();

    /**
     * @var Smarty
     *
     */
    /**
     * @var \Smarty|boolean $smarty
     */
    public static $smarty = false;

    /**
     * Objektas darbui su e-paštu
     * @var PHPmailer
     */
    public static $mail = false;

    /**
     * Sistemos db objektas
     */
    public static $db = false;

    /**
     * Įjungos modulių grupės
     */
    public static $module_groups = false;

    /**
     * Įjungti moduliai
     *
     * @var $enabled_modules : array('module_id'=>array(), ...)
     */
    public static $enabled_modules = false;

    /**
     * Kontrollerių konfigūracija
     */
    public static $controller_config = false;

    /**
     * Sistemos vartotojo (svetainės vartotojo) dalies konfigūracija
     */
    public static $frontend_config = false;

    /**
     * TODO: dokumentuot
     * @var \Elab\Project\System\Frontend
     */
    public static $frontend = false;

    /**
     * TODO: dokumentuot
     * @var bool|Backend $backend
     */
    public static $backend = false;

    /**
     * TODO: dokumentuot
     */
    public static $app = false;

    /**
     * TODO: dokumentuot
     */
    public static $full_uri = false;

    /**
     * Čia kaupiamps visos įvykdytos užklausos
     */
    public static $queries = array();

    /**
     * Sistemos versija
     */
    public static $version = array();

    /**
     * DEBUG_MODE alternatyva
     * @var boolean
     */
    public static $debug_mode = null;

    /**
     * Ar dabartinis sablonas ateina is branduolio?
     *
     * TODO: pamastyti, ar tikrai cia verta saugoti, galbut tiesiog sudeti i view klase, nes iÅ� esmes Å�ie duomenys turi
     * buti pasiekiami TIK vertimu klaseje, o pasetinami Frontend/Backend klasese
     */
    public static $core_view = true;

    /**
     * Kokiam moduliui priklauso dabar paiÅ�omas Å�ablonas?
     *
     * TODO: pamastyti, ar tikrai cia verta saugoti, galbut tiesiog sudeti i view klase, nes iÅ� esmes Å�ie duomenys turi
     * buti pasiekiami TIK vertimu klaseje, o pasetinami Frontend/Backend klasese
     */
    public static $view_module = false;
    public static $translated_fields = null;
    public static $photos = null;
    public static $meta_fields = null;

    public static $mysql_debug_total_queries = 0;
    public static $mysql_debug_total_time = 0;

    /**
     * Bendras cache'as.
     * @var array()
     */
    public static $cache = array();

    public static function get_config()
    {
        return Repository::$config;
    }

    public static function get_smarty()
    {
        return Repository::$smarty;
    }

    public static function get_mail()
    {
        return Repository::$mail;
    }

    public static function get_db()
    {
        return Repository::$db;
    }

    public static function get_module_groups()
    {
        return Repository::$module_groups;
    }

    public static function get_enabled_modules()
    {
        return Repository::$enabled_modules;
    }

    public static function get_controller_config()
    {
        return Repository::$controller_config;
    }

    public static function get_frontend_config()
    {
        return Repository::$frontend_config;
    }

    public static function get_frontend()
    {
        return Repository::$frontend;
    }

    public static function get_backend()
    {
        return Repository::$backend;
    }

    public static function get_app()
    {
        return Repository::$app;
    }

    public static function get_full_uri()
    {
        return Repository::$full_uri;
    }

    public static function get_queries()
    {
        return Repository::$queries;
    }

    public static function get_version()
    {
        return Repository::$version;
    }

    public static function get_debug_mode()
    {
        return Repository::$debug_mode;
    }

    public static function get_core_view()
    {
        return Repository::$core_view;
    }

    public static function get_view_module()
    {
        return Repository::$view_module;
    }

    public static function get_translated_fields()
    {
        return Repository::$translated_fields;
    }

    public static function get_photos()
    {
        return Repository::$photos;
    }

    public static function get_meta_fields()
    {
        return Repository::$meta_fields;
    }

    public static function get_cache()
    {
        return Repository::$cache;
    }
}
