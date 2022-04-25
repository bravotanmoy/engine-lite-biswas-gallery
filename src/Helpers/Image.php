<?php

namespace Elab\Lite\Helpers;

class Image
{
    private const MULT_VALUE = 10;

    public static function tr_image($src, $params, $force_cache = false)
    {
        if (!empty($params)) {
            if (!is_array($params)) {
                parse_str($params, $params);
            }
            $image_path = preg_replace('@^' . PROJECT_URL . '@', '', $src);
            $res_url = (preg_match('@^https?://@', $src)) ? RESOURCES_URL . "tr_images/?src=$image_path&amp;" . Arr::array2url($params) : RESOURCES_URL . "tr_images/$image_path?" . array2url($params);
            $file = self::cached_image_path($image_path, $params);
            if (!$file) {
                // file not found arba http://...
                $tr_image = $src;
            } elseif (file_exists($file)) {
                // yra cache versija
                $tr_image = PROJECT_URL . $file;
            } elseif ($force_cache) {
                // reikia sukurti cache versija
                copy(htmlspecialchars_decode($res_url), $file);
                $tr_image = PROJECT_URL . $file;
            } else {
                // graziname tr_image resurso url'a
                $tr_image = $res_url;
            }
        }
        return $tr_image;
    }

    public static function copy_image($src, $dest, $image_params = array(), &$error_msg = false)
    {
        if(File::file_ext($src) == 'svg') {
            if ($dest) {
                return File::move_file($src, $dest);
            } else {
                while (ob_get_level()) {
                    ob_end_clean();
                }
                $fp = fopen($src, 'rb');
                self::set_image_headers('image/svg+xml', filesize($src));
                fpassthru($fp);
                fclose($fp);
            }
        }
        $is_url = preg_match('/^https?:/i', $src);
        if (!$is_url && !file_exists($src)) {
            $error_msg = "Paveiksėlis nerastas ($src)";
            return false;
        }

        $params = array_merge(array(
            'allowed_types' => array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP),
            'mode' => 'resize', // resize, crop, no_resize
            'width' => 800,
            'height' => 600,
            'quality' => null,
            'grayscale' => false,
            'bgcolor' => 'ffffff',
        ), $image_params);
        if (!$params['quality']) {
            $params['quality'] = $params['width'] > 200 && $params['height'] > 200 ? 80 : 90;
        }

        $cache = true;
        $cache = $cache && !$dest;

        $cache_dest = self::cached_image_path($src, $image_params);
        if (file_exists($cache_dest)) {
            if ($dest) {
                if ($dest == $cache_dest) {
                    return true;
                } else {
                    return @copy($cache_dest, $dest);
                }
            } else {
                $img_info = getimagesize($src);
                $type = $img_info[2];
                $fp = fopen($cache_dest, 'rb');
                self::set_image_headers(image_type_to_mime_type($type), filesize($cache_dest));
                fpassthru($fp);
                exit;
            }
        }

