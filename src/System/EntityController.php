<?php

namespace Elab\Lite\System;

use Elab\Lite\Helpers\Arr;
use Elab\Lite\Services\Debug;
use Elab\Lite\Helpers\File;
use Elab\Lite\Helpers\Form;
use Elab\Lite\Helpers\Image;
use Elab\Lite\Helpers\Inflector;
use Elab\Lite\Services\Response;
use Elab\Lite\Engine;
use Elab\Lite\Services\Database;

/**
 * @package core
 * @author kran
 *
 */
class EntityController extends BaseController
{
    public static $cache = array();
    /**
     * modelio lauku pavadinimai, atitinkantys konkrecios lenteles laukus.
     *
     * @var array
     */
    public $fields = array();
    public $select_fields = '';
    /**
     * unikalus urls su pateiktu entities urls.
     *
     * @var array
     */
    public $unique_urls_with = array();
    /**
     * Modelio konfiguracija. Tas pats kas $config.
     * Paduodama per konstruktoriu.
     *
     * @var array
     */
    public $config = array();
    /**
     * Paskutine ivykusi klaida
     *
     * @var string
     */
    public $last_error = 'Nežinoma klaida.';

    /**
     * Parodo ar buvo iskviestas invalidate metodas
     *
     * @var boolean
     */
    public $invalidated = false;

    /**
     * Validacijos klaidos. Raktai - lauku id (name'ai), title - pilnas/zmogiskas lauko pavadinimas, message - klaidos pavadinimas
     *
     * @var unknown_type
     */
    public $invalidated_fields = array();

    /**
     * Naudojamas common moduliuose. Pvz.: $prefix = "comments_".
     * Reikalinga pvz imant configuracija is get'o i sesija.
     *
     * @var string
     */
    public $prefix = '';
    public $controller = false;
    public $get_params = array(
        'sort_by' => 'session',
        'sort_type' => 'session',
        'page' => 'temporary',
        'page_size' => 'session',
    );
    public $entity_errors = array();
    public $available_containers = array('photos', 'fields', 'comments', 'files',);
    public $backup_id_stack = array();
    public $language = null;
    /**
     * Paskutine validacijos klaida (kaip ir Validator::last_message)
     *
     * @var string
     */
    protected $last_message;

    public function __construct($name, $parent = false, $config = array())
    {
        parent::__construct($name, $parent, $config);
        if (!is_array($this->config)) {
            $this->config = array();
        }

        if (empty($this->config['table'])) {
            $this->config['table'] = 'lite_' . $name;
        }

        if (isset($this->config['unique_urls_with']) && !empty($this->config['unique_urls_with'])) {
            $this->unique_urls_with = $this->config['unique_urls_with'];
        }

        // Perkelta is modelio
        if (!empty(self::$cache[$this->config['table']])) {
            $this->fields = self::$cache[$this->config['table']];
        } else {
            if (isset($this->config['table'])) {
                $query = Database::query("SHOW TABLES LIKE '{$this->config['table']}'");
                if (!$query->num_rows) {
                    return false;
                    throw new \Exception("Table '{$this->config['table']}' not found.");
                }

                $fields = array();
                $qr = Database::query("DESCRIBE {$this->config['table']}");
                while ($r = mysqli_fetch_array($qr, MYSQLI_ASSOC)) {
                    $field = array();
                    if (isset($r['Default']) && $r['Default'] !== '') {
                        $field['default'] = $r['Default'];
                    } elseif (($r['Null'] == 'NO') && ($r['Extra'] != 'auto_increment')) {
                        $field['required'] = 'required';
                    }
                    if ($r['Null'] == 'NO') {
                        $field['validation'][] = 'not_empty';
                    }
                    if ($r['Key'] == 'PRI' || $r['Key'] == 'UNI') {
                        $field['validation'][] = 'unique';
                    }
                    $field['validation'][] = "sql_data_type|$r[Type]";
                    $fields[$r['Field']] = $field;
                }
                if (!empty($this->config['extra_validation']) && is_array($this->config['extra_validation'])) {
                    foreach ($this->config['extra_validation'] as $key => $validation) {
                        if (isset($fields[$key])) {
                            $fields[$key]['validation'] = array_merge(
                                is_array($validation) ? $validation : array($validation),
                                is_array($fields[$key]['validation']) ? $fields[$key]['validation'] : array($fields[$key]['validation'])
                            );
                        }
                    }
                }
                $this->fields = $fields;
            }

            self::$cache[$this->config['table']] = $this->fields;
        }
        // /Perkelta is modelio

        // page_type
        if (isset($this->fields['page'], Repository::$config['pages'])) {
            foreach (Repository::$config['pages'] as $type => $info) {
                if ($type == $this->get_entity_page_type()) {
                    $this->config['page_type'] = $type;
                }
            }
        }

        // pagal nutylejima, slepiam irasus, kuriu lankytojui nereikia matyti
        if (Repository::$frontend && empty(Repository::$app->show_unavailable)) {
            $this->set_show_unavailable(false);
        }

        // papildom 'translated_fields' reiksmemis su 'meta_fields' laukais
        if (@$this->config['translated_fields'] && @$this->config['meta_fields']) {
            $this->config['translated_fields'] = array_merge($this->config['translated_fields'], ['meta_title', 'meta_description', 'meta_keywords', 'header_title']);
        }
    }

    /**
     * Grąžina esybės puslapio tipą.
     * @return unknown_type
     */
    public function get_entity_page_type()
    {
        return $this->get_name();
    }

    public function set_show_unavailable($is_shown)
    {
        $this->set_show_inactive($is_shown);
        $this->set_show_deleted($is_shown);
    }

    public function set_show_inactive($is_shown)
    {
        if ($is_shown) {
            return $this->disable_condition('active');
        }
        if (!isset($this->fields['active'])) {
            return false;
        } elseif (!$this->enable_condition('active')) {
            $this->add_condition('active', '`active`=1', 'equal');
        }
    }

    public function disable_condition($name)
    {
        if (empty($this->config['conditions'][$name])) {
            return false;
        }
        $this->config['conditions'][$name]['disabled'] = true;
        return true;
    }

    public function enable_condition($name)
    {
        if (empty($this->config['conditions'][$name])) {
            return false;
        }
        $this->config['conditions'][$name]['disabled'] = false;
        return true;
    }

    /**
     *
     * @param string $name - pavadinimas
     * @param string $condition - salyga
     * @param string $type - salygos tipas ('mixed' arba 'equal')
     * @return type
     */
    public function add_condition($name, $condition, $type = 'mixed')
    {
        $this->config['conditions'][$name] = array('type' => $type, 'condition' => $condition);
        return true;
    }

    public function set_show_deleted($is_shown)
    {
        if (!isset($this->fields['deleted'])) {
            return false;
        }
        if ($is_shown) {
            $this->disable_condition('not_deleted');
        } elseif (!$this->enable_condition('not_deleted')) {
            $this->add_condition('not_deleted', '`deleted`=0', 'equal');
        }
    }

    public function add_join($name, $condition, $type = 'left')
    {
        $this->config['joins'][$name] = array('type' => $type, 'condition' => $condition);
        return true;
    }

    /** PACHEKINT */
    /**
     * Kviečiamas po save'o backend/frontend kontroleryje, t.y. kai sukuriama/paredaguojama pagrindinė esybė.
     * Metodo esmė - paruošti atitinkamą atsakymą (json, arba nieko), kai formą submitinam su ajax'u.
     *
     * @param boolean $success - ar buvo sėkmingas save'as?
     * @param unknown_type $return_url - jei reikia po save'o redirektinti kokiu nors "custom" adresu.
     *
     * FIXME: čia tikrai ne vieta yra daryti output'ą
     */
    public function ajax_submit($success, $return_url = false)
    {
        if (isset($_GET['ajax_submit'])) {
            // Ankstesnio output'o mums tikrai nereikia...
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            Repository::$db->close();

            //TODO: kitoje versijoje iškelti į atskirą funkciją/metodą
            //The first two headers prevent the browser from caching the response (a problem with IE and GET requests) and the third sets the correct MIME type for JSON.
            //http://snippets.dzone.com/posts/show/5882
            header('Cache-Control: no-cache, must-revalidate');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            // Kai pasiustas sitas headeris, firefox'as atidaro download dialoga :)
            // header('Content-type: application/json');

            if (!$success) {
                echo(json_encode($this->get_errors()));
            } elseif ($return_url) {
                echo(json_encode(array('return_url' => $return_url)));
            }
            exit;
        }
    }

    public function get_errors()
    {
        return $this->invalidated_fields;
    }

    /**
     * apdoroja iš vaikinės esybės siunčiamus pranešimus - „trigerius“, vaiko sukūrimo/redagavimo/trinimo proga
     * @param $code
     * @return unknown_type
     */
    public function process_trigger($code, $id = false, $element = false)
    {
        $success = true;
        $method = "process_$code";
        if (method_exists($this, $method)) {
            $this->{$method}($id, $element);
        }
        return $success;
    }

    public function get_all_childs($parent = 0, $entity = 'pages', $where = '')
    {
        if (isset($this->_cache['family']["$entity/$parent/$where"])) {
            return $this->_cache['family']["$entity/$parent/$where"];
        }
        $e = ($this->get_name() == $entity ? $this : $this->get_e($entity));
        $elements = $this->select_elements($params = array('auto_params' => false, 'where' => $where));
        if ($where) {
            $all_elements = $this->load_entity_controller($entity)->select_elements($params = array('auto_params' => false));
        } else {
            $all_elements = $elements;
        }
        $id_elements = array();
        foreach ($all_elements as $el) {
            $id_elements[$el['id']] = $el;
        }
        $family = array();
        foreach ($elements as $el) {
            $i = $el;
            while ($i) {
                if (!$i['parent'] || $i['parent'] == $parent || !isset($id_elements[$i['parent']])) {
                    break;
                }
                $i = $id_elements[$i['parent']];
            }
            if ($i['parent'] == $parent) {
                $family[] = $el['id'];
            }
        }
        return $this->_cache['family']["$entity/$parent/$where"] = $family;
    }

