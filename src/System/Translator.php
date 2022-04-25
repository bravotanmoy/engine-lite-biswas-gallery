<?php

namespace Elab\Lite\System;

use Elab\Lite\Services\Debug;
use Elab\Lite\Engine;
use Elab\Lite\Services\Database;

/**
 * Vertimo klasė.
 *
 * @author    kran
 * @date    2008-09-02
 * @package core
 */
final class Translator
{
    public static $language = null;
    private static $translation_entity = null;
    private static $cache = null;

    public static function set_page()
    {
        $translation_entity = self::get_translation_entity();
        $translation_entity->load_page();
        self::clear_cache();
    }

    /**
     * grąžina vertimų esybę, jei reikia sukuria (lazy initialization)
     *
     * @return unknown
     */
    private static function get_translation_entity()
    {
        if (empty(self::$translation_entity)) {
            self::$translation_entity = Engine::load_controller('entity', 'translations');
        }
        return self::$translation_entity;
    }

    private static function clear_cache()
    {
        self::$cache = null;
    }

    public static function get_label($key)
    {
        $result = self::translate($key);
        if ($key[0] == '-') {
            // kintamasis, todel nieko keisti nereikia.
        } elseif ($key == mb_strtoupper($key)) {
            // uppercase
            $result = mb_strtoupper($result);
        } elseif ($key == mb_strtolower($key)) {
            // lowercase
            $result = mb_strtolower($result);
        } elseif ($key[0] == mb_strtoupper($key[0])) {
            // ubfirst
            $result = mb_strtoupper(mb_substr($result, 0, 1)) . mb_substr($result, 1);
        }
        return $result;
    }

    public static function translate($key)
    {
        static $cache;

        if (empty(self::$language)) {
            if (!empty(Repository::$app->lang_key)) {
                $lang_key = Repository::$app->lang_key;
            }
            if (empty($lang_key)) {
                // nebuvom issprende sitos problemos?
                $path = explode('/', @$_GET['PATH_INFO']);
                $lang_key = (@$path[0] && strlen($path[0]) == 2) ? $path[0] : 'lt';
            }
            self::$language = $lang_key;
        }

        //$key = htmlspecialchars($key, ENT_QUOTES);

        self::get_cache();

        if (empty(self::$cache[self::$language][md5($key)])) {
            return $key;
        } else {
            return self::$cache[self::$language][md5($key)];
        }
    }

    /**
     * grąžina vertimų cache, jei reikia sukuria (lazy initialization)
     *
     * @return unknown
     */
    private static function get_cache()
    {
        if (!is_array(self::$cache)) {
            self::$cache = array();
        }
        $lang_key = self::$language;
        if (empty(self::$cache[$lang_key])) {
            self::$cache[$lang_key] = array();
            $values = Database::get_assoc_all("SELECT t.hash, tv.value, tv.html
										FROM lite_translations AS t
										LEFT JOIN lite_translations_values AS tv ON tv.translation_id=t.id
										WHERE tv.language='{$lang_key}'");

            foreach ($values as $value) {
                if ($value['html'] == 1) {
                    $value['value'] = html_entity_decode($value['value'], ENT_QUOTES);
                }
                self::$cache[$lang_key][$value['hash']] = $value['value'];
            }
        }
    }

    public static function get_phrase($key)
    {
        return self::translate($key);
    }

    public static function get_text($key)
    {
        return self::translate($key);
    }

    public static function get_field($key)
    {
        return self::translate($key);
    }

    /**
     * Enter description here...
     *
     * @return unknown
     * @deprecated unknown_type $key
     */
    public static function get_button($key)
    {
        Debug::debug('"button" vertimai yra deprecated. Reikia naudoti "label".');
        return self::translate(TRANSLATION_TYPE_BUTTON, $key);
    }

    /**
     * Enter description here...
     *
     * @return unknown
     * @deprecated unknown_type $key
     */
    public static function get_message($key)
    {
        Debug::debug('"message" vertimai yra deprecated. Reikia naudoti "phrase".');
        return self::translate(TRANSLATION_TYPE_MESSAGE, $key);
    }
}
