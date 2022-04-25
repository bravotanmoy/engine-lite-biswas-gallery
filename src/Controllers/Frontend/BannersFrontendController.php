<?php
namespace Elab\Lite\Controllers\Frontend;

use Elab\Lite\System\FrontendController;

class BannersFrontendController extends FrontendController
{
    public function hero()
    {
        $e = $this->get_e('banners');
        $elements = $e->list_elements(" (visible_mobile = 1 OR visible_desktop = 1) AND type = 1 AND (valid_from IS NULL OR valid_from <= NOW()) AND (valid_till IS NULL OR valid_till >= NOW()) ", 'list');
        $this->assign(array(
            'elements' => $elements,
        ));
    }
    
    public function small()
    {
        $e = $this->get_e('banners');
        $elements = $e->list_elements(" type = 2 AND (visible_desktop = 1 OR visible_mobile = 1) AND (valid_from IS NULL OR valid_from <= NOW()) AND (valid_till IS NULL OR valid_till >= NOW()) ", 'list');
        $this->assign('elements', $elements);
    }
}
