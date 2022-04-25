<?php

namespace Elab\Lite\System;

use Elab\Lite\Services\Database;

/**
 * Klase, kuri kontroliuoja ivairius esybei priskiriamus rinkinius (pvz.: komentarai, tag'ai, fieldsetai)
 * Konfiguracijoj turi buti nurodyta
 *        'container_table' (pvz.: lite_commented_elements, lite_photo_containers ir pan.)
 *        'container_key' (pvz.: commented_element_id, gallery_id ir pan.
 * @package core
 */
class ContainerEntityController extends EntityController
{
    public function format_element($element, $mode = 'default')
    {
        if ($mode && !empty($element[$this->config['container_key']])) {
            $element['entity_info'] = $this->get_entity($element[$this->config['container_key']]);
        }
        return parent::format_element($element, $mode);
    }

    public function get_entity($container_id)
    {
        if (isset($this->_cache['entity_info'][$container_id])) {
            return $this->_cache['entity_info'][$container_id];
        }
        if ($row = Database::get_assoc("SELECT * FROM {$this->config['container_table']} WHERE id='$container_id'")) {
            $this->_cache['entity_info'][$container_id] = $result = array('entity_name' => $row['entity_name'], 'entity_id' => $row['foreign_key']);
            return $result;
        }
        return false;
    }

    /**
     * Nustatomos esybes, su kuriomis susije elementai bus imami.
     *
     * @param int $entity_id
     */
    public function set_entity_names($entity_name)
    {
        $container_condition = "`entity_name`" . (is_array($entity_name) ? " IN ('" . implode("', '", $entity_name) . "')" : " = '$entity_name'");
        $this->add_condition('container_key', "`{$this->config['container_key']}` IN (SELECT id FROM {$this->config['container_table']} WHERE $container_condition)");
    }

    public function delete_container($entity_id)
    {
        if (!$this->set_container($entity_id)) {
            // tokio konteinerio nera
            return false;
        }

        // trinam konteinerio elementus
        $this->delete_elements();

        // trinam pati konteineri
        $entity_name = $this->parent_object->get_name();
        Database::query("DELETE FROM {$this->config['container_table']} WHERE `entity_name`='$entity_name' AND `foreign_key`='$entity_id'");
        return Repository::$db->affected_rows;
    }

    /**
     * Nustatomas elementu konteineris, is kurio bus imamai/keiciami/kuriami elementai.
     *
     * @param int $entity_id
     */
    public function set_container($entity_id, $create = false, $container_name = '')
    {
        if ($container = $this->get_container($entity_id, $create, $container_name)) {
            $this->add_condition('container_key', "`{$this->config['container_key']}`='$container[id]'", 'equal');
            return true;
        } else {
            return false;
        }
    }

    public function get_container($entity_id, $create = false, $container_name = '')
    {
        if (!empty($this->_cache['containers'][$entity_id][$container_name])) {
            return $this->_cache['containers'][$entity_id][$container_name];
        }
        $entity_name = $this->parent_object->get_name();

        $params = array(
            'table' => $this->config['container_table'],
            'where' => "`entity_name`='$entity_name' AND `foreign_key`='$entity_id'" . ($container_name ? " AND `container_name`='$container_name'" : ""),
            'auto_params' => false,
        );
        if ($container = $this->select_element($params)) {
            $this->_cache['containers'][$entity_id][$container_name] = $container;
            return $container;
        } else {
            if ($create) {
                Database::query("INSERT INTO {$this->config['container_table']} (`entity_name`, `foreign_key`, `container_name`) VALUES ('$entity_name', '$entity_id', '$container_name')");
                $params['where'] = 'id=' . Repository::$db->insert_id;
                return $this->select_element($params);
            } else {
                return false;
            }
        }
    }

    /**
     * Grazina esybes priskirta elementu rinkini, pagal tos esybes id
     *
     * @param unknown_type $entity_id
     * @return array
     */
    public function get_entity_elements($entity_id, $formatting_mode = false, &$return_params = array())
    {
        $entity_name = $this->parent_object->get_name();
        $params = array(
            'table' => $this->config['container_table'],
            'where' => "`entity_name`='$entity_name' AND `foreign_key`='$entity_id' ",
            'auto_params' => false,
        );
        $containers = $this->select_elements($params);

        $elements = array();
        foreach ($containers as $container) {
            if (!$this->set_container($entity_id, false, $container['container_name'])) {
                // esybe neturi konteinerio
                $elements[$container['container_name']] = array();
            } else {
                $elements[$container['container_name']] = $this->list_elements("", $formatting_mode, $return_params);
                foreach ($elements[$container['container_name']] as $element) {
                    if (array_key_exists("language", $element)) {
                        if (!isset($elements[$container['container_name'] . '_formatted'])) {
                            $elements[$container['container_name'] . '_formatted'] = array('default' => array());
                        }
                        if (empty($element['language'])) {
                            $element['language'] = 'default';
                        }
                        if (!isset($elements[$container['container_name'] . '_formatted'][$element['language']])) {
                            $elements[$container['container_name'] . '_formatted'][$element['language']] = array();
                        }
                    }
                }
            }
        }

        return $elements;
    }

