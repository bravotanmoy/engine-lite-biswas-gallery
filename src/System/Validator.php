<?php

namespace Elab\Lite\System;

/**
 * Klasė, skirta stringų validavimui.
 * @author kran
 * @date 2008-10-08, Tarptautinė diena prieš stichines nelaimes
 * @package core
 */
class Validator
{
    protected static $last_message;

    public static function get_last_message()
    {
        return self::$last_message;
    }

    public static function validates_not_empty($value, $validation_params = array())
    {
        if (preg_match('/^\s*$/', $value)) {
            self::$last_message = t('Laukas negali būti tuščias.');
            return false;
        }
        return true;
    }

    public static function validates_email($value, $validation_params = array())
    {
        if (!empty($value) && !self::check_email_address($value)) {
            self::$last_message = t('Nekorektiškas e-pašto adresas (%s).');
            return false;
        }
        return true;
    }

    /**
     * funkcija, validuojanti e-mail adresus
     * pasiskolinta is: http://www.ilovejackdaniels.com/php/email-address-validation/
     * @date 2008-05-20
     * @param unknown_type $email
     * @return unknown
     * @author kran
     *
     */
    public static function check_email_address($email)
    {
        // First, we check that there's one @ symbol, and that the lengths are right
        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }
        return true;
    }

    public static function validates_url($value, $validation_params = array())
    {
        // SCHEME
        $urlregex = "^(https?|ftp)\:\/\/";

        // USER AND PASS (optional)
        $urlregex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?";

        // HOSTNAME OR IP
        $urlregex .= "[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*";  // http://x = allowed (ex. http://localhost, http://routerlogin)
        //$urlregex .= "[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)+";  // http://x.x = minimum
        //$urlregex .= "([a-z0-9+\$_-]+\.)*[a-z0-9+\$_-]{2,3}";  // http://x.xx(x) = minimum
        //use only one of the above
        // PORT (optional)
        $urlregex .= "(\:[0-9]{2,5})?";
        // PATH  (optional)
        $urlregex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?";
        // GET Query (optional)
        $urlregex .= "(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?";
        // ANCHOR (optional)
        $urlregex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?\$";

        // check
        $result = preg_match("/$urlregex/i", $value);

        if (!empty($value) && !$result) {
            self::$last_message = t('Nekorektiškas url adresas.');
            return false;
        }
        return true;
    }

    public static function validates_number(&$value, $validation_params = array())
    {
        $value = str_replace(',', '.', $value);
        if (!empty($value) && !is_numeric($value)) {
            self::$last_message = t('Nekorektiškas skaičius.');
            return false;
        }
        if ($value === '') {
            $value = null;
        }
        return true;
    }

    public static function validates_positive_number($value, $validation_params = array())
    {
        if (!empty($value) && !(is_numeric($value) && ($value >= 0))) {
            self::$last_message = t('Nekorektiškas skaičius.');
            return false;
        }
        return true;
    }

    public static function validates_bool($value, $validation_params = array())
    {
        if (!in_array($value, array(1, 0, true, false))) {
            self::$last_message = t('Nekorektiška reikšmė.');
            return false;
        }
        return true;
    }

    /**
     *  Minimum Length
     *
     * @return    bool
     */
    public static function validates_min_length($value, $validation_params = array())
    {
        if (mb_strlen($value) < $validation_params[0]) {
            self::$last_message = sprintf(t('Turi būti įvesta bent %s simbolių.'), $validation_params[0]);
            return false;
        }
        return true;
    }

    /**
     * Max Length
     *
     * @return    bool
     */
    public static function validates_max_length($value, $validation_params = array())
    {
        if (mb_strlen($value) > $validation_params[0]) {
            self::$last_message = sprintf(t('Turi būti įvesta nedaugiau kaip %s simbolių.'), $validation_params[0]);
            return false;
        }
        return true;
    }

    /**
     * Exact Length
     *
     * @access    public
     * @return    bool
     */
    public static function validates_exact_length($value, $validation_params = array())
    {
        if (mb_strlen($value) != $validation_params[0]) {
            self::$last_message = sprintf(t('Turi būti lygiai %s simbolių.'), $validation_params[0]);
            return false;
        }
        return true;
    }

    /**
     * Alpha
     *
     * @return    bool
     */
    public static function validates_alpha($value, $validation_params = array())
    {
        if (!preg_match('/^([a-zA-Z])+$/i', $value)) {
            self::$last_message = t('Leistina naudoti tik lotyniškas raides.');
            return false;
        }
        return true;
    }

    /**
     * Alpha-numeric
     *
     * @return    bool
     */
    public static function validates_alpha_numeric($value, $validation_params = array())
    {
        if (!preg_match('/^([a-zA-Z0-9])+$/i', $value)) {
            self::$last_message = t('Leistina naudoti tik lotyniškas raides ir skaičius.');
            return false;
        }
        return true;
    }

    /**
     * Alpha-numeric with underscores and dashes
     *
     * @return    bool
     */
    public static function validates_alpha_numeric_dash($value, $validation_params = array())
    {
        if (!preg_match('/^([-a-zA-Z0-9_-])+$/i', $value)) {
            self::$last_message = t('Leistina naudoti tik lotyniškas raides, skaičius bei brūkšnius _ ir -.');
            return false;
        }
        return true;
    }

    public static function validates_alpha_dash($value, $validation_params = array())
    {
        if (!preg_match('/^([-a-zA-Z_])+$/i', $value)) {
            self::$last_message = t('Leistina naudoti tik lotyniškas raides bei brūkšnius _ ir -.');
            return false;
        }
        return true;
    }

    /**
     * Validuoja lietuviško mob. tel. nr: 370xxxyyyyy
     * @param $value
     * @param $validation_params
     * @return unknown_type
     */
    public static function validates_lithuanian_mobile_phone_number($value, $validation_params = null)
    {
        if (!preg_match('/^(8|\+370|370)(\d{8})$/', $value, $a)) {
            self::$last_message = t('Neteisingas lietuviško mobilaus telefono numeris. Teisingas formatas: 370yyyyyyyy, kur y-dešimtainiai skaitmenys.');
            return false;
        }
        return true;
    }

    /**
     * Validuoja reiksme pagal duota patterna
     * @param $value
     * @param $validation_params
     * @return unknown_type
     */
    public static function validate_pattern($value, $validation_params = null)
    {
        if (!preg_match($validation_params['pattern'], $value, $a)) {
            self::$last_message = !empty($validation_params['message']) ? $validation_params['message'] : t('Neteisingas formatas');
            return false;
        }
        return true;
    }

    public static function validates_time(&$value, $validation_params = array())
    {
        if ($value === '') {
            $value = null;
            return true;
        }

        if (!self::is_date_time_format($value, array('H:i', 'H:i:s'))) {
            self::$last_message = t('Nekorektiškas laiko formatas.');
            return false;
        }
        return true;
    }

    public static function is_date_time_format($value, $formats)
    {
        foreach ($formats as $format) {
            if ($value == date($format, strtotime($value))) {
                return true;
            }
        }
        return false;
    }

    public static function validates_sql_data_type(&$data, $type)
    {
        if (is_array($type)) {
            $type = $type[0];
        }
        if (preg_match('/ unsigned$/', $type)) {
            $type = preg_replace('/ unsigned$/', '', $type);
            $unsigned = true;
        } else {
            $unsigned = false;
        }

        // 1. Bendra validacija.

        /*
          // 1. 1. Ar laukas nera tuscias?
          if (preg_match('/^(tinyint)|(smallint)|(mediumint)|(int(eger)?)|(int(eger)?)|(decimal)|(float)|(double)/', $type)) {
          if (preg_match('/^\s*$/', $data)) {
          $data = null;
          return true;
          self::$last_message = t('Laukas negali būti tuščias.');
          // t("Neįvestas skaičius.");
          return false;
          }
          } */

        if (preg_match('/^\s*$/', $data)) {
            $data = null;
            return true;
        }

        // 1. 2. Sveiki skaičiai
        if (preg_match('/^(tinyint)|(smallint)|(mediumint)|(int(eger)?)|(int(eger)?)/', $type)) {
            if (!preg_match('/\-?\d+/', $data)) {
                self::$last_message = "'$data' " . t("nėra sveikasis skaičius.");
                return false;
            }
        }

        // 1. 3. Realieji skaiciai
        if (preg_match('/^(decimal)|(float)|(double)/', $type)) {
            $data = str_replace(',', '.', $data);
            if (!is_numeric($data)) {
                self::$last_message = "'$data' " . t("nėra skaičius.");
                return false;
            }
        }

        // 1. 4. Neneigiami skaičiai
        if (preg_match('/^(tinyint)|(smallint)|(mediumint)|(int(eger)?)|(int(eger)?)|(decimal)|(float)|(double)/', $type)) {
            if ($unsigned && ($data < 0)) {
                self::$last_message = t("Skaičius negali būti neigiamas.");
                return false;
            }
        }

        // 2. Validacija pagal duomenų tipus.

        if (preg_match('/^tinyint/', $type)) {
            $range = 255;
            $min = $unsigned ? 0 : -($range + 1) / 2;
            $max = $unsigned ? $range : ($range + 1) / 2 - 1;
            if (($data > $max) || ($data < $min)) {
                self::$last_message = t("Skaičius netelpa į ribas") . " [$min, $max].";
                return false;
            }
        } elseif (preg_match('/^smallint/', $type)) {
            $range = 65535;
            $min = $unsigned ? 0 : -($range + 1) / 2;
            $max = $unsigned ? $range : ($range + 1) / 2 - 1;
            if (($data > $max) || ($data < $min)) {
                self::$last_message = t("Skaičius netelpa į ribas") . " [$min, $max].";
                return false;
            }
        } elseif (preg_match('/^mediumint/', $type)) {
            $range = 16777215;
            $min = $unsigned ? 0 : -($range + 1) / 2;
            $max = $unsigned ? $range : ($range + 1) / 2 - 1;
            if (($data > $max) || ($data < $min)) {
                self::$last_message = t("Skaičius netelpa į ribas") . " [$min, $max].";
                return false;
            }
        } elseif (preg_match('/^int(eger)?/', $type)) {
            $range = 4294967295;
            $min = $unsigned ? 0 : -($range + 1) / 2;
            $max = $unsigned ? $range : ($range + 1) / 2 - 1;
            if (($data > $max) || ($data < $min)) {
                self::$last_message = t("Skaičius netelpa į ribas") . " [$min, $max].";
                return false;
            }
        } elseif (preg_match('/^bigint/', $type)) {
            $range = 18446744073709551615;
            $min = $unsigned ? 0 : -($range + 1) / 2;
            $max = $unsigned ? $range : ($range + 1) / 2 - 1;
            if (($data > $max) || ($data < $min)) {
                self::$last_message = t("Skaičius netelpa į ribas") . " [$min, $max].";
                return false;
            }
        } elseif (preg_match('/^decimal/', $type)) {
            $data = str_replace(',', '.', $data);
            $m = 10;
            $d = 0; // defaultinės ribos
            if (preg_match('/\((.+)\)/', $type, $matches)) {
                $limits = preg_split('/[, ]+/', $matches[1]);
                $m = $limits[0];
                if (isset($limits[1])) {
                    $d = $limits[1];
                }
            }
            // tikrinam kiek skaičių po kablelio
            if (preg_match('/\.(\d+)$/', $data, $matches)) {
                if (strlen($matches[1]) > $d) {
                    self::$last_message = t("Per daug skaitmenų po kablelio. Turi būti iki") . " $d.";
                    return false;
                }
            }
            // tikrinam kiek skaičių sveikojoje dalyje
            preg_match('/-?(\d+)(\.\d+)?$/', $data, $matches);
            if (strlen($matches[1]) > ($m - $d)) {
                self::$last_message = t("Maksimalus leistinas skaitmuo yra") . " " . str_repeat('9', $m - $d) . '.';
                return false;
            }
        } elseif (preg_match('/^(float)|(double)/', $type)) {
        } elseif (preg_match('/^year/', $type)) {
            if (!preg_match('/^\d{2}|\d{4}$/', $data) || !((($data >= 1901) && ($data <= 2155)) || ($data == '00' || $data == '0000') || (($data >= 0) && ($data <= 99)))) {
                self::$last_message = t("Neteisingas metų formatas.");
                return false;
            }
        } elseif (preg_match('/^datetime/', $type) || preg_match('/^timestamp/', $type)) {
            if (!self::validates_datetime($data)) {
                return false;
            }
        } elseif (preg_match('/^date/', $type)) {
            if (!self::validates_date($data)) {
                return false;
            }
        }
        return true;
    }

    public static function validates_datetime(&$value, $validation_params = array())
    {
        if ($value === '') {
            $value = null;
            return true;
        }

        if (!self::is_date_time_format($value, array('Y-m-d', 'Y-m-d H:i', 'Y-m-d H:i:s'))) {
            self::$last_message = t('Nekorektiškas datos-laiko formatas.');
            return false;
        }
        return true;
    }

    public static function validates_date(&$value, $validation_params = array())
    {
        // jei data neivesta, verciam i NULL
        if ($value === '') {
            $value = null;
            return true;
        }

        if (!self::is_date_time_format($value, array('Y-m-d'))) {
            self::$last_message = t('Nekorektiškas datos formatas.');
            return false;
        }
        return true;
    }
}
