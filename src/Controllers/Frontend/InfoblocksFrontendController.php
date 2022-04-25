<?php
namespace Elab\Lite\Controllers\Frontend;

use Elab\Lite\System\Repository;
use Elab\Lite\System\FrontendController;

class InfoblocksFrontendController extends FrontendController
{
    public function show_infoblock($id)
    {
        if ($infoblock = $this->get_entity()->get_element($id, is_numeric($id) ? 'id' : 'alias')) {
            Repository::$smarty->assign('infoblock', $infoblock);
        }
    }
}
