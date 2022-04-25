<?php
namespace Elab\Lite\Controllers\Entity;

use Elab\Lite\System\Repository;
use Elab\Lite\Services\Database;
use Elab\Lite\System\EntityController;
use Elab\Lite\System\Helper;

class Products extends EntityController
{
    public function format_element_backend_list($element)
    {
        $this->format($element, 'list');
        $element['modification_count'] = $this->get_e('product_modifications')->count_elements("product_id=$element[id]");
        $element['item_count'] = $this->get_e('product_items')->count_elements("product_id=$element[id]");
        return $element;
    }

    public function format_element_photos($element)
    {
        return $element;
    }
    
    public function format_element_first_photo($element)
    {
        // product_foto
        $e = $this->load_entity_controller('photos');
        if ($photo = $e->get_first_entity_element($element['id'], 'default')) {
            $photo['entity'] = 'products';
            $element['photo'] = $photo;
            return $element;
        }

        // modifikacijos foto
        if ($this->load_entity_controller('product_modifications')->config['active']) {
            $e = $this->load_entity_controller('product_modifications')->load_entity_controller('photos');
            $ids = Database::get_all_first("SELECT id FROM lite_product_modifications WHERE product_id = $element[id] AND active = 1 ORDER BY position");
            foreach ($ids as $id) {
                if ($photo = $e->get_first_entity_element($id, 'default')) {
                    $photo['entity'] = 'product_modifications';
                    $element['photo'] = $photo;
                    return $element;
                }
            }
        }
        
        // SKU photo
        $e = $this->load_entity_controller('product_items')->load_entity_controller('photos');
        $ids = Database::get_all_first("SELECT id FROM lite_product_items WHERE product_id = $element[id] AND active = 1 ORDER BY position");
        foreach ($ids as $id) {
            if ($photo = $e->get_first_entity_element($id, 'default')) {
                $photo['entity'] = 'product_items';
                $element['photo'] = $photo;
                return $element;
            }
        }
        
        return $element;
    }
    
    public function format_element_search($element)
    {
        $element = parent::format_element_search($element);
        $element['description'] = strip_tags(html_entity_decode($element['description'], ENT_QUOTES));
        return $element;
    }
    
    public function format_element_default($element)
    {
        $element = parent::format_element_default($element);
        $element['product_name'] = $element['name'];
        if ($element['brand_id']) {
            $element['brand'] = $this->get_e('brands')->get_element($element['brand_id']);
        }
        return $element;
    }
    
    public function format_element_list($element)
    {
        $element = parent::format_element_list($element);
        $element['product_name'] = $element['name'];
        $where = "product_id=$element[id] AND quantity>0 AND active=1";
        $element['quantity'] = Database::get_first("SELECT sum(quantity) FROM lite_product_items WHERE $where");
        $item1 = Database::get_assoc("SELECT * FROM lite_product_items WHERE $where ORDER BY price ASC, regular_price DESC LIMIT 1");
        $item2 = Database::get_assoc("SELECT * FROM lite_product_items WHERE $where ORDER BY price DESC, regular_price DESC LIMIT 1");
        $element['price'] = !empty($item1) ? $item1['price'] : 0;
        $element['regular_price'] = !empty($item1) ?$item1['regular_price']:0;
        $element['price_differs'] = (!empty($item1) ?$item2['price']:0) > (!empty($item1) ?$item1['price']:0);
        if ($element['price'] < $element['regular_price']) {
            $element['discount_percent'] = round((1 - $element['price']/$element['regular_price'])*100);
        }
        return $element;
    }
        
    public function format_element_detailed($element)
    {
        $element = parent::format_element_detailed($element);
        $this->format($element, array('list', 'filters_detailed', 'categories'));

        if ($this->load_entity_controller('product_modifications')->config['active']) {
            // modifikacijos
            $modifications = $this->get_e('product_modifications')->list_elements("product_id=$element[id]", 'list');
            // modifikacijas kuriu nera, rodome saraso gale
            $na_list = $stock_list = array();
            foreach ($modifications as $m) {
                if ($m['quantity'] > 0) {
                    $stock_list[$m['id']] = $m;
                } else {
                    $na_list[$m['id']] = $m;
                }
            }
            $element['modifications'] = $stock_list + $na_list;
        } else {
            $element['modifications'] = false;
        }
        
        // items
        $element['items'] = array();
        $items = $this->get_e('product_items')->list_elements("product_id=$element[id]");
        foreach ($items as $item) {
            $element['items'][$item['id']] = $item;
        }

        $element['related_products'] = $this->get_e('relations')->get_related_elements('related_products', $element['id'], false);
        $element['similar_products'] = $this->get_e('relations')->get_related_elements('similar_products', $element['id'], false, true);

        return $element;
    }
    
