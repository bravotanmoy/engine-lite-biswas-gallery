<?php
namespace Elab\Lite\Controllers\Frontend;

use Elab\Lite\Services\Response;
use Elab\Lite\Helpers\Form;
use Elab\Lite\Services\Price;
use Elab\Lite\System\Repository;
use Elab\Lite\Engine;
use Elab\Lite\System\FrontendController;
use Elab\Lite\System\ProductsCollection;
use Elab\Lite\Services\Database;
use Elab\Lite\Services\GalleryAPI;

class ProductsFrontendController extends FrontendController
{
    public $product = false;
    private $productList = '';
    public function logic()
    {
        $f = Repository::$frontend;
        $e = $this->get_e('products');
        $e_pc = $this->get_e('product_categories');
        $e_brands = $this->get_e('brands');
        $e_collections = $this->get_e('collections');
        $categories = array();

        if (!empty($this->path[0]) && ($brand = $e_brands->find_by_url($this->path[0], false, 'detailed'))) {
            // brand'as
            $f->brand = $brand;
            $f->set_content_type('products/listing');
            $path = array_slice($this->path, 1);
            $f->add_page_path($brand['name'], $brand['full_url']);
        } elseif (!empty($this->path[0]) && ($collection = $e_collections->find_by_url($this->path[0], false, 'detailed'))) {
            // kolekcija
            $f->collection = $collection;
            $f->set_content_type('products/listing');
            $path = array_slice($this->path, 1);
            $f->add_page_path($collection['name'], $collection['full_url']);
        } elseif (!empty($this->path[0]) && $product = $e->find_by_url($this->path[0], false, 'detailed')) {
            // produktas
            $f->product = $product;
            $f->set_content_type('products/detailed');
            $path = array();
        } else {
            $path = $this->path;
        }

        //kategorija
        $categories = array();
        $parent = 0;
        foreach ($path as $url) {
            $e_pc->backup_conditions();
            $e_pc->add_condition("parent", "parent = $parent");
            if ($category = $e_pc->find_by_url($url, false, 'detailed')) {
                $categories[] = $category;
                $f->add_page_path($category['name'], $category['full_url']);
                $parent = $category['id'];
            } elseif (@$f->category) {
                Response::redirect($f->category);
            } else {
                $f->set_page_not_found();
                return;
            }
            $e_pc->rollback_conditions();
        }
        $f->categories = $categories;
        $f->category = @$categories[0];
        $f->subcategory = @$categories[1];
        $f->subsubcategory = @$categories[2];

        $last_category = end($categories);
        if (!@$f->product) {
            if (@$f->collection) {
                $e_collections->load_meta_fields($f->collection, 'condition');
            } else {
//                if (@$last_category && @$f->brand) {
//                    $f->set_title("{$f->brand['name']} {$last_category['name']}");
//                }
                if (@$f->brand) {
                    $e_brands->load_meta_fields($f->brand, 'condition');
                } elseif ($last_category) {
                    $e_collections->load_meta_fields($last_category, 'condition');
                } else {
                    //$f->set_title(t('Visos prekės'));
                }
            }
            $f->set_content_type('products/listing');
            $this->filter();
        } else {
            $e->load_meta_fields($f->product, 'condition');
            $f->add_page_path($f->product['name'], $f->product['full_url']);
        }
    }

