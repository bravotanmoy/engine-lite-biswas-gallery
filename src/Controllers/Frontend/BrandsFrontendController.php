<?php
namespace Elab\Lite\Controllers\Frontend;

use Elab\Lite\System\Repository;
use Elab\Lite\System\FrontendController;

class BrandsFrontendController extends FrontendController
{
    public function logic()
    {
        $f = Repository::$frontend;
        $f->set_content_type('brands/listing');
    }
    
    public function index_listing($page_size = 10)
    {
        $e = $this->app->get_e('brands');
        $e->config['sort_by'] = 'RAND()';
        $where = "id IN (SELECT `foreign_key` FROM lite_photo_containers fc JOIN lite_photos f ON f.gallery_id=fc.id WHERE fc.entity_name='".$e->get_name()."')";
        $elements = $e->find_elements($where, 'default', $page_size);
        Repository::$smarty->assign("elements", $elements);
    }

    public function listing()
    {
        $e_brands = $this->load_entity_controller('brands');
        $elements = $e_brands->list_all_elements(false, 'detailed');
        Repository::$smarty->assign(array(
            "elements" => $elements,
        ));
    }
}
