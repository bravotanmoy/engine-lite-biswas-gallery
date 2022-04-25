<?php
namespace Elab\Lite\Controllers\Entity;

use Elab\Lite\Services\Price;
use Elab\Lite\System\Repository;
use Elab\Lite\Services\Database;
use Elab\Lite\System\EntityController;

class ProductModifications extends EntityController
{
    public function format_element_backend_list($element)
    {
        $this->format($element, 'list');
        $element['item_count'] = $this->get_e('product_items')->count_elements("modification_id=$element[id]");
        return $element;
    }

    public function format_element_photos($element)
    {
        return $element;
    }
    
    public function format_element_first_photo($element)
    {
        $element['photo'] = GALLERY_API_HOST."/api/gallery/catalog-image/".$element['id'].".jpg";
        return $element;
    }

    public function get_price_info(&$element)
    {
        $where = "modification_id = $element[id] AND quantity>0 AND active=1";
        $price_info = [];
        $item_ids = Database::get_first("SELECT GROUP_CONCAT(id) FROM lite_product_items WHERE $where");
        $item1 = Database::get_assoc("SELECT * FROM lite_product_items WHERE $where ORDER BY price ASC, regular_price DESC LIMIT 1");
        $item2 = Database::get_assoc("SELECT * FROM lite_product_items WHERE $where ORDER BY price DESC, regular_price DESC LIMIT 1");
        if ($item1 && $item2) {
            $price_info['price'] = $item1['price'];
            $price_info['min_price'] = $item1['price'];
            $price_info['max_price'] = $item2['price'];
            $price_info['regular_price'] = $item1['regular_price'];
        }
        return $price_info;
    }

    public function format_element_list($element)
    {
        $element = parent::format_element_list($element);
        $where = "modification_id = $element[id] AND quantity>0 AND active=1";
        if (!isset($element['quantity'])) {
            $element['quantity'] = Database::get_first("SELECT sum(quantity) FROM lite_product_items WHERE $where");
        }

        if (!isset($element['price'], $element['min_price'], $element['max_price'], $element['regular_price'])) {
            $price_info = $this->get_price_info($element);
            $element = array_merge($element, $price_info);
        }

        $element['price_differs'] = @$element['max_price'] > @$element['min_price'];
        if (@$element['price'] < @$element['regular_price']) {
            // $element['discount_percent'] = round((1 - $element['price']/$element['regular_price'])*100);
        }
        $product = $this->get_e('products')->get_element($element['product_id'], 'id', 'translate');
        $element['product_name'] = $product['name'];
        $element['modification_name'] = @$element['name'];
        return $element;
    }
    
    public function format_element_detailed($element)
    {
        $element = parent::format_element_detailed($element);
        $this->format($element, 'list');
        if (@$element['price']) {
            $element['one_unit'] = Price::format_unit(@$element['name'], $element['price']);
        }
        return $element;
    }
    
    public function get_full_url($element)
    {
        $this->prepare_element($element);
        $full_url = $this->get_e('products')->get_full_url($element['product_id']);
        return $full_url ? $full_url.'?modification='.$element['id'] : false;
    }
    
    public function save(&$params = array())
    {
        if (!parent::save($params)) {
            return false;
        }
        
        if (isset($params['update_collections']) && isset($params['collections'])) {
            $collections = array_keys(array_filter($params['collections']));
            $this->get_e('relations')->save_elements_by_relation('collections_product_modifications', $collections, $params['id']);
        }

        $this->get_e('products')->update_product_omnisend($params['product_id']);

        return true;
    }

    public function delete_element($id, $key = "id")
    {
        if ($key == 'id') {

            $this->get_e('product_items')->delete_elements("modification_id=$id");

            /* @var $re RelationsEntityController */
            $re = $this->get_e('relations');
            $re->delete_elements_by_relation('tags_product_modifications', $id);
            $re->delete_elements_by_relation('collections_product_modifications', $id);
            $re->delete_elements_by_relation('vouchers_product_modifications', $id);
            $re->delete_elements_by_relation('pd_product_modifications', $id);
        }
        return parent::delete_element($id, $key);
    }

}
