<?php

namespace Elab\Lite\Helpers;

class Str
{
    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle) ? true : false;
    }

    public static function truncate($text, $numb = 75, $etc = "...", $encoding = "UTF-8")
    {
        mb_internal_encoding($encoding);
        $text = html_entity_decode($text, ENT_QUOTES);
        if (mb_strlen($text) > $numb) {
            $text = mb_substr($text, 0, $numb);
            $text = mb_substr($text, 0, mb_strrpos($text, " "));
            $text = $text . $etc;
        }
        $text = htmlspecialchars($text, ENT_QUOTES);
        return $text;
    }

    /**
     * Pagal pavadinima, kuriame zodziai atskirti underscore'u "_" arba bruksneliu "-",
     * suformuoja CamelCase pavadinima.
     *
     * @param unknown_type $string
     * @return unknown
     */
    public static function camel_case($string)
    {
        $camel_case = "";
        $words = preg_split('/[-_]/', $string);
        foreach ($words as $w) {
            $camel_case .= ucfirst($w);
        }
        return $camel_case;
    }

    /**
     * steven --at-- acko --dot-- net pointed out that you can't make strip_slashes allow comments.
     * With this function, you can.  Just pass <!--> as one of the allowed tags.  Easy as pie: just
     * pull them out, strip, and then put them back.
     *
     * http://us2.php.net/manual/en/function.strip-tags.php
     *
     * @param $string
     * @param $allowed_tags
     * @return unknown_type
     */
    public static function strip_tags_c($string, $allowed_tags = '')
    {
        $allow_comments = (strpos($allowed_tags, '<!-->') !== false);
        if ($allow_comments) {
            $string = str_replace(array('<!--', '-->'), array('&lt;!--', '--&gt;'), $string);
            $allowed_tags = str_replace('<!-->', '', $allowed_tags);
        }
        $string = strip_tags($string, $allowed_tags);
        if ($allow_comments) {
            $string = str_replace(array('&lt;!--', '--&gt;'), array('<!--', '-->'), $string);
        }
        return $string;
    }

    public static function generate($length = 6)
    {
        $str = 'QWERTYUPASDFGHJKLZXCVNM' .
            'qwertyuipasdfghjkzxcvbnm' .
            '2345679' .
            '2345679';
        $code = "";
        for ($i = 0; $i < $length; $i++) {
            $key = rand(0, strlen($str) - 1);
            $code .= $str[$key];
        }
        return $code;
    }
}
