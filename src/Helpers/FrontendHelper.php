<?php

namespace Elab\Lite\Helpers;

use Elab\Lite\System\Repository;
use Elab\Lite\System\Helper;

class FrontendHelper extends Helper
{
    public function add_css($file)
    {
        if (!preg_match('/^(https?:|\/)/', $file)) {
            $file = RESOURCES_URL . 'css/' . $file;
        }
        Repository::$frontend->css[] = $file;
    }

    public function add_js($file)
    {
        if (!preg_match('/^(https?:|\/)/', $file)) {
            $file = RESOURCES_URL . 'js/' . $file;
        }
        Repository::$frontend->js[] = $file;
    }

    public function _descriptive_filters($filters)
    {
        $this->assign('filters', $filters);
    }

    protected function _default_list($title, $items, $colums = 1)
    {
        $colums = $colums ?: 1;
        $items = $items ? array_chunk($items, ceil(count($items) / $colums)) : array();
        $this->assign(array(
            'title' => $title,
            'items' => $items,
            'columns' => 1,
        ));
    }
}