    /**
     *
     * @param type|string $name
     * @return \Elab\Project\System\EntityController
     */
    public function get_e($name, $config = array())
    {
        return $this->load_entity_controller($name, $config);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $name
     * @return \Elab\Project\System\EntityController
     */
    public function load_entity_controller($name, $config = array())
    {
        if (!empty($this->_cache['controllers'][$name])) {
            $controller = $this->_cache['controllers'][$name];
        } else {
            $controller = Engine::load_entity_controller($name, $this, $config);
        }
        $this->_cache['controllers'][$name] = $controller;
        return $controller;
    }

    /**
     * suformuoja SQL SELECT sakini ir grazina uzklausos rezultato eilutes
     *
     * @param array $params
     * galimi $params nustatymai:
     *        'fields' - lauku, kuriuos reikia isselect'inti sarasas
     *        'table' - lentele(-es) arba join'as(-ai)
     *        'where' - papildoma WHERE salyga uzklausai (kita dalis WHERE salygos bus sugeneruota automatiskai, controller->config['conditions'])).
     *        'group_by' - GROUP BY salyga
     *        'order_by' - ORDER BY salyga (jei nepaduota visai, tai ima is nustatymu. Galima paduot false jei rusiuoti nenorim)
     *        'limit' - LIMIT salyga. Jei limit parametras nepaduotas, tai tada puslapiuojam. Jei false, tai nedarom nieko (ir nepuslapiuojam).
     *        'page_size' - jei nenurodytas, ima is nustatymu. Jei nenorim puslapiuot, paduodam 0 arba false.
     *        'page' - jei nenurodytas, imam is nustatymu/sesijos.
     *        'paginate' - suformuoja puslapiavimo link'us
     *        'page_info' - informacija apie rodomus puslapio irasus: $this->config['showing'] = array("from"=>..., "to"=>...);
     *
     * @return array
     */
    public function select_elements($params = array(), &$return_params = array())
    {

        // defaultiniai parametrai
        $params = array_merge(array(
            'auto_params' => true, // config[conditions], order by, etc...
        ), $params);

        // laukai
        if (empty($params['fields'])) {
            $params['fields'] = '*';
        }

        // lentele
        if (empty($params['table'])) {
            $params['table'] = $this->config['table'];
        }

        $query = "SELECT $params[fields] FROM $params[table]";

        // JOINS

        if ($joins = $this->get_joins()) {
            foreach ($joins as $table => $join) {
                $query .= " {$join['type']} JOIN {$table} ON {$join['condition']} ";
            }
        }

        // WHERE salyga
        $where = array();
        if (!empty($params['where'])) {
            $where[] = "$params[where]";
        }
        //papildomos sql salygos is nustatymu
        if ($params['auto_params'] && ($conditions = $this->get_conditions())) {
            $where = array_merge($where, $conditions);
        }

        if (!empty($where)) {
            $query .= ' WHERE (' . implode(') AND (', $where) . ')';
        }

        // GROUP BY salyga
        if (!empty($params['group_by'])) {
            $query .= " GROUP BY $params[group_by]";
        }

        // ORDER BY salyga
        if ($params['auto_params'] && !isset($params['order_by'])) {
            // jei nenurodyta kitaip, rusiuojam pagal parametrus is sesijos/konfiguracijos
            $order_by = false;
            if (!empty($this->config['sort_by']) && is_array($this->config['sort_by'])) {
                $order = array();
                foreach ($this->config['sort_by'] as $key => $value) {
                    $str = "$key";
                    if (!empty($value)) {
                        $str .= " $value";
                    }
                    $order[] = $str;
                }
                $order_by = implode(',', $order);
            } else {
                if (isset($this->config['sort_by'])) {
                    $sort_by = $this->config['sort_by'];
                    if (isset($this->fields[$sort_by])) {
                        $order_by = "`{$this->config['sort_by']}`";
                    } elseif (strtolower($sort_by) == 'rand()') {
                        $order_by = $sort_by;
                    }
                }
                if ($order_by !== false && @$this->config['sort_type'] && (strtolower($this->config['sort_type']) != 'asc')) {
                    $order_by .= ' DESC';
                }
            }
            $params['order_by'] = $order_by;
        }
        if (!empty($params['order_by'])) {
            $query .= " ORDER BY $params[order_by]";
        }

        // LIMIT salyga
        if (!isset($params['limit']) && !empty($params['paginate']) && !empty($params['page_size'])) {
            // Puslapiavimas
            $elements_count = $this->count_elements(!empty($params['where']) ? $params['where'] : '');

            $page_size = $params['page_size'];
            $pages_count = ceil($elements_count / $page_size);

            $page = isset($params['page']) ? $params['page'] : 1;
            if ($page > $pages_count) {
                $page = $pages_count;
            }
            if ($page < 1) {
                $page = 1;
            }

            $params['limit'] = (($page - 1) * $page_size) . ", $page_size";
            $return_params['pages'] = $this->paginate($page, $page_size, $elements_count, "");
        }
        if (!empty($params['limit'])) {
            $query .= " LIMIT $params[limit]";
        }


        $result = Database::query($query);
        $rows = array();
        if ($result) {
            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $rows[] = $row;
            }
        }

        if (!empty($params['paginate'])) {
            // Informacija apie rodomus puslapio irasus (nuo ... iki)
            if (!isset($page_size)) {
                $page_size = count($rows);
            }
            if (!isset($page)) {
                $page = 1;
            }
            $return_params['page_info'] = array(
                'from' => ($page - 1) * $page_size + ($rows ? 1 : 0),
                'to' => ($page - 1) * $page_size + count($rows),
                'page' => $page,
                'pages_count' => $pages_count,
            );
        }

        return $rows;
    }

    public function get_joins($type = false)
    {
        $result = array();
        if (!empty($this->config['joins'])) {
            foreach ($this->config['joins'] as $name => $item) {
                if ((!$type || ($type == $item['type'])) && (empty($item['disabled']))) {
                    $result[$name] = $item;
                }
            }
        }
        return $result;
    }

    public function get_conditions($type = false)
    {
        $result = array();
        if (!empty($this->config['conditions'])) {
            foreach ($this->config['conditions'] as $item) {
                if ((!$type || ($type == $item['type'])) && (empty($item['disabled']))) {
                    $result[] = $item['condition'];
                }
            }
        }
        return $result;
    }

    public function count_elements($where_clause = false, $count_by = '*')
    {
        $params = array(
            'fields' => "count($count_by)",
            'order_by' => false,
            'limit' => false,
            'table' => $this->config['table'],
            'group_by' => false,
        );
        if (!empty($where_clause)) {
            $params['where'] = $where_clause;
        }
        if ($result = $this->select_element($params)) {
            list($count) = array_values($result);
            return $count;
        } else {
            return false;
        }
    }

    /**
     * Gražina pirmą esybės elementą pagal paduotus parametrus
     * @param $params
     * @return unknown_type
     */
    public function select_element($params = array())
    {
        $params['limit'] = 1;
        $result = $this->select_elements($params);
        return empty($result) ? false : $result[0];
    }

    public function paginate($aktyvus_psl, $el_per_psl, $viso, $url, $prefix = '', $bookmark = '', $config = array())
    {
        if ($url == 'auto') {
            parse_str($_SERVER['QUERY_STRING'], $params);
            unset($params['PATH_INFO'], $params['page'], $params['display']);
            $url = http_build_query($params, '', '&amp;');
        }

        $back_label = t('atgal');
        $next_label = t('pirmyn');

        $config = array_merge(
            array(
                'limit' => 15,
                'parts' => 4,
                'width' => 5,
                'back' => "&laquo; $back_label",
                'next' => "$next_label &raquo;",
            ),
            $config
        );
        extract($config);

        $puslapiu_sk = $el_per_psl ? ceil($viso / $el_per_psl) : 1;
        $numeriai = array();

        if ($puslapiu_sk > $limit) {
            $zingsnis = floor($puslapiu_sk / $parts);

            // zingsniuojam is kaires
            $psl = 1;
            while ($psl < $aktyvus_psl) {
                if (!in_array($psl, $numeriai)) {
                    array_push($numeriai, $psl);
                }
                $psl += $zingsnis;
            }

            // zingsniuojam is desines
            $psl = $puslapiu_sk;
            while ($psl > $aktyvus_psl) {
                if (!in_array($psl, $numeriai)) {
                    array_push($numeriai, $psl);
                }
                $psl -= $zingsnis;
            }

            // formuojam puslapiavima apie aktyvu psl
            $radius = floor($width / 2);
            if ($aktyvus_psl - $radius < 1) {
                $psl = 1;
            } elseif ($aktyvus_psl + $radius > $puslapiu_sk) {
                $psl = $puslapiu_sk - $width + 1;
            } else {
                $psl = $aktyvus_psl - $radius;
            }
            for ($i = 1; $i <= $width; $i++) {
                if (!in_array($psl, $numeriai)) {
                    array_push($numeriai, $psl);
                }
                $psl += 1;
            }
            asort($numeriai);
        } else {
            for ($psl = 1; $psl <= $puslapiu_sk; $psl++) {
                if (!in_array($psl, $numeriai)) {
                    array_push($numeriai, $psl);
                }
            }
        }

        if (method_exists($this, 'get_container')) {
            $prefix = "{$this->get_name()}_";
        }

        Repository::$smarty->assign(array(
            'puslapiu_sk' => $puslapiu_sk,
            'url' => $url,
            'aktyvus_psl' => $aktyvus_psl,
            'prefix' => $prefix,
            'numeriai' => $numeriai,
            'bookmark' => $bookmark,
        ));
        return Repository::$smarty->fetch(Helper::get_view_path('frontend/elements/pager.tpl'));
    }

    public function get_condition($name)
    {
        return !empty($this->config['conditions'][$name]['condition']) ? $this->config['conditions'][$name]['condition'] : false;
    }

    public function get_controller_type()
    {
        return 'entity';
    }

    public function set_show_invisible($is_shown)
    {
        // bet kokiu atveju, nepasiekiamų elementų matyti nenorim.
        $this->set_show_unavailable(false);

        if (!isset($this->fields['visible'])) {
            return false;
        }
        if ($is_shown) {
            $this->disable_condition('visible');
        } elseif (!$this->enable_condition('visible')) {
            $this->add_condition('visible', '`visible`=1', 'equal');
        }
    }

