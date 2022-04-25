<?php

namespace Elab\Lite\System;

use Elab\Lite\Helpers\Arr;
use Elab\Lite\Helpers\File;
use Elab\Lite\Helpers\Image;
use Elab\Lite\Helpers\Inflector;
use Elab\Lite\Engine;
use Elab\Lite\Services\Database;

/**
 * Bazinė helperio klasė.
 *
 * @package helpers
 */
class Helper
{
    public static $image_mimes = array('image/jpeg', 'image/png', 'image/gif');
    protected $jq_form_loaded = false;
    protected $jq_validation_loaded = false;
    protected $_current_tpl;
    private $cache_params = [];

    public function __call($name, $arguments)
    {
        $this->_current_tpl = $name;
        if (method_exists($this, "_$name")) {
            call_user_func_array(array($this, "_$name"), $arguments);
        }
        return $this->render($this->_current_tpl);
    }

    public function render($template)
    {
        if (!$template) {
            return;
        }
        $template = preg_replace('/\.tpl$/', '', $template);

        $classes = [];
        $class = get_class($this);
        while ($class) {
            $classes[] = $class;
            $class = get_parent_class($class);
        }
        $paths = [];
        $last_path = '';
        $last_base_class = '';
        foreach (array_reverse($classes) as $class) {
            $base_class = str_replace(Engine::$namespaces, '', $class);
            $base_class = str_replace(['System\\', 'Helpers\\'], '', $base_class);
            if ($path = preg_replace("/$last_base_class\$/", '', $base_class)) {
                $path = Inflector::camel2id($path, '_');
                $last_path .= $path . '/';
                $last_base_class = $base_class;
                $paths[] = $last_path;
            }
        }

        foreach ($paths as $path) {
            $tpl_path = preg_replace('@^helper/@', 'helpers/', $path) . $template . '.tpl';
            if ($tpl = self::get_view_path($tpl_path)) {
                //try {
                $result = Repository::$smarty->fetch($tpl);
                /*} catch (Exception $e) {
                    debug ($e->getMessage());
                    debug ("$path: $tpl"); die();
                }*/
                return $result;
            }
            $tpl_paths[] = $tpl_path;
        }
    }

    public function display_html($content, $decode = true)
    {
        if ($decode) {
            $content = htmlspecialchars_decode($content, ENT_QUOTES);
        }
        $content = $this->fix_images($content);
        return $content;
    }

    public function fix_images($content)
    {
        $uid = uniqid();
        if (preg_match_all('@<img(.*)src="(.+)" alt="(.*)" width="(\d+)" height="(\d+)" />@U', $content, $matches)) {
            foreach ($matches[0] as $match_key => $match) {
                $match_before = $match;
                $src = $matches[2][$match_key];
                if (preg_match('@^/@', $src)) {
                    $src = 'http://' . $_SERVER['SERVER_NAME'] . $src;
                }
                if (preg_match('@^' . PROJECT_URL . '@', $src)) {
                    $src = preg_replace('@^' . PROJECT_URL . '@', '', $src);
                    $other = $matches[1][$match_key];
                    $alt = $matches[3][$match_key];
                    $w = $matches[4][$match_key];
                    $h = $matches[5][$match_key];
                    $match = '<img' . $other . 'alt="' . $alt . '" width="' . $w . '" height="' . $h . '" src="' . $this->tr_image($src, "width=$w&height=$h") . '"/>';
                    $src = PROJECT_URL . $src;
                }
                if (preg_match('/class=".*lightbox.*"/', $match)) {
                    $match = "<a class=\"resizedimg\" rel=\"lightbox[$uid]\" href=\"{$src}\">$match<span class=\"zoom\">&nbsp;</span></a>";
                }
                if ($match != $match_before) {
                    $content = str_replace($match_before, $match, $content);
                }
            }
        }
        return $content;
    }