    public function get_first_entity_element($entity_id, $formatting_mode = false, $container_name = '')
    {
        if (!$this->set_container($entity_id, false, $container_name)) {
            return false;
        } else {
            $where = "";
            $lang = !empty(Repository::$frontend->lang_key) ? Repository::$frontend->lang_key : null;
            if (!empty($lang)) {
                $where .= $langWhere = "language = '" . $lang . "'";
            } else {
                $where .= $langWhere = "language IS NULL";
            }
            if (!$element = $this->find_element($where, $formatting_mode)) {
                $element = $this->find_element("language IS NULL", $formatting_mode);
            }
            return $element;
        }
    }

    /**
     * Grazina esybei priskirtu elementu skaiciu, pagal tos esybes id
     *
     * @param unknown_type $entity_id
     * @return unknown
     */
    public function count_entity_elements($entity_id, $container_name = '')
    {
        if ($container = $this->get_container($entity_id, false, $container_name)) {
            return $this->count_elements("`{$this->config['container_key']}`=$container[id]");
        } else {
            return 0;
        }
    }

    public function save_entity_elements($entity_id, $params)
    {
        $element_param_name = $this->config['element_param_name'];
        $new_element_param_name = $this->config['new_element_param_name'];


        if (empty($params[$element_param_name]) && empty($params[$new_element_param_name])) {
            exit;
            return true;
        }


        //jau esantys (paredaguoti) elementai
        foreach (isset($params[$element_param_name]) ? $params[$element_param_name] : array() as $container_name => $elements) {
            $container_info = explode("_", $container_name);
            if (count($container_info) >= 2) {
                //CHECKIT
                $container_name = implode("_", array_slice($container_info, 0, -2));
            } else {
                $container_name = $container_info[0];
            }
            $this->set_container($entity_id, true, $container_name);
            foreach ($elements as $key => $element) {
                if (array_key_exists(count($container_info) - 1, $container_info)) {
                    $element['language'] = ($container_info[count($container_info) - 1] == "default") ? null : $container_info[count($container_info) - 1];
                }
                if ($element['edited'] == 1) {
                    if ($element['deleted'] == 1) {
                        $this->delete_element($key);
                        continue;
                    }
                    unset($element['deleted'], $element['edited']);
                    $this->save($element);
                }
            }
        }

        //nauji (pridedami) elementai
        $success = 0;
        $element_count = 0;

        foreach (isset($params[$new_element_param_name]) ? $params[$new_element_param_name] : array() as $container_name => $elements) {
            $container_info = explode("_", $container_name);
            if (count($container_info) >= 3) {
                $container_name = implode("_", array_slice($container_info, 0, -2));
            } else {
                $container_name = $container_info[0];
            }
            $this->set_container($entity_id, true, $container_name);
            $container = $this->get_container($entity_id, true, $container_name);

            $this->disable_condition('container_key');
            $positions = $this->get_min_max_positions("`{$this->config['container_key']}` = {$container['id']}");
            $this->enable_condition('container_key');

            $position = !empty($positions['max']) ? $positions['max'] + 1 : 1;
            foreach ($elements as $key => $element) {
                if (array_key_exists(count($container_info) - 1, $container_info)) {
                    $element['language'] = ($container_info[count($container_info) - 1] == "default") ? null : $container_info[count($container_info) - 1];
                }
                $element['position'] = $position;
                $element[$this->config['container_key']] = $container['id'];

                if ($this->create_element($key, $element)) {
                    $position++;
                    $success++;
                }
                $element_count++;
            }
        }

        return $success == $element_count;
    }

    /**
     * Ištrina esybę, prieš tai iškviesdamas atitinkamą triggerį tėvinei esybei
     * @author kran
     * (non-PHPdoc)
     * @see include/EntityController#delete_element()
     */
    public function delete_element($id, $key = "id")
    {
        $this->parent_object->process_trigger('delete_' . $this->get_name(), $id);
        return parent::delete_element($id, $key);
    }

    /**
     * Išsaugo entity elementą, iškviesdamas reikalingus trigerius
     * @see include/EntityController#save($params)
     */
    public function save(&$params = array())
    {
        $trigger_code = (!empty($params['id']) ? 'update' : 'create') . '_' . $this->get_name();
        if ($success = parent::save($params)) {
            $this->parent_object->process_trigger($trigger_code, $params['id'], $params);
        }
        return $success;
    }
}