    protected function filter()
    {
        $e = $this->get_e('products');
        $f = Repository::$frontend;

        if (!isset($f->filter)) {
            $f->filter = array();
        }
        $filter = & $f->filter;

        $filter_hash = '';
        if (isset($_GET['filter'])) {
            $filter_hash = $_GET['filter'];
        }
        if ($filter_hash) {
            $vars = explode(";", $filter_hash);
            $filter = array();
            foreach ($vars as $var) {
                $vars2 = explode(":", $var);
                if (count($vars2) == 2) {
                    $key = $vars2[0];
                    $values = explode(',', $vars2[1]);
                    if ($key == 'price0') {
                        $filter['prices'][0] = (float) $values[0];
                    } elseif ($key == 'price1') {
                        $filter['prices'][1] = (float) $values[0];
                    } elseif (strpos($key, 'fmod') !== false) {
                        list($fmod, $filter_id) = explode('_', $key);
                        foreach ($values as $v) {
                            $v = urldecode($v);
                            $filter[$fmod][$filter_id][$v] = true;
                        }
                    } else {
                        foreach ($values as $v) {
                            $v = urldecode($v);
                            $filter[$key][$v] = true;
                        }
                    }
                }
            }
        }

        $f->filter_hash = $filter_hash;
        $this->app->track_loadtime_start('load_products');
        $this->products = new ProductsCollection();
        $this->app->track_loadtime_stop();
        $this->products->filter = $filter;

        // filtruojam pagal branda
        if (@$f->brand) {
            $this->products->filter('brands', $f->brand['id']);
            $this->get_e('product_categories')->brand = $f->brand;
        }

        // filtruojam pagal kolekcija
        if (@$f->collection) {
            if ($f->collection['scope'] == 'filters') {
                $this->get_e('collections')->format($f->collection, array('filters_edit', 'categories'));
                // filtruojam produktus pagal priskirtas kategorijas
                if (!empty($f->collection['categories'])) {
                    $this->products->filter('categories', array_keys($f->collection['categories']));
                    // apribojam, kad kategoriju medyje nebutu rodomos nereikalingos kategorijos
                    $all_parents = array();
                    foreach ($f->collection['categories'] as $cat=>$foo) {
                        $parents = array();
                        while ($cat) {
                            //array_
                            $parents[] = $cat;
                            $cat = Database::get_first("SELECT parent FROM lite_product_categories WHERE id=$cat");
                        }
                        $all_parents[] = $parents;
                    }
                    if (count($all_parents)>1) {
                        $parents = call_user_func_array('array_intersect', $all_parents);
                    }
                    $f->root_category = array_shift($parents);
                }
                // filtruojam produktus pagal priskirtus filtrus
                foreach ($f->collection['filters'] as $group=>$filters) {
                    switch ($group) {
                        case 'text':
                            $ids = array();
                            foreach ($filters as $filter_id => $text_value) {
                                $text_value = Repository::$db->real_escape_string(htmlspecialchars_decode($text_value, ENT_QUOTES));
                                $ids[] = Database::get_all_first("SELECT element_id FROM lite_filter_values_elements WHERE entity_name='products' AND filter_id='$filter_id' AND text_value REGEXP '$text_value'");
                            }
                            $ids = count($ids)>1 ? call_user_func_array('array_intersect', $ids) : $ids[0];
                            $ids = array_fill_keys($ids, 1); // flip
                            $this->products->filter('product_ids', $ids);
                            break;
                        case 'select':
                        case 'color':
                        case 'checkboxes':
                            $fmod = array();
                            foreach ($filters as $filter_id=>$filter_values) {
                                $fmod[$filter_id] = array_fill_keys($filter_values, 1);
                            }
                            $this->products->filter('fmod', $fmod);
                            break;
                        case 'checkbox':
                            $this->products->filter('fmod_check', $filters);
                            break;
                    }
                }
            } else {
                $this->products->filter('collections', $f->collection['id']);
                $this->get_e('product_categories')->collection = $f->collection;
            }
        }

        // kategoriju sarase rodysim tik tas, kuriose yra prekiu
        $cat_ids = ($this->products->get_values('product_categories'));
        $cat_ids[] = 0;
        $this->get_e('product_categories')->add_condition('available_categories', "id IN (".implode(',', $cat_ids).")");

        // filtruojam pagal kategorija
        if (@$f->categories) {
            $cat = end($f->categories);
            $this->products->filter('category', $cat['id']);
            $f->root_category = $cat['id'];
        }
    }

    public function element($element)
    {
        Repository::$smarty->assign('element', $element);
    }

