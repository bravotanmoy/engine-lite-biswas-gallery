<?php
namespace Elab\Lite\Controllers\Entity;

use Elab\Lite\Services\Price;
use Elab\Lite\System\Repository;
use Elab\Lite\Services\Database;
use Elab\Lite\System\EntityController;

class ProductItems extends EntityController
{
    public function format_element_edit($element)
    {
        $element = parent::format_element_edit($element);
        $collections = $this->get_e('relations')->get_elements_by_relation('collections_product_items', $element['id']);
        $element['collections'] = $collections ? array_combine($collections, $collections) : array();
        return $element;
    }
    
    public function format_element_photos($element)
    {
        return $element;
    }
    
    public function format_element_first_photo($element)
    {
        return $element;
    }
    
    public function get_full_url($element)
    {
        $this->prepare_element($element);
        $e = $this->get_e('products');
        $product = $e->get_element($element['product_id']);
        $full_url = $e->get_full_url($product);
        return $full_url ? $full_url.'?item='.$element['id'] : false;
    }

    public function format_element_list($element)
    {
        $element = parent::format_element_list($element);

        $e = $this->get_e('products');
        $product = $e->get_element($element['product_id'], 'id', 'translate');
        $element['product_name'] = $product['name'];

        $element['item_name'] = $element['name'];
        
        if ($element['price'] < $element['regular_price']) {
            $element['discount_percent'] = round((1 - $element['price'] / $element['regular_price']) * 100);
        }
        if ($element['modification_id']) {
            $e = $this->get_e('product_modifications');
            $element['modification'] = $e->get_element($element['modification_id'], 'id', 'translate');
            $element['modification_name'] = @$element['modification']['name'];
        }

        $element['one_unit'] = Price::format_unit($element['name'], $element['price']);
        
        return $element;
    }

    public function format_element_offer_list($element)
    {
        $this->format($element, 'list');
        $this->get_e('product_modifications')->format($element['modification'], 'info');
        $element['product'] = $this->get_e('products')->get_element($element['product_id'], 'id', 'info');
        return $element;
    }

    public function save(&$params = array())
    {
        $new = empty($params['id']);
        if ($new) {
            $params['price'] = $params['regular_price'];
        }
        
        if (isset($params['promo_price']) && $params['promo_price'] >= $params['regular_price']) {
            $params['promo_price'] = null;
        }
        
        if ($result = parent::save($params)) {
            $this->update_prices("id=$params[id]");
            $this->update_group_by("pi.id=$params[id]");
        }
        
        if ($result && isset($params['update_collections'])) {
            $collections = array_keys(array_filter(@$params['collections'] ?: []));
            $this->get_e('relations')->save_elements_by_relation('collections_product_items', $collections, $params['id']);
        }

        if (isset($params['product_id'])) {
            $this->get_e('products')->update_product_omnisend($params['product_id']);
        }

        return $result;
    }

    public function update_prices($where = '1')
    {
        Database::query("UPDATE lite_product_items SET price = IF(promo_price IS NOT NULL AND promo_price < regular_price, promo_price, regular_price) WHERE $where");
    }

    public function update_group_by($where = '1')
    {
        Database::query("
			UPDATE lite_product_items pi 
			JOIN lite_products p ON p.id=pi.product_id 
			SET pi.group_by = 
				CASE p.item_group 
					WHEN 1 THEN CONCAT('id:',pi.id) 
					WHEN 2 THEN CONCAT('modification_id:',pi.modification_id) 
					WHEN 3 THEN CONCAT('product_id:',pi.product_id) 
					ELSE NULL 
				END
			WHERE $where
		");
    }

}