    public function tr_image($src, $params, $title = false)
    {
        $tmp_src = $src;
        if(is_array($tmp_src)) {
            $tmp_src = $tmp_src['src'];
        }
        if(File::file_ext($tmp_src) == 'svg') {
            return $tmp_src;
        }
        if (!empty($params)) {
            if (!is_array($params)) {
                parse_str($params, $params);
            }

            if (is_array($src)) {
                // cache/galleries/
                $photo_id = $src['id'];
                $params_id = $this->get_photos_cache_params_id($params);

                $fileinfo = pathinfo($src['image']);
                if (empty($title)) {
                    $title = !empty($src['name']) ? $src['name'] : preg_replace("/^[0-9]+_/", '', $fileinfo['filename']);
                }
                $ext = !empty($params['type']) ? $params['type'] : (!empty($fileinfo['extension']) ? $fileinfo['extension'] : "");
                if (is_numeric($ext)) {
                    $ext = Image::get_image_extension_by_type($ext);
                }
                $title = Inflector::slug(htmlspecialchars_decode($title, ENT_QUOTES)) . '.' . $ext;
                $tr_image = PROJECT_URL . "cache/galleries/{$params_id}-{$photo_id}/{$title}";
            } else {
                // cache/images/
                $image_path = preg_replace('@^' . PROJECT_URL . '@', '', $src);
                if (strpos($image_path, 'images/galleries') === 0 && !file_exists($image_path) && defined('LIVE_URL')) {
                    $image_path = LIVE_URL . $image_path;
                }
                $file = Image::cached_image_path($image_path, $params);
                $file2 = Image::nice_cached_image_path($image_path, $params, $title);
                $res_url = (preg_match('@^https?://@', $src))
                    ? RESOURCES_URL . "tr_images/?src=$image_path&amp;" . Arr::array2url($params)
                    : RESOURCES_URL . "tr_images/$image_path?" . Arr::array2url($params);
                $tr_image = $file && file_exists($file)
                    ? PROJECT_URL . $file2
                    : $res_url;
            }

            return $tr_image;
        }
        return is_array($src) ? $src['src'] : $src;
    }

    public function get_photos_cache_params_id($params)
    {
        ksort($params);
        $params = http_build_query($params);

        if (!$params_id = @$this->cache_params[$params]) {
            if (!$params_id = Database::get_first("SELECT id FROM lite_photos_cache_params WHERE params = '$params' ")) {
                Database::query("INSERT INTO lite_photos_cache_params(params) VALUES ('$params')");
                $params_id = Repository::$db->insert_id;
                $this->cache_params[$params] = $params_id;
            }
        }
        return $params_id;
    }

    public function set_ajax_submit($selector)
    {
        $config = '';
        if (Repository::$frontend) {
            $config = "form_config['error_tag'] = 'div';
			form_config['error_class'] = 'error';";
        }
        $result = $this->jq_ajax_scripts();
        $result .= "
		<script type=\"text/javascript\">
			var form_config = new Array();
			$config
			setAjaxSubmit('$selector', form_config);
		</script>
		";
        return $result;
    }

    protected function jq_ajax_scripts()
    {
        $b = Repository::$backend;
        $result = '';
        if (!$this->jq_form_loaded) {
            echo "<script type=\"text/javascript\">var validationErrorMsg=\"" . t('Klaida validuojant formą.') . '";</script>';
        }
        return $result;
    }

    /**
     * Submitina formą ir visą templeitą pakeičia kitu.
     *
     * @param $form_selector
     * @param $area_selector
     * @param $template
     * @return unknown_type
     */
    public function submit_and_replace($form_selector, $area_selector, $template)
    {
        $result = $this->jq_ajax_scripts();
        $result .= "<script type=\"text/javascript\">$(function(){submitAndReplace('$form_selector', '$area_selector', '$template');});</script>";
        return $result;
    }

    public function ajax_links($link_selector, $area_selector, $template)
    {
        $result = $this->jq_ajax_scripts();
        $result .= "<script type=\"text/javascript\">ajaxLinks('$link_selector', '$area_selector', '$template');</script>";
        return $result;
    }

    public function ajax_replace($area_selector, $template)
    {
        $result = $this->jq_ajax_scripts();
        $result .= "<script type=\"text/javascript\">ajaxReplace('$area_selector', '$template');</script>";
        return $result;
    }

    //TODO: suvienodint pavadinimus, kad nebūtų makalynės - vienaip vadinasi metodas php, kitaip templeite
    public function get_setting($path)
    {
        return Engine::get_config($path);
    }

    public function include_file($path, $place = 'both')
    {
        if ($path = $this->get_view_path($path, $place)) {
            echo Repository::$smarty->fetch($path);
        }
    }

    public static function get_view_path($template_path, $place = 'both')
    {
        $possible_paths = array();
        $possible_paths[] = "views/$template_path";
        foreach ($possible_paths as $file) {
            if (file_exists($file)) {
                Repository::$core_view = false;
                return str_replace("../", '', $file);
                //return $file;
            }
        }
        return false;
    }

