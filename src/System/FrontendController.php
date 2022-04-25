<?php

namespace Elab\Lite\System;

class FrontendController extends ApplicationController
{
    public function __construct($name, $parent = false, $config = array())
    {
        parent::__construct($name, $parent, $config);
        if (!empty($this->config['entity'])) {
            $this->set_entity($this->load_entity_controller($this->config['entity'], false));
        }

        // gaunam path'a, kuriame visi keliai is url'o uz aktyvaus puslapio
        if (is_a($this->app, Frontend::class) && !empty($this->app->page)) {
            $this->path = array_slice($this->app->path, $this->app->page['depth']);
        }
    }

    public function logic()
    {
    }

    /**
     * (non-PHPdoc)
     * @see include/BaseController#get_controller_type()
     * @overrided
     */
    public function get_controller_type()
    {
        return 'frontend';
    }


    protected function get_http_protocol()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            if ($_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
                return 'https://';
            }

            return 'http://';
        }

        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] == 1)) {
            return 'https://';
        }

        return 'http://';
    }

    protected function prepare()
    {
        if (!empty($this->config['content_layout'])) {
            $this->get_frontend()->set_content_layout(@$this->config['content_layout'] ? $this->config['content_layout'] : 'default.tpl');
        }
    }

    public function get_frontend()
    {
        return $this->app;
    }
}
