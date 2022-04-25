<?php
namespace Elab\Lite\Controllers\Frontend;

use Elab\Lite\System\Repository;
use Elab\Lite\System\FrontendController;

class PhotosFrontendController extends FrontendController
{
    public function listing($element_id, $entity_name, $start = 0, $title='')
    {
        $entity = $this->load_entity_controller($entity_name);
        if (empty($entity->config['photos'])) {
            $this->get_frontend()->set_current_template(false);
            return;
        }
        $photos_controller = $entity->load_entity_controller('photos');
        //$photos_controller->load_get_params();
        $info = false;
        $elements = $photos_controller->get_entity_elements($element_id, 'all', $info);
        Repository::$smarty->assign(array(
            'photos' => @$elements['photos'] ? array_slice($elements['photos'], $start) : null,
            'photos_info' => $info,
            'title' => $title,
        ));
    }
}