    /**
     * Patikrina ar elementas (kuri gavom su get_element() arba find_element() ir pan.) turi buti rodomas svetaineje
     *
     * @param unknown_type $element
     * @return unknown
     */
    public function is_available($element)
    {
        // galbut tokio elemento visai nera?
        if (empty($element)) {
            return false;
        }
        // active?
        if ((isset($element['active']) && !$element['active'])) {
            return false;
        }
        // valid_from?
        if (!empty($element['valid_from']) && (!$element['valid_from'] > date("Y-m-d"))) {
            return false;
        }
        // valid_till?
        if (!empty($element['valid_till']) && (!$element['valid_till'] < date("Y-m-d"))) {
            return false;
        }
        // lang?
        $lang = isset($this->config['lang']) ? $this->config['lang'] : LANG;
        if (!empty($element['lang']) && ($element['lang'] != $lang)) {
            return false;
        }
        // is_available($page)?
        if (!empty($element['page'])) {
            $pages_controller = $this->load_entity_controller('pages');
            if (!$page = $pages_controller->get_element($element['page'])) {
                return false;
            }
            if (!$pages_controller->is_available($page)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Ar elementas yra pasiekiamas (atsizvelgiant i teviniu elementu savybe '$field_name' (pvz 'active'))?
     *
     * @param int $id
     * @param string $field_name - lauko pavadinimas
     * @return boolean
     */
    public function is_accessible($elem, $field_name = 'active')
    {
        $result = true;
        if (!isset($this->fields[$field_name])) {
            return $result;
        }
        if ($this->get_value($elem, $field_name) == 0) {
            $result = false;
        } else {
            if (isset($this->fields['parent'])) {
                if ($parent = $this->get_value($elem, 'parent')) {
                    $result = $this->is_accessible($parent, $field_name);
                }
            }
        }
        return $result;
    }

    public function get_value($id, $field)
    {
        if (isset($this->_cache['elements'][$id][$field])) {
            return $this->_cache['elements'][$id][$field];
        }
        if (($element = $this->get_element($id))) {
            $this->_cache['elements'][$id] = $element;
            if (isset($element[$field])) {
                return $element[$field];
            }
        }
        return false;
    }

    // TODO: padaryti saugojima per entity_controller

    public function get_element($id, $key = 'id', $formatting_mode = false, $cache = true)
    {
        $element = false;
        if ($key != 'id') {
            $cache = false;
        }
        //debug ("$key:$id");
        if ($cache && isset($this->_cache['elements'][$id])) {
            $element = $this->_cache['elements'][$id];
        } else {
            $params = array(
                'where' => "`$key`='$id'",
                'fields' => $this->select_fields,
            );
            if ($element = $this->select_element($params)) {
                $this->_cache['elements'][$element[$key]] = $element;
            }
        }
        if ($element && $formatting_mode) {
            $this->format($element, $formatting_mode);
        }
        return $element;
    }

    public function format(&$data, $mode = 'default')
    {
        if (!$mode || !$data || !is_array($data)) {
            return;
        }
        // keletas formatavimo taisykliu?
        if (is_array($mode)) {
            foreach ($mode as $m) {
                $this->format($data, $m);
            }
            return;
        }

        // elementu sarasas?
        if (!isset($data['id'])) {
            foreach ($data as &$element) {
                $this->format($element, $mode);
            }
            return;
        }

        // vienas elementas?
        if (isset($data['id'])) {
            if (!isset($data['_formatted'][$mode])) {
                $data['_formatted'][$mode] = true;
                $this->prepare_element($data);
                if (method_exists($this, $method_name = 'format_element_' . $mode)) {
                    $data = $this->$method_name($data);
                }
            }
            // suderinamumas su senu kodu
            if (!isset($data['_formatted']["format_element:$mode"])) {
                $data['_formatted']["format_element:$mode"] = true;
                $data = $this->format_element($data, $mode);
            }
        }
    }

    public function prepare_element(&$element)
    {
        if (is_array($element) && is_numeric(@$element['id'])) {
            $element += ($this->get_element($element['id']) ?: array());
        } elseif (is_numeric($element)) {
            $element = $this->get_element($element);
        } else {
            return false;
        }
    }

    /**
     * Suformatuoja viena elementa.
     *
     * @param array $element
     * @param string mode - formatavimo režimas
     * @return unknown
     */
    public function format_element($element, $mode = 'default')
    {
        // Turetu buti nebenaudojama.
        // Formatavimo taisykles aprasomos ::format_element_... metoduose, o kvieciamos su ::format($element, $mode).

        if (!@$element['_formatted'][$mode]) {
            // buvo kreiptasi tiesiai i ->format_element(...)
            $this->format($element, $mode);
        }
        return $element;
    }

    public function format_errors($errors = false)
    {
        if (!$errors) {
            $errors = $this->get_errors();
        }
        if (!$errors) {
            return $this->get_last_error();
        } else {
            $messages = array();
            foreach ($errors as $error) {
                $messages[] = ($error['label'] ? "$error[label]: " : '') . $error['message'];
            }
            return implode('<br/>', $messages);
        }
    }

    public function get_last_error()
    {
        return $this->last_error;
    }

    /**
     * Naudojama hierarchinese strukturose (kur yra laukas 'parent').
     * Suformuojamas masyvas is visu elemento proteviu.
     *
     * @param unknown_type $element_id
     * @param unknown_type $url
     * @return unknown
     */
    public function get_element_path_array($element_id, $url = false)
    {
        $result = array();
        $element['parent'] = $element_id;
        while (isset($element['parent']) && ($element = $this->get_element($element['parent']))) {
            $result[] = array(
                'title' => $element['name'],
                'url' => $url ? $this->get_full_url($element) : false,
            );
        }
        return array_reverse($result);
    }

    /**
     * grazina pilna url, kuriuo elementas pasiekiamas svetaineje
     *
     * @param unknown_type $element
     */
    public function get_full_url($element)
    {
        $this->prepare_element($element);
        $this->format($element, 'translate');
        if (!@$element['url']) {
            return false;
        }
        if (@$element['page']) {
            $page_url = $this->get_e('pages')->get_full_url($element['page']);
        } elseif (@$element['page_id']) {
            $page_url = $this->get_e('pages')->get_full_url($element['page_id']);
        } else {
            $page_url = $this->get_e('pages')->get_full_url_by_type($this->get_name());
        }
        return $page_url ? $page_url . $element['url'] . '/' : false;
    }

    /**
     * Pakelia elementa per viena pozicija aukstyn.
     *
     * @param unknown_type $id
     * @return boolean
     */
    public function element_up($id, $where_clause = false)
    {
        return $this->element_up_down($id, +1, $where_clause);
    }

    public function element_up_down($id, $position, $where_clause = false)
    {
        $this->process_filter(); // kad veiktu kartu su filtru
        $element1 = $this->get_element($id, 'id', false, false);
        if (!$element1) {
            return false;
        }
        $params['where'] = $where_clause ? array($where_clause) : array();
        $params['where'][] = '`position`' . ($position > 0 ? '<' : '>') . "'$element1[position]'";
        $params['where'] = '(' . implode(') AND (', $params['where']) . ')';
        $params['order_by'] = 'position ' . ($position > 0 ? 'DESC ' : '');
        if ($element2 = $this->select_element($params)) {
            $params1 = array('id' => $element1['id'], 'position' => $element2['position']);
            $params2 = array('id' => $element2['id'], 'position' => $element1['position']);
            if ($this->save($params1) && $this->save($params2)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Paima konteinerio (komentaru, tag'u ir pan.) id. Turint kontainerio id,
     * paskui bus galima atrinkti jo elementus, pvz konkrecios naujienos komentarus.
     *
     * @param unknown_type $entity_id - isorinis raktas i esybe
     * @param boolean $auto_create - ar sukurti nauja konteineri, jei jo nera?
     * @param unknown_type $table - konteineriu lentele
     */
    // TODO: ar tas kazkur naudojamas?
    public function process_filter()
    {
        if (empty($this->config['filter']) || empty($this->config['filter_config'])) {
            return;
        }

        foreach ($this->config['filter'] as $filter_key => $filter_value) {
            if (empty($this->config['filter_config'][$filter_key])) {
                continue;
            }
            $filter_config = $this->config['filter_config'][$filter_key];

            if (isset($filter_config['filter_type']) && $filter_config['filter_type'] == 'field') {
                // default filtras
                if ($filter_value || is_numeric($filter_value)) {
                    $operator = isset($filter_config['operator']) ? $filter_config['operator'] : "=";
                    if (strtolower($operator) == 'like') {
                        $filter_value = "%$filter_value%";
                    }
                    $field_name = !empty($filter_config['field_name']) ? $filter_config['field_name'] : $filter_key;
                    if (isset($this->fields[$field_name])) {
                        $condition = "`$field_name` $operator '$filter_value'";
                        $condition_type = isset($filter_config['condition_type']) ? $filter_config['condition_type'] : "mixed";
                        $this->add_condition($filter_key, $condition, $condition_type);
                    }
                }
            } else {
                // custom filtras
                if (!empty($filter_config['function']) && method_exists($this, $method = 'process_filter_' . $filter_config['function'])) {
                    $this->$method($filter_key, @$filter_config['fields']);
                }
            }
        }
    }

    /**
     * Nuleidzia elementa per viena pozicija zemyn.
     *
     * @param unknown_type $id
     * @return boolean
     */
    public function element_down($id, $where_clause = false)
    {
        return $this->element_up_down($id, -1, $where_clause);
    }


    public function get_container_id($entity_id, $auto_create, $table)
    {
        $table_id = $this->get_table_id();
        $params = array(
            'fields' => 'id',
            'table' => $table,
            'where' => "foreign_key='$entity_id' AND table_id=$table_id",
        );
        if ($element = $this->select_element($params)) {
            return $element['id'];
        } elseif ($auto_create) {
            Database::query("INSERT INTO $table (`table_id`, `foreign_key`) VALUES ('$table_id', '$entity_id')");
            return Repository::$db->close();
        }
        return false;
    }

    /**
     * Pagal lenteles varda gauna jos ID "lenteliu lenteleje".
     *
     * @param string $tbl_name
     * @return int
     */
    public function get_table_id($tbl_name = null)
    {
        if (empty($tbl_name)) {
            if (!empty($this->config['table'])) {
                $tbl_name = $this->config['table'];
            } else {
                return false;
            }
        }
        if (isset($this->_cache['table_ids'][$tbl_name])) {
            return $this->_cache['table_ids'][$tbl_name];
        } else {
            list($table_id) = Database::get_array("SELECT id FROM lite_tables WHERE `table_name`='$tbl_name'");
            return $this->_cache['table_ids'][$tbl_name] = $table_id;
        }
    }

    public function list_all_elements($where_clause = null, $apply_formating = 'default')
    {
        $params = array(
            'limit' => false,
        );
        $where = $where_clause ? array($where_clause) : array();
        $params['where'] = implode(' AND ', $where);
        $elements = $this->select_elements($params);
        if ($elements && $apply_formating) {
            $this->format($elements, $apply_formating);
        }
        return $elements;
    }

    /**
     * Grąžina pirmą esybės elementą.
     *
     * @param $where
     * @param $formatting_mode
     * @return unknown_type
     *
     * TODO: įjungti where sąlygą.
     */
    public function get_first_element($where = false, $formatting_mode = false)
    {
        if ($element = $this->select_element()) {
            $this->format($element, $formatting_mode);
            return $element;
        } else {
            // konteineryje nera elementu
            return false;
        }
    }

    /**
     * Grąžina reikalingą kiekį atsitiktinių suformatuotų esybės elementų.
     * Dėmesio!!! Naudoti tik, kai reikia daugiau nei vieno elemento. Žr. Model::get_random_elements()
     *
     * @param    $count - reikalingas kieks
     * @param    $where - papildomos sąlygos
     * @param    $formatting_mode
     * @return    array
     *
     * @author    kran
     * @since    0.7
     * @date    2008-11-11
     *
     */
    public function get_random_elements($count = 1, $where = false, $formatting_mode = 'list')
    {
        $params = array(
            'order_by' => 'RAND()',
            'where' => $where,
            'limit' => $count,
        );
        $elements = $this->select_elements($params);
        if (!empty($elements)) {
            $this->format($elements, $formatting_mode);
        }
        return $elements;
    }

    /**
     * Grąžina vieną suformatuotą esybės elementą pagal pateiktas sąlygas.
     *
     * @param    $where
     * @param    $formatting_mode
     * @return    array
     *
     * @author    kran
     * @since    0.7
     * @date    2008-11-11
     *
     */
    public function get_random_element($where = false, $formatting_mode = 'list')
    {
        if (empty($where)) {
            $where = '(1=1)';
        }
        if (($conditions = $this->get_conditions())) {
            $where_conds = array_merge($where, $conditions);
            $where .= ' AND ' . implode(' AND ', $where_conds);
        }

        $offset_result = Database::query("SELECT FLOOR(RAND() * COUNT(*)) AS `offset` FROM `{$this->config['table']}` WHERE $where");
        $offset_row = $offset_result->fetch_object();
        $offset = $offset_row->offset;
        $params = array(
            'where' => $where,
            'limit' => "$offset, 1",
        );
        $result = $this->select_elements($params);
        $element = empty($result) ? false : $result[0];
        if (!empty($element)) {
            $this->format($element, $formatting_mode);
        }
        return $element;
    }

    public function init_filter()
    {
        return true;
    }

    public function read_filter()
    {
        if (Form::form_requested('filter')) {
            Form::fix_post();
            foreach (!empty($this->config['filter_config']) ? $this->config['filter_config'] : array() as $filter_name => $filter_config) {
                $this->add_config("filter/$filter_name", isset($_POST[$filter_name]) ? $_POST[$filter_name] : false, 'session');
            }
            Response::redirect(FULL_URL);
        }
    }

    public function prepare_filter()
    {
        if (empty($this->config['filter_config'])) {
            return false;
        }
        foreach ($this->config['filter_config'] as $filter_key => $filter) {
            if (!empty($filter['function']) && method_exists($this, $method = 'prepare_filter_' . $filter['function'])) {
                if ($options = $this->$method()) {
                    $this->add_config("filter_config/$filter_key/options", $options);
                } else {
                    // Jeigu nera pasirinkimo (option'u), tai nereikia rodyti filtro visai.
                    unset($this->config['filter_config'][$filter_key]);
                }
            }
        }
    }

    public function prepare_filter_page()
    {
        $options = false;
        if (isset($this->fields['page']) && ($pages = $this->get_page_options())) {
            $options = array('' => '...') + $pages;
        }
        return $options;
    }

    public function get_page_options($type = false)
    {
        $pages = $this->list_pages($type);
        $result = array();
        foreach ($pages as $page) {
            $result[$page['id']] = $page['path_name'];
        }
        asort($result);
        return $result;
    }

    /**
     * Formuoja esybes tipo puslapiu sarasa
     *
     */
    public function list_pages($type = false)
    {
        // pagal nutylėjimą, puslapio tipą ($type) nustatom patys
        if (!$type) {
            if (isset($this->config['page_usage']) && ($this->config['page_usage'] == 'language')) {
                $type = 'language';
            } else {
                $type = $this->get_entity_page_type();
            }
        }
        $pages_controller = $this->load_entity_controller('pages');
        $pages = $pages_controller->list_elements("`type`='$type'", 'page_list');
        return $pages;
    }

    public function prepare_filter_tags()
    {
        return $this->get_available_tags();
    }

    /**
     * Gražina sąrašą tagų, kurie gali būti priskirti esybei.
     *
     * @param unknown_type $full_tag_entities - jeigu true, grąžina pilnas tag esybes, priešingu atveju
     *    tik vardų sąrašą
     * @return unknown
     */
    public function get_available_tags($full_tag_entities = false)
    {
        $tags = array();
        if (!empty($this->config['tags'])) {
            $key = $full_tag_entities ? 'full_available_tags' : 'available_tags';
            if (isset($this->_cache[$key])) {
                return $this->_cache[$key];
            }
            $tags_controller = $this->load_entity_controller('tags'); //' \'\' OR prefix IS NULL '
            $where = " (prefix='' OR prefix IS NULL)";
            if (!empty($this->config['tags_prefix'])) {
                $where = " prefix='" . $this->config['tags_prefix'] . "' ";
            }
            $tag_list = $tags_controller->list_elements($where);
            foreach ($tag_list as $tag) {
                $tags[$tag['id']] = $full_tag_entities ? $tag : $tag['name'];
            }
            $this->_cache[$key] = $tags;
        }
        return $tags;
    }

    public function process_filter_keywords($filter_key, $fields = array())
    {
        $filter = $this->config['filter'];
        if (!empty($filter['keywords'])) {
            $search = array();
            foreach ($fields as $field) {
                if (isset($this->fields[$field])) {
                    $search[] = "`$field` LIKE '%{$filter['keywords']}%'";
                }
            }
            if ($search) {
                $condition = '(' . implode(') OR (', $search) . ')';
                $this->add_condition('keywords', $condition);
            }
        }
    }

    /**
     * Nustato tag'a, pagal kuri bus selectinami esybes elementai
     *
     * @param int $tag_id
     */
    public function set_tag($tag_id)
    {
        $this->set_tags(array($tag_id));
    }

    /**
     * Nustato tag'us, pagal kuriuos bus selectinami esybes elementai.
     *
     * @param array $tag_list
     */
    public function set_tags($tag_list = array())
    {
        $rel_e = $this->get_e('relations');
        $relation_id = $rel_e->get_relation_id('tag_' . $this->get_name());

        if ($tag_list) {
            $tag_ids = implode(',', $tag_list);
            $this->add_condition('tags', "id IN (SELECT related_element_id FROM lite_related_elements WHERE relation_id={$relation_id} AND element_id IN ({$tag_ids}) )");
        } else {
            $this->disable_condition('tags');
        }
    }

    public function process_filter_tags()
    {
        $filter = $this->config['filter'];
        if (!empty($filter['tags'])) {
            $this->set_tags(array_keys($filter['tags']));
        }
    }

    public function process_filter_not_empty($filter_key)
    {
        $filter_value = $this->config['filter'][$filter_key];
        $cond = "(`$filter_key` IS NULL) OR (`$filter_key`='')";
        if ($filter_value == 1) {
            $cond = "NOT ($cond)";
        }
        if ($filter_value) {
            $this->add_condition($filter_key, $cond);
        } else {
            $this->disable_condition($filter_key);
        }
    }

    public function select($fields = '')
    {
        $this->select_fields = $fields;

        return $this;
    }

    public function find_all($where = "", $auto_params = true)
    {
        return $this->select_elements(array("where" => $where, 'auto_params' => $auto_params));
    }

    public function find_by_url($url, $lang = false, $format_mode = false)
    {
        if (!$lang) {
            $lang = Translator::$language;
        }

        $tfe = $this->get_e('translated_fields');
        $tfe->add_condition('entity_url', "entity_name = '{$this->get_name()}' AND lang = '{$lang}' AND field_name = 'url' AND value>''");

        // ieskom URL vertimuose
        foreach ($tfe->find_elements("value = '{$url}'") as $translation) {
            if ($element = $this->get_element($translation['entity_id'], 'id', $format_mode)) {
                return $element;
            }
        }

        // jei nera vertimuose, ieskom prie entity
        if ($element = $this->get_element($url, 'url', $format_mode)) {
            // patikrinam, ar nera papildomai isverstas
            if ($tfe->find_element("entity_id=$element[id]")) {
                return false;
            }
            return $element;
        }
        return false;
    }

    public function set_active($element_id, $is_active = 0)
    {
        if (isset($this->fields['active'])) {
            $params = array(
                'id' => $element_id,
                'active' => $is_active ? 1 : 0,
            );
            return $this->save($params);
        }
        return false;
    }

    /**
     * Suformatuoja visus masyvo elementus.
     *
     * @param unknown_type $elements
     * @return unknown
     */
    public function format_elements($elements, $mode = 'default')
    {
        $this->format($elements, $mode);
        return $elements;
    }

    public function format_element_meta_fields($element)
    {
        if (isset($this->config['meta_fields']) && $this->config['meta_fields']) {
            $entity_name = $this->get_name();
            $entity_id = $element['id'];

            $meta_data = Database::get_assoc("SELECT * FROM lite_meta_fields WHERE `entity_name`='$entity_name' AND `foreign_key`='$entity_id'");

            if ($meta_data) {
                $element['meta_title'] = $meta_data['meta_title'];
                $element['meta_description'] = $meta_data['meta_description'];
                $element['meta_keywords'] = $meta_data['meta_keywords'];
                $element['header_title'] = $meta_data['header_title'];
                $element['nofollow'] = $meta_data['nofollow'];
                $element['noindex'] = $meta_data['noindex'];
            } else {
                $element['meta_title'] = '';
                $element['meta_description'] = '';
                $element['meta_keywords'] = '';
                $element['header_title'] = '';
                $element['nofollow'] = 0;
                $element['noindex'] = 0;
            }
        }
        return $element;
    }

    public function format_element_default($element)
    {
        $this->format($element, array('meta_fields', 'translate', 'photo_info', 'full_url'));

        if (!empty($this->config['tags'])) {
            $element['tag_ids'] = @$element['tag_ids'] ?: array_values($this->get_tags($element['id']));
            $element['tags'] = [];
            foreach ($element['tag_ids'] ?: [] as $tag_id) {
                $element['tags'][] = $this->get_e('tags')->get_element($tag_id,'id', $this->get_e('tags')->config['photos']?'photos':'default');
            }
        }

        return $element;
    }

    public function get_tags($element_id)
    {
        $tags = $this->get_e('relations')->get_elements_by_relation('tags_' . $this->get_name(), $element_id);

        return $tags;
    }

    public function format_element_list($element)
    {
        $this->format($element, 'default');

        // FIXME: turetu buti page_id
        if (!empty($element['page'])) {
            $pages_controller = $this->load_entity_controller('pages');
            $element['page_name'] = $pages_controller->get_value($element['page'], 'name');
        }

        if (!empty($element['page_id'])) {
            $element['page'] = $this->get_e('pages')->get_element($element['page_id'], 'id', 'full_url');
        }

        return $element;
    }

    public function format_element_detailed($element)
    {
        $this->format($element, array('default', 'photos'));
        return $element;
    }

    /**
     * Gražina esybei priskirtus konteinerio $container_name elementus
     * @param $container_type
     * @param $element
     * @return unknown_type
     */
    protected function get_container_elements($container_type, $element, &$return_params)
    {
        $result = false;
        if (!empty($this->config[$container_type])) {
            $entity_container_controller = $this->load_entity_controller($container_type);

            //konteinerio esybės puslapiavimas kiek skirsis nuo pagrindinės modulio esybės - naudosim kitą parametrą.
            if (!empty($_GET[$param_name = $entity_container_controller->get_name() . '_page'])) {
                $entity_container_controller->config['page'] = $_GET[$param_name];
            }

            $result = $entity_container_controller->get_entity_elements($element['id'], 'default', $return_params);
        }

        return $result;
    }

    public function format_element_filters_detailed($element)
    {
        $selected_filters = Database::get_assoc_all("
			SELECT pf.*, f.type, f.name as filter_name
			FROM lite_filter_values_elements AS pf
			JOIN lite_filters as f ON f.id=pf.filter_id
			WHERE pf.element_id=$element[id] AND pf.entity_name = '{$this->get_name()}' AND show_in_desc ORDER BY position ASC
		");

        $element['filters'] = array();
        $f_e = $this->get_e('filters');
        foreach ($selected_filters as $filter) {
            $filter_info = $f_e->get_element($filter['filter_id'], 'id', 'translate');
            switch ($filter['type']) {

                case 'text':
                    if ($filter['text_value']) {
                        $element['filters'][$filter_info['name']] = array("type" => "text", "value" => $filter['text_value']);
                    }
                    break;

                case 'select':
                    if ($filter['filter_value_id']) {
                        $value = $this->get_e('filter_values')->get_element($filter['filter_value_id']);
                        $element['filters'][$filter_info['name']] = array("type" => "text", "value" => $value['name']);
                    }
                    break;


                case 'color':
                    if ($filter['filter_value_id']) {
                        $value = $this->get_e('filter_values')->get_element($filter['filter_value_id']);
                        $element['filters'][$filter_info['name']] = array("type" => "color", "value" => $value['name']);
                    }
                    break;

                case 'checkbox':
                    $element['filters'][$filter_info['name']] = array("type" => "checkbox", "value" => ($filter['checked_value'] ? true : false));
                    break;

                case 'image':
                    if ($filter['filter_value_id']) {
                        $value = $this->get_e('filter_values')->get_element($filter['filter_value_id'], 'id', 'photos');
                        $element['filters'][$filter_info['name']] = array("type" => "image", "value" => $value['name']);
                        if (isset($value["photos"])) {
                            $element['filters'][$filter_info['name']]["photos"] = $value["photos"];
                        }
                    }
                    break;
                case 'multi_select':
                case 'checkboxes':
                    if ($filter['filter_value_id']) {
                        $value = $this->get_e('filter_values')->get_element($filter['filter_value_id']);
                        $element['filters'][$filter_info['name']] = array("type" => "texts", "value" => array());
                        $element['filters'][$filter_info['name']]["value"][] = $value['name'];
                    }
                    break;
            }
        }
        return $element;
    }

    public function format_element_filters_edit($element)
    {
        $selected_filters = Database::get_assoc_all("
			SELECT pf.*, f.type, f.name as filter_name
			FROM lite_filter_values_elements AS pf
			JOIN lite_filters as f ON f.id=pf.filter_id
			WHERE pf.element_id=$element[id] AND pf.entity_name='{$this->get_name()}'
		");
        $element['filters'] = array();
        foreach ($selected_filters as $filter) {
            switch ($filter['type']) {

                case 'text':
                    $element['filters'][$filter['type']][$filter['filter_id']] = $filter['text_value'];
                    break;

                case 'select':
                case 'color':
                    $element['filters'][$filter['type']][$filter['filter_id']] = $filter['filter_value_id'];
                    break;

                case 'checkbox':
                    $element['filters'][$filter['type']][$filter['filter_id']] = $filter['checked_value'];
                    break;

                case 'image':
                case 'multi_select':
                case 'checkboxes':
                    $element['filters'][$filter['type']][$filter['filter_id']][] = $filter['filter_value_id'];
                    break;
            }
        }
        return $element;
    }

    public function format_element_full_url($element)
    {
        $element['full_url'] = $this->get_full_url($element);
        return $element;
    }

    public function format_element_translate($element)
    {
        if (!empty($this->config['translated_fields'])) {
            $entity_name = $this->get_name();
            if (is_null(Repository::$translated_fields) || !isset(Repository::$translated_fields[$entity_name][$element['id']])) {
                // uzkeshuojam visus vertimus i Repository, kad nebutu bereikalingu queriu i DB
                if (is_null(Repository::$translated_fields)) {
                    Repository::$translated_fields = array();
                }
                $q = Database::query("SELECT * FROM lite_translated_fields WHERE value>'' AND entity_name = '$entity_name' AND entity_id='$element[id]'");
                while ($r = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
                    Repository::$translated_fields[$r['entity_name']][$r['entity_id']][$r['field_name']][$r['lang']] = $r['value'];
                }
            }
            $lang = Repository::$app && @Repository::$app->lang_key ? Repository::$app->lang_key : false;
            if (@Repository::$translated_fields[$entity_name][$element['id']]) {
                foreach (Repository::$translated_fields[$entity_name][$element['id']] as $field_name => $lang_info) {
                    foreach ($lang_info as $lang_key => $val) {
                        $translation_found = false;
                        if (@$element['_formatted']['edit']) {
                            // nereikia versti, jeigu edit'inam
                            $translation_found = true;
                        }
                        $element["{$field_name}_{$lang_key}"] = $val;
                        if (!$translation_found && $val && $lang == $lang_key) {
                            $element[$field_name] = $val;
                        }
                    }
                }
            }
        }
        return $element;
    }

    public function format_element_photos($element)
    {
        if (!empty($this->config['photos'])) {
            $photos_controller = $this->load_entity_controller("photos");
            $element = $element + $photos_controller->get_entity_elements($element['id'], 'default');
        }
        return $element;
    }

    public function format_element_files($element)
    {
        if (!empty($this->config['files'])) {
            $files_controller = $this->load_entity_controller('files');
            $element = $element + $files_controller->get_entity_elements($element['id'], 'default');
        }
        return $element;
    }

    public function format_element_first_photo($element)
    {
        if (empty($element['photo']) && !empty($this->config['photos'])) {
            $photos_controller = $this->load_entity_controller("photos");
            $element['photo'] = $photos_controller->get_first_entity_element($element['id'], 'default', 'photos');
        }
        return $element;
    }

    public function format_element_comments($element)
    {
        if (!empty($this->config['comments'])) {
            $comments_controller = $this->load_entity_controller('comments');
            $comments_controller->load_get_params();
            $comments_info = false;
            $element['comments'] = $comments_controller->get_entity_elements($element['id'], 'list', $comments_info);
            $element['comments_info'] = $comments_info;
        }
        return $element;
    }

    public function delete_hierarchy($id)
    {
        $result = true;
        // istrinam visus vaikus
        $elements = $this->select_elements(array(
            'fields' => 'id',
            'where' => "`parent`='$id'",
        ));
        foreach ($elements as $element) {
            $result &= $this->delete_hierarchy($element['id']);
        }
        // istrinam elementa (kiekviena esybe turi moketi istrinti savo elements)
        $result &= $this->delete_element($id);
        return $result;
    }

    /**
     * Istrina irasa ir visus kitus su juo susijusius irasus (komentarus, tagus ir pan.)
     *
     * @param unknown_type $id
     * @param unknown_type $key
     * @return unknown
     */
    public function delete_element($id, $key = 'id')
    {
        if ($key != 'id') {
            $result = 0;
            $elements = $this->find_elements("`$key`='$id'");
            foreach ($elements as $element) {
                $result += $this->delete_element($element['id'], 'id');
            }
            return $result;
        }

        $where = array("`$key`='$id'");
        if ($conditons = $this->get_conditions()) {
            $where = array_merge($where, $conditons);
        }
        $where_clause = '(' . implode(') AND (', $where) . ')';
        Database::query('DELETE FROM ' . $this->config['table'] . " WHERE $where_clause");
        $result = Repository::$db->affected_rows;

        //ištriname visas įmanomas esybes, pririštas per konteinerius
        foreach ($this->available_containers as $container_name) {
            if (!empty($this->config[$container_name])) {
                $entity_container_controller = $this->load_entity_controller($container_name);
                $entity_container_controller->delete_container($id);
            }
        }

        // trinam susijusias zymes
        if (!empty($this->config['tags'])) {
            // TODO: nera tokio metodo
            // $this->remove_all_tags($id);
        }

        if (!empty($this->config['filters'])) {
            $this->remove_all_filters($id);
        }

        if ($result) {
            if (!empty($this->config['audit'])) {
                $audits_controller = $this->load_entity_controller('audits')->log($id, 'deleted');
            }
        }
        return $result;
    }

    public function find_elements($where = "", $format_mode = false, $limit = false)
    {
        $elements = $this->select_elements(array('where' => $where, 'limit' => $limit));
        if ($format_mode) {
            $this->format($elements, $format_mode);
        }
        return $elements;
    }

    public function remove_all_filters($element_id)
    {
        Database::query("DELETE FROM lite_filter_values_elements WHERE entity_name='" . $this->get_name() . "' AND element_id='$element_id'");
        return Repository::$db->affected_rows;
    }

    /**
     * Apskaiciuoja kiek medis turi palikuoniu (vaiku, anuku ir t.t.).
     *
     * @param unknown_type $tree
     * @return unknown
     */
    public function count_total(&$tree)
    {
        if (!isset($tree['count'])) {
            $tree['count'] = 0;
        }
        if (!empty($tree['childs'])) {
            foreach ($tree['childs'] as &$item) {
                $tree['count'] += $this->count_total($item);
            }
        }
        return $tree['count'];
    }

    public function search($keywords, $fields = array())
    {
        $params = array(
            'limit' => 50, // max. paieskos rezultatų.
        );
        if (empty($fields)) {
            $fields = array('name', 'description', 'short_description', 'content', 'text');
        }
        $where = array();
        foreach ($fields as $field_name) {
            if (isset($this->fields[$field_name])) {
                $where[] = "`$field_name` LIKE '%$keywords%'";
            }
        }
        if ($where) {
            $params['where'] = '(' . implode(') OR (', $where) . ')';
        }
        $elements = $this->select_elements($params);
        $this->format($elements, 'search');
        return $elements;
    }


    public function edit_entity_page($params)
    {
        if (isset($this->fields['page'])) {
            if (!$this->find_element("`page` = {$params['id']}")) {
                return $this->create_entity_page($params);
            }
        }
        return true;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $where
     * @param unknown_type $format_mode - parametras formatavimo metodui (formatavimo tipas)
     * @param unknown_type $additional_params
     * @return unknown
     */
    public function find_element($where = false, $format_mode = false, $additional_params = array())
    {
        $result = false;
        $params = array_merge(array(
            'where' => $where,
            'limit' => '1',
            'fields' => $this->select_fields,
        ), $additional_params);
        if ($element = $this->select_element($params)) {
            if (!empty($format_mode)) {
                $this->format($element, $format_mode);
            }

            if (!empty($this->select_fields) && count($element) === 1) {
                $element = $element[$this->select_fields];
            }

            $result = $element;
        }
        return $result;
    }

    /**
     * Pagal paduotus parametrus ir papildomą where sąlygą, grąžina unikalų elemento URL
     *
     * @param unknown_type $params
     * @param unknown_type $where_clause
     * @return unknown
     */
    //TODO: kadangi url yra sugeneruojamas automatiškai iš save() metodo, reikia pereiti per visus
    //senuosius entity/modulius/modelius ir pašalinti prepare_url()

    public function create_entity_page($params)
    {
        if (isset($this->fields['page'])) {
            // naujo elemento su lauku 'page' sukurimas
        }
        return true;
    }


    public function delete_entity_page($page_id)
    {
        if (isset($this->fields['page'])) {
            $this->load_page($page_id);
            $this->delete_elements();
        }
        return true;
    }

    public function load_page($page_id = false)
    {
        if (!isset($this->fields['page'])) {
            return false;
        }
        // Pagal nutylėjimą, page_id nustatom patys.
        if (($page_id === false) && (Repository::$frontend)) {
            $page_id = Repository::$frontend->page['id'];
        }
        if ($page_id) {
            $this->add_condition('page', "`page`='$page_id'", 'equal');
        } else {
            $this->disable_condition('page');
        }
    }

    public function delete_elements($where = false)
    {
        $elements = $this->find_elements($where);
        foreach ($elements as $el) {
            $this->delete_element($el['id']);
        }
    }

    public function full_url($id)
    {
        $full_url = '';
        $page['parent'] = $id;
        while ($page = $this->get_element($page['parent'])) {
            $full_url = $page['url'] . '/' . $full_url;
        }
        return PROJECT_URL . $full_url;
    }

    /**
     * Pagal paduotą matmenų masyvą ir kelią iki paveikslėlio ($image_path), suformuoja transformuotų paveikslėlių masyvą.
     * Dažniausiai naudojama formatavime.
     *
     * @return array
     */
    public function get_tr_images($sizes_config = array(), $image_path)
    {
        $tr_images = array();
        if (!empty($sizes_config) && is_array($sizes_config)) {
            foreach ($sizes_config as $config_name => $params) {
                $tr_images[$config_name] = (($file = Image::cached_image_path($image_path, $params)) && file_exists($file)) ? PROJECT_URL . $file : RESOURCES_URL . "tr_images/$image_path?" . Arr::array2url($params);
            }
        }
        return $tr_images;
    }

    public function save_image($img, $id = false, &$error = 0)
    {
        $img_config = array_merge(array('path' => 'images/misc/', 'max_size' => IMAGE_MAX_SIZE), $this->config['gallery']);


        if ($entity_name = $this->parent_object->get_name()) {
            if (!file_exists($img_config['path'] . $entity_name)) {
                mkdir($img_config['path'] . $entity_name, 0777);
            }

            $img_config['path'] = $img_config['path'] . $entity_name . '/';
        }

        if (!empty($_FILES[$img]['size'])) {
            $file = $_FILES[$img];
            if ($file['size'] > $img_config['max_size']) {
                // nuotraukos dydis virsyja leistina
                $this->invalidate(sprintf(t('Nuotraukos dydis negali viršyti %s.'), File::human_file_size($img_config['max_size'])), $file['name']);
                $error = true;
                return false;
            } else {
                $ext = isset($img_config['type']) ? Image::get_image_extension_by_type($img_config['type']) : (File::file_ext($file['name']) ? File::file_ext($file['name']) : 'jpg');
                $config = array('ext' => $ext);
                if (isset($img_config['prefix'])) {
                    $config['prefix'] = $img_config['prefix'];
                }
                $config['length'] = isset($img_config['length']) ? $img_config['length'] : 100;
                $dest = File::generate_file_name($file['name'], $config);
                if (!Image::copy_image($file['tmp_name'], $img_config['path'] . $dest, $img_config, $error_msg)) {
                    $this->invalidate($error_msg, $img);
                    return false;
                } else {
                    $dest = $entity_name . '/' . $dest;
                    // viskas ok
                    if ($id) {
                        $this->delete_image($id, $img, $entity_name);
                        $params = array('id' => $id, $img => $dest);
                        unset($_FILES[$img]);
                        return $this->save($params);
                    } else {
                        return $dest;
                    }
                }
            }
        } else {
            // empty file
            return false;
        }
    }

    public function delete_image($id, $img, $entity_name = false)
    {
        if ($fname = $this->get_value($id, $img)) {
            $path = (!empty($this->config[$img]['path'])) ? $this->config[$img]['path'] : IMAGES_PATH . 'misc/';

            if ($entity_name) {
                $path = $path . $entity_name . '/';
            }

            return @unlink($path . $fname);
        } else {
            return false;
        }
    }

    /**
     * Prie esybės elemento prisega paveikslėlį
     *
     * @param $element_id
     * @param $image
     * @return unknown_type
     */
    public function assign_image($element_id, array $image)
    {
        $result = Database::get_assoc("SELECT id FROM lite_photo_containers WHERE (`entity_name` = '{$this->get_name()}') AND (`foreign_key` = $element_id)");
        $gallery_id = false;
        $success = true;
        if (!empty($result['id'])) {
            $gallery_id = $result['id'];
        } else {
            $success &= Database::query("INSERT INTO lite_photo_containers (entity_name, foreign_key) VALUES ('{$this->get_name()}', {$element_id})");
            $gallery_id = Repository::$db->insert_id;
        }
        $success &= Database::query("INSERT INTO lite_photos (image, name, gallery_id, position) VALUES ('{$image['image']}', '{$image['name']}', $gallery_id, 1)");
        return $success;
    }

    public function levels_list($root_element = 0, $escape_element = 0)
    {
        $hierarchy = $this->get_hierarchy($root_element, /* formatting_mode */
            false, /* where_clause */
            "id!='$escape_element'");
        return $this->format_levels($hierarchy);
    }

    /**
     * Sudaro elementu medi. Tinka esybems, kurios turi lauka "parent".
     * Vaikai sudedami i lauka "childs".
     *
     * @param int $id - sakninio elemento id
     * @param string $formatting_mode - formatavimo budas. Jei false - formatuojama nebus
     * @param string $where_clause - papildomos salygos
     * @param int $levels - maksimalus medzio gylis. Jeigu 0 - i gyli nebus reaguojama.
     * @return unknown
     */
    public function get_hierarchy($root_element = 0, $formatting_mode = false, $where_clause = "", $levels = 0, $current_level = 0)
    {
        $params['where'] = ($where_clause ? "$where_clause AND " : '') . "`parent` = $root_element";
        //$params['order_by'] = "`position` ASC, `name` ASC";
        $params['limit'] = false;
        $elements = $this->select_elements($params);
        $this->format($elements, $formatting_mode);
        foreach ($elements as &$el) {
            $el['level'] = $current_level;
        }
        if ($levels == 1) {
            return $elements;
        }
        if (Repository::$backend) {
            $this->config['marginal_positions'][$root_element] = $this->get_min_max_positions($params['where']);
        }
        foreach ($elements as &$element) {
            $element['childs'] = $this->get_hierarchy($element['id'], $formatting_mode, $where_clause, $levels ? $levels - 1 : 0, $current_level + 1);
        }
        return $elements;
    }

    public function format_levels($hierarchy, $depth = 0)
    {
        $result = array();
        foreach ($hierarchy as $item) {
            $result[$item['id']] = str_repeat(' &rarr; ', $depth) . $item['name'];
            if (!empty($item['childs'])) {
                $result += $this->format_levels($item['childs'], $depth + 1);
            }
        }
        return $result;
    }

    public function add_log($id, $params)
    {
        $module_logs_module = $this->load_entity_controller('entity_logs');
        $params = array_merge(array('foreign_key' => $id, 'entity_name' => $this->get_name()), $params);
        return $module_logs_module->save($params);
    }

    public function list_log_elements($id)
    {
        $module_logs_module = $this->load_entity_controller('entity_logs');
        $where = "(`entity_name` = '{$this->get_name()}') AND (`foreign_key` = $id)";
        return $module_logs_module->list_elements($where);
    }

    //TODO: sakykit, ką norit, bet čia paramsų per daug ;] sumažint!!

    public function delete_logs($id)
    {
        $module_logs_module = $this->load_entity_controller('entity_logs');
        $where = "(`entity_name` = '{$this->get_name()}') AND (`foreign_key` = $id)";
        return $module_logs_module->dele_elements($where);
    }

    /**
     * metodas, iškviečiamas sukūrus esybės tipo puslapį. Jame reiktų realizuoti esybės sukūrimą
     *
     * @return unknown
     */
    //TODO: realizuoti:
    //TODO: iškelti į trigerius
    public function save_file($alias, $id = false, &$error = 0)
    {
        $file_config = (isset($this->config[$alias . '_settings']) && is_array($this->config[$alias . '_settings'])) ? $this->config[$alias . "_settings"] : (!empty($this->config[$alias]) ? $this->config[$alias] : array());
        $file_config = array_merge(array('path' => 'files/', 'max_size' => 10 * 1024 * 1024), $file_config);

        if (!empty($_FILES[$alias]['size'])) {
            $file = $_FILES[$alias];
            if ($file['size'] > $file_config['max_size']) {
                // nuotraukos dydis virsyja leistina
                $this->invalidate(sprintf(t('Bylos dydis negali viršyti %s.'), File::human_file_size($file_config['max_size'])), $file['name']);
                $error = true;
                return false;
            } else {
                $file_config['fname'] = uniqid(); // perkelta is configo, nes sugeneruoja toki pat patha visiem failaims
                $dest = File::generate_file_name($file['name'], $file_config);
                if (!move_uploaded_file($file['tmp_name'], $file_config['path'] . $dest) && !rename($file['tmp_name'], $file_config['path'] . $dest)) {
                    // ivyko klaida kopijuojant
                    $this->invalidate("Nepavyko įrašyti failo ({$file_config['path']}$dest)", $alias, $file['name']);
                    // $this->add_message('error_message', $error_msg);
                    $error = true;
                    return false;
                } else {
                    // viskas ok
                    if ($id) {
                        $this->delete_file($id, $alias);
                        $params = array('id' => $id, $alias => $dest);
                        unset($_FILES[$alias]);
                        return $this->save($params);
                    } else {
                        return $dest;
                    }
                }
            }
        } else {
            // empty file
            return false;
        }
    }

    /**
     * metodas, iškviečiamas paredagavus esybės tipo puslapį. Jame patikrinama, ar egzistuoja
     * prie puslapio prisegta esybė, jeigu ne, sukuriama nauja
     *
     * @param unknown_type $params
     */
    //TODO: iškelti į trigerius
    /**
     * Sąlygos puslapiui parinkti, jeigu netinka standartinės.
     * @return unknown_type
     */
    public function get_page_conditions()
    {
        return '';
    }

    /**
     * metodas, iškviečiamas prieš ištrinant esybės tipo puslapį. Jame reiktų realizuoti esybės ištrynimą.
     *
     * @return unknown
     */
    //TODO: iškelti į trigerius
    public function get_options($where = false, $fields = array(), $seperator = ' ')
    {
        if (is_string($fields)) {
            $tmp = $fields;
            $fields = array();
            $fields[] = $tmp;
        }

        if (empty($fields)) {
            $fields[] = 'name';
        }

        $options = array();
        if ($elements = $this->select_elements(array("where" => $where))) {
            foreach ($elements as $el) {
                $values = array();
                foreach ($fields as $field) {
                    $values[] = $el[$field];
                }
                $options[$el['id']] = implode($seperator, $values);
            }
        }
        return $options;
    }

    public function clear_errors()
    {
        $this->invalidated = false;
        $this->invalidated_fields = array();
        $this->last_error = 'Nežinoma klaida';
    }

    public function backup_conditions($clear = false)
    {
        $id = uniqid();
        $this->config['conditions_backup'][$id] = !empty($this->config['conditions']) ? $this->config['conditions'] : array();
        $this->backup_id_stack[] = $id;
        if ($clear) {
            $this->config['conditions'] = array();
        }
        return $id;
    }

    public function rollback_conditions($id = false)
    {
        $tmp = array_pop($this->backup_id_stack);
        if (!$id) {
            $id = $tmp;
        }
        if (isset($this->config['conditions_backup'][$id])) {
            $this->config['conditions'] = $this->config['conditions_backup'][$id];
            return true;
        } else {
            return false;
        }
    }

    public function delete_all($id, $key)
    {
        $elements = $this->select_elements(array('where' => "`$key`='$id'"));
        foreach ($elements as $el) {
            $this->delete_element($el['id']);
        }
        return true;
    }


    public function update_photos($id, $photos, $container_name = 'photos', &$errors = null)
    {
        $errors = [];
        $all = $this->get_e('photos')->get_entity_elements($id);
        $old_photos = @$all[$container_name] ?: array();
        $tmp_files = [];
        foreach ($photos as $k => $photo) {
            if (@$photo['url']) {
                $tmp_file = sys_get_temp_dir() . "/" . uniqid();
                if (@copy($photo['url'], $tmp_file)) {
                    $photo['hash'] = md5($tmp_file);
                    $photo['image'] = $tmp_file;
                    $tmp_files[] = $tmp_file;
                    $photos[$k] = $photo;
                } else {
                    $errors[$k] = t('Nepavyko parsisiųsti ir išsaugoti paveikslėlio.');
                    unset($photos[$k]);
                }
            }
        }
        foreach ($old_photos as $ko => $old_photo) {
            $exists = false;
            foreach ($photos as $kn => $new_photo) {
                if ($old_photo['hash'] == $new_photo['hash']) {
                    $exists = $kn;
                    break;
                }
            }
            if ($exists !== false) {
                $new_name = htmlspecialchars($new_photo['name'], ENT_QUOTES);
                if ($old_photo['name'] != $new_name) {
                    $old_photo['name'] = $new_name;
                    $this->get_e('photos')->save($old_photo);
                }
                unset($old_photos[$ko]);
                unset($photos[$kn]);
            }
        }
        foreach ($old_photos as $photo) {
            $this->get_e('photos')->delete_element($photo['id']);
        }
        foreach ($photos as $k => $photo) {
            //if (!$photo['url']) break;
            $tmp_file = 'images/galleries/' . uniqid();
            if (!copy($photo['image'], $tmp_file)) {
                $errors[$k] = t('Nepavyko nukopijuoti paveikslėlio.');
                break;
            }
            if (!($info = getimagesize($tmp_file)) || !$info['mime']) {
                $errors[$k] = t('Neatpažintas paveikslėlio formatas.');
                break;
            }
            if (!($ext = File::mime2ext($info['mime'])) || !in_array($ext, array('jpg', 'png', 'gif'))) {
                $errors[$k] = sprintf(t('Nepalaikomas paveikslėlio formatas: %s'), $info['mime']);
                break;
            }
            $photo['image'] = uniqid() . '.' . $ext;
            rename($tmp_file, 'images/galleries/' . $photo['image']);
            $this->get_e('photos')->set_container($id, true, $container_name);
            if (!$this->get_e('photos')->save($photo)) {
                $errors[$k] = $this->get_e('photos')->get_errors();
                break;
            }
        }
        foreach ($tmp_files as $tmp_file) {
            unlink($tmp_file);
        }
    }

    //entity log'ai

    public function update_photo($id, $photo_path, $overwrite = false, &$error = '')
    {
        // patikrinam ar produktas turi nuotrauka
        $f = $this->get_e('photos');
        $f->set_container($id, true, 'photos');
        if ((!$photo = $f->find_element('1')) || $overwrite) {
            if (file_exists($photo_path)) {
                $ext = pathinfo($photo_path, PATHINFO_EXTENSION);
                $fname = $this->get_name() . '/' . uniqid() . ".$ext";
                $dest = 'images/galleries/' . $fname;
                if (!file_exists(dirname($dest))) {
                    mkdir(dirname($dest), 0777);
                }
                if (copy($photo_path, $dest)) {
                    $new_photo = array(
                        'image' => $fname,
                    );
                    if ($photo) {
                        $new_photo['id'] = $photo['id'];
                        @unlink('images/galleries/' . $photo['image']);
                    }
                    if ($f->save($new_photo)) {
                        //debug ("Photo added for #$id.");
                        return 1;
                    } else {
                        $error = "Error saving photo: " . $f->format_errors();
                        $f->clear_errors();
                        return false;
                    }
                }
            } else {
                $error = "Error copying photo from $photo_path to images/galleries/$fname";
                return false;
            }
        }
        return 0;
    }

    public function add_photo($id, $photo_path, &$error = '')
    {
        // patikrinam ar produktas turi nuotrauka
        if (file_exists($photo_path)) {
            $f = $this->get_e('photos');
            $f->set_container($id, true, 'photos');
            $ext = pathinfo($photo_path, PATHINFO_EXTENSION);
            $fname = $this->get_name() . '/' . uniqid() . ".$ext";
            $dest = 'images/galleries/' . $fname;
            if (!file_exists(dirname($dest))) {
                mkdir(dirname($dest), 0777);
            }
            if (copy($photo_path, $dest)) {
                $new_photo = array(
                    'image' => $fname,
                );
                if ($f->save($new_photo)) {
                    //debug ("Photo added for #$id.");
                    return 1;
                } else {
                    $error = "Error saving photo: " . $f->format_errors();
                    $f->clear_errors();
                    return false;
                }
            }
        } else {
            $error = "Error copying photo from $photo_path to images/galleries/$fname";
            return false;
        }
    }

    //end of entity logai
    // TODO: Viskas zemiau, tiesiog perkelta is modelio, bet netvarkyta

    public function load_meta_fields($element, $type = 'default')
    {
        $config = $this->config;
        if (isset($config['meta_fields']) && $config['meta_fields']) {
            // meta fields
            $title = (strlen($element['header_title']) > 0 ? $element['header_title'] : $element['name']);
            $this->app->set_title($title);
            $this->app->page['meta_title'] = $element['meta_title'];
            $this->app->page['meta_description'] = $element['meta_description'];
            $this->app->page['meta_keywords'] = $element['meta_keywords'];
            $this->app->page['nofollow'] = $element['nofollow'];
            $this->app->page['noindex'] = $element['noindex'];
        } elseif ($type == 'condition') {
            $this->app->set_title($element['name']);
        }

        return 0;
    }

    public function enable_join($name)
    {
        if (empty($this->config['joins'][$name])) {
            return false;
        }
        $this->config['joins'][$name]['disabled'] = false;
        return true;
    }

    public function disable_join($name)
    {
        if (empty($this->config['joins'][$name])) {
            return false;
        }
        $this->config['joins'][$name]['disabled'] = true;
        return true;
    }

    public function get_join($name)
    {
        return !empty($this->config['joins'][$name]['condition']) ? array($name => $this->config['joins'][$name]) : false;
    }

    public function backup_joins($clear = false)
    {
        $id = uniqid();
        $this->config['joins_backup'][$id] = !empty($this->config['joins']) ? $this->config['joins'] : array();
        $this->backup_id_stack[] = $id;
        if ($clear) {
            $this->config['joins'] = array();
        }
        return $id;
    }

    public function rollback_joins($id = false)
    {
        $tmp = array_pop($this->backup_id_stack);
        if (!$id) {
            $id = $tmp;
        }
        if (isset($this->config['joins_backup'][$id])) {
            $this->config['joins'] = $this->config['joins_backup'][$id];
            return true;
        } else {
            return false;
        }
    }

    public function format_element_sensitive_data($element)
    {
        if (is_a($this->app_controller, 'BackendController')) {
            if (!isset($_SESSION['user']['sensitive_data']) || !$_SESSION['user']['sensitive_data']) {
                if (!empty($this->config['sensitive_data'])) {
                    foreach ($this->config['sensitive_data'] as $field) {
                        $element[$field] = '******';
                    }
                }
            }
        }
        return $element;
    }

    public function validates_unique($key, $params, $validation_params = array())
    {
        $query = "(`$key`='{$params[$key]}')";
        if (!empty($params['id'])) {
            $query .= "AND (`id` != {$params['id']})";
        }
        if (!empty($params[$key]) && $this->find_element($query)) {
            $this->last_message = sprintf('„%s“ jau yra užimtas.', $params[$key]);
            return false;
        }
        return true;
    }

    /**
     * Grąžina frontend objektą, iškvietųsį elementą arba jo (pro-(pro-(...)))tėvą
     * @return unknown_type
     * @author kran
     * @date 2008-10-16
     */
    protected function get_frontend()
    {
        if (is_a($this->app, 'Frontend')) {
            return $this->app;
        } else {
            return false;
        }
    }

    /**
     * Sisteminis metodas, skirtas persaugoti iš naujo visiems esybės elementams,
     * naudojamas tais atvejais, kai tarkime, reikia pergenertuoti visų elementų kodus, url ir t.t.
     *
     * Turėtų būti naudojamas tik kaip pagalbinis metodas, kai vykdomas kodo kūrimas.
     *
     * @return unknown_type
     */
    protected function resave_all_elements()
    {
        $list = $this->list_elements();
        foreach ($list as $element) {
            $this->save($element);
        }
    }

    /**
     * formuoja elementu sarasa
     *
     * @param string $where_clause - papildomos salygos SQL sakinio WHERE dalyje.
     * @return unknown
     */
    public function list_elements($where_clause = false, $apply_formating = 'default', &$return_params = array())
    {
        $params = array(
            'paginate' => $this->get_page_size() > 0,
            'page_info' => true,
            'page_size' => $this->get_page_size(),
            /* TODO: šeip reiktų pagaliau išskirti sąvokas „puslapis“ - naudojamas puslapiavimo mechanizme ir
             * „puslapis“ - naudojamas svetainės medžio mechanizme, nes per daug makalynės su tomis sąvokomis :(
             * kran 2008-10-17
             */
            'group_by' => $this->get_group_by(),
            'page' => $this->get_current_page(),
        );
        $where = $where_clause ? array($where_clause) : array();
        $params['where'] = implode(' AND ', $where);
        $elements = $this->select_elements($params, $return_params);
        if (Repository::$backend && isset($this->fields['position'])) {
            $return_params['marginal_positions'] = $this->get_min_max_positions($where_clause);
        }
        if ($apply_formating) {
            $this->format($elements, $apply_formating);
        }
        return $elements;
    }

    public function get_page_size()
    {
        $page_size = isset($this->config['page_size']) ? $this->config['page_size'] : 0;
        return $page_size;
    }

    public function get_group_by()
    {
        return isset($this->config['group_by']) ? $this->config['group_by'] : false;
    }

    public function get_current_page()
    {
        return isset($this->config['page']) ? $this->config['page'] : 1;
    }

    public function get_min_max_positions($where = "")
    {
        $params['fields'] = 'min(position) as `min`, max(position) as `max`';
        $params['where'] = $where;
        return $this->select_element($params);
    }

    /**
     *
     * @param $params
     * @param $force_insert
     * @return unknown_type
     */
    public function save(&$params = array())
    {
        $before = @$params['id'] ? $this->get_element($params['id'], 'id', false, false) : false;

        // jei reikia, apskaiciuojam elemento pozicija
        if (empty($params['id']) && isset($this->fields['position']) && !isset($params['position'])) {
            $this->prepare_position($params);
        }

        // Jei reikia, sugeneruojam elemento url. Url'a irasom tik tuo atveju, jeigu prepare_url() grazino ne tuscia rezultata.
        if (isset($this->fields['url']) && empty($params['url'])) {
            $url = false;
            if (!empty($params['id'])) {
                $url = $this->prepare_url($params, "id<>{$params['id']}");
            } else {
                $url = $this->prepare_url($params);
            }
            if (!empty($url)) {
                $params['url'] = $url;
            }
        }

        // pirma patikrinam duomenu validuma
        if (!$this->validates($params)) {
            return false;
        }

        $is_insert = !isset($params['id']);

        if ($is_insert && isset($this->fields['ip'])) {
            $params['ip'] = $_SERVER['REMOTE_ADDR'];
        }

        if ($this->is_page()) {
            $params['page'] = $this->page_id;
        }

        if (empty($this->config['table'])) {
            $this->invalidate(t('Nenustatyta modulio lentele.'));
            return false;
        } else {
            $table_name = $this->config['table'];
        }

        // papildomi laukai is 'conditions' nustatymu
        if ($conditions = $this->get_conditions('equal')) {
            foreach ($conditions as $c) {
                list($key, $value) = preg_split('/[ ]?(<=>|=|IS)[ ]?/i', $c);
                if (strtolower($value) == 'null') {
                    $value = null;
                }
                $value = trim($value, "'");
                $key = trim($key, "`");
                if (!isset($params[$key])) {
                    $params[$key] = $value;
                }
            }
        }
        $avail_fields = $this->_available_fields($params);
        $avail_fields_keys = array_keys($avail_fields);
        $avail_fields_values = array_values($avail_fields);
        if (empty($avail_fields)) {
            $this->invalidate(t('Nenurodytas nei vienas laukas.'));
            return false;
        }

        if (!$is_insert) {
            // update'as
            $query = "UPDATE $table_name SET ";
            $pairs = array();
            foreach ($avail_fields as $key => $value) {
                if ($value !== null) {
                    $value = Repository::$db->real_escape_string($value);
                }
                $pairs[] = "`$key`=" . ($value === null ? 'NULL' : "'$value'");
            }
            $query .= implode(", ", $pairs) . " WHERE `id`='$params[id]'";
        } else {
            // insert'as
            $req_fields = $this->_required_fields();
            $diff = array_diff($req_fields, $avail_fields_keys);
            if (!empty($diff)) {
                $diff_tr = array();
                foreach ($diff as $field_name) {
                    $diff_tr[] = $this->field_name($field_name);
                }
                $this->invalidate(sprintf(t('Trūksta privalomų laukų: %s.'), implode(', ', $diff_tr)));
                return false;
            }

            //yra atvejų, kai tenka insertinti su nustatytų id ;
            if (!empty($params['id'])) {
                $avail_fields_values[] = $params['id'];
                $avail_fields_keys[] = 'id';
            }

            $keys = '`' . implode('`, `', $avail_fields_keys) . '`';
            foreach ($avail_fields_values as $k => $v) {
                if ($v !== null) {
                    $v = Repository::$db->real_escape_string($v);
                }
                $avail_fields_values[$k] = ($v === null) ? 'NULL' : "'$v'";
            }
            $values = implode(', ', $avail_fields_values);
            $query = "INSERT INTO $table_name ($keys) VALUES($values)";
        }
        if (Database::query($query)) {
            if (!isset($params['id'])) {
                $params['id'] = Repository::$db->insert_id;
            }
            $success = true;
        } else {
            $this->invalidate(sprintf(t('mysql klaida: %s.'), Repository::$db->error));
            $success = false;
        }

        // jei reikia issaugom kalbas
        if ($success && !empty($this->config['languages']) && !empty(@$params['languages'])) {
            $this->update_languages($params);
        }

        // jei reikia, issaugom tag'us
        if ($success && !empty($this->config['tags']) && @$params['update_tags']) {
            $tags = !empty($params['tags']) ? array_keys(array_filter($params['tags'])) : array();
            $this->update_tags($params['id'], $tags);
        }

        //issaugom prie elemento prisegtą esybės konteinerį
        if ($success) {
            foreach ($this->available_containers as $container_name) {
                if (!empty($this->config[$container_name]) && ($entity_container_controller = $this->load_entity_controller($container_name))) {
                    //jeigu į parametrus yra paduota naujo elemento, arba elemento redagavimo duomenys
                    if (!empty($entity_container_controller->config['element_param_name']) && !empty($params[$entity_container_controller->config['element_param_name']]) ||
                        !empty($entity_container_controller->config['new_element_param_name']) && !empty($params[$entity_container_controller->config['new_element_param_name']])) {
                        if (!$entity_container_controller->save_entity_elements($params['id'], $params, $this->get_name())) {
                            $this->entity_errors[$container_name] = $entity_container_controller->format_errors();
                            $entity_container_controller->clear_errors();
                        }
                    }
                }
            }
        }
        if ($success && @$this->config['meta_fields'] && @$params['update_meta_fields']) {
            $this->update_meta_fields($params);
        }

        //filtrai
        if ($success && !empty($params['update_filters'])) {
            $this->update_filters($params['id'], @$params['filters'] ?: array());
        }

        if ($success) {
            $after = $this->get_element($params['id'], 'id', false, false);
            $this->on_change($before, $after);
        }
        return $success;
    }

    public function prepare_position(&$params = array())
    {
        $element = $this->select_element(array('fields' => 'max(`position`) as position', 'auto_params' => false));
        $params['position'] = !empty($element['position']) ? ($element['position'] + 1) : 1;
    }


    public function prepare_url($params, $where_clause = "")
    {
        if (empty($params['name'])) {
            return false;
        }
        $name = $params['name'];
        $where = $where_clause ? " AND $where_clause" : '';
        if (isset($this->fields['parent'], $params['parent'])) {
            $where = " AND `parent`='$params[parent]' $where";
        }
        $url = Inflector::slug($name);
        if (strlen($url) > 40) {
            $url = substr($url, 0, 43);
            $url = trim($url, '-');
            $url = preg_replace('/^(.*)-[^-]+$/', '$1', $url);
        }
        $tmp_url = $url ? $url : '-';
        $nr = 1;
        while ($this->select_element(array('fields' => 'url', 'where' => "url='$tmp_url' $where", 'auto_params' => false))) {
            $nr++;
            $tmp_url = $url . "-$nr";
        }
        if (!empty($this->unique_urls_with)) {
            foreach ($this->unique_urls_with as $unique_entity_name => $unique_entity_title) {
                while ($this->get_e($unique_entity_name)->find_element("url='$tmp_url'")) {
                    $nr++;
                    $tmp_url = $url . "-$nr";
                }
            }
        }
        $translation_tmp_url = $url ? $url : '-';
        $existing_urls = Database::get_assoc_all("SELECT cast(REPLACE(REPLACE(value , '$translation_tmp_url-', ''), '', '') AS UNSIGNED) as position FROM lite_translated_fields WHERE " . (isset($params['id']) ? "entity_id != {$params['id']} AND" : "") . " entity_name = '{$this->get_name()}' AND field_name = 'url' AND value REGEXP '^$translation_tmp_url-[0-9]+$'");
        if (!empty($existing_urls) || $this->get_e('translated_fields')->find_element((isset($params['id']) ? "entity_id != {$params['id']} AND" : "") . " entity_name = '{$this->get_name()}' AND field_name = 'url' AND value = '{$translation_tmp_url}'")) {
            $min_position = $this->find_first_open_position($existing_urls, 'position', $nr);
            return $translation_tmp_url . '-' . $min_position;
        } elseif ($nr > 1) {
            return $translation_tmp_url . '-' . $nr;
        }
        return $tmp_url;
    }

    private function find_first_open_position($array, $field, $min = 2)
    {
        if (empty($array)) {
            return $min;
        }
        $existing_positions = array_column($array, $field);
        $full_range = range($min, max($existing_positions));
        $missing = array_diff($full_range, $existing_positions);
        if (empty($missing)) {
            return ((max($existing_positions) < $min ? $min : max($existing_positions)) + 1);
        }
        return min($missing);
    }

    /**
     * Lauku validacija.
     *
     * @param array $params
     * @return success
     */
    public function validates(&$params)
    {
        $params = $this->_before_validate($params);
        foreach ($this->fields as $key => $value) {
            if (isset($this->invalidated_fields[$key])) {
                continue;
            }
            // jei yra entity config'e nurodyta unique_urls_with, patikrinam kiekvieno nurodyto entity ar neturi elemento su tokiu pat url.
            if ($key == 'url' && !empty($this->unique_urls_with)) {
                $unique_urls_msg = [];
                $unique_urls_error = false;
                foreach ($this->unique_urls_with as $unique_entity_name => $unique_entity_title) {
                    if ($exist = Database::get_assoc("SELECT * FROM lite_{$unique_entity_name} WHERE `url`='{$params['url']}'")) {
                        $unique_urls_msg[] = sprintf('"%s" jau naudojama "%s"', $params['url'], $unique_entity_title);
                        $unique_urls_error = true;
                    }
                }

                if ($unique_urls_error) {
                    $unique_urls_msgs = implode('<br/>', $unique_urls_msg);
                    $this->invalidate($unique_urls_msgs, 'url');
                }
            }
            if (!empty($value['validation']) && isset($params[$key])) {
                if (!is_array($value['validation'])) {
                    $value['validation'] = array($value['validation']);
                }
                $invalid = false;
                foreach ($value['validation'] as $rule) {
                    $validation_params = explode('|', $rule);
                    $validation_rule = "validates_" . array_shift($validation_params);
                    if (method_exists($this, $validation_rule)) {
                        if (!$this->$validation_rule($key, $params, $validation_params)) {
                            $invalid = true;
                            $this->invalidate($this->get_last_message(), $key);
                        }
                    } else {
                        try {
                            if (!Validator::$validation_rule($params[$key], $validation_params)) {
                                $invalid = true;
                                $this->invalidate(sprintf(Validator::get_last_message(), $params[$key]), $key);
                            }
                        } catch (Exception $e) {
                            Debug::debug("Nėra tokios validavo taisyklės: $validation_rule");
                        }
                    }
                    if ($invalid) {
                        break;
                    }
                }
            }
        }
        return !$this->invalidated;
    }

    /**
     * Čia turėtų būti atliekami visi duomenų transformavimai, kurie reikalingi prieš validuojant ir saugant elementą.
     *
     *
     * @param    $params
     * @return    unknown_type
     *
     * @author    kran
     * @since    0.7
     * @date    2009-08-11
     */
    protected function _before_validate($params)
    {
        return $params;
    }

    /**
     * Invalidavimas nurodant klaidos pavadinima
     *
     * @param string $msg
     */
    public function invalidate($msg, $field_name = false)
    {
        $field_id = $field_name;
        if ($field_name) {
            $field_name = $this->field_name($field_name);
        }
        $message = $field_name ? '„' . $field_name . '“' . ": $msg" : $msg;
        $error = array(
            'label' => $field_name,
            'message' => $msg,
        );
        if ($field_id) {
            $this->invalidated_fields[$field_id] = $error;
        } else {
            $this->invalidated_fields[] = $error;
        }
        $this->last_error = $message;
        $this->invalidated = true;
    }

    /**
     * Pagal lauko raktą, randa lauko pavadinimą. Pirmiausiai ima iš controller, tuomet pagal default, o neradus gražina be vertimo.
     *
     * @param string $field_key - lauko raktas
     * @return string
     */
    public function field_name($field_key)
    {
        $unique_field_name = @$this->app_controller->config['field_names'][$field_key];
        $default_field_name = @Engine::get_config('field_names')[$field_key];

        return isset($unique_field_name) ? $unique_field_name : (isset($default_field_name) ? $default_field_name : t($field_key));
    }

    public function get_last_message()
    {
        return $this->last_message;
    }

    /**
     * patikrina, esybe priklauso kuriam nors puslapiui (tinklalapiui)
     *
     */
    public function is_page()
    {
        return isset($this->fields['page']) && !empty($this->page_id);
    }

    /**
     * Is $params masyvo ismeta laukus kurie nera aprasyti fields
     *
     * @param array $params
     * @return array
     */
    public function _available_fields($params)
    {
        $avail_fields = array();
        foreach ($this->fields as $key => $value) {
            if (array_key_exists($key, $params)) {
                $avail_fields[$key] = $params[$key];
            }
        }
        return $avail_fields;
    }

    /**
     * Suformuoja privalomu lauku masyva. Pvz.: array('name', 'url', 'text');
     *
     * @return array
     */
    public function _required_fields()
    {
        $req_fields = array();
        foreach ($this->fields as $key => $value) {
            if (!empty($value['required']) || in_array('required', $value)) {
                $req_fields[] = $key;
            }
        }
        return $req_fields;
    }

    public function update_languages($params)
    {
        Database::query("DELETE FROM lite_entities_languages WHERE entity_name = '{$this->get_name()}' AND entity_id = {$params['id']}");
        foreach ($params['languages'] as $language => $active) {
            if ($active) {
                Database::query("INSERT INTO lite_entities_languages (entity_name, entity_id, language) VALUES ('{$this->get_name()}',{$params['id']},'{$language}')");
            }
        }
    }

    //TODO: datos validacijos taisykle!
    //TODO: laiko validacijos taisykle!
    //TODO: isvesti erroru array'ju
    //TODO: praeiti visus modulius ir sudėti naujas validation rules!!!
    //TODO: sukurti javascript validacija!!!

    public function update_tags($element_id, $tags)
    {
        $this->get_e('relations')->save_elements_by_relation('tags_' . $this->get_name(), $tags, $element_id);
    }

    public function update_meta_fields($params)
    {
        $entity_name = $this->get_name();
        $entity_id = $params['id'];
        if ($row = Database::get_assoc("SELECT * FROM lite_meta_fields WHERE `entity_name`='$entity_name' AND `foreign_key`='$entity_id'")) {
            Database::query("
                    UPDATE lite_meta_fields 
                    SET `meta_title`='{$params['meta_title']}',
                        `meta_description`='{$params['meta_description']}',
                        `meta_keywords`='{$params['meta_keywords']}',
                        `header_title`='{$params['header_title']}',
                        `noindex`='{$params['noindex']}',
                        `nofollow`='{$params['nofollow']}'
                    WHERE `entity_name`='$entity_name' AND `foreign_key`='$entity_id'");
        } else {
            Database::query("
                    INSERT INTO lite_meta_fields (`entity_name`, `foreign_key`, `meta_title`, `meta_description`, `meta_keywords`, `header_title`, `noindex`, `nofollow`)
                    VALUES ('$entity_name', '$entity_id', '{$params['meta_title']}', '{$params['meta_description']}', '{$params['meta_keywords']}', '{$params['header_title']}', '{$params['noindex']}', '{$params['nofollow']}')
                ");
        }
    }

    /*	 * ************************************************************************************* */

    public function prepare_translation_url($entity_id, $name, $lang, $project_id = 0, $field_name = 'url')
    {
        if (empty($name)) {
            return false;
        }

        $url = Inflector::slug($name);
        if (strlen($url) > 100) {
            $url = substr($url, 0, 103);
            $url = trim($url, '-');
            $url = preg_replace('/^(.*)-[^-]+$/', '$1', $url);
        }
        $tmp_url = $url ? $url : '-';
        $min_nr = 1;
        while ($this->select_element(array('fields' => 'url', 'where' => "url='$tmp_url'", 'auto_params' => false))) {
            $min_nr++;
            $tmp_url = $url . "-$min_nr";
        }
        if (!empty($this->unique_urls_with)) {
            foreach ($this->unique_urls_with as $unique_entity_name => $unique_entity_title) {
                while ($this->get_e($unique_entity_name)->find_element("url='$tmp_url'")) {
                    $min_nr++;
                    $tmp_url = $url . "-$min_nr";
                }
            }
        }
        $tmp_url = $url ? $url : '-';
        $existing_urls = Database::get_assoc_all("SELECT cast(REPLACE(REPLACE(value , '$tmp_url-', ''), '', '') AS UNSIGNED) as position FROM lite_translated_fields WHERE entity_id != {$entity_id} AND entity_name = '{$this->get_name()}' AND field_name = '{$field_name}' AND lang = '{$lang}' AND value REGEXP '^$tmp_url-[0-9]+$'");
        if (!empty($existing_urls) || $this->get_e('translated_fields')->find_element("entity_id != {$entity_id} AND entity_name = '{$this->get_name()}' AND field_name = '{$field_name}' AND lang = '{$lang}' AND value = '{$tmp_url}'")) {
            $min_position = $this->find_first_open_position($existing_urls, 'position', $min_nr);
            return $tmp_url . '-' . $min_position;
        } elseif ($min_nr > 1) {
            return $tmp_url . '-' . $min_nr;
        }
        return $tmp_url;
    }

    public function update_filters($product_id, $filters)
    {
        Database::query("DELETE FROM lite_filter_values_elements WHERE element_id=$product_id AND entity_name='{$this->get_name()}'");

        $e = $this->load_entity_controller('filters');
        $pfv_e = $this->load_entity_controller('filter_values');
        $pf_e = $this->load_entity_controller('filter_values_elements');

        foreach ($filters as $key => $filter) {
            if ($key == "text") {
                foreach ($filter as $filter_id => $value) {
                    if ($value !== '') {
                        $save_data = array(
                            'element_id' => $product_id,
                            'entity_name' => $this->get_name(),
                            'filter_id' => $filter_id,
                            'text_value' => $value,
                        );
                        $pf_e->save($save_data);
                    }
                }
            } elseif ($key == 'select') {
                foreach ($filter as $filter_id => $value) {
                    if (is_array($value)) {
                        // multiselect
                        foreach ($value as $k => $v) {
                            if (1 == $v) {
                                $save_data = array(
                                    'element_id' => $product_id,
                                    'entity_name' => $this->get_name(),
                                    'filter_id' => $filter_id,
                                    'filter_value_id' => $k,
                                );
                                $pf_e->save($save_data);
                            }
                        }
                    } elseif ($value !== '') {
                        $save_data = array(
                                'element_id' => $product_id,
                                'entity_name' => $this->get_name(),
                                'filter_id' => $filter_id,
                                'filter_value_id' => $value,
                            );
                        $pf_e->save($save_data);
                    }
                }
            } elseif ($key == 'checkbox') {
                foreach ($filter as $filter_id => $value) {
                    if (is_numeric($value)) {
                        $save_data = array(
                            'element_id' => $product_id,
                            'entity_name' => $this->get_name(),
                            'filter_id' => $filter_id,
                            'checked_value' => $value,
                        );
                        $pf_e->save($save_data);
                    }
                }
            } elseif ($key = 'checkboxes') {
                foreach ($filter as $filter_id => $values) {
                    foreach ($values as $key => $value) {
                        if (1 == $value) {
                            $save_data = array(
                                'element_id' => $product_id,
                                'entity_name' => $this->get_name(),
                                'filter_id' => $filter_id,
                                'filter_value_id' => $key,
                            );
                            $pf_e->save($save_data);
                        }
                    }
                }
            }
        }
    }

    public function on_change($before, $after)
    {
    }

    /**
     * prideda elemento pirmą nuotrauką ir bendrą nuotraukų statistiką
     * @param $element -elementas
     * @return unknown_type -suformatuotas elementas (su foto informacija)
     */
    protected function format_element_photo_info($element)
    {
        if (!empty($this->config['photos'])) {
            $photos_controller = $this->load_entity_controller('photos');
            $this->format($element, 'first_photo');
            $element['photos_count'] = $photos_controller->count_entity_elements($element['id']);
        }
        return $element;
    }
}