    public function add2cart()
    {
        $element = $this->app->product;

        if (Form::form_requested('add2cart')) {
            Engine::add_message('add2cart', 'message', t('Added to cart'));

            Repository::$smarty->assign('load_fbq_viewcontent', true);
        }

        $item_id = false;
        $modification_id = false;
        if (@$_GET['item'] && ($item = @$element['items'][$_GET['item']])) {
            $item_id = $item['id'];
            $modification_id = $item['modification_id'];
        } elseif (@$_GET['modification'] && @$element['modifications'][$_GET['modification']]) {
            $modification_id = intval($_GET['modification']);
        } elseif ($element['modifications']) {
            $modification = reset($element['modifications']);
            $modification_id = $modification['id'];
        }

        $modification_items = array();
        $available_modification_items = array();
        $item_names = array();
        foreach ($element['items'] as $item) {
            if ($item['quantity']>0) {
                $item_names[$item['name']][$item['modification_id']] = $item['id'];
            }
            if ($this->get_e('product_modifications')->config['active']) {
                if ($item['modification_id'] == $modification_id) {
                    $modification_items[$item['id']] = $item;
                    if ($item['quantity'] > 0) {
                        $available_modification_items[$item['id']] = $item;
                    }
                }
            } else {
                $modification_items[$item['id']] = $item;
                if ($item['quantity'] > 0) {
                    $available_modification_items[$item['id']] = $item;
                }
            }
        }
        if (count($available_modification_items) == 1) {
            $item = reset($available_modification_items);
            $item_id = $item['id'];
        }

        $price_info = @$element['items'][$item_id] ?: (@$element['modifications'][$modification_id] ?: $element);

        if ($selected_modification = @$element['modifications'][$modification_id]) {
            $this->get_e('product_modifications')->format($selected_modification, 'filters_detailed');
        }
        if ($selected_item = @$element['items'][$item_id]) {
            $this->get_e('product_items')->format($selected_item, 'filters_detailed');
        }

        Repository::$smarty->assign($t=array(
            'element' => $element,
            'modification_items' => $modification_items,
            'selected_modification' => $selected_modification,
            'selected_item' => $selected_item,
            'selected_item_modifications' => @$item_names[@$selected_item['name']],
            'price_info' => array(
                'price' => $price_info['price'],
                'regular_price' => $price_info['regular_price'],
                'price_differs' => @$price_info['price_differs'],
                'quantity' => @$price_info['quantity'],
            ),
        ));
        $this->app->item_id = $item_id;
        $this->app->modification_id = $modification_id;
        $this->app->product_id = $element['id'];
    }

    public function similar_products($element)
    {
        $e = $this->get_e('products');
        $similar_products = $element['similar_products'];

        $full_similar_products = new ProductsCollection('pi.product_id');
        $full_similar_products
            ->filter('eval', array('product_id'=>"in_array(\$val, array(".implode(",", $similar_products)."))"))
            ->format();
        $similar_products = $full_similar_products->data;


        if ($limit = (int)$e->config['similar_products_limit']) {
            if (count($similar_products) < $limit) {
                $temp_similar_ids = [];
                foreach ($similar_products as $temp) {
                    if (isset($temp['item_id'])) {
                        $temp_similar_ids[] = $temp['item_id'];
                    }
                }

                if (!empty($temp_similar_ids)) {
                    $ids = $element['id'].','.implode(',', $temp_similar_ids);
                } else {
                    $ids = $element['id'];
                }

                $products = new ProductsCollection('pi.product_id');
                $products
                    ->filter('categories', array_keys($element['categories']))
                    ->filter('in_stock')
                    ->filter('eval', array('product_id'=>"!in_array(\$val, array($ids))"))
                    ->sort('shuffle')
                    ->slice(0, $limit - count($similar_products))
                    ->format();

                $similar_products = array_merge($similar_products, $products->data);
            } else {
                $similar_products = array_slice($similar_products, 0, $limit - count($similar_products));
            }
        }

        Repository::$smarty->assign('similar_products', $similar_products);

        // Max 6 similar products for social meta tags
        if (count($similar_products) > 6) {
            if (!$limit || $limit > 6) {
                $limit = 6;
            }

            $similar_products = array_slice($similar_products, 0, $limit);
        }
    }

    public function related_products($element)
    {
        $e = $this->get_e('products');
        $related_products = $element['related_products'];

        $full_related_products = new ProductsCollection('pi.product_id');
        $full_related_products
            ->filter('eval', array('product_id'=>"in_array(\$val, array(".implode(",", $related_products)."))"))
            ->format();
        $related_products = $full_related_products->data;

        if ($limit = (int)$e->config['related_products_limit']) {
            if (count($related_products) < $limit) {
                $temp_related_ids = [];
                foreach ($related_products as $temp) {
                    if (isset($temp['item_id'])) {
                        $temp_related_ids[] = $temp['item_id'];
                    }
                }

                if (!empty($temp_related_ids)) {
                    $ids = $element['id'].','.implode(',', $temp_related_ids);
                } else {
                    $ids = $element['id'];
                }

                $products = new ProductsCollection('pi.product_id');
                $products->data = $this->filter_same_modification($element, $products->data);
                $products
                    ->filter('in_stock')
                    ->filter('eval', array('product_id'=>"!in_array(\$val, array($ids))"))
                    ->sort('shuffle')
                    ->slice(0, $limit - count($related_products))
                    ->format();

                $related_products = array_merge($related_products, $products->data);
            } else {
                $related_products = array_slice($related_products, 0, $limit - count($related_products));
            }
        }

        Repository::$smarty->assign('related_products', $related_products);
    }

