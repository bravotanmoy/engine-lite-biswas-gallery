<?php

namespace Elab\Lite\System;

use Elab\Lite\Services\Database;

class ProductsCollection extends DataCollection
{

//	public $filter = null;

    public function __construct($data = null, $cache = true)
    {
        if (is_array($data)) {
            parent::__construct($data);
        } else {
            $this->data = $this->load_products($data, $cache);
        }
        Repository::$app->products_collection = $this;
    }

    public function load_products($group_by = 'pi.group_by', $cache = true)
    {
        if (empty($group_by)) {
            $group_by = 'pi.group_by';
        }
        $app = Repository::$app;
        $rel_e = $this->get_e('relations');
        $lang = @$app->lang_key ?: 'unknown';
        $cache_file = "product_collection-$lang-$group_by";
        if ($cache && ($products = $app->read_cache($cache_file))) {
            return $products;
        }

        $product_categories = array();
        $q = Database::query("
			SELECT
				p.id
				,GROUP_CONCAT(DISTINCT pc.id) product_categories1
				,GROUP_CONCAT(DISTINCT pc2.id) product_categories2
				,GROUP_CONCAT(DISTINCT pc3.id) product_categories3
			FROM lite_products p 
			JOIN lite_products_categories p_c ON p_c.product_id = p.id
			JOIN lite_product_categories pc ON p_c.product_category_id = pc.id
			LEFT JOIN lite_product_categories pc2 ON pc2.id = pc.parent
			LEFT JOIN lite_product_categories pc3 ON pc3.id = pc2.parent
			WHERE p.active = 1
			GROUP BY p.id
		");

        while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
            $categories = array_filter(array($row['product_categories1'], $row['product_categories2'], $row['product_categories3']));
            $product_categories[$row['id']] = explode(',', implode(',', array_unique($categories)));
        }

        $product_tags = array();
        $products_relation_id = $rel_e->get_relation_id('tags_products');
        $q = Database::query("
			SELECT p.id, GROUP_CONCAT(re.element_id) tag_ids
			FROM lite_products p 
			JOIN lite_related_elements re ON re.relation_id = {$products_relation_id} AND re.related_element_id = p.id
			JOIN lite_tags t ON t.id = re.element_id
			WHERE p.active = 1
			GROUP BY p.id
		");
        while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
            $product_tags[$row['id']] = explode(',', $row['tag_ids']);
        }

        $modification_tags = array();
        $product_modifications_relation_id = $rel_e->get_relation_id('tags_product_modifications');
        $q = Database::query("
			SELECT pm.id, GROUP_CONCAT(re.element_id) tag_ids
			FROM lite_product_modifications pm
			JOIN lite_related_elements re ON re.relation_id = {$product_modifications_relation_id} AND re.related_element_id = pm.id
			JOIN lite_tags t ON t.id = re.element_id
			WHERE pm.active = 1
			GROUP BY pm.id
		");
        while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
            $modification_tags[$row['id']] = explode(',', $row['tag_ids']);
        }

        $item_tags = array();
        $product_items_relation_id = $rel_e->get_relation_id('tags_product_items');
        $q = Database::query("
			SELECT pi.id, GROUP_CONCAT(re.element_id) tag_ids
			FROM lite_product_items pi 
			JOIN lite_related_elements re ON re.relation_id = {$product_items_relation_id} AND re.related_element_id = pi.id
			JOIN lite_tags t ON t.id = re.element_id
			WHERE pi.active = 1
			GROUP BY pi.id
		");
        while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
            $item_tags[$row['id']] = explode(',', $row['tag_ids']);
        }

        $product_collections = array();
        $q = Database::query("
			SELECT
				p.id, GROUP_CONCAT(DISTINCT c.id) collection_ids
			FROM lite_products p
			JOIN lite_related_elements ri ON ri.related_element_id = p.id
            JOIN lite_relations r ON r.id=ri.relation_id AND r.name='collections_products'
			JOIN lite_collections c ON c.id = ri.element_id
			WHERE c.active = 1 AND p.active = 1
			GROUP BY p.id
		");
        while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
            $product_collections[$row['id']] = explode(',', $row['collection_ids']);
        }

        $modification_collections = array();
        $q = Database::query("
			SELECT
				pm.id, GROUP_CONCAT(DISTINCT c.id) collection_ids
			FROM lite_product_modifications pm
			JOIN lite_related_elements ri ON ri.related_element_id = pm.id
            JOIN lite_relations r ON r.id=ri.relation_id AND r.name='collections_product_modifications'
			JOIN lite_collections c ON c.id = ri.element_id
			WHERE c.active = 1 AND pm.active = 1
			GROUP BY pm.id
		");
        while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
            $modification_collections[$row['id']] = explode(',', $row['collection_ids']);
        }

        $item_collections = array();
        $q = Database::query("
			SELECT
				pi.id, GROUP_CONCAT(DISTINCT c.id) collection_ids
			FROM lite_product_items pi
			JOIN lite_related_elements ri ON ri.related_element_id = pi.id
            JOIN lite_relations r ON r.id=ri.relation_id AND r.name='collections_product_items'
			JOIN lite_collections c ON c.id = ri.element_id
			WHERE c.active = 1 AND pi.active = 1
			GROUP BY pi.id
		");
        while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
            $item_collections[$row['id']] = explode(',', $row['collection_ids']);
        }

        $product_filter_values = array();
        $q = Database::query("
			SELECT
				p.id, GROUP_CONCAT(DISTINCT fve.filter_value_id) filter_values_ids
			FROM lite_products p
			JOIN lite_filter_values_elements fve ON fve.element_id = p.id AND entity_name = 'products' AND filter_value_id IS NOT NULL
			JOIN lite_filters f ON f.id = fve.filter_id AND f.active = 1
			WHERE p.active = 1 
			GROUP BY p.id
		");
        while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
            $product_filter_values[$row['id']] = explode(',', $row['filter_values_ids']);
        }

        $modification_filter_values = array();
        $q = Database::query("
			SELECT
				pm.id, GROUP_CONCAT(DISTINCT fve.filter_value_id) filter_values_ids
			FROM lite_products p
			JOIN lite_product_modifications pm ON pm.product_id = p.id
			JOIN lite_filter_values_elements fve ON fve.element_id = pm.id AND entity_name = 'product_modifications' AND filter_value_id IS NOT NULL
			JOIN lite_filters f ON f.id = fve.filter_id AND f.active = 1
			WHERE p.active = 1 AND pm.active = 1
			GROUP BY pm.id
		");
        while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
            $modification_filter_values[$row['id']] = explode(',', $row['filter_values_ids']);
        }

        $item_filter_values = array();
        $q = Database::query("
			SELECT
				pi.id, GROUP_CONCAT(DISTINCT fve.filter_value_id) filter_values_ids
			FROM lite_products p
			JOIN lite_product_items pi ON pi.product_id = p.id
			JOIN lite_filter_values_elements fve ON fve.element_id = pi.id AND entity_name = 'product_items' AND filter_value_id IS NOT NULL
			JOIN lite_filters f ON f.id = fve.filter_id AND f.active = 1
			WHERE p.active = 1 AND pi.active = 1
			GROUP BY pi.id
		");
        while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
            $item_filter_values[$row['id']] = explode(',', $row['filter_values_ids']);
        }

        $product_filter_checks = array();
        $q = Database::query("
			SELECT
				p.id, GROUP_CONCAT(DISTINCT fve.filter_id) filter_ids
			FROM lite_products p
			JOIN lite_filter_values_elements fve ON fve.element_id = p.id AND fve.entity_name = 'products' AND checked_value IS NOT NULL
			JOIN lite_filters f ON f.id = fve.filter_id AND f.active = 1
			WHERE p.active = 1
			GROUP BY p.id
		");
        while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
            $product_filter_checks[$row['id']] = explode(',', $row['filter_ids']);
        }

        $collections = $app->load_entity_controller('collections')->find_elements();
        $collection_ids = array();
        $filter_collections = array();
        foreach ($collections as $c) {
            if (is_numeric($c['scope'])) {
                $collection_ids['tags'][$c['scope']][] = $c['id'];
            } elseif ($c['scope'] == 'filters') {
                $c = $this->collection_data($c);
                $filter_collections[] = $c;
            } else {
                $collection_ids[$c['scope']][] = $c['id'];
            }
        }

        $products = array();
        $q = Database::query("
			SELECT
				pi.id item_id, MIN(pi.price) price, MAX(pi.price) max_price, MIN(pi.regular_price) regular_price, SUM(pi.quantity) quantity, GROUP_CONCAT(pi.id) item_ids, pi.group_by,
				pm.id modification_id, GROUP_CONCAT(DISTINCT pm.id) modification_ids,
				p.id product_id, p.position, p.date, p.name product_name,
				b.id brand_id, b.name brand_name
			FROM lite_product_items pi
			JOIN lite_products p ON pi.product_id = p.id
			LEFT JOIN lite_brands b ON b.id = p.brand_id
			LEFT JOIN lite_product_modifications pm ON pm.id = pi.modification_id
			WHERE 
				p.active = 1 AND
				(b.active IS NULL OR b.active = 1) AND
				pi.quantity > 0 AND pi.active = 1 AND
				(pm.active IS NULL OR pm.active = 1) AND
				$group_by IS NOT NULL
			GROUP BY $group_by
		");
        while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
            $row['min_price'] = $row['price'];
            $row['modification_ids'] = explode(',', $row['modification_ids']);
            $row['item_ids'] = explode(',', $row['item_ids']);
            $row['product_categories'] = @$product_categories[$row['product_id']] ?: array();
            $row['filter_values_ids'] = @$product_filter_values[$row['product_id']] ?: array();
            foreach ($row['modification_ids'] as $modification_id) {
                $row['filter_values_ids'] = array_merge($row['filter_values_ids'], @$modification_filter_values[$modification_id] ?: array());
            }
            foreach ($row['item_ids'] as $item_id) {
                $row['filter_values_ids'] = array_merge($row['filter_values_ids'], @$item_filter_values[$item_id] ?: array());
            }
            $row['filter_values_ids'] = array_unique($row['filter_values_ids']);
            $row['filter_ids_checked'] = @$product_filter_checks[$row['product_id']] ?: array();
            $row['tag_ids'] = @$product_tags[$row['product_id']] ?: array();
            $row['collections'] = @$product_collections[$row['product_id']] ?: array();
            foreach ($row['item_ids'] as $id) {
                $row['tag_ids'] = array_merge($row['tag_ids'], @$item_tags[$id] ?: array());
                $row['collections'] = array_merge($row['collections'], @$item_collections[$id] ?: array());
            }
            foreach ($row['modification_ids'] as $id) {
                $row['tag_ids'] = array_merge($row['tag_ids'], @$modification_tags[$id] ?: array());
                $row['collections'] = array_merge($row['collections'], @$modification_collections[$id] ?: array());
            }
            $row['tag_ids'] = array_unique($row['tag_ids']);
            // jeigu preke ikelta neveliau kaip pries 1 menesi, dedam ja i "new" ir "special" kolekcijas
            if (strtotime($row['date']) > strtotime("-1 month")) {
                $row['collections'] = array_merge(
                    $row['collections'],
                    @$collection_ids['new'] ?: array(),
                    @$collection_ids['special'] ?: array()
                );
            }
            // dedam preke i visas tag-kolekcijas, pagal prekes tag'us
            foreach ($row['tag_ids'] as $tag) {
                $row['collections'] = array_merge($row['collections'], @$collection_ids['tags'][$tag] ?: array());
            }
            // jei prekei taikoma akcijine kaina, dedam ja i "discounts" ir "special" kolekcijas
            if ($row['price'] < $row['regular_price']) {
                $row['discount_percent'] = round((1 - $row['price'] / $row['regular_price']) * 100);
                $row['collections'] = array_merge(
                    $row['collections'],
                    @$collection_ids['discounts'] ?: array(),
                    @$collection_ids['special'] ?: array()
                );
            }
            foreach ($filter_collections as $c) {
                $category_match = true;
                $filters_match = true;
                if (!empty($c['categories'])) {
                    $category_match = false;
                    if (count(array_intersect(array_keys($c['categories']), $row['product_categories'])) > 0) {
                        $category_match = true;
                    }
                }
                if (!empty($c['filters'])) {
                    foreach ($c['filters'] as $filter_id => $filter_values) {
                        $filters_match = false;
                        if (count(array_intersect($filter_values, $row['filter_values_ids'])) > 0) {
                            $filters_match = true;
                        } else {
                            break;
                        }
                    }
                }
                if ($category_match && $filters_match) {
                    $row['collections'][] = $c['id'];
                }
            }
            $row['collections'] = array_unique($row['collections']);
            list($row['group_by']) = explode(':', $row['group_by']);
            $products[] = $row;
        }
        $app->write_cache($cache_file, $products);
        return $products;
    }

