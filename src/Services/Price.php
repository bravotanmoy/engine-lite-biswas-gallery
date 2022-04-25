<?php

namespace Elab\Lite\Services;

use Elab\Lite\System\Repository;

class Price
{
    private static $translations = array(
        'lt' => array(
            'units' => array(
                0 => 'nulis',
                1 => 'vienas',
                2 => 'du',
                3 => 'trys',
                4 => 'keturi',
                5 => 'penki',
                6 => 'šeši',
                7 => 'septyni',
                8 => 'aštuoni',
                9 => 'devyni',
                10 => 'dešimt',
                11 => 'vienuolika',
                12 => 'dvylika',
                13 => 'trylika',
                14 => 'keturiolika',
                15 => 'penkiolika',
                16 => 'šešiolika',
                17 => 'septyniolika',
                18 => 'aštuoniolika',
                19 => 'devyniolika',
            ),
            'tens' => array(
                0 => '',
                1 => '',
                2 => 'dvidešimt',
                3 => 'trisdešimt',
                4 => 'keturiasdešimt',
                5 => 'penkiasdešimt',
                6 => 'šešiasdešimt',
                7 => 'septyniasdešimt',
                8 => 'aštuoniasdešimt',
                9 => 'devyniadešimt',
            ),
            'hundreds' => array(
                0 => '',
                1 => 'šimtas',
                2 => 'du šimtai',
                3 => 'trys šimtai',
                4 => 'keturi šimtai',
                5 => 'penki šimtai',
                6 => 'šeši šimtai',
                7 => 'septyni šimtai',
                8 => 'aštuoni šimtai',
                9 => 'devyni šimtai',
            ),
            'thousands' => array(
                1 => 'tūkstantis',
                2 => 'tūkstančiai',
                10 => 'tūkstančių',
            ),
            'milions' => array(
                1 => 'milijonas',
                2 => 'milijonai',
                10 => 'milijonų',
            ),
            'litas' => array(
                'banknotes' => array(
                    0 => 'nulis litų',
                    1 => 'litas',
                    2 => 'litai',
                    10 => 'litų',
                ),
                'coins' => array(
                    1 => 'centas',
                    2 => 'centai',
                    10 => 'centų',
                ),
            ),
            'euras' => array(
                'banknotes' => array(
                    0 => 'nulis eurų',
                    1 => 'euras',
                    2 => 'eurai',
                    10 => 'eurų',
                ),
                'coins' => array(
                    1 => 'centas',
                    2 => 'centai',
                    10 => 'centų',
                ),
            ),
        ),
        'en' => array(
            'units' => array(
                0 => 'zero',
                1 => 'one',
                2 => 'two',
                3 => 'three',
                4 => 'four',
                5 => 'five',
                6 => 'six',
                7 => 'seven',
                8 => 'eight',
                9 => 'nine',
                10 => 'ten',
                11 => 'eleven',
                12 => 'twelve',
                13 => 'thirteen',
                14 => 'fourteen',
                15 => 'fifteen',
                16 => 'sixteen',
                17 => 'seventeen',
                18 => 'eighteen',
                19 => 'nineteen',
            ),
            'tens' => array(
                0 => '',
                1 => '',
                2 => 'twenty',
                3 => 'thirty',
                4 => 'forty',
                5 => 'fifty',
                6 => 'sixty',
                7 => 'seventy',
                8 => 'eighty',
                9 => 'ninety',
            ),
            'hundreds' => array(
                0 => '',
                1 => 'one hundred',
                2 => 'two hundreds',
                3 => 'three hundreds',
                4 => 'four hundreds',
                5 => 'five hundreds',
                6 => 'six hundreds',
                7 => 'seven hundreds',
                8 => 'eight hundreds',
                9 => 'nine hundreds',
            ),
            'thousands' => array(
                1 => 'one thousand',
                2 => 'thousands',
                10 => 'thousands',
            ),
            'milions' => array(
                1 => 'milion',
                2 => 'milions',
                10 => 'milions',
            ),
            'litas' => array(
                'banknotes' => array(
                    0 => 'zero litas',
                    1 => 'litas',
                    2 => 'litas',
                    10 => 'litas',
                ),
                'coins' => array(
                    1 => 'cent',
                    2 => 'cents',
                    10 => 'cents',
                ),
            ),
            'euras' => array(
                'banknotes' => array(
                    0 => 'zero euros',
                    1 => 'euro',
                    2 => 'euros',
                    10 => 'euros',
                ),
                'coins' => array(
                    1 => 'cent',
                    2 => 'cents',
                    10 => 'cents',
                ),
            ),
        ),
    );

    public static function format_simple($number, $currency)
    {
        $result = number_format($number, 2, ',', '');
        if ($currency) {
            $result .= " $currency";
        }
        return $result;
    }