        if ($img_info = getimagesize($src)) {
            $width = $img_info[0];
            $height = $img_info[1];
            $type = $img_info[2];

            if (empty($params['type'])) {
                $params['type'] = $type;
            }

            // issiskiriam atminties
            self::set_memory_for_image($img_info);

            if (empty($params['fill']) && empty($params['mask']) && ($params['type'] == $type) && ($params['mode'] != 'crop') && ($width <= $params['width']) && ($height <= $params['height']) && empty($params['watermark'])) {
                if (!$dest) {
                    if (!empty($cache_dest)) {
                        @copy($src, $cache_dest);
                    }
                    $fp = fopen($src, 'rb');
                    self::set_image_headers(image_type_to_mime_type($type), filesize($src));
                    fpassthru($fp);
                    exit;
                } else {
                    return @copy($src, $dest);
                }
            } else {
                if (in_array($type, $params['allowed_types'])) {
                    switch ($type) {
                        case IMAGETYPE_GIF:
                            $im_src = imagecreatefromgif($src);
                            break;

                        case IMAGETYPE_JPEG:
                            $im_src = imagecreatefromjpeg($src);
                            break;

                        case IMAGETYPE_PNG:
                            $im_src = imagecreatefrompng($src);
                            break;

                        case IMAGETYPE_BMP:
                            $im_src = imagecreatefrombmp($src);
                            break;
                    }
                } else {
                    $error_msg = "Netinkamas paveikslelio tipas ($img_info[mime])";
                    return false;
                }

                $max_dest_width = ($params['mode'] != 'no_resize') ? $params['width'] : imagesx($im_src);
                $max_dest_height = ($params['mode'] != 'no_resize') ? $params['height'] : imagesy($im_src);
                $crop = ($params['mode'] == "crop");

                $width_orig = imagesx($im_src);
                $height_orig = imagesy($im_src);

                $width_dest = $max_dest_width;
                $height_dest = $max_dest_height;

                if (($width_orig > $width_dest) || ($height_orig > $height_dest)) {
                    if ($crop) {
                        if (($width_orig < $width_dest) || ($height_orig < $height_dest)) {
                            $width_dest = $width_orig;
                            $height_dest = $height_orig;
                        } else {
                            if (($width_orig / $width_dest) < ($height_orig / $height_dest)) {
                                $height_dest = ($width_dest / $width_orig) * $height_orig;
                            } else {
                                $width_dest = ($height_dest / $height_orig) * $width_orig;
                            }
                        }
                    } else {
                        if (($width_orig / $width_dest) < ($height_orig / $height_dest)) {
                            $width_dest = ($height_dest / $height_orig) * $width_orig;
                        } else {
                            $height_dest = ($width_dest / $width_orig) * $height_orig;
                        }
                    }
                } else {
                    $width_dest = $width_orig;
                    $height_dest = $height_orig;
                }

                $bgcolor = (isset($params['bgcolor']) && (preg_match("/[0-9A-Fa-f]{6}/", $params['bgcolor']))) ? $params['bgcolor'] : false;

                // iskiriam atminties naujam paveiksliukui
                self::set_memory_for_image(array($max_dest_width, $max_dest_height));

                if ($crop || !empty($params['fill'])) {
                    $dst_offset_x = -round(($width_dest - $max_dest_width) / 2);
                    $dst_offset_y = -round(($height_dest - $max_dest_height) / 2);
                    $image_p = self::prepare_dest_image_resource($im_src, $img_info, $max_dest_width, $max_dest_height, $bgcolor);
                } else {
                    $dst_offset_x = 0;
                    $dst_offset_y = 0;
                    $image_p = self::prepare_dest_image_resource($im_src, $img_info, $width_dest, $height_dest, $bgcolor);
                }

                imagecopyresampled($image_p, $im_src, $dst_offset_x, $dst_offset_y, 0, 0, $width_dest, $height_dest, $width_orig, $height_orig);

                // grayscale?
                if ($params['grayscale']) {
                    $width = imagesx($image_p);
                    $height = imagesy($image_p);
                    for ($i = 0; $i < $width; $i++) {
                        for ($j = 0; $j < $height; $j++) {
                            $current = imagecolorsforindex($image_p, imagecolorat($image_p, $i, $j));
                            if ($current['alpha'] < 127) {
                                $gs = round(($current['red'] + $current['green'] + $current['blue']) / 3);
                                $color = imagecolorallocatealpha($image_p, $gs, $gs, $gs, $current['alpha']);
                                imagesetpixel($image_p, $i, $j, $color);
                            }
                        }
                    }
                }

                if (isset($params['mask'], $params['mask_color'], $params['mask_alpha'])) {
                    $mask_color = (isset($params['mask_color']) && (preg_match("/[0-9A-Fa-f]{6}/", $params['mask_color']))) ? $params['mask_color'] : "000000";
                    $mask_alpha = (isset($params['mask_alpha']) && is_numeric($params['mask_alpha']) && ($params['mask_alpha'] >= 0) && ($params['mask_alpha'] <= 127)) ? $params['mask_alpha'] : 63;
                    $color = ImageColorAllocateAlpha($image_p, base_convert(substr($mask_color, 0, 2), 16, 10), base_convert(substr($mask_color, 2, 2), 16, 10), base_convert(substr($mask_color, 4, 2), 16, 10), $mask_alpha);
                    imagefilledrectangle($image_p, 0, 0, imagesx($image_p) - 1, imagesy($image_p) - 1, $color);
                }

                if (!empty($params['watermark'])) {
                    $w_name = strlen($params['watermark']) > 1 ? $params['watermark'] : 'watermark';
                    self::set_memory_for_image(getimagesize("images/$w_name.png"));
                    $w_img = imagecreatefrompng("images/$w_name.png");
                    $ww = imagesx($w_img); // watermark width
                    $wh = imagesy($w_img); // watermark height

                    $percent = 0.2 / ($ww / imagesx($image_p));

                    imagecopyresampled($image_p, $w_img, imagesx($image_p) / 2 - $ww * $percent / 2, imagesy($image_p) / 2 - $wh * $percent / 2, 0, 0, $ww * $percent, $wh * $percent, $ww, $wh);
                }

                if (in_array($type, $params['allowed_types'])) {
                    $result = false;
                    $filename = ($cache ? $cache_dest : $dest) ?: null;
                    if (!$filename) {
                        self::set_image_headers(image_type_to_mime_type($type));
                    }

                    $dest_type = $params['type'];
                    if ($filename && ($dest_type == IMAGETYPE_WEBP)) {
                        $dest_type = $type; // src type
                    }

                    if ($filename) {
                        $ext = strstr($filename, '.');
                        $filename_webp = str_replace($ext, '.webp', $filename);
                    }

                    switch ($params['type']) {

                        case IMAGETYPE_WEBP:
                            $quality = (isset($params['quality']) && ($params['quality'] <= 100) && ($params['quality'] >= 0)) ? $params['quality'] : 80;
                            $result = @imagewebp($image_p, $filename_webp, $quality);//@imagewebp($image_p, null, $quality);
                            break;

                        case IMAGETYPE_GIF:
                            $result = @imagegif($image_p, $filename);
                            if ($filename) {
                                @imagewebp($image_p, $filename_webp, 100);
                            }
                            break;

                        case IMAGETYPE_JPEG:
                            $quality = (isset($params['quality']) && ($params['quality'] <= 100) && ($params['quality'] >= 0)) ? $params['quality'] : 80;
                            $result = imagejpeg($image_p, $filename, $quality);
                            if ($filename) {
                                @imagewebp($image_p, $filename_webp, $quality);
                            }
                            break;

                        case IMAGETYPE_PNG:
                            $quality = (isset($params['quality']) && ($params['quality'] <= 9) && ($params['quality'] >= 0)) ? $params['quality'] : 0;
                            $result = @imagepng($image_p, $filename, $quality);

                            if ($filename) {
                                imagewebp($image_p, $filename_webp, 100);
                            }
                            break;

                        case IMAGETYPE_BMP:
                            $result = @imagebmp($image_p, $filename);

                            if ($filename) {
                                imagewebp($image_p, $filename_webp, 100);
                            }
                            break;
                    }

                    if ($result) {
                        if ($filename && !$dest) {
                            while (ob_get_level()) {
                                ob_end_clean();
                            }
                            $fp = fopen($filename, 'rb');
                            self::set_image_headers(image_type_to_mime_type($params['type']), filesize($filename));
                            fpassthru($fp);
                            fclose($fp);
                        }

                        return true;
                    } else {
                        $error_msg = "Nepavyko suformuoti paveikslėlio.";
                        return false;
                    }
                } else {
                    $error_msg = "Paprašyta neatpažinto formato: $params[type].";
                    return false;
                }
            }
        } else {
            $error_msg = "Neatpažintas paveikslėlio tipas.";
            return false;
        }
    }

    public static function get_image_extension_by_type($type)
    {
        switch ($type) {
            case IMAGETYPE_GIF:
                return 'gif';
            case IMAGETYPE_JPEG:
                return 'jpg';
            case IMAGETYPE_PNG:
                return 'png';
            case IMAGETYPE_BMP:
                return 'bmp';
            case IMAGETYPE_WEBP:
                return 'webp';
        }
        return false;
    }

    public static function nice_cached_image_path($src, $params, $title = false)
    {
        $is_url = preg_match('/^https?:/i', $src);
        if ($is_url) {
            $headers = get_headers($src, 1);
            $filectime = @$headers['Last-Modified'] ? strtotime($headers['Last-Modified']) : false;
        } elseif (is_file($src)) {
            $filectime = filemtime($src);
        } else {
            $filectime = false;
        }
        $path_info = pathinfo($src);
        $ext = !empty($params['type']) ? '.' . $params['type'] : (!empty($path_info['extension']) ? '.' . $path_info['extension'] : "");
        $cache_folder = 'cache/images/';
        $array = array_merge($params, array('src' => $src, 'filectime' => $filectime));
        foreach ($array as &$value) {
            $value = (string)$value;
        }
        ksort($array);
        $hash = md5(serialize($array));

        if (!empty($title)) {
            $title = Inflector::slug(htmlspecialchars_decode($title, ENT_QUOTES));
        } else {
            $title = preg_replace("/^[0-9]+_/", '', $path_info['filename']);
        }

        $file = "$cache_folder$hash/$title" . $ext;
        return $file;
    }

    public static function cached_image_path($src, $params)
    {
        $is_url = preg_match('/^https?:/i', $src);
        if ($is_url) {
            $headers = get_headers($src, 1);
            $filectime = @$headers['Last-Modified'] ? strtotime($headers['Last-Modified']) : false;
        } elseif (is_file($src)) {
            $filectime = filemtime($src);
        } else {
            $filectime = false;
        }
        $path_info = pathinfo($src);
        $ext = !empty($params['type']) ? '.' . $params['type'] : (!empty($path_info['extension']) ? '.' . $path_info['extension'] : "");
        $cache_folder = 'cache/images/';
        $array = array_merge($params, array('src' => $src, 'filectime' => $filectime));
        foreach ($array as &$value) {
            $value = (string)$value;
        }
        ksort($array);
        $hash = md5(serialize($array));
        $file = "$cache_folder$hash" . $ext;
        return $file;
    }

    public static function set_image_headers($content_type, $content_length = 0)
    {
        header("Content-type: $content_type");
        if (!empty($content_length)) {
            header("Content-Length: $content_length");
        }
        header('Cache-Control: must-revalidate');
        if (!defined('EXPIRES_HEADER_TIME')) {
            define('EXPIRES_HEADER_TIME', 60 * 60 * 24 * 256);
        }
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + EXPIRES_HEADER_TIME) . ' GMT');
        header('ExpiresDefault: "access plus 10 years"');
    }

    /**
     * Generuoja paveiksleli is teksto.
     * Galimi paramerai:
     *        size - norimas srifto dydis
     *        color - srifto spalvos kodas hex'u
     *        bgcolor - pagrindo spalvos hex kodas, naudojamas tam, kad butu galima sugeneruot perejima is srifto spalvos i fono spalva
     *        transparent_background - nupiesto paveikslelio pagrindas bus permatomas
     *        font - srifto pavadinimas is images/fonts direktorijos
     *        shadow - bus piesiamas seselis tekstui
     *        angle - teksto pavertimas
     *        ***parametrai seselio piesimui:
     *            line_width - eilutes ilgis simboliais, jeigu paduotas tekstas virsyja si skaiciu, bus keliama i kita eilute
     *            align - teksto lygiavimas
     *            shadow_color - seselio spalvos hex kodas
     *            shadow_offset_x - seselio atitraukimas x asies atzvilgiu
     *            shadow_offset_y - seselio atitraukimas y asies atzvilgiu
     * FIXME: neveikia transparent_background = false
     */
    public static function text2img($text, $params)
    {
        if (empty($text)) {
            exit;
        }

        $params = array_merge(
            array(
                'size' => 12,
                'color' => '#000000',
                'bgcolor' => '#000000',
                'transparent_background' => true,
                'font' => 'trebuc.ttf',
                'output' => false,
                'shadow' => false,
                'line_width' => 40,
                'align' => 'left',
                'shadow_color' => "#aaa",
                'shadow_offset_x' => 2,
                'shadow_offset_y' => 2,
                'angle' => 0,
            ),
            $params
        );

        $cache_images = false; // <-- atkeisti i true
        $cache_folder = 'cache';
        $mime_type = 'image/png';
        $extension = '.png';
        $send_buffer_size = 4096;

        // look for cached copy, send if it exists
        $hash = md5($text . $params['size'] . $params['color'] . $params['bgcolor'] . $params['transparent_background'] . $params['font'] . $params['shadow'] . $params['angle']);
        $cache_filename = $cache_folder . '/' . $hash . $extension;
        if ($cache_images && (file_exists($cache_filename))) {
            return (PROJECT_URL . $cache_filename);
        }

        if ($params['shadow']) {
            $image = self::draw_text_shadow($text, $params);
        } else {
            $image = self::draw_paragraph($text, $params);
        }

        @ImagePNG($image, $cache_filename);

        ImageDestroy($image);
        return PROJECT_URL . $cache_filename;
    }

    private static function set_memory_for_image($imageInfo)
    {
        if (!defined("MAX_MEMORY_SET")) {
            $imageInfo = array_merge(array('bits' => 16, 'channels' => 4), $imageInfo);
            //$imageInfo = getimagesize("cache/images/13580b4a6af201e7bc952136b400e749.jpg");
            $MB = 1048576;  // number of bytes in 1M
            $K64 = 65536; // number of bytes in 64K
            $TWEAKFACTOR = 3;  // Or whatever works for you
            $memoryNeeded = round(
                (
                $imageInfo[0] * $imageInfo[1] * $imageInfo['bits'] * $imageInfo['channels'] / 8 + $K64
            ) * $TWEAKFACTOR
            );
            //ini_get('memory_limit') only works if compiled with "--enable-memory-limit" also
            //Default memory limit is 8MB so well stick with that.
            //To find out what yours is, view your php.ini file.
            $memoryLimitMB = 32;
            $memoryLimit = $memoryLimitMB * $MB;
            if (function_exists('memory_get_usage') &&
                memory_get_usage() + $memoryNeeded > $memoryLimit) {
                $newLimit = $memoryLimitMB + ceil(
                    (
                    memory_get_usage() + $memoryNeeded - $memoryLimit
                ) / $MB
                );
                ini_set('memory_limit', $newLimit . 'M');
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * paveiksleliu formavimas/kopijavimas pagal paduotus parametrus
     *
     * @param Str $src - kelias iki pradinio paveikslelio (source file name)
     * @param Str $dest - rezultato failo vardas (jei null - bus daromas outputas)
     * @param array $params - konfiguracijos masyvas:
     *              width - naujo paveikslelio ilgis (jei nenurodytas, imamas originalaus paveikslelio ilgis)
     *              height - naujo paveikslelio aukstis (jei nenurodytas, imamas originalaus paveikslelio aukstis)
     *              allowed_types - sarasas leistinu IMAGETYPE_xxx konstantu
     *              mode - vienas is saraso: resize, crop, no_resize:
     *                      resize - islaiko proporcijas
     *                      crop - apkirptas kad tiksliai atitiktu width x height
     *                      no_resize - islaikomas originalo dydis - veikia?
     *              type - rezultato formatas (jei nenurodyta, paveldi is src)
     *              fill (jei nurodomas) - turi prasme tik kai nera crop. Naujo pav. dydis bus tiksliai w ir h, tuscia vieta uzpildoma fonu.
     *              bgcolor - "rrggbb" - fono spalva.
     *              mask - boolean
     *              mask_color - "rrggbb"
     *              mask_alpha - [0; 127]
     *              grayscale - boolean (default:false) - nespalvotas rezultatas
     *              watermark - string / boolean (jei true, tai uzdeda watermark'a is images/watermark.png, jei string - images/{string}.png)
     *
     * @param unknown_type $error_msg
     * @return unknown
     */
    private static function prepare_dest_image_resource($src_res, $src_info, $width, $height, $bgcolor = false)
    {
        $image_res = imagecreatetruecolor($width, $height);
        if (!$bgcolor && (($src_info[2] == IMAGETYPE_GIF) || ($src_info[2] == IMAGETYPE_PNG))) {
            $trnprt_indx = imagecolortransparent($src_res);
            // If we have a specific transparent color
            if ($trnprt_indx >= 0 && $trnprt_indx < 255) {
                // Get the original image's transparent color's RGB values
                $trnprt_color = imagecolorsforindex($src_res, $trnprt_indx);
                // Allocate the same color in the new image resource
                $trnprt_indx = imagecolorallocate($image_res, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
                // Completely fill the background of the new image with allocated color.
                imagefill($image_res, 0, 0, $trnprt_indx);
                // Set the background color for new image to transparent
                imagecolortransparent($image_res, $trnprt_indx);
            } // Always make a transparent background color for PNGs that don't have one allocated already
            elseif ($src_info[2] == IMAGETYPE_PNG) {
                // Turn off transparency blending (temporarily)
                imagealphablending($image_res, false);
                // Create a new transparent color for image
                $color = imagecolorallocatealpha($image_res, 0, 0, 0, 127);
                // Completely fill the background of the new image with allocated color.
                imagefill($image_res, 0, 0, $color);
                // Restore transparency blending
                imagesavealpha($image_res, true);
            }
        } else {
            if (!$bgcolor) {
                $bgcolor = "ffffff";
            }
            $color = ImageColorAllocate($image_res, base_convert(substr($bgcolor, 0, 2), 16, 10), base_convert(substr($bgcolor, 2, 2), 16, 10), base_convert(substr($bgcolor, 4, 2), 16, 10));
            imagefill($image_res, 0, 0, $color);
        }
        return $image_res;
    }


    /**
     * TEXT 2 IMG PRIVATES
     */

    private static function draw_text_shadow($text, $params)
    {
        $params_text = array_merge($params, array(
            'transparent_background' => true,
        ));
        $text_img = self::draw_paragraph($text, $params_text);

        $params_shadow = array_merge($params, array(
            'color' => $params['shadow_color'],
            'transparent_background' => true,
        ));
        $shadow_img = self::draw_paragraph($text, $params_shadow);

        $final_img = imagecreatetruecolor(imagesx($text_img) + $params['shadow_offset_x'], imagesy($text_img) + $params['shadow_offset_y']);
        imagesavealpha($final_img, true);
        $trans_colour = imagecolorallocatealpha($final_img, /* $background_rgb['red'], $background_rgb['green'], $background_rgb['blue'], */ 0, 0, 0, 127);
        imagefill($final_img, 0, 0, $trans_colour);

        $bg_color_rgb = Color::hex_to_rgb($params['bgcolor']);
        $bg_color = @ImageColorAllocate($final_img, $bg_color_rgb['red'], $bg_color_rgb['green'], $bg_color_rgb['blue']);

        imagecopyresampled($final_img, $shadow_img, $params['shadow_offset_x'], $params['shadow_offset_y'], 0, 0, imagesx($shadow_img), imagesy($shadow_img), imagesx($shadow_img), imagesy($shadow_img));
        imagecopyresampled($final_img, $text_img, 0, 0, 0, 0, imagesx($text_img), imagesy($text_img), imagesx($text_img), imagesy($text_img));

        if ($params['output']) {
            header('Content-type: image/png');
            ImagePNG($final_img);
        } else {
            return $final_img;
        }
    }

    private static function draw_paragraph($text, $params = array())
    {
        $params = array_merge(
            array(
                'transparent_background' => false,
                'bgcolor' => "#EEEEEE",
                'spacing' => 4,
                'size' => 14,
                'align' => 'left',
                'output' => true,
                'line_width' => 30,
            ),
            $params
        );

        $text = preg_replace('@<br[/]*>@i', "\n", $text);
        $text = wordwrap($text, $params['line_width'], "\n");

        $text_params = $params;
        $text_params['output'] = false;

        $lines = preg_split("/[\r]*[\n]/", $text);

        $width = 0;
        $line_height = 0;
        $images = array();
        foreach ($lines as $line) {
            $img = self::draw_text($line, $text_params);
            $width = max($width, imagesx($img));
            $line_height = max($line_height, imagesy($img));
            $images[] = $img;
        }
        $offset_y = round($params['spacing'] / 2); // atitraukimas nuo virsaus per puse tarpo
        $line_height += $params['spacing']; // tarpo aukstis
        $height = $line_height * count($lines); // rezultato aukstis

        $final_img = imagecreatetruecolor($width, $height);

        imagesavealpha($final_img, true);
        $trans_colour = imagecolorallocatealpha($final_img, /* $background_rgb['red'], $background_rgb['green'], $background_rgb['blue'], */ 0, 0, 0, 127);
        imagefill($final_img, 0, 0, $trans_colour);

        $bg_color_rgb = Color::hex_to_rgb($params['bgcolor']);
        $bg_color = @ImageColorAllocate($final_img, $bg_color_rgb['red'], $bg_color_rgb['green'], $bg_color_rgb['blue']);

        foreach ($images as $i => $img) {
            switch ($params['align']) {
                case "center":
                    $align_offset_x = round(($width - imagesx($img)) / 2);
                    break;
                case "right":
                    $align_offset_x = $width - imagesx($img);
                    break;
                default:
                    $align_offset_x = 0;
            }
            imagecopy($final_img, $img, $align_offset_x, $i * $line_height + $offset_y, 0, 0, imagesx($img), imagesy($img));
        }

        if ($params['transparent_background']) {
            ImageColorTransparent($final_img, $bg_color);
        }

        if ($params['output']) {
            header("Content-type: image/png");
            imagepng($final_img);
        } else {
            return $final_img;
        }
    }

    /**
     * is paduoto teksto, naudojant paduotus parametrus sugeneruoja png paveiksleli
     *
     * @param unknown_type $text - tekstas, kuri reikia sugeneruoti
     * @param unknown_type $params - parametru masyvas, ateinantis is GET'o:
     * 'size' - srifto dydis,
     * 'color' - srifto spalva
     * 'bgcolor' - srifto pagrindo spalva, reikalinga, kad butu galima apskaiciuoti, i kokia spalva turi lietis nupiestas sriftas
     * 'font' - srifto pavadinimas is images/fonts direktorijos
     * 'output' - nurodo rezultato grazinimo buda. True - iraso rezultata i standartini output, false - grazina sugeneruota paveikslelio resursa
     * 'angle' - teksto pavertimas
     *
     * @return unknown
     */
    private static function draw_text($text, $params = array())
    {
        if (!defined('MAX_MEMORY_SET')) {
            define('MAX_MEMORY_SET', true);
            @ini_set('memory_limit', '32M');
        }

        $resize = true;

        $font_file = File::get_font_file($params['font']);

        // imam font'a is project'o, jei ten nera, tada is core

        $font_size = $params['size'];
        $font_color = $params['color'];
        $background_color = $params['bgcolor'];
        $transparent_background = $params['transparent_background'];
        $angle = $params['angle'];

        /* tam, kad nebutu iskraipomas paisomas vaizdas ant skirtingu serveriu, bei būtų įmanomas trupmeninis fonto dydis,
          pradzioje reikia generuoti didesni paveiksleli */
        if ($resize) {
            $font_size *= self::MULT_VALUE;
        }

        $mime_type = 'image/png';

        // check for GD support
        if (!function_exists('ImageCreate')) {
            self::fatal_error('Error: Server does not support PHP image generation');
        }

        // clean up text
        $text = self::javascript_to_html($text);

        // check font availability
        $font_found = is_readable($font_file);
        if (!$font_found) {
            self::fatal_error('Error: The server is missing the specified font.');
        }

        // create image
        $background_rgb = Color::hex_to_rgb($background_color);
        $font_rgb = Color::hex_to_rgb($font_color);
        $max_box = self::get_max_box($font_file, $font_size, 0);
        $box = @ImageTTFBBox($font_size, $angle, $font_file, $text);
        $box0 = @ImageTTFBBox($font_size, 0, $font_file, $text); // nepasuktas box'as
        // nepasukto teksto ilgis ir plotis - reikalingi tolimesniam skaiciavimui:
        $width = abs(max($box0[2], $box0[4]) - min($box0[0], $box0[6]));
        $height = abs(max($max_box[1], $max_box[3]) - min($max_box[5], $max_box[7]));

        //paverstos dezutes ilgi ir ploti randam, pasinaudodami sin/cos teoremomis:
        $allWidth = $height * abs(sin(deg2rad($angle))) + $width * abs(cos(deg2rad($angle)));
        $allHeight = $width * abs(sin(deg2rad($angle))) + $height * abs(cos(deg2rad($angle)));
        $height_rotated = abs(max($box[1], $box[3]) - min($box[5], $box[7]));
        $width_rotated = abs(max($box[2], $box[4]) - min($box[0], $box[6]));
        if ($allWidth < $width_rotated) {
            $allWidth = $width_rotated;
        }
        if ($allHeight < $height_rotated) {
            $allHeight = $height_rotated;
        }

        $text_offset_x = $box0[0]; // tuscias tarpas nuo kairio krasto iki teksto

        $h1 = abs($max_box[7]); // aukstis nuo baseline iki virsaus
        $h2 = abs($max_box[1]); // aukstis nuo baseline iki apacios
        $TextStartX = -$text_offset_x + $h1 * abs(sin(deg2rad($angle)));
        $TextStartY = $allHeight - $h2 * abs(cos(deg2rad($angle))) + abs($text_offset_x) * abs(sin(deg2rad($angle)));

        set_memory_for_image(array($allWidth, $allHeight));
        $image = @imagecreatetruecolor($allWidth, $allHeight);
        if (!$image || !$box) {
            self::fatal_error('Error: The server could not create this heading image.');
        }

        // set transparency
        if ($transparent_background) {
            imagesavealpha($image, true);
            $trans_colour = imagecolorallocatealpha($image, $background_rgb['red'], $background_rgb['green'], $background_rgb['blue'], 127);
            imagefill($image, 0, 0, $trans_colour);
        }

        // allocate colors and draw text
        $background_color = @ImageColorAllocate($image, $background_rgb['red'], $background_rgb['green'], $background_rgb['blue']);
        $font_color = ImageColorAllocate($image, $font_rgb['red'], $font_rgb['green'], $font_rgb['blue']);
        ImageTTFText($image, $font_size, $angle, $TextStartX, $TextStartY, $font_color, $font_file, $text);


        //jei generuodami padidinome vaizda, dabar reikia ji atstatyti i norima
        if ($resize) {
            $width = imagesx($image);
            $height = imagesy($image);
            $nWidth = $width / self::MULT_VALUE;
            $nHeight = $height / self::MULT_VALUE;
            self::set_memory_for_image(array($nWidth, $nHeight));
            $tmpImage = @imagecreatetruecolor($nWidth, $nHeight);
            if ($transparent_background) {
                imagesavealpha($tmpImage, true);
                $trans_colour = imagecolorallocatealpha($tmpImage, 0, 0, 0, 127);
                imagefill($tmpImage, 0, 0, $trans_colour);
            }
            imagecopyresampled($tmpImage, $image, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
            imagedestroy($image);
            $image = $tmpImage;
        }

        if ($params['output']) {
            header('Content-type: ' . $mime_type);
            ImagePNG($image);
        } else {
            return $image;
        }
    }

    /**
     * attempt to create an image containing the error message given.
     * if this works, the image is sent to the browser. if not, an error
     * is logged, and passed back to the browser as a 500 code instead.
     */
    private static function fatal_error($message)
    {
        // send an image
        if (function_exists('ImageCreate')) {
            $width = ImageFontWidth(5) * strlen($message) + 10;
            $height = ImageFontHeight(5) + 10;
            if ($image = ImageCreate($width, $height)) {
                $background = ImageColorAllocate($image, 255, 255, 255);
                $text_color = ImageColorAllocate($image, 0, 0, 0);
                ImageString($image, 5, 5, 5, $message, $text_color);
                header('Content-type: image/png');
                ImagePNG($image);
                ImageDestroy($image);
                exit;
            }
        }
        // send 500 code
        header("HTTP/1.0 500 Internal Server Error");
        print($message);
        exit;
    }

    /**
     * convert embedded, javascript unicode characters into embedded HTML
     * entities. (e.g. '%u2018' => '&#8216;'). returns the converted string.
     */
    private static function javascript_to_html($text)
    {
        $matches = null;
        preg_match_all('/%u([0-9A-F]{4})/i', $text, $matches);
        if (!empty($matches)) {
            for ($i = 0; $i < sizeof($matches[0]); $i++) {
                $text = str_replace($matches[0][$i], '&#' . hexdec($matches[1][$i]) . ';', $text);
            }
        }

        return $text;
    }

    /**
     * try to determine the "dip" (pixels dropped below baseline) of this
     * font for this size.
     */
    private static function get_max_box($font, $size, $angle = 0)
    {
        $test_chars = 'ąčęėįšųūžabcdefghijklmnopqrstuvwxyz' .
            'ĄČĘĖĮŲŪŽABCDEFGHIJKLMNOPQRSTUVWXYZ' .
            '1234567890' .
            '!@#$%^&*()\'"\\/;.,`~<>[]{}-+_-=';
        $box = @ImageTTFBBox($size, $angle, $font, $test_chars);
        return $box;
    }
}