    public function filter_same_modification($element, $products)
    {
        $m_e = $this->get_e('product_modifications');

        $element_modification = '';
        if ($item_id = @$_GET['item']) {
            $item = $element['items'][$item_id];
            if ($item['modification_id']) {
                $element_modification = $m_e->select('name')->find_element("id = {$item['modification_id']} AND product_id = {$element['id']}");
            }
        } elseif ($modification_id = @$_GET['modification']) {
            $element_modification = $element['modifications'][$modification_id]['name'];
        }

        $data = [];
        if (!empty($element_modification)) {
            foreach ($products as $product) {
                if (isset($product['modification_id']) && $product['modification_id']) {
                    $product_modification = $m_e->select('name')->find_element("id = {$product['modification_id']}");

                    if ($product_modification === $element_modification) {
                        $data[] = $product;
                    }
                }
            }
        }

        return $data;
    }

    public function listing()
    {
        $f = Repository::$frontend;
        $e = $this->get_e('products');
        $e_pi = $this->get_e('product_items');

        $e->load_get_params();

        $elements = $this->products->copy();
        $selected_sort_by = $e->config['sort_by'];
        if (!preg_match('/(asc|desc)$/i', $selected_sort_by)) {
            $selected_sort_by .= " " . $e->config['sort_type'];
        }

   

        $elements->filter()->sort($selected_sort_by);
        $total = $elements->count();
        $page = !empty($e->config['page']) ? $e->config['page'] : 1;

        $page_size = $e->config['page_size'];

        if ($page_size > 0 && $page > ceil($total / $page_size)) {
            $page = ceil($total / $page_size);
        }
        if ($page_size == 0) {
            // lazy load
            $page_size = 60;
            if (!empty($e->config['page'])) {
                //$page++;
            }
        }
        $elements->slice(($page - 1) * $page_size, $page_size);
        $elements->format();
        $count = $elements->count();

        $elements_info = array(
            'pages' => $e->paginate($page, $page_size, $total, 'auto'),
            'page_info' => array(
                'from' => ($page - 1) * $page_size + ($count ? 1 : 0),
                'to' => ($page - 1) * $page_size + $count,
                'page' => $page,
                'pages_count' => ceil($total / $page_size),
            ),
            'total' => $total,
        );

        Repository::$smarty->assign(array(
            'elements' => $elements->data,
            'elements_info' => $elements_info,
            'page_size' => $e->config['page_size'],
            'entity_config' => $e->config,
            'lazy_load' => $e->config['page_size'] == 0,
            'default_page_size' => Engine::get_config_from_file('controllers/entity/products/page_size'),
        ));

        return;
    }

    public function filter_categories()
    {
        $f = Repository::$frontend;
        $e_c = $this->get_e('product_categories');
        $categories = array();

        if ($last_category = end($f->categories)) {
            if ($childs = $e_c->find_elements("parent={$last_category['id']}", 'full_url')) {
                $name = $last_category['name'];
                $categories = $childs;
                $back_category_id = $last_category['parent'];
            } else {
                $parent = $e_c->get_element($last_category['parent'], 'id', 'full_url');
                $name = $parent['name'];
                $categories = $e_c->find_elements("parent={$last_category['parent']}", 'full_url');
                $back_category_id = $parent['parent'];
            }
            if ($back_category_id) {
                $back_category = $e_c->get_element($back_category_id, 'id', array('full_url'));
            } else {
                $back_category = array(
                    'name' => t('Visos prekės'),
                    'full_url' => $f->page_types['products']['full_url'] . (@$f->brand ? $f->brand['url'].'/' : '')
                );
            }
        } else {
            //$categories = $e_c->get_hierarchy(0, 'detailed', false, 1);
            $categories = $e_c->find_elements("parent=0", array('full_url'));
            $name = t('Prekių grupės');
            $back_category = false;
        }

        Repository::$smarty->assign(array(
            'title' => $name,
            'categories' => $categories,
            'back_category' => $back_category
        ));
    }

