<?php
namespace Elab\Lite\Controllers\Entity;

use Elab\Lite\Helpers\Str;
use Elab\Lite\System\Repository;
use Elab\Lite\Engine;
use Elab\Lite\System\EntityController;

class Pages extends EntityController
{

    /**
     * Randa titulini puslapi
     *
     * @return array
     */
    public function root_page($url = false)
    {
        $where = array("`parent`='0'");
        if ($url) {
            $where[] = "`url`='$url'";
        }
        $where = implode(' AND ', $where);
        return $this->find_element($where, 'translate');
    }

    public function get_full_url_by_type($type)
    {
        if (isset(Repository::$frontend->page_types[$type])) {
            return Repository::$frontend->page_types[$type]['full_url'];
        }
        $where = "type='$type'";
        $page = $this->find_element($where, 'translate');
        return $page ? $this->get_full_url($page['id']) : false;
    }
    
    public function get_full_url($page)
    {
        $this->prepare_element($page);
        $this->format($page, 'translate');
        $conds = isset($this->conditions) ? $this->conditions : false;
        $this->disable_condition('visible');
        if (($page['type'] == 'link' || $page['type'] == 'external_link') && !empty($page['link_to'])) {
            $full_url = $page['link_to'];
        } else {
            $full_url = '';
            while ($page) {
                if ($page['type'] != 'index') {
                    $full_url = $page['url'] . '/' . $full_url;
                }
                $page = $page['parent'] ? $this->get_element($page['parent'], 'id', 'translate') : false;
            }
        }
        if ($conds) {
            $this->conditions = $conds;
        }
        if (!preg_match('/^http/', $full_url)) {
            $project_url = PROJECT_URL;
            $langs = $this->get_e('languages')->find_elements();
            $langs_by_key = array_column($langs, null, 'language');
            $first = reset($langs_by_key);
            if ($this->app->lang_key!=$first['language']) {
                $project_url .= $this->app->lang_key.'/';
            }
            $full_url = $project_url . ltrim($full_url, '/');
        }
        return $full_url;
    }

    public function format_element($element, $mode = 'default')
    {
        $this->format($element, 'full_url');
        switch ($mode) {
            case 'site_tree':
                break;
                
            case 'detailed':
                $element['content'] = html_entity_decode($element['content'], ENT_QUOTES);
                // ar reikia parent keywords, desc jeigu neturi esamas puslapis???
                //$element['meta_keywords'] = $this->meta_keywords($element['id']);
                //$element['meta_description'] = $this->meta_description($element['id']);
                break;
                
            case 'page_list':
                $element['path_name'] = $this->path_name($element['id']);
                break;
            
            case 'menu':
                $element['target'] = ($element['type'] == 'external_link') ? '_blank' : '_self';
                $frontend = Repository::$frontend;
                if (!empty($frontend)) {
                    // Formatavimas kvieciamas is frontend'o.
                    $current_full_url = $frontend->page['full_url'];
                    $element['active'] = (strpos($current_full_url . '/', trim($element['full_url'], '/') . "/") === 0);
                }
                break;
                
            case 'search':
                $element['description'] = strip_tags(html_entity_decode($element['content'], ENT_QUOTES));
                break;
            
            case 'default':
                break;
        }

        return parent::format_element($element, $mode);
    }

    /**
     * Puslapio meta-keyword'ai. Jei nenustatytas, ima tevo keyword'us
     *
     * @param int $elem
     */
    public function meta_keywords($elem)
    {
        $result = $this->get_value($elem, 'meta_keywords');
        if (empty($result) && ($parent = $this->get_value($elem, 'parent'))) {
            $result = $this->meta_keywords($parent);
        }
        return $result;
    }

    /**
     * Puslapio meta-description. Jei nenustatytas, ima tevo description'a
     *
     * @param int $elem
     */
    public function meta_description($elem)
    {
        $result = $this->get_value($elem, 'meta_description');
        if (empty($result) && ($parent = $this->get_value($elem, 'parent'))) {
            $result = $this->meta_description($parent);
        }
        return $result;
    }

    /**
     * Suformuoja kelia iki puslapio, pvz. /imone/apie/kontaktai
     *
     * @param int $id
     */
    public function path_name($id)
    {
        $c_id = $id; // current_id
        $result_array = array();
        while ($name = $this->get_value($c_id, 'name')) {
            $result_array[] = $name;
            $c_id = $this->get_value($c_id, 'parent');
        }
        $result_array = array_reverse($result_array);
        $result = implode("/", $result_array);
        return $result;
    }

    /**
     * Trina puslapi su visais jo vaikais
     *
     */
    public function delete_element($id, $key = 'id')
    {
        $element = false;
        if ($key != 'id') {
            if ($element = $this->get_element($id, $key)) {
                $id = $element['id'];
                $key = 'id';
            } else {
                return false;
            }
        }
        if (empty($element) && (!($element = $this->get_element($id)))) {
            return false;
        }



        // Ar leidzima trinti?
        if ($this->get_value($id, 'protected') > 0) {
            return false;
        }

        //triname prie puslapio prikabintą esybę
        //TODO: iškelti į trigerius

        $this->load_entity_controller($element['type']);


        if (!empty($element['type']) && ($entity_controller = $this->load_entity_controller($element['type']))) {
            if (method_exists($entity_controller, 'delete_entity_page')) {
                if (!$entity_controller->delete_entity_page($id)) {
                    return false;
                }
            }
        }

        // Trinam visus vaikus, jei jų yra
        $params['where'] = "`parent`='$id'";
        $childs = $this->select_elements($params);
        foreach ($childs as $child) {
            parent::delete_element($child['id']);
        }

        // Trinam pati elementa
        return parent::delete_element($id);
    }

    public function save(&$params = array())
    {
        $create = empty($params['id']);
        $success = parent::save($params);

        //kuriame arba redaguojame prie puslapio prisegtą esybę
        if($success && !empty($params['type'])) {
            $configs = Engine::get_config('pages');
            if($configs[$params['type']] && !empty($configs[$params['type']]['controller']) && ($entity_controller = $this->load_entity_controller($configs[$params['type']]['controller'])) && is_a($entity_controller, Str::camel_case($configs[$params['type']]['controller']) . 'EntityController')) {
                if ($create) {
                    //TODO: iškelti į trigerius
                    $entity_controller->create_entity_page($params);
                } else {
                    //TODO: iškelti į trigerius
                    $entity_controller->edit_entity_page($params);
                }
            }
        }
        return $success;
    }

    /**
     * @overrided
     * (non-PHPdoc)
     * @see include/EntityController#get_hierarchy($root_element, $formatting_mode, $where_clause, $levels)
     */
    public function get_hierarchy($root_element = 0, $formatting_mode = false, $where_clause = "", $levels = 0, $current_level = 0)
    {
        //jeigu esame svetainėje, tinklapio medžio puslapyje, mums reikia parodyti ir puslapius, kurie yra atvaizduojami papildomuose meniu.
        if ($formatting_mode == 'site_tree' && !Repository::$backend && isset($this->config['fields']['visible_additional_menu'])) {
            $this->config['conditions']['visible'] = array('type' => 'mixed', 'condition' => '`visible`=1 OR `visible_additional_menu` = 1');
        }
        return parent::get_hierarchy($root_element, $formatting_mode, $where_clause, $levels, $current_level);
    }

    public function set_language($language)
    {
        if ($this->language == $language) {
            return;
        }
        Translator::$language = $language;
        $this->language = $language;
    }
}