    public function draw_tree($tree, $view = "helper/tree.tpl")
    {
        $smarty = Repository::$smarty;
        $_smarty = clone $smarty;
        // issisaugom esamus kintamuosius
        $tree_vars = $_smarty->getTemplateVars('tree_vars');
        // priskiriam naujus
        $_smarty->assign('tree_vars', array('tree' => $tree, 'view' => $view));
        $result = $_smarty->fetch($this->get_view_path($view));
        // atstatom senus
        $_smarty->assign('tree_vars', $tree_vars);
        return $result;
    }

    public function show_messages($module_id = null)
    {
        if (empty($module_id)) {
            if (!isset($this->mod_config['id'])) {
                return false;
            }
            $module_id = $this->mod_config['id'];
        }
        $result = "";
        if (isset($_SESSION[$module_id]['messages']) && is_array($_SESSION[$module_id]['messages'])) {
            foreach ($_SESSION[$module_id]['messages'] as $type => $msg) {
                unset($_SESSION[$module_id]['messages'][$type]);
                if ($msg) {
                    if ($type == 'message') {
                        $class = 'alert-success';
                    } elseif ($type == 'error_message') {
                        $class = 'alert-danger';
                    } elseif ($type == 'notice_message') {
                        $class = 'alert-info';
                    } else {
                        $class = "alert-warning $type";
                    }
                    $result = "
						<div class=\"alert $class alert-dismissible\" role=\"alert\">
							<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>
							$msg
						</div>
					";
                }
            }
        }
        echo $result;
    }

    public function truncate($text, $length = 100, $symbols = "...", $strip_tags = false)
    {
        mb_internal_encoding('utf-8');
        if ($strip_tags) {
            $text = strip_tags(htmlspecialchars_decode($text, ENT_QUOTES));
        }
        if (mb_strlen($text) > $length) {
            $text = mb_substr($text, 0, $length - mb_strlen($symbols)) . $symbols;
        }
        return $text;
    }

    public function ending($number, $word, $linksnis = 'G')
    {
        $variations['G'] = array(
            'a' => array('ų', 'ą', 'as'),
            'ė' => array('ių', 'ę', 'es'),
            'as' => array('ų', 'ą', 'us'),
            'tis' => array('čių', 'tį', 'čius'),
            'dis' => array('džių', 'dį', 'džius'),
            'is' => array('ių', 'į', 'ius'),
            'tys' => array('čių', 'tį', 'čius'),
            'dys' => array('džių', 'dį', 'džius'),
            'ys' => array('ių', 'į', 'ius'),
            'ius' => array('ių', 'ių', 'ius'),
        );
        $variations['V'] = array(
            'a' => array('ų', 'as', 'os'),
            'ė' => array('ių', 'ė', 'ės'),
            'as' => array('ų', 'as', 'ai'),
            'tis' => array('čių', 'tis', 'čiai'),
            'dis' => array('džių', 'dis', 'džiai'),
            'is' => array('ių', 'is', 'iai'),
            'tys' => array('čių', 'tys', 'čiai'),
            'dys' => array('džių', 'dys', 'džiai'),
            'ys' => array('ių', 'ys', 'iai'),
            'ius' => array('ių', 'ius', 'iai'),
        );

        if (($number % 10 == 0) || (($number % 100 > 10) && ($number % 100 < 20))) {
            $var = 0;
        } elseif ($number % 10 == 1) {
            $var = 1;
        } else {
            $var = 2;
        }

        foreach ($variations[$linksnis] as $k => $v) {
            if (preg_match("/$k\$/", $word)) {
                return preg_replace("/$k\$/", $v[$var], $word);
            }
        }
        return $word . "?";
    }

    public function truncate_words($length, $text, $ending = '...')
    {
        $text = htmlspecialchars_decode($text, ENT_QUOTES);
        if (mb_strlen($text) > $length) {
            $text = mb_substr($text, 0, $length + 1);
            $text = preg_replace('/[-, .;]+[^-, .;]*$/', $ending, $text);
        }
        $text = htmlspecialchars($text, ENT_QUOTES);
        return $text;
    }