    public function filter_category_tree()
    {
        $f = Repository::$frontend;
        $e_c = $this->get_e('product_categories');
        $categories = $e_c->get_hierarchy(@$f->root_category ?: 0, 'default');
        $checked = @$this->products->filter['categories'] ?: array();
        $this->assign(array(
            'categories' => $categories,
            'checked' => $checked,
        ));
        foreach ($checked as $id=>$foo) {
            $cat = $this->get_e('product_categories')->get_element($id, 'id', 'translate');
            $f->filter_info['categories']['options'][$id]['title'] = $cat['name'];
        }
    }

    public function filter_brands()
    {
        $f = Repository::$frontend;
        $e = $this->get_e('brands');
        $filters_other = array_diff($this->products->filter ? array_keys($this->products->filter) : array(), array('brands'));
        $products_filtered = $this->products->copy()->filter('default', $filters_other);
        $counts = array(
            'all' => array(),
            'filtered' => array()
        );
        foreach ($this->products->data as $prod) {
            $counts['all'][$prod['brand_id']] = @$counts['all'][$prod['brand_id']] + 1;
        }
        foreach ($products_filtered->data as $prod) {
            $counts['filtered'][$prod['brand_id']] = @$counts['filtered'][$prod['brand_id']] + 1;
        }

        $ids = $counts['all'] ? implode(',', array_filter(array_keys($counts['all']))) : 0;
        $elements = !empty($ids) ? $e->list_elements("id IN ($ids)", false) : array();
        foreach ($elements as $k => $element) {
            $element['count'] = intval(@$counts['filtered'][$element['id']]);
            $element['selected'] = !empty($this->products->filter['brands'][$element['id']]);
            $elements[$k] = $element;
        }
        $f->set_current_template('frontend/content_types/products/filter_simple.tpl');
        Repository::$smarty->assign(array(
            'filter_id' => 'brands',
            'filter_title' => t('Prekių ženklai'),
            'filter_items' => $elements,
        ));

        foreach ($elements as $item) {
            $f->filter_info['brands']['options'][$item['id']]['title'] = $item['name'];
        }
    }

    public function filter_subcategories()
    {
        $f = Repository::$frontend;
        $e = $this->get_e('product_categories');
        $filters_other = array_diff($this->products->filter ? array_keys($this->products->filter) : array(), array('subcategories'));
        $products_filtered = $this->products->copy()->filter('default', $filters_other);
        $counts = array(
            'all' => array(),
            'filtered' => array()
        );
        foreach ($this->products->data as $prod) {
            foreach ($prod['product_categories'] as $cat_id) {
                $counts['all'][$cat_id] = @$counts['all'][$cat_id] + 1;
            }
        }
        foreach ($products_filtered->data as $prod) {
            foreach ($prod['product_categories'] as $cat_id) {
                $counts['filtered'][$cat_id] = @$counts['filtered'][$cat_id] + 1;
            }
        }

        $ids = $counts['all'] ? implode(',', array_keys($counts['all'])) : 0;
        $elements = $e->list_elements("id IN ($ids) AND parent={$f->category['id']}", false);
        foreach ($elements as $k => $element) {
            $element['count'] = intval(@$counts['filtered'][$element['id']]);
            $element['selected'] = !empty($this->products->filter['subcategories'][$element['id']]);
            $elements[$k] = $element;
        }
        $f->set_current_template('frontend/content_types/products/filter_simple.tpl');
        Repository::$smarty->assign(array(
            'filter_id' => 'subcategories',
            'filter_title' => $f->category['name'],
            'filter_items' => $elements,
        ));

        foreach ($elements as $item) {
            $f->filter_info['subcategories']['options'][$item['id']]['title'] = $item['name'];
        }
    }

