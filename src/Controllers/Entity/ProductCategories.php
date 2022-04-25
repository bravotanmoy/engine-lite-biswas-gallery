<?php
namespace Elab\Lite\Controllers\Entity;

use Elab\Lite\System\Repository;
use Elab\Lite\Services\Database;
use Elab\Lite\System\EntityController;

class ProductCategories extends EntityController
{
    public function format_element_edit($element)
    {
        $element = parent::format_element_edit($element);

        $element['assigned_products'] = Database::get_all_first("SELECT product_id FROM lite_products_categories WHERE product_category_id = '{$element['id']}' ");

        return $element;
    }
    
    public function save(&$params = array())
    {
        if ($result = parent::save($params)) {
            if (isset($params['update_assigned_products'])) {
                Database::query("DELETE FROM lite_products_categories WHERE product_category_id='$params[id]'");
                if (!empty($params['assigned_products'])) {
                    foreach ($params['assigned_products'] as $v) {
                        if ($v) {
                            Database::query("INSERT INTO lite_products_categories (product_id, product_category_id) VALUES ('$v', '{$params['id']}')");
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function get_categories_with_products()
    {
        if (!@Repository::$cache['categories_with_products']) {
            // tik kategorijos su aktyviomis prekėmis
            $ids = Database::get_all_first("
				SELECT DISTINCT p_c.product_category_id 
				FROM lite_products_categories p_c
				JOIN lite_product_items pif ON p_c.product_id = pif.product_id AND pif.quantity>0 AND pif.active=1
			");
            // tevines kategorijos tuomet taip pat turi aktyviu prekiu
            $parent_ids = $ids ? Database::get_all_first("SELECT parent FROM lite_product_categories WHERE id IN (".implode(',', $ids).")") : array();
            $grandparent_ids = $parent_ids ? Database::get_all_first("SELECT parent FROM lite_product_categories WHERE id IN (".implode(',', $parent_ids).")") : array();
            @Repository::$cache['categories_with_products'] = array_merge($ids, $parent_ids, $grandparent_ids);
        }
        return @Repository::$cache['categories_with_products'];
    }

    public function delete_element($id, $key = "id")
    {
        if ($key == 'id') {
            Database::query("DELETE FROM lite_products_categories WHERE (`product_category_id` = {$id})");
            Database::query("DELETE FROM lite_filters_categories WHERE (`category_id` = {$id})");
            $cats = $this->select_elements(array('where' => "`parent`='$id'"));
            foreach ($cats as $cat) {
                $this->delete_element($cat['id']);
            }

            /* @var $re RelationsEntityController */
            $re = $this->get_e('relations');
            $re->delete_elements_by_relation('pd_product_categories', $id);
            $re->delete_elements_by_relation('vouchers_product_categories', $id);
            $re->delete_elements_by_relation('collections_product_categories', $id);
        }
        return parent::delete_element($id, $key);
    }


    public function get_hierarchy($root_element = 0, $formatting_mode = false, $where_clause = "", $levels = 0, $current_level = 0)
    {
        $params['where'] = ($where_clause ? "$where_clause AND " : '') . "`parent` = $root_element";
        $params['order_by'] = "`position` ASC, `name` ASC";
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

    public function levels_list($root_element = 0, $escape_element = 0)
    {
        $hierarchy = $this->get_hierarchy($root_element, /* formatting_mode */ 'default', /* where_clause */ "id!='$escape_element'");
        return $this->format_levels($hierarchy);
    }

    public function get_entity_page_type()
    {
        return 'products';
    }

    /**
     * @overrided
     * (non-PHPdoc)
     * @see include/EntityController#prepare_filter_page()
     */
    public function prepare_filter_page()
    {
        $options = parent::prepare_filter_page();
        $options[0] = VC_SELECT_ONE;
        return $options;
    }

    public function format_element($element, $mode = 'default')
    {
        if (!$mode) {
            return $element;
        }
        if ($mode=='tree') {
            $products_controller = $this->load_entity_controller('products');
            if (Repository::$backend) {
                $element['accessible'] = $this->is_accessible($element['id'], 'active');
            }
            $childs = $this->get_all_childs($element['id'], 'product_categories', "`active`='1'");
            $childs[] = $element['id'];
        }
        return parent::format_element($element, $mode);
    }

    /**
     * Suformuojamas kelias is herarchijos
     *
     * @param type $id
     * @return type
     */
    public function get_breadcrumb($id, $formatting_mode = false)
    {
        $result = array();

        $element = $this->get_element($id, 'id', $formatting_mode);
        $result[] = $element;
        while ($element['parent']) {
            $element = $this->get_element($element['parent'], 'id', $formatting_mode);
            $result[] = $element;
        }

        $result = array_reverse($result);
        return $result;
    }

    public function get_full_url($element)
    {
        $this->prepare_element($element);
        $page_url = $this->get_e('pages')->get_full_url_by_type('products');
        if (@$this->brand) {
            $page_url .= $this->brand['url'].'/';
        }
        if (@$this->collection) {
            $page_url .= $this->collection['url'].'/';
        }
        if (!$page_url) {
            return false;
        }
        if($element) {
            $breadcrumb = $this->get_breadcrumb($element['id'], 'translate');
            foreach ($breadcrumb as $cat) {
                $page_url .= $cat['url'] . '/';
            }
        }
        return $page_url;
    }
    
    public function format_element_default($element)
    {
        $element = parent::format_element_default($element);
        $element['child_count'] = $this->count_elements("parent=$element[id]");
        return $element;
    }
}
