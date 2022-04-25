<?php
namespace Elab\Lite\Controllers\Frontend;

use Elab\Lite\Services\Response;
use Elab\Lite\System\Repository;
use Elab\Lite\Engine;
use Elab\Lite\Services\Database;
use Elab\Lite\System\FrontendController;

class PagesFrontendController extends FrontendController
{
    public function logic()
    {
        $e = $this->get_e('pages');

        switch ($this->app->page['type']) {
            case 'language':
                if ($this->path) {
                    // Yra papildomu parametru url'e, vadinosi kreipesi i gilesni puslapi, ir jo nerado.
                    $this->app->not_found();
                } elseif ($this->app->page['link_to_page'] != $this->app->page['id']) {
                    $url_params = '?';
                    if (!empty($_GET)) {
                        $params = $_GET;
                        if (isset($params['PATH_INFO'])) {
                            unset($params['PATH_INFO']);
                        }
                        $url_params .= http_build_query($params);
                    }
                    Response::redirect($e->get_full_url($this->get_frontend()->page['link_to_page']) . (($url_params != '?') ? $url_params : ''));
                } else {
                    // Puslapis rodo pats i save, todel neredirektinam, o laikom ji paprastu puslapiu.
                    $this->app->set_content_type('pages/detailed');
                }
                break;
            
            case 'index':
                if (!empty($this->path)) {
                    $this->app->not_found();
                }
                $this->app->set_content_layout('index');
                break;
                
            case 'link':
            case 'external_link':
            Response::redirect($this->app->page['link_to']);
                break;
            
            case 'link_to_first_child':
                if ($page = $e->find_element("`parent`='{$this->app->page['id']}'", array('translate','full_url'))) {
                    Response::redirect($page['full_url']);
                } else {
                    $this->app->not_found();
                }
                break;
                
            case 'internal_link':
                if ($this->app->page['link_to_page'] != $this->app->page['id']) {
                    $new_url = $e->get_full_url($this->get_frontend()->page['link_to_page']);
                    if (preg_match('/\?.*$/', $_SERVER['REQUEST_URI'], $matches)) {
                        $new_url .= $matches[0];
                    }
                    Response::redirect($new_url);
                } else {
                    $this->get_frontend()->not_found();
                }
                break;
                
            case 'content':
                if ($this->path) {
                    // Yra papildomu parametru url'e, vadinosi kreipesi i gilesni puslapi, ir jo nerado.
                    $this->app->not_found();
                } else {
                    $this->app->set_content_type('pages/detailed');
                }
                break;
        }
    }

    public function forbidden($action = false)
    {
        if ($action) {
            Repository::$smarty->assign('forbidden_action', $action);
        }
        if ($this->get_frontend()->get_content_type() != 'pages/forbidden') {
            Repository::$smarty->assign('show_backlink', false);
        }
    }

    public function gallery($photos)
    {
        Repository::$smarty->assign('photos', $photos);
    }
    
    /**
     * Patogu naudoti, kai reikia isvesti puslapio content'a fancybox'e
     * @param string $alias
     */
    public function view_alias($alias=false)
    {
        if (@$_GET['alias']) {
            $alias = $_GET['alias'];
        }
        $element = $this->get_entity()->get_element($alias, 'alias', 'default');
        Repository::$smarty->assign('element', $element);
    }
    
    public function languages_menu()
    {
        $current_language = Repository::$frontend->lang_key;
        $languages = $this->get_e('languages')->find_elements("frontend = 1 AND language <> '$current_language'");

        Repository::$smarty->assign('languages', $languages);
    }

    public function languages_menu_mobile()
    {
        $this->languages_menu();
    }

    public function context_menu()
    {
        $e = $this->get_e('pages');
        if ($this->app->pages) {
            $parent_level = $this->app->pages[0]['type']=='language' ? 1 : 0;
            $parent = $this->app->pages[$parent_level];
            if ($childs = $e->find_elements("parent=$parent[id] and visible_menu=1", 'list')) {
                $this->assign(array(
                    'parent' => $parent,
                    'childs' => $childs,
                ));
                return;
            }
        }
        $this->app->set_current_template(false);
    }
    
    public function menu($parent_id, $where = false)
    {
        $where = $where ?: "1";
        $e = $this->get_e('pages');
        $this->assign(array(
            'parent' => $e->get_element($parent_id, 'id', 'default'),
            'childs' => $e->find_elements("parent=$parent_id AND $where", 'default'),
        ));
    }
    
    public function mega_menu($cache = false)
    {
        Repository::$smarty->assign('elements', $this->get_menu_elements($cache, 'main'));
    }

