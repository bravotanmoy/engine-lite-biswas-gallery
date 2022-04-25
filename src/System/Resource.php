<?php

namespace Elab\Lite\System;

use Elab\Lite\Helpers\Image;
use Elab\Lite\Services\Database;
use Elab\Lite\Engine;

class Resource extends Application
{
    public static $contenttypes = array(
        'html' => 'text/html',
        'htm' => 'text/html',
        'txt' => 'text/plain',
        'gif' => 'image/gif',
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'sxw' => 'application/vnd.sun.xml.writer',
        'sxg' => 'application/vnd.sun.xml.writer.global',
        'sxd' => 'application/vnd.sun.xml.draw',
        'sxc' => 'application/vnd.sun.xml.calc',
        'sxi' => 'application/vnd.sun.xml.impress',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'doc' => 'application/msword',
        'rtf' => 'text/rtf',
        'zip' => 'application/zip',
        'mp3' => 'audio/mpeg',
        'pdf' => 'application/pdf',
        'tgz' => 'application/x-gzip',
        'gz' => 'application/x-gzip',
        'vcf' => 'text/vcf',
        'css' => 'text/css',
        'js' => 'text/javascript',
    );
    public $show_unavailable = false;

    public function __construct($mode = ENGINE_MODE_SIMPLE)
    {
        parent::__construct($mode);
    }

    public function logic()
    {
        if ($params = $this->read_path_info()) {
            list($method) = explode(".", "res_$params[0]", 2);
            if (method_exists($this, $method)) {
                $this->$method(array_slice($params, 1));
            } else {
                die(sprintf('Resursas „%s“ nerastas.', $method));
            }
        }
    }

    public function read_path_info()
    {
        $path = Engine::read_path_info();
        return array_slice($path, 1);
    }

    /**
     * Grąžina failo content-type pagal paduotą išplėtimą.
     * @param $extension
     * @return unknown_type
     */
    public static function get_file_content_type($extension)
    {
        if (!empty(self::$contenttypes[$extension])) {
            return self::$contenttypes[$extension];
        } else {
            return 'text/html';
        }
    }

    public function res_tr_galleries($params)
    {
        if (!empty($params[0])) {
            $this->init_db();
            list($params_id, $photo_id) = explode('-', $params[0]);

            $params = Database::get_first("SELECT params FROM lite_photos_cache_params WHERE id = '{$params_id}' ");
            parse_str($params, $params);


            $src = 'images/galleries/' . Database::get_first("SELECT image FROM lite_photos WHERE id = '{$photo_id}' ");
            if (strpos($src, 'images/galleries') === 0 && !file_exists($src) && defined('LIVE_URL')) {
                $src = LIVE_URL . $src;
            }

            $fileinfo = pathinfo($src);
            $ext = !empty($params['type']) ? Image::get_image_extension_by_type($params['type']) : (!empty($fileinfo['extension']) ? $fileinfo['extension'] : "");

            $path = 'cache/galleries/';
            $dest = "{$path}{$params_id}/{$photo_id}.{$ext}";

            if (!file_exists($dest)) {
                $dir = "{$path}{$params_id}/";
                if (!file_exists($dir)) {
                    mkdir($dir, 0777);
                }
                if (!Image::copy_image($src, $dest, $params, $error_msg)) {
                    echo $error_msg;
                }
            }

            $img_info = getimagesize($dest);
            $type = $img_info[2];
            $fp = fopen($dest, 'rb');
            Image::set_image_headers(image_type_to_mime_type($type), filesize($dest));
            fpassthru($fp);
            exit;
        }
    }

    /**
     * Pasiunčia užklaustą paveikslėlį į outputą.
     * Pirmiausia tikrinama projekto direktorijoje esantis katalogas "/images"
     * jei randama, pasiunčiamas paveikslėlis, jei ne, tikrinamas projekto
     * branduolyje esantis katalogas "/images", jei randama, pasiunciama, kitu
     * atveju pasiunciamas paveikslelis "not_found", kuris jei yra, imamas iš
     * projekto, kitu atveju iš branduolio.
     *
     * @param unknown_type $params
     */
    protected function res_images($params = array())
    {
        if ($file_name = implode('/', $params)) {
            if (file_exists($file = "images/$file_name")) {
            } elseif (file_exists($file = CORE_PATH . "images/$file_name")) {
            } elseif (file_exists($file = 'images/not_found.gif')) {
            } elseif (!file_exists($file = CORE_PATH . 'images/not_found.gif')) {
                $file = false;
            }

            if ($file) { //sutvarkyti, kad veiktu headeriai
                while (ob_get_level() > 0) {
                    ob_end_clean();
                }
                if ($img_info = getimagesize($file)) {
                    $type = $img_info[2];

                    header('Content-type: ' . image_type_to_mime_type($type));
                    header('Content-Length: ' . filesize($file));
                    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + EXPIRES_HEADER_TIME) . ' GMT');
                    header('ExpiresDefault: "access plus 10 years"');
                    header('Cache-Control: must-revalidate');
                    $fp = fopen($file, 'r');

                    fpassthru($fp);
                }
            }
        }
    }

    /**
     * I output pasiuncia transformuotą paveiklėlį pagal paduotus parametrus.
     * Parametrų sąrašą žr copy_image aprašyme.
     *
     * @param unknown_type $params
     */
    protected function res_tr_images($params = array())
    {
        if ($file_name = isset($_GET['src']) ? $_GET['src'] : implode('/', $params)) {
            $params = array_diff_key($_GET, array('PATH_INFO' => true));
            if (strpos($file_name, 'images/galleries') === 0 && !file_exists($file_name) && defined('LIVE_URL')) {
                $file_name = LIVE_URL . $file_name;
            }
            if (!Image::copy_image($file_name, false, $params, $error_msg)) {
                echo $error_msg;
            }
        }
    }
}
