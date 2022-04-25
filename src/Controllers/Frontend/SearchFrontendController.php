<?php
namespace Elab\Lite\Controllers\Frontend;

use Elab\Lite\Helpers\Form;
use Elab\Lite\Helpers\Inflector;
use Elab\Lite\System\FrontendController;
use Elab\Lite\System\ProductsCollection;
use Elab\Lite\Services\Database;

class SearchFrontendController extends FrontendController
{
    public function logic()
    {
        $this->app->set_content_layout('plain');
        $this->app->set_content_type('search/results');
        
        $search = array('count' => 0);
        $search_entities = array(
            'brands' => t('Prekių ženklai'),
            'brand_categories' => t('Prekių ženklų kategorijos'),
            'product_items' => t('Produktai'),
            'products' => t('Produktai'),
            'news' => t('Naujienos'),
            'pages' => t('Svetainės informacija'),
        );

        if (Form::form_requested('search')) {
            $search['query'] = $_POST['query'];
        } elseif (isset($_GET['query'])) {
            $search['query'] = htmlspecialchars($_GET['query'], ENT_QUOTES);
        } elseif (isset($_GET['search'])) {
            $search['query'] = htmlspecialchars($_GET['search'], ENT_QUOTES);
        }
        
        if (!empty($search['query'])) {
            $this->app->set_title(htmlspecialchars(t('Paieškos rezultatai'), ENT_QUOTES));
            $q_kw = array_unique(preg_split("/[\r\n,;\- ]/", Inflector::slug($search['query'])));
    
            foreach (array('products') as $entity_name) {
                $controller = $this->load_entity_controller($entity_name);
                if ($results = $controller->search($search['query'])) {
                    $search['results']['product_items'] = $results;
                    $search['count'] += count($results);
                }
            }

            //productu atvaizdavimas is collekcijos
            if (@$search['results']['product_items']) {
                $this->products = new ProductsCollection();
                foreach ($search['results']['product_items'] as $item_id) {
                    $filter['items'][$item_id] = 1;
                }
                $this->products->filter('main');
                $elements = $this->products->copy();
                $elements->filter = $filter;
                $elements->filter()->sort('position asc');
                $elements->format();
                $count = $elements->count();
                $search['results']['product_items'] = $elements->data;
            }
                        
            //-------
            foreach (array('news', 'pages') as $entity_name) {
                $controller = $this->load_entity_controller($entity_name);
                if ($results = $controller->search($search['query'])) {
                    $search['results'][$entity_name] = $results;
                    $search['count'] += count($results);
                }
            }
        }

        
        
        if (!isset($_SESSION['search_queries'])) {
            $_SESSION['search_queries'] = array();
        }
        if (@$search['query'] && !in_array($search['query'], $_SESSION['search_queries'])) {
            Database::query("INSERT INTO lite_search_log (`query`, `results`, `date`) VALUES ('".htmlspecialchars($search['query'], ENT_QUOTES)."', $search[count], CURDATE())");
            $_SESSION['search_queries'][] = $search['query'];
        }
        $this->assign('search_entities', $search_entities);
        $this->assign('search', $search);
    }
}