    public function format_element_categories($element)
    {
        if ($element['categories'] = Database::get_all_first("SELECT product_category_id FROM lite_products_categories WHERE product_id=$element[id]")) {
            $element['categories'] = array_combine($element['categories'], array_fill(0, count($element['categories']), true));
        }
        return $element;
    }
    
    public function format_element_edit($element)
    {
        $element = parent::format_element_edit($element);
        $this->format($element, 'categories');
        $element['related_products'] = $this->get_e('relations')->get_related_elements('related_products', $element['id'], false);
        $element['similar_products'] = $this->get_e('relations')->get_related_elements('similar_products', $element['id'], false, true);
        $collections = $this->get_e('relations')->get_elements_by_relation('collections_products', $element['id']);
        $element['collections'] = $collections ? array_combine($collections, $collections) : array();
        return $element;
    }
    
    public function get_categories($product_id)
    {
        $categories = array();
        $rez = Database::query("SELECT * FROM lite_products_categories WHERE product_id='$product_id'");
        while ($row = mysqli_fetch_array($rez, MYSQLI_ASSOC)) {
            $categories[$row['product_category_id']] = 1;
        }
        return $categories;
    }

    public function save(&$params = array())
    {
        if (!parent::save($params)) {
            return false;
        }

        //$saved_params = $params;
        if (empty($params['id'])) {
            $before = false;
        } else {
            $before = $this->get_element($params['id']);
        }

        if (($before || !empty($params['category'])) && isset($params['full_name'], $params['name']) && empty($params['full_name'])) {
            $cat_id = !empty($params['category']) ? $params['category'] : $before['category'];
            $category = $this->load_entity_controller('product_categories')->find_element("id=$cat_id", false, array('auto_params' => false));
            $params['full_name'] = "{$category['brand']} {$params['name']}";
        }

        if (isset($params['labels']) && is_array($params['labels'])) {
            $labels = '';
            foreach ($params['labels'] as $k => $v) {
                if ($v) {
                    $labels .= " $k";
                }
            }
            $params['labels'] = trim($labels);
        }

        if (isset($params['update_collections'])) {
            $collections = array_keys(array_filter(@$params['collections'] ?: []));
            $this->get_e('relations')->save_elements_by_relation('collections_products', $collections, $params['id']);
        }

        if (!empty($params['update_categories'])) {
            $ids = array();
            foreach (@$params['categories'] ?: [] as $k => $v) {
                if ($v) {
                    $ids[] = $k;
                    $ids = $this->check_parent_category($k, $ids);
                }
            }
            $ids_str = implode(",", $ids);
            $values = "($params[id]," . implode("),($params[id],", $ids) . ")";

            if ($ids) {
                Database::query("INSERT IGNORE INTO lite_products_categories (product_id, product_category_id) VALUES $values");
                Database::query("DELETE FROM lite_products_categories WHERE product_id=$params[id] AND product_category_id NOT IN ($ids_str)");
            } else {
                Database::query("DELETE FROM lite_products_categories WHERE product_id=$params[id]");
            }
        }

        if (isset($params['update_related_products'])) {
            $this->get_e('relations')->save_related_elements('related_products', $params['id'], @$params['related_products'] ?: array());
        }
        if (isset($params['update_relatedto_products'])) {
            $this->get_e('relations')->save_related_elements('related_products', $params['id'], @$params['related_products'] ?: array());
        }

        if (isset($params['update_similar_products'])) {
            $this->get_e('relations')->save_related_elements('similar_products', $params['id'], @$params['similar_products'] ?: array(), true);
        }

        // atnaujinam lauka 'group_by' lenteleje 'e_product_items'
        $this->load_entity_controller('product_items')->update_group_by("pi.product_id=$params[id]");

        $this->update_product_omnisend($params['id']);

        return true;
    }