    public static function format($number, $currency = 'auto', $rate = null, $format = true)
    {
        if (!$currency || $currency == 'auto') {
            $currency = isset($_SESSION['currency']) ? $_SESSION['currency'] :  (defined('CURRENCY') ? CURRENCY : 'EUR');
        }
        if (!empty($rate)) {
            $number *= $rate;
        }
        $price = number_format($number, 2, ',', '');
        $price = str_replace(',00', '', $price);
        $price = $price . " " . "€";
        return $price;
    }

    public static function format_unit($name, $price)
    {
        if ($name && $price && preg_match('/^((\d+) x )?([0-9.,]+)\s+(kg|g|ml|l|but\.?|amp\.?|kaps\.?|tab\.?|pak\.?|vnt\.?)$/', $name, $matches)) {
            $k = $matches[2] ? $matches[2] : 1;
            $number = str_replace(',', '.', $matches[3]);
            $unit = $matches[4];
            $number *= $k;
            if (!$number) {
                return false;
            }
            $unit_price = $price / $number;
            $unit_size = 1;
            if ($unit_price < 0.2) {
                $unit_size = 100;
                $unit_price *= $unit_size;
            }
            return sprintf(t('%s už %s'), self::format($unit_price), "$unit_size " . trim($unit));
        }
        return false;
    }

    /**
     * SUM IN WORDS
     */
    public static function sum_in_words($sum, $currency ='euras', $lang_key = 'lt')
    {
        return self::banknotes_to_words(floor($sum), $currency, $lang_key) . ' ' . self::coins_to_words(round($sum * 100) % 100, $currency, $lang_key);
    }

    private static function number_in_words($n, $lang_key)
    {
        return self::milions($n, $lang_key);
    }

    private static function banknotes_to_words($sum, $currency, $lang_key)
    {
        $result = '';
        if (!$sum) {
            $result = self::$translations[$lang_key][$currency]['banknotes'][0];
        } else {
            $result = self::number_in_words($sum, $lang_key);
            $key = 2;

            if (($sum % 10 == 1) && ($sum % 100 != 11)) {
                $key = 1;
            } elseif (($sum % 10 == 0) || (($sum % 100 >= 10) && ($sum % 100 < 20))) {
                $key = 10;
            }
            $result .= ' ' . self::$translations[$lang_key][$currency]['banknotes'][$key];
        }
        return $result;
    }

    public static function coins_to_words($sum, $currency, $lang_key)
    {
        $result = self::tens($sum, $lang_key);
        $key = 2;

        if (($sum % 10 == 1) && ($sum % 100 != 11)) {
            $key = 1;
        } elseif (($sum % 10 == 0) || (($sum % 100 >= 10) && ($sum % 100 < 20))) {
            $key = 10;
        }
        $result .= ' ' . self::$translations[$lang_key][$currency]['coins'][$key];
        return $result;
    }

    private static function units($n, $lang_key)
    {
        if (($n % 10 == 0) && ($n != 0)) {
            return "";
        }
        return self::$translations[$lang_key]['units'][$n % 10];
    }

    private static function tens($n, $lang_key)
    {
        $n %= 100;
        if ($n >= 10 && $n <= 19) {
            return self::$translations[$lang_key]['units'][$n];
        } else {
            return self::$translations[$lang_key]['tens'][floor($n / 10)] . ' ' . self::units($n, $lang_key);
        }
    }

    private static function hundreds($n, $lang_key)
    {
        $n %= 1000;
        return self::$translations[$lang_key]['hundreds'][floor($n / 100)] . ' ' . self::tens($n, $lang_key);
    }

    private static function thousands($n, $lang_key)
    {
        $n %= 1000000;
        $th = floor($n / 1000);
        $result = '';

        if ($th) {
            $result = self::hundreds($th, $lang_key);
            if (($th % 10 == 1) && ($th % 100 != 11)) {
                $result .= ' ' . self::$translations[$lang_key]['thousands'][1] . ' ';
            } elseif (($th % 10 == 0) || (($th % 100 >= 10) && ($th % 100 < 20))) {
                $result .= ' ' . self::$translations[$lang_key]['thousands'][10] . ' ';
            } else {
                $result .= ' ' . self::$translations[$lang_key]['thousands'][2] . ' ';
            }
        }

        $result .= self::hundreds($n, $lang_key);
        return $result;
    }

    private static function milions($n, $lang_key)
    {
        $n %= 1000000000;
        $milion = floor($n / 1000000);
        $result = '';

        if ($milion) {
            $result = self::hundreds($milion, $lang_key);

            if (($milion % 10 == 1) && ($milion % 100 != 11)) {
                $result .= ' ' . self::$translations[$lang_key]['milions'][1] . ' ';
            } elseif (($milion % 10 == 0) || (($milion % 100 >= 10) && ($milion % 100 < 20))) {
                $result .= ' ' . self::$translations[$lang_key]['milions'][10] . ' ';
            } else {
                $result .= ' ' . self::$translations[$lang_key]['milions'][2] . ' ';
            }
        }

        $result .= self::thousands($n, $lang_key);
        return $result;
    }
}