    public function filter_fmodule()
    {
        $f = Repository::$frontend;
        $lang = $f->lang_key;
        $e_f = $this->get_e('filters');
        $filter_data = array();

        $cat_ids = array('-1'); // visos prekes
        foreach ($f->categories as $c) {
            $cat_ids[] = $c['id'];
        }
        $filters = $e_f->list_all_elements("show_in_filter = 1 AND type IN ('select', 'checkboxes') AND id IN (SELECT filter_id FROM lite_filters_categories WHERE category_id IN (".implode(',', $cat_ids)."))");

        $filter_data = [];
        foreach ($filters as $filter) {
            $filter_params = $this->products->filter;
            unset($filter_params['fmod'][$filter['id']]);
            $filters_other = array_diff($filter_params ? array_keys($filter_params) : array(), array("fmod_{$filter['id']}"));
            $products_filtered = $this->products->copy()->filter('default', $filters_other, $filter_params);
            $counts = array(
                'all' => array(),
                'filtered' => array()
            );
            foreach ($this->products->data as $prod) {
                foreach ($prod['filter_values_ids'] as $id) {
                    $counts['all'][$id] = @$counts['all'][$id] + 1;
                }
            }
            foreach ($products_filtered->data as $prod) {
                foreach ($prod['filter_values_ids'] as $id) {
                    $counts['filtered'][$id] = @$counts['filtered'][$id] + 1;
                }
            }
            $ids = $counts['all'] ? implode(',', array_keys($counts['all'])) : 0;

            if ($filter_values = $this->get_e('filter_values')->find_elements("id IN ($ids) AND filter_id=$filter[id]", "translate")) {
                foreach ($filter_values as $k => $fv) {
                    $fv['selected'] = !empty($this->products->filter["fmod"][$filter['id']][$fv['id']]);
                    $fv['count'] = @$counts['filtered'][$fv['id']] ?: 0;
                    $filter_values[$k] = $fv;
                    $f->filter_info["fmod_$filter[id]"]['options'][$fv['id']]['title'] = $fv['name'];
                }
                $filter['filter_items'] = $filter_values;
                $filter['filter_id'] = "fmod_".$filter['id'];
                $filter_data[] = $filter;
            }
        }

        $f->set_current_template('frontend/content_types/products/filter_fmodule.tpl');
        Repository::$smarty->assign('filter_data', $filter_data);
    }

    public function filter_collections()
    {
        $f = Repository::$frontend;
        $e = $this->get_e('collections');

        $filters_other = array_diff($this->products->filter ? array_keys($this->products->filter) : array(), array('collections'));
        $products_filtered = $this->products->copy()->filter('default', $filters_other);

        $counts = array(
            'all' => array(),
            'filtered' => array()
        );

        foreach ($this->products->data as $prod) {
            foreach ($prod['collections'] as $id) {
                $counts['all'][$id] = @$counts['all'][$id] + 1;
            }
        }
        foreach ($products_filtered->data as $prod) {
            foreach ($prod['collections'] as $id) {
                $counts['filtered'][$id] = @$counts['filtered'][$id] + 1;
            }
        }

        $ids = $counts['all'] ? implode(',', array_keys($counts['all'])) : 0;
        $elements = $e->list_elements("id IN ($ids)", 'translate');

        foreach ($elements as $k => $element) {
            $element['count'] = intval(@$counts['filtered'][$element['id']]);
            $element['selected'] = !empty($this->products->filter['collections'][$element['id']]);
            $elements[$k] = $element;
        }
        $f->set_current_template('frontend/content_types/products/filter_simple.tpl');
        Repository::$smarty->assign(array(
            'filter_id' => 'collections',
            'filter_title' => t('Rinkiniai'),
            'filter_items' => $elements
        ));

        foreach ($elements as $item) {
            $f->filter_info['collections']['options'][$item['id']]['title'] = $item['name'];
        }
    }

