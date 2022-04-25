<?php
namespace Elab\Lite\Controllers\Entity;

use Elab\Lite\System\Repository;
use Elab\Lite\Services\Database;
use Elab\Lite\System\ContainerEntityController;

class Photos extends ContainerEntityController
{
    public function get_entity_elements($entity_id, $formatting_mode = false, &$return_params = array(), $container_name = 'photos')
    {
        if (Repository::$photos) {
            $entity_name = $this->parent_object->get_name();
            if (isset(Repository::$photos[$entity_name][$entity_id])) {
                $lang = !empty(Repository::$frontend->lang_key) ? Repository::$frontend->lang_key : 'default';
                if (isset(Repository::$photos[$entity_name][$entity_id][$container_name][$lang])) {
                    $photos = array_values(Repository::$photos[$entity_name][$entity_id][$container_name][$lang]);
                } elseif ($lang !== "default" && isset(Repository::$photos[$entity_name][$entity_id][$container_name]["default"])) {
                    $photos = array_values(Repository::$photos[$entity_name][$entity_id][$container_name]["default"]);
                } else {
                    return array();
                }
                $this->format($photos, $formatting_mode);
                return array($container_name => $photos);
            } else {
                return array();
            }
        } else {
            return parent::get_entity_elements($entity_id, $formatting_mode, $return_params);
        }
    }

    public function get_first_entity_element($entity_id, $formatting_mode = false, $container_name = 'photos')
    {
        if (Repository::$photos) {
            $entity_name = $this->parent_object->get_name();
            if (isset(Repository::$photos[$entity_name][$entity_id][$container_name])) {
                $lang = !empty(Repository::$frontend->lang_key) ? Repository::$frontend->lang_key : 'default';

                if (isset(Repository::$photos[$entity_name][$entity_id][$container_name][$lang])) {
                    $photos = array_values(Repository::$photos[$entity_name][$entity_id][$container_name][$lang]);
                } elseif ($lang !== "default" && isset(Repository::$photos[$entity_name][$entity_id][$container_name]["default"])) {
                    $photos = array_values(Repository::$photos[$entity_name][$entity_id][$container_name]["default"]);
                } else {
                    return false;
                }

                $this->format($photos[0], $formatting_mode);
                return $photos[0];
            } else {
                return false;
            }
        } else {
            return parent::get_first_entity_element($entity_id, $formatting_mode, $container_name);
        }
    }

    public function format_element($element, $mode = 'default')
    {
        if ($mode) {
            if (empty($element['path'])) {
                $element['path'] = $this->config['gallery']['path'] . $element['image'];
            }
            if (empty($element['src'])) {
                $element['src'] = PROJECT_URL . $element['path'];
            }
            /* 	if (empty($element['image_size'])) $element['image_size'] = @getimagesize($element['path']);
              $image = $this->get_tr_images($this->config['sizes'], preg_replace('@^'.PROJECT_URL.'@', '', $element['src']));
              switch($mode) {
              case 'all':
              $element['tr_images'] = $image;
              break;
              default:
              if (isset($image[$mode])) {
              $element['tr_images'][$mode] = $image[$mode];
              }
              break;
              } */
        }
        return parent::format_element($element, $mode);
    }

    public function delete_element($id, $key = "id")
    {
        $element = $this->get_element($id, $key);
        if (parent::delete_element($id)) {
            // Pries trinant image patikrinam ar nera kitu elementu su ta pacia nuotrauka
            if (!Database::get_first("SELECT id FROM lite_photos WHERE image = '".$element['image']."'")) {
                @unlink($this->config['gallery']['path'] . $element['image']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function create_element($key, $photo)
    {
        $picture_name = "new_photo_file_";
        //if ($this->type!='default') $picture_name .= $this->type."_";
        $picture_name .= "$key";
        if (isset($_FILES[$picture_name]) && ($_FILES[$picture_name]['size'] > 0)) {
            if ($photo['image'] = $this->save_image($picture_name)) {
                if ($this->save($photo)) {
                    return true;
                }
            }
            return false;

        //TODO: sugalvoti, kaip protingai sutvarkyti šią vietą:::
        } elseif (!empty($_FILES[$picture_name]['error'])) { //jeigu ateina klaidos kodas iš FILES, reiškia kažkas įvyko negero - grąžinam false, o toliau jau apdorosim klaidą
            //$this->last_error = 'Klaida išsaugant paveikslėlį. Galbūt jis per didelis?';
            //return false;
            return true;
        } else {
            // veiksmas sekmingas (nebuvo nuotraukos, jos ir neissaugojom).
            return true;
        }
    }
    
    public function get_full_url($element)
    {
        $this->prepare_element($element);
        return PROJECT_URL.$this->config['gallery']['path'].$element['image'];
    }

    /*
     * Is modelio
     */
    public $prefix = "gallery_";

    /**
     * islistina nuotraukas pagal paduota galerijos id
     *
     * @param unknown_type $gallery_id
     * @return unknown
     */
    public function list_gallery_photos($gallery_id)
    {
        if (!empty($gallery_id)) {
            $where = "`gallery_id` = $gallery_id";
            return $this->list_elements($where);
        } else {
            return array();
        }
    }
}