    public function named_number($number, $word, $linksniuote = 1, $show_number = true)
    {
        $galunes = array(
            // 1, 2, 11
            1 => array('as', 'ai', 'ų'), // yra 1 balsas, 2 balsai, 11 balsų
            2 => array('a', 'os', 'ų'), // yra 1 nuotrauka, 2 nuotraukos, 11 nuotraukų
            3 => array('gui', 'nėms', 'nių'), // patinka 1 žmogui, 2 žmonėms, 11 žmonių
            4 => array('ą', 'us', 'ų'), // turi 1 balsą, 2 balsus, 11 balsų
        );
        $return = $show_number ? "$number " : '';
        if ($number % 10 == 0 || $number % 100 > 10 && $number % 100 < 20) {
            return $return . $word . $galunes[$linksniuote][2];
        } elseif ($number % 10 == 1) {
            return $return . $word . $galunes[$linksniuote][0];
        } else {
            return $return . $word . $galunes[$linksniuote][1];
        }
    }

    public function assign($tpl_var, $value = null, $nocache = false)
    {
        Repository::$smarty->assign($tpl_var, $value, $nocache);
    }

    public function picture($image, $params, $alt = '', $class = '', $additional_attr = '', $picture_class = '', $only_desktop = false)
    {
        if (!is_array($params)) {
            parse_str($params, $params);
        }

        $src = $this->tr_image($image, $params, $alt);
        $src_webp = function_exists('imagewebp') ? $this->tr_image($image, array_merge($params, ['type' => IMAGETYPE_WEBP]), $alt) : false;

        $params2x = $params;
        if (!empty($params2x['width'])) {
            $params2x['width'] *= 2;
        }
        if (!empty($params2x['height'])) {
            $params2x['height'] *= 2;
        }
        $src2x = $this->tr_image($image, $params2x, $alt);
        if (!isset($params['disablewebp2x'])) {
            $src2x_webp = function_exists('imagewebp') ? $this->tr_image($image, array_merge($params2x, ['type' => IMAGETYPE_WEBP]), $alt) : false;
            $src2x = $this->tr_image($image, $params2x, $alt);
        }
        if(!$only_desktop) {
            $params_mobile = $params;
            if (!empty($params_mobile['width'])) {
                if(!isset($params_mobile['mobile_width'])) $params_mobile['mobile_width'] = 360;
                $ratio = $params_mobile['width'] / $params_mobile['mobile_width'];
                $params_mobile['width'] = $params_mobile['mobile_width'];
                $params_mobile['type'] = IMAGETYPE_WEBP;
                $params_mobile['height'] =  !empty($params_mobile['mobile_height'])?$params_mobile['mobile_height']:($params_mobile['height'] / $ratio);
                $webp_mobile = $this->tr_image($image, $params_mobile, $alt);
                unset($params_mobile['type'] );
                $src_mobile = $this->tr_image($image, $params_mobile, $alt);
            }
        }

        // ToDo: cia turbut reikia ir next-gen image 2x assignint
        Repository::$smarty->assign(array(
            'src' => $src,
            'src2x' => isset($src2x) ? $src2x : '',
            'alt' => $alt,
            'webp' => @$src_webp,
            'webp2x' => @$src2x_webp,
            'webpmobile' => $webp_mobile,
            'srcmobile' => $src_mobile,
            'class' => $class,
            'picture_class' => $picture_class,
            'additional_attr' => $additional_attr
//                      'jp2'  => @$jp2,
//                      'jxr'  => @$jxr,
        ));
        return Repository::$smarty->fetch($this->get_view_path("helpers/frontend/picture.tpl"));
    }

    /**
     * Get the path to a versioned Mix file.
     *
     * @param string $path
     * @param string $manifestDirectory
     * @return string
     *
     * @throws \Exception
     */
    public function mix($path, $manifestDirectory = 'frontend')
    {
        $publicFolder = '/public';
        $rootPath = $_SERVER['DOCUMENT_ROOT'];
        $publicPath = $rootPath . $publicFolder;
        if ($manifestDirectory && !\Elab\Lite\Helpers\Str::startsWith($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }

        if (!file_exists($manifestPath = ($rootPath . $publicFolder . $manifestDirectory . '/manifest.json'))) {
            throw new \Exception('The Mix manifest does not exist.');
        }
        $manifest = json_decode(file_get_contents($manifestPath), true);
        if (!array_key_exists($path, $manifest)) {
            throw new \Exception(
                "Unable to locate Mix file: {$path}. Please check your " .
                'webpack.mix.js output paths and try again.'
            );
        }
        return '/' . $manifest[$path];
    }
}