    public function footer_menu($cache = false)
    {
        Repository::$smarty->assign('elements', $this->get_menu_elements($cache, 'footer'));
    }

    public function get_menu_elements($cache = false, $menu_type = 'main')
    {
        if (!$cache || (!$elements = $this->app->read_cache($menu_type.'-menu-struct'))) {
            $elements = $this->load_entity_controller('menu_groups')->find_elements("active=1 AND menu_type='{$menu_type}'", 'detailed');
            if ($cache) {
                $this->app->write_cache($menu_type.'-menu-struct', $elements);
            }
        }
        return $elements;
    }

    public function footer_menu_list($menu_group)
    {
        $this->assign('menu_group', $this->get_menu_group_list($menu_group));
    }

    public function mega_menu_list($menu_group)
    {
        $this->assign('menu_group', $this->get_menu_group_list($menu_group));
    }

    protected function get_menu_group_list($menu_group)
    {
        if ($menu_group['type'] == 'category_list') {

            //get full selected categories list
            $category_id = $menu_group['link'] ?: 0;
            $category = $this->load_entity_controller('product_categories')->get_element($category_id, 'id', 'detailed');
            $categories = $this->load_entity_controller('product_categories')->get_hierarchy($category_id, 'detailed');
            $menu_group['element'] = $category;
            $menu_group['menu_columns'] = $categories;
        } elseif ($menu_group['type'] == 'page_list') {

            //get full selected pages list
            $page_id = $menu_group['link'];
            $page = $this->load_entity_controller('pages')->get_element($page_id, 'id', 'detailed');
            $pages = $this->load_entity_controller('pages')->get_hierarchy($page_id, 'detailed');
            $menu_group['element'] = $page;
            $menu_group['menu_columns'] = $pages;
        } elseif ($menu_group['type'] == 'brand_list') {

            //get active brand list
            $brands = $this->load_entity_controller('brands')->list_elements("active=1");
            $menu_group['menu_columns'] = $brands;
        }
        return $menu_group;
    }

    public function mega_menu_panel($menu_group)
    {
        $this->assign('menu_group', $this->get_menu_group_list($menu_group));
    }

    public function menu_information()
    {
        $f = Repository::$frontend;
        $e = $this->load_entity_controller('pages');
        
        $query_lang = "";
        $query_parent = "";
        if ($f->lang_key) {
            $query_lang = " AND p1.`url` != '{$f->lang_key}' ";
            $query_parent = " AND p.parent != 0 ";
        }
        
        $ids = Database::get_assoc_all(" 
			SELECT p.id, p1.id as id_child
			FROM lite_pages p
			LEFT JOIN lite_pages p1 ON p1.id = p.parent AND p1.visible_top_menu = 1 AND p1.active = 1 {$query_lang}
			WHERE p.visible_top_menu = 1 AND p.active = 1 {$query_parent}
			ORDER BY p.position ASC	
		");
        
        $top_menu = array();
        foreach ($ids as $id) {
            if (!$id['id_child']) {
                $top_menu[$id['id']] = $e->get_element($id['id'], 'id', 'default');
                
                $top_menu[$id['id']]['menu_items'] = $e->list_elements("parent = {$id['id']} AND visible_top_menu = 1");
            }
        }

        Repository::$smarty->assign(array("top_menu" => $top_menu));
    }
    
    public function contextual_menu($id = false)
    {
        $e = $this->get_e('pages');
        $parent = $id ? $e->get_element($id) : Repository::$frontend->pages[0];
        $e->format($parent);
        $parent['childs'] = $e->find_all("parent=$parent[id] and visible_menu = 1");
        $e->format($parent['childs']);
        Repository::$smarty->assign(array(
            'parent' => $parent,
        ));
    }

    public function footer_advantages()
    {
        $f = Repository::$frontend;

        $infoblocks = $this->get_e('infoblocks')
            ->find_elements('alias="advantage"', 'default');

        if ($f->lang_key) {
            $lang = $f->lang_key . '/';
        }

        foreach ($infoblocks as &$infoblock) {
            $infoblock['url'] = PROJECT_URL . $lang . $infoblock['url'];
        }
        unset($infoblock);

        Repository::$smarty->assign([
            'infoblocks' => $infoblocks,
        ]);
    }

    public function cookie_bar()
    {
        $element = $this->get_e('pages')->find_element("alias = 'privacy'", 'detailed');

        $prefix = 'controllers/backend/settings/cookie_bar';
        $element['cookie_bar'] = $this->get_e('settings')->select('value')->find_element("path LIKE '{$prefix}%'");

        Repository::$smarty->assign('element', $element);
    }
}