    public function delete_element($id, $key = "id")
    {
        if ($key == 'id') {
            $this->remove_product_omnisend($id);

            Database::query("DELETE FROM lite_products_categories WHERE (`product_id` = {$id})");

            $this->get_e('product_modifications')->delete_elements("product_id=$id");
            $this->get_e('product_items')->delete_elements("product_id=$id");

            /* @var $re RelationsEntityController */
            $re = $this->get_e('relations');
            $re->delete_elements_by_relation('tags_products', $id);
            $re->delete_elements_by_relation('collections_products', $id);
            $re->delete_elements_by_relation('pd_products', $id);
            $re->delete_elements_by_relation('vouchers_products', $id);
            $re->delete_elements_by_relation('similar_products', $id);
            $re->delete_elements_by_relation('related_products', $id);
            $re->delete_related_elements('similar_products', $id);
            $re->delete_related_elements('related_products', $id);
        }
        return parent::delete_element($id, $key);
    }

    public function list_elements($where_clause = false, $apply_formating = 'default', &$return_params = array())
    {
        $return = parent::list_elements($where_clause, $apply_formating, $return_params);
        $return_params['total'] = $this->count_elements($where_clause);
        return $return;
    }


    public function search($keywords, $fields = array())
    {
        if (empty($fields)) {
            $fields = array('`p`.`name`', '`pi`.`name`', '`p`.`code`', '`pi`.`code`', '`b`.`name`');
        }
        $keywords = preg_split("/[,\r\n\t ;'\"()]+/", htmlspecialchars_decode($keywords, ENT_QUOTES));
        foreach ($keywords as $k => $v) {
            if (!$v) {
                unset($keywords[$k]);
            }
        }
        $elements = array();
        $where = array();
        foreach ($keywords as $kw) {
            $kw = htmlspecialchars($kw, ENT_QUOTES);
            $where2 = array();
            foreach ($fields as $fld) {
                $where2[] = "$fld LIKE '%$kw%'";
            }
            $where[] = implode(" OR ", $where2);
        }
        $where = "(" . implode(") AND (", $where) . ")";
        $elements = Database::get_assoc_all(" 
			SELECT pi.id
			FROM lite_product_items pi
			JOIN lite_products p ON pi.product_id = p.id
			JOIN lite_brands b ON b.id = p.brand_id
			LEFT JOIN lite_product_modifications pm ON pm.id = pi.modification_id
			WHERE $where
			ORDER BY pi.position
			LIMIT 100
		");

        $ids = array();
        foreach ($elements as $el) {
            $ids[] = $el['id'];
        }

        return $ids;
    }

    /**
     * FILTRAI
     */
    public function prepare_filter_categories()
    {
        $list = $this->load_entity_controller("product_categories")->levels_list();
        $list = array('' => "...") + $list;
        return $list;
    }

    public function process_filter_categories()
    {
        $filter = $this->config['filter'];
        if (isset($filter['category']) && is_numeric($filter['category'])) {
            $this->add_condition('category', " id IN (SELECT product_id FROM lite_products_categories WHERE product_category_id = {$filter['category']} )");
        }
    }

    public function prepare_filter_brands()
    {
        $list = $this->load_entity_controller("brands")->get_options();
        $list = array('' => "...") + $list;
        return $list;
    }

    public function process_filter_brands()
    {
        $filter = $this->config['filter'];
        if (!empty($filter['brand_id'])) {
            $this->add_condition('brands', "brand_id = {$filter['brand_id']}");
        }
    }

    public function prepare_filter_qty()
    {
        $list = [
            '' => '...',
            'in_stock' => '> 0',
            'out_of_stock' => '= 0',
        ];

        return $list;
    }

    public function process_filter_qty()
    {
        $filter = $this->config['filter'];

        if (!empty($filter['qty'])) {
            if ($filter['qty'] == "in_stock") {
                $this->add_condition('qty', "id IN (SELECT product_id FROM lite_product_items WHERE quantity > 0)");
            }
            if ($filter['qty'] == "out_of_stock") {
                $this->add_condition('qty', "id IN (SELECT product_id FROM lite_product_items WHERE quantity = 0)");
            }
        }
    }

    public function prepare_filter_active()
    {
        $list = [
            '' => '...',
            '1' => t('Taip'),
            '0' => t('Ne'),
        ];

        return $list;
    }

    public function process_filter_active()
    {
        $filter = $this->config['filter'];

        if (!empty($filter['active'])) {
            $this->add_condition('products', "active = {$filter['active']}");
        }
    }

    public function process_filter_dynamic($key)
    {
        $val = $this->config['filter'][$key];
        if (!$val) {
            return false;
        }
        $filter_id = preg_replace('/^filter_/', '', $key);
        $filter = $this->get_e('filters')->get_element($filter_id);
        $this->add_condition($key, "id IN (SELECT element_id FROM lite_filter_values_elements fve WHERE fve.entity_name='products' AND fve.filter_value_id='$val' AND fve.filter_id=$filter[id])");
    }

    public function paginate($aktyvus_psl, $el_per_psl, $viso, $url, $prefix = '', $bookmark = '', $config = array())
    {
        if ($url=='auto') {
            parse_str($_SERVER['QUERY_STRING'], $params);
            unset($params['PATH_INFO'],$params['page'],$params['display']);
            $url = http_build_query($params, '', '&amp;');
        }
        //debug ($url);
        
        $back_label = t(t('atgal'));
        $next_label = t(t('pirmyn'));

        $config = array_merge(
            array(
            'limit' => 3,
            'parts' => 1,
            'width' => 3,
            'back' => "&laquo; $back_label",
            'next' => "$next_label &raquo;",
            ),
            $config
        );
        extract($config);

        $puslapiu_sk = ceil($viso / $el_per_psl);
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
                $psl+=1;
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
    
    public function check_parent_category($category_id, $categories)
    {
        $category = $this->get_e('product_categories')->get_element($category_id);

        if (isset($category['parent']) && is_numeric($category['parent']) && $category['parent'] !== 0) {
            $categories[] = $category['id'];
            return $this->check_parent_category($category['parent'], $categories);
        } else {
            return $categories;
        }
    }

    /*
    function import($products) {
        $config = array(
            'categories' => array(
                40 => 24, // avalyne
                47 => 79, // avalyne/klumpes
            ),
            'import_category' => 99, // imported/open24
            'filters' => array(
                'target' => array(
                    'id' => 147,
                    'values' => array(
                        'men' => 23,
                        'women' => 25,
                        'kids' => 34,
                    ),
                )
            ),
            'brands' => array(
                'Crocs-tm' => 'Crocs',
            ),
        );
        $products = array(
            array(
                'id' => 458,
                'name' => 'Crocs Baya',
                'brand' => 'Crocs',
                'categories' => array(
                    40 => 'Avalynė|Klumpes',
                ),
                'filters' => array(
                    'target' => array('men', 'women'),
                    'season' => array('summer'),
                    'width' => array('wide', 'normal')
                ),
                'images' => array(
                    array(
                        'name' => 'Front',
                        'url' => 'http://open24.lt/images/galleries/1904120912.jpg',
                        'hash' => '1f3870be274f6c49b3e31a0c6728957f',
                    ),
                ),
                'modifications' => array(
                    'id' => 947,
                    'name' => 'Blue',
                    'images' => array(
                        array(
                            'name' => 'Front',
                            'url' => 'http://open24.lt/images/galleries/1904120912.jpg',
                            'hash' => '1f3870be274f6c49b3e31a0c6728957f',
                        ),
                        array(
                            'name' => 'Side',
                            'url' => 'http://open24.lt/images/galleries/1904120912.jpg',
                            'hash' => '1f3870be274f6c49b3e31a0c6728957f',
                        ),
                    ),
                    'filters' => array(
                        'colors' => array('blue'),
                    ),
                ),
                'items' => array(
                    'id' => 'G-23242-M',
                    'name' => 'W5',
                    'modification' => 947,
                    'price' => 29.45,
                    'promo_price' => 24.99,
                    'filters' => array(
                        'sizes' => array('36','37'),
                    ),
                ),
            )
        );
        $supplier = 'open24';
        foreach ($products as $p) {
            $product = $this->get_element("$supplier-$p[id]", 'import_id');
            $product['name'] = $p['name'];

            // brand
            if ($p['brand']) {
                $bname = $config['brnands'][$product['brand']] ?: $product['brand'];
                if (!$brand = $this->get_e('brands')->get_element($bname)) {
                    $brand = array(
                        'name' => $bname,
                        'active' => 0,
                    );
                    $this->get_e('brands')->save($brand);
                }
                $product['brand_id'] = $brand['id'];
            } else {
                $product['brand_id'] = null;
            }

            $this->get_e('products')->save($product);
            $this->get_e('products')->update_photos($product['images'], 'photos');

            // categories
            $product['update_categories'] = true;
            $product['categories'] = array();
            $e = $this->get_e('product_categories');
            if ($config['import_category'] && $e->find_element("id=$config[import_category]")) {
                Database::query("DELETE FROM lite_products_categories WHERE category_id IN (SELECT id FROM lite_product_categories WHERE parent=$config[import_category])");
                foreach ($p['categories'] as $k=>$name) {
                    if (!$e->find_element("parent=$config[import_category] AND import_id=$k")) {
                        $c = array(
                            'parent' => $config['import_category'],
                            'name' => $name,
                            'active' => 0,
                            'import_id' => $k,
                        );
                        if ($e->save($c)) {
                            $product['categories'][$c['id']] = true;
                        }
                    }
                }
            }
            foreach ($p['categories'] as $k=>$name) {
                if ($config['categories'][$k]) {
                    $product['categories'][$k] = true;
                }
            }
            $this->save($product);

            // modifications
            $modification_ids = array();
            $e = $this->get_e('product_modifications');
            foreach ($p['modifications'] ?: array() as $m) {
                $modification = $e->find_element("$supplier-$m[id]", 'import_id') ?: array();
                $modification['name'] = $m['name'];
                $modification['import_id'] = "$supplier-$m[id]";
                if ($e->save($modification)) {
                    $e->update_photos($modification['id'], $m['photos']);
                    $modification_ids[$m['id']] = $modification['id'];
                }
            }

            // items
            $e = $this->get_e('product_items');
            foreach ($p['items'] ?: array() as $i) {
                if (@$i['modification'] && !@$modification_ids[$i['modification']]) {
                    // neatpazinta modifikacija
                    break;
                }
                $item = $e->find_element("$supplier-$i[id]", 'import_id') ?: array();
                $item['name'] = $i['name'];
                $item['import_id'] = "$supplier-$i[id]";
                $item['modification_id'] = $modification_ids[$i['modification']];
                $item['regular_price'] = $i['price'];
                $item['promo_price'] = $i['promo_price'] ?: null;
                if ($e->save($item)) {
                    $e->update_photos($item['id'], $i['photos']);
                }
            }

            // filters


            if (!$c_sup = $this->get_e('product_categories')->find_element("name='$supplier' AND parent=$c_imp[id]")) {
                $c_sup = array('name'=>$supplier, 'active'=>0, 'parent'=>$c_imp['id']);
                $this->get_e('product_categories')->save($c_sup);
            }
            Database::query("DELETE FROM lite_products_categories WHERE product_id='$product[id]'");

        }
    }*/

    public function update_product_omnisend($product_id)
    {
        $sub_e = $this->get_e('subscribers');
        if ($sub_e->config['api'] == 'omnisend') {
            $product = $this->get_element($product_id, 'id', 'detailed');
            $os = new Omnisend($sub_e->config['omnisend']['api_key']);
            $os->replace_product($product_id, $product);
        }
    }

    public function remove_product_omnisend($product_id)
    {
        $sub_e = $this->get_e('subscribers');
        if ($sub_e->config['api'] == 'omnisend') {
            $product = $this->get_element($product_id, 'id', 'detailed');
            $os = new Omnisend($sub_e->config['omnisend']['api_key']);
            if (!empty($product['modifications'])) {
                foreach ($product['modifications'] as $modification) {
                    $os->remove_product($modification['id']);
                }
            } else {
                $os->remove_product($product_id);
            }
        }
    }
}