    public function filter_prices()
    {
        $f = Repository::$frontend;
        $filters_other = array_diff(array_keys($this->products->filter), array('prices'));
        $products_filtered = $this->products->copy()->filter('default', $filters_other);
        $max = 0;
        $min = 99999;
        $all_prices = array();
        foreach ($products_filtered->data as $prod) {
            $min = min($min, floor($prod['price']));
            $max = max($max, ceil($prod['price']));
            $all_prices[] = round($prod['price']);
        }
        $all_prices = array_values(array_unique($all_prices));
        sort($all_prices);
        $all_prices_flipped = array();
        foreach ($all_prices as $row => $price) {
            $all_fprices[$row] = Price::format($price);
            $all_prices_flipped[$price] = $row;
        }
        if ($min < $max) {
            $filter = & $this->products->filter;
            $prices = array(
                'from' => $min,
                'to' => $max,
                'all_prices' => $all_prices,
                'all_fprices' => $all_fprices,
                'all_prices_flipped' => $all_prices_flipped,
                'price1_flipped' => @$all_prices_flipped[$filter['prices'][0]] ? $all_prices_flipped[$filter['prices'][0]] : reset($all_prices_flipped),
                'price2_flipped' => @$all_prices_flipped[$filter['prices'][1]] ? $all_prices_flipped[$filter['prices'][1]] : end($all_prices_flipped),
            );
            Repository::$smarty->assign('price_filter', $prices);
        }
    }


    public function detailed()
    {


        $e = $this->get_e('products');
        $f = $this->app;
        $element = $f->product;



        $galleryAPI = new GalleryAPI();
    //    $getGalleryAPI = $galleryAPI->login('uptest@gmail.com','abcdef');
    //    echo "<pre>";
    //       var_dump($getGalleryAPI);
    //    echo "</pre>";
    //
    //     $getGalleryAPI = $galleryAPI->register('uptest2', 'uptest2@gmail.comhh', 'abcdef', 'abcdef');
    


        $brand_name = null;
        if ($element['brand_id']) {
            $brand_name = $this->get_e('brands')->select('name')->find_element("id={$element['brand_id']}");
        }


        $modification = false;
        $availability = 0;
        if ($item_id = @$_GET['item']) {
            $item = $element['items'][$item_id];
            $price = $item['price'];
            $availability = $item['quantity'] > 0 ? 1 : 0;
         
        } elseif ($modification_id = @$_GET['modification']) {
            $modification = (isset($element['modifications'][$modification_id]) ? $element['modifications'][$modification_id] : NULL);
            $price = (isset($modification['price']) ? $modification['price'] : NULL);
            $getGalleryAPI = $galleryAPI->gallery($modification_id);
          
        } else {
            $price = $element['price'];
            $availability = $element['quantity'] > 0 ? 1 : 0;

        }

        $og_description = $element['meta_description'] ?: $element['short_description'];
        if (empty($og_description)) {
            $description = $f->load_helper()->display_html($element['description']);
            $description = strip_tags(html_entity_decode($description));

            $content = substr($description, 0, 300);
            $pos = strrpos($content, ' ');
            if ($pos > 0) {
                $content = substr($content, 0, $pos);
                $content .= '...';
            }

            $og_description = $content;
        }

        $this->similar_products($element);

        if(!$modification){
            $getProductImage = $this->photos();
            $getGalleryAPI['gallery'] = $getProductImage;
        }


        $this->assign([
            'element'  => $element,
            'currency' => 'EUR',
            'modificationStatus' => $modification,
            'gallery' => $getGalleryAPI['gallery']
        ]);
    }

    public function photos()
    {
        $f = $this->app;
        $element = $f->product;
        $productList = $f->products_collection->data;
        

        $item = count($element['items'])==1 ? reset($element['items']) : @$element['items'][@$_GET['item']];
        if ($this->load_entity_controller('product_modifications')->config['active']) {
            $modification = @$element['modifications'][@$_GET['modification']] ?: reset($element['modifications']);
        } else {
            $modification = false;
        }
        if ($item) {
            $this->get_e('product_items')->format($item, 'photos');
            $element['photos'] = @$item['photos'] ?: array();
        } elseif ($modification) {
            $this->get_e('product_modifications')->format($modification, 'photos');
            $element['photos'] = @$modification['photos'] ?: array();
        } else {
            $this->get_e('products')->format($element, 'photos');
        }
        $this->assign('photos', @$element['photos']);
        return $productList;
    }


