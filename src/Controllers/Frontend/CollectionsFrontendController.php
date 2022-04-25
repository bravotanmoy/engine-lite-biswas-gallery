<?php
namespace Elab\Lite\Controllers\Frontend;

use Elab\Lite\System\Repository;
use Elab\Lite\System\FrontendController;
use Elab\Lite\System\ProductsCollection;

class CollectionsFrontendController extends FrontendController
{
    public function index_listing($row_size = 5)
    {
        $e = $this->load_entity_controller('collections');
        $this->products = new ProductsCollection();
        $collections = $e->find_elements("visible_homepage = 1 AND (valid_from IS NULL OR valid_from <= '" . NOW . "') AND (valid_till IS NULL OR valid_till >= '" . NOW . "')", 'default');
        foreach ($collections as $k => $collection) {
            $elements = $this->products
                    ->copy()
                    ->filter('collections', $collection['id'])->filter('in_stock')
                    ->sort('shuffle')
                    ->slice(0, $collection['scope'] == 'special' ? $row_size*2 : $row_size)
                    ->format();
            if ($elements->data) {
                $collections[$k]['items'] = $elements->data;
            } else {
                unset($collections[$k]);
            }
        }
        Repository::$smarty->assign(array(
            'collections' => $collections,
        ));
    }
}
