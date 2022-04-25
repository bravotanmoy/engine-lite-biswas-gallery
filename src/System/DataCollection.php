<?php

namespace Elab\Lite\System;

use Elab\Lite\Engine;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of data_collection
 *
 * @author Giedrius
 */
class DataCollection
{
    public $filter = null;
    public $data = null;
    protected $cache = array();

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function get_e($name)
    {
        return $this->load_entity_controller($name);
    }

    public function load_entity_controller($name)
    {
        if (!isset($this->cache[$name])) {
            $this->cache[$name] = Engine::load_controller('entity', $name, $this);
        }
        return $this->cache[$name];
    }

    public function copy()
    {
        $class = get_class($this);
        return new $class($this->data);
    }

    public function filter($rule = 'default', $filter_params = null, $filter = array())
    {
        $this->filter_params = $filter_params;
        if (!empty($filter)) {
            $this->filter = $filter;
        }
        $this->data = array_filter($this->data, array($this, "filter_$rule"));
        return $this;
    }

    public function format($rule = 'default', $format_params = null)
    {
        array_walk($this->data, array($this, "format_$rule"), $format_params);
        return $this;
    }

    public function slice($offset, $length, $preserve_keys = true)
    {
        $this->data = array_slice($this->data, $offset, $length, $preserve_keys);
        return $this;
    }

    public function sort($rule, $custom_order = array(), $custom_order_col = 'id')
    {
        $rule = preg_replace('/\s+/', '_', $rule);
        if ($rule == 'shuffle') {
            shuffle($this->data);
        } else {
            $this->custom_order = array_reverse($custom_order);
            $this->custom_order_col = $custom_order_col;
            $this->sort_rule = $rule;

            usort($this->data, array($this, "_sort"));
        }
        return $this;
    }

    public function count()
    {
        return count($this->data);
    }

    protected function _sort($a, $b)
    {
        if (!$result = $this->sort_main($a, $b)) {
            $method = "sort_" . $this->sort_rule;
            $result = method_exists($this, $method) ? $this->$method($a, $b) : 0;
        }
        return $result;
    }

    protected function sort_main($a, $b)
    {
        return 0;
    }

    protected function filter_sizes($element)
    {
        $sizes = $this->filter_params;
        if (empty($sizes)) {
            return true;
        }
        if (!is_array($sizes)) {
            $sizes = array($sizes);
        }
        return count(array_intersect($sizes, $element['sizes'])) > 0;
    }

    protected function filter_brand($element)
    {
        $brands = $this->filter_params;
        if (empty($brands)) {
            return true;
        }
        if (!is_array($brands)) {
            $brands = array($brands);
        }
        foreach ($brands as $brand) {
            if (stripos($element['full_name'], $brand) === 0) {
                return true;
            }
        }
        return false;
    }

    protected function format_default(&$element, $key, $params = null)
    {
        // $element['uniqid'] = uniqid();
    }

    protected function filter_price_range($element)
    {
        $range = $this->filter_params;
        if (!is_array($range)) {
            return true;
        }
        return ($element['max_price'] >= $range[0] && $element['min_price'] <= $range[1]);
    }

    protected function sort_price($a, $b)
    {
        return $a['min_price'] - $b['min_price'];
    }

    protected function sort_discount($a, $b)
    {
        return $a['min_price'] / $a['old_price'] - $b['min_price'] / $b['old_price'];
    }

    protected function sort_custom_order($a, $b)
    {
        $pos_a = array_search($a[$this->custom_order_col], $this->custom_order);
        $pos_b = array_search($b[$this->custom_order_col], $this->custom_order);
        if (isset($pos_a)) {
            $pos_a++;
        }
        if (!isset($pos_a)) {
            $pos_a = 0;
        }
        if (isset($pos_b)) {
            $pos_b++;
        }
        if (!isset($pos_b)) {
            $pos_b = 0;
        }
        return $pos_b - $pos_a;
    }

    private function filter_big($element)
    {
        return $element['size'] >= 10;
    }

    private function filter_color($element)
    {
        $colors = $this->filter_params;
        if (!is_array($colors)) {
            return true;
        }
        return in_array($element['color'], $colors);
    }
}