    protected function collection_data($c)
    {
        $c = $this->get_e('collections')->format_element($c, 'categories');
        $c['filters'] = array();
        $selected_filters = Database::get_assoc_all("
			SELECT pf.*, f.type, f.name as filter_name
			FROM lite_filter_values_elements AS pf
			JOIN lite_filters as f ON f.id=pf.filter_id
			WHERE pf.element_id=$c[id] AND pf.entity_name='collections'
		");
        foreach ($selected_filters as $f) {
            if (!array_key_exists($f['filter_id'], $c['filters'])) {
                $c['filters'][$f['filter_id']] = array();
            }
            $c['filters'][$f['filter_id']][] = $f['filter_value_id'];
        }
        return $c;
    }

    /**
     * @return \Elab\Lite\System\ProductsCollection
     */
    public function copy()
    {
        $obj = parent::copy();
        $obj->filter = $this->filter;
        return $obj;
    }

    public function get_values($field)
    {
        if (!empty($this->data)) {
            $values = call_user_func_array('array_merge', array_column($this->data, $field));
            return array_unique($values);
        } else {
            return [];
        }
    }

    public function filter_default($element)
    {
        $fp = $this->filter_params;
        $rules = is_array($fp) ? $fp : array('brands', 'collections', 'prices', 'fmod', 'sp', 'items', 'categories', 'subcategories', 'tags');
        foreach ($rules as $rule) {
            if (empty($this->filter[$rule])) {
                continue;
            }
            if ($rule == 'prices' || $rule == 'fmod') {
                $this->filter_params = $this->filter[$rule];
            } else {
                $this->filter_params = is_array($this->filter[$rule]) ? array_keys($this->filter[$rule]) : $this->filter[$rule];
            }
            $method = "filter_$rule";
            $result = $this->$method($element);
            $this->filter_params = $fp;
            if (!$result) {
                return false;
            }
        }
        return true;
    }

    protected function filter_main($element)
    {
        $fp = $this->filter_params;
        $f = Repository::$frontend;
        // category
        if (@$f->category) {
            $this->filter_params = @$f->category['id'];
            $result = $this->filter_category($element);
            if (!$result) {
                return false;
            }
        }
        /*
          // brand
          if (@$f->brand) {
          $this->filter_params = $f->brand['id'];
          $result = $this->filter_brands($element);
          if (!$result) return false;
          }
         */
        $this->filter_params = $fp;
        return true;
    }

    protected function filter_category($element)
    {
        $category = $this->filter_params;
        return in_array($category, $element['product_categories']);
    }

    protected function filter_fmod($element)
    {
        $params = $this->filter_params;
        foreach ($params as $values) {
            $result = count(array_intersect(array_keys($values), $element['filter_values_ids'])) > 0;
            if (!$result) {
                return false;
            }
        }
        return true;
    }

    protected function filter_sp($element)
    {
        $sp_items = $this->filter_params;
        if (!is_array($sp_items)) {
            $sp_items = array($sp_items);
        }
        return in_array($element['id'], $sp_items);
    }

    protected function filter_subcategories($element)
    {
        return $this->filter_categories($element);
    }

    protected function filter_categories($element)
    {
        $categories = $this->filter_params;
        $result = count(array_intersect($categories, $element['product_categories'])) > 0;
        return $result;
    }

    protected function filter_collections($element)
    {
        if (!is_array($this->filter_params)) {
            return in_array($this->filter_params, $element['collections']);
        } else {
            return count(array_intersect($this->filter_params, $element['collections'])) > 0;
        }
    }

    protected function filter_prices($element)
    {
        $prices = $this->filter_params;
        if (empty($prices)) {
            return true;
        }
        if (isset($prices[0], $prices[1]) && $prices[0] == $prices[1]) {
            $prices[1] += 1;
        }
        $min_price_ok = !isset($prices[0]) || $element['price'] >= $prices[0];
        $max_price_ok = !isset($prices[1]) || $element['price'] <= $prices[1];
        return $min_price_ok && $max_price_ok;
    }

    protected function filter_brands($element)
    {
        $brands = $this->filter_params;
        if (!is_array($brands)) {
            $brands = array($brands);
        }
        return in_array($element['brand_id'], $brands);
    }

    protected function filter_tags($element)
    {
        $tags = $this->filter_params;
        $result = count(array_intersect($tags, $element['tag_ids'])) > 0;
        return $result;
    }

    protected function filter_items($element)
    {
        return count(array_intersect($this->filter_params, $element['item_ids'])) > 0;
    }

    protected function filter_item_names($element)
    {
        return in_array($element['item_name'], $this->filter_params);
    }

    protected function filter_modifications($element)
    {
        return count(array_intersect($this->filter_params, $element['modification_ids'])) > 0;
    }

    protected function filter_product($element)
    {
        return $element['product_id'] == $this->filter_params;
    }

    protected function filter_brand($element)
    {
        $brand = $this->filter_params;
        return $element['brand_id'] == $brand;
    }

    protected function filter_in_stock($element)
    {
        $result = @$element['quantity'] > 0;
        return $result;
    }

    protected function filter_amount($element)
    {
        return $element['quantity'] > 0;
    }

    protected function filter_eval($element)
    {
        // pvz.:
        // ...->filter('eval', array('id' => '$val!=23'))
        // ...->filter('eval', array('id' => 'in_array($val, array(1,2,3)'))
        $fields = $this->filter_params;
        foreach ($fields as $field => $expr) {
            if (!isset($element[$field])) {
                return false;
            }
            $val = $element[$field];
            eval("\$result = ($expr);");
            if (!$result) {
                return false;
            }
        }
        return true;
    }

    protected function filter_exclude_fields($element)
    {
        $brands = $this->filter_params;
        $result = $element['quantity'] > 0;
        return $result;
    }

    protected function format_default(&$element, $key, $params = null)
    {
        if ($element['group_by'] == "id") {
            $element['id'] = $element['item_id'];
            $this->get_e('product_items')->format($element, 'list');
        } elseif ($element['group_by'] == "modification_id") {
            $element['id'] = $element['modification_id'];
            $this->get_e('product_modifications')->format($element, 'list');
        } elseif ($element['group_by'] == "product_id") {
            $element['id'] = $element['product_id'];
            $this->get_e('products')->format($element, 'list');
        }
    }

    protected function sort_price_desc($a, $b)
    {
        if ($a['price'] > $b['price']) {
            return -1;
        }
        if ($a['price'] < $b['price']) {
            return 1;
        }
        if ($a['regular_price'] > $b['regular_price']) {
            return -1;
        }
        if ($a['regular_price'] < $b['regular_price']) {
            return 1;
        }
        return 0;
    }

    protected function sort_price_asc($a, $b)
    {
        if ($a['price'] > $b['price']) {
            return 1;
        }
        if ($a['price'] < $b['price']) {
            return -1;
        }
        if ($a['regular_price'] > $b['regular_price']) {
            return 1;
        }
        if ($a['regular_price'] < $b['regular_price']) {
            return -1;
        }
        return 0;
    }

    protected function sort_position_asc($a, $b)
    {
        if ($a['position'] > $b['position']) {
            return 1;
        }
        if ($a['position'] < $b['position']) {
            return -1;
        }
        return 0;
    }

    protected function sort_position_desc($a, $b)
    {
        if ($a['position'] > $b['position']) {
            return -1;
        }
        if ($a['position'] < $b['position']) {
            return 1;
        }
        return 0;
    }

    protected function sort_date_desc($a, $b)
    {
        return $a['date'] > $b['date'] ? -1 : ($a['date'] < $b['date'] ? 1 : 0);
    }

    protected function sort_discount_desc($a, $b)
    {
        $a_discount = $a['regular_price'] > 0 ? $a['price'] / $a['regular_price'] : 0;
        $b_discount = $b['regular_price'] > 0 ? $b['price'] / $b['regular_price'] : 0;

        if ($a_discount > $b_discount) {
            return 1;
        }
        if ($a_discount < $b_discount) {
            return -1;
        }
        if ($a['price'] > $b['price']) {
            return 1;
        }
        if ($a['price'] < $b['price']) {
            return -1;
        }
        return 0;
    }

    protected function sort_main($a, $b)
    {
        if ($a['quantity'] > 0 && $b['quantity'] == 0) {
            return -1;
        } elseif ($b['quantity'] > 0 && $a['quantity'] == 0) {
            return 1;
        } else {
            return 0;
        }
    }

    protected function sort_name_asc($a, $b)
    {
        return strcmp("$a[product_name]", "$b[product_name]");
    }

    protected function sort_full_name_desc($a, $b)
    {
        return -1 * strcmp("$a[brand_name] $a[name]", "$b[brand_name] $b[name]");
    }
}