    public function add_review($mode, $products=null)
    {
        if ($products===null) {
            $products = array($this->product);
        }
        $form = array();
        if (Form::form_requested('add_review') && !empty($_POST['review'][$this->product['id']])) {
            foreach ($_POST['review'] as $k=>$v) {
                if (!empty($v['review'])) {
                    $_POST['review'][$k]['review'] = mb_substr($v['review'], 0, 1000);
                }
            }
            Form::fix_post();

            // vertinamas tik 1 produktas
            $params = $_POST['review'][$this->product['id']];
            $ok = false;
            foreach (array('raiting', 'age_range', 'recomend') as $k) {
                if (isset($params[$k]) && $params[$k]!=='') {
                    $ok = true;
                } else {
                    $params[$k] = null;
                }
            }

            if (!$ok) {
                Engine::add_message('add_review', 'error_message', 'Būtina užpildyti bent vieną kriterijų.');
            } else {
                $params['product_id'] = $this->product['id'];
                $params['cookie'] = $_COOKIE['user_token'];
                $params['ip'] = $_SERVER['REMOTE_ADDR'];
                if (isset($_SESSION['customer'])) {
                    $params['customer_id'] = $_SESSION['customer']['id'];
                }
                $e = $this->load_entity_controller('product_reviews');
                $where = "`cookie`='$params[cookie]' OR ip='$params[ip]'";
                if (!empty($params['customer_id'])) {
                    $where .= " OR customer_id='$params[customer_id]'";
                }
                if (empty($_POST['check']) || empty($_SESSION['spam_check']) || $_POST['check']!=$_SESSION['spam_check']) {
                    Engine::add_message('add_review', 'error_message', 'Nepavyko išsaugoti įvertinimo.');
                    file_put_contents('logs/spam.txt', "------------------\r\n".date("Y-m-d H:i:s")."\r\nproducts/add_review\r\n".print_r($_POST, true)."\r\n", FILE_APPEND);
                } elseif ($e->find_element("product_id=$params[product_id] AND ($where)", false, array('auto_params'=>false))) {
                    Engine::add_message('add_review', 'error_message', 'Jūs jau anksčiau įvertinote šį produktą.');
                } else {
                    if ($e->save($params)) {
                        Repository::$smarty->assign('review_status', 'sent');
                        Engine::add_message('add_review_alert', 'popup-message2', array('content'=>'<p style="font-size:14px; font-weight:bold;">Jūsų įvertinimas išsaugotas.</p><p style="font-size:14px;">Visų klientų vardu dėkojame už jūsų išreikštą nuomonę! :)</p>', 'footer'=>true, 'close_icon'=>true, 'timeout'=>8000));
                        //Engine::add_message('add_review_alert', 'popup-message', 'Jūsų įvertinimas išsaugotas. Dėkojame.');
                    } else {
                        Engine::add_message('add_review', 'error_message', 'Nepavyko išsaugoti įvertinimo.');
                    }
                }
            }
            $form = $_POST;
        }
        if ($mode=='popup') {
            Repository::$smarty->assign('form', $form);
        }

        Repository::$smarty->assign(array(
            'products' => $products,
            'mode' => $mode,
        ));
    }


    public function product_reviews()
    {
        $f = Repository::$frontend;
        $e = $this->load_entity_controller('product_reviews');
        $e->config['page_size'] = 3;
        $e->config['page'] = @$_GET['page']? : 1;
        $reviews = $e->list_elements("product_id={$f->product['id']} AND review IS NOT NULL", 'default', $return_params);


        $all_reviews = $e->list_all_elements("product_id={$f->product['id']} AND review IS NOT NULL");
        $sumary = array(
            'raitings' => array(
                1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0
            )
        );
        if ($all_reviews) {
            $sum = 0;
            $recomend = 0;
            $raitings_cnt = count($all_reviews);
            foreach ($all_reviews as $re) {
                $sumary['raitings'][$re['raiting']]++;
                $sum += $re['raiting'];
                $recomend += $re['recomend'];
            }

            foreach ($sumary['raitings'] as &$r) {
                $r = $r/$raitings_cnt*100;
            }
            $sumary['average'] = number_format($sum/$raitings_cnt, 1, '.', '.');
            $sumary['recomend'] = number_format($recomend/$raitings_cnt, 1, '.', '.');
            $sumary['raiting_cnt'] = $raitings_cnt;
        }

        $this->app->google_reviews['reviews'] = $sumary;

        Repository::$smarty->assign(array(
            'reviews' => $reviews,
            'pages_count' => $return_params['page_info']['pages_count'],
            'age_ranges' => $e->config['age_ranges'],
            'reviews_summary' => $sumary
        ));
    }
}
