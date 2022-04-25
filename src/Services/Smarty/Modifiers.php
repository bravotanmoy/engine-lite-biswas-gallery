<?php

namespace Elab\Lite\Services\Smarty;

use Elab\Lite\Helpers\File;
use Elab\Lite\Helpers\Image;
use Elab\Lite\Services\Price;
use Elab\Lite\System\Repository;

class Modifiers
{
    public static function load()
    {
        Repository::$smarty->registerPlugin('modifier', 'price', array(Price::class, 'format_simple'));
        Repository::$smarty->registerPlugin('modifier', 'fprice', array(Price::class, 'format'));
        Repository::$smarty->registerPlugin('modifier', 'human_file_size', array(File::class, 'human_file_size'));
        Repository::$smarty->registerPlugin('modifier', 'ifnull', array(self::class, 'ifnull'));
        Repository::$smarty->registerPlugin('modifier', 'ifelse', array(self::class, 'ifelse'));
        Repository::$smarty->registerPlugin('modifier', 'tr_image', array(Image::class, 'tr_image'));
    }

    public static function ifnull($val1, $val2)
    {
        return $val1 ?: $val2;
    }

    public static function ifelse($condition, $true_value, $false_value)
    {
        return $condition ? $true_value : $false_value;
    }
}
