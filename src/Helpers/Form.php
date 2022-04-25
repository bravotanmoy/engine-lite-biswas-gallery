<?php

namespace Elab\Lite\Helpers;

class Form
{
    public static function fix_checkboxes()
    {
        if ($numargs = func_num_args()) {
            $arg_list = func_get_args();
        } else {
            return false;
        }
        for ($i = 0; $i < $numargs; $i++) {
            if (isset($_POST[$arg_list[$i]])) {
                if (is_array($_POST[$arg_list[$i]])) {
                    // ...
                } elseif (!empty($_POST[$arg_list[$i]]) && !preg_match('/^\s*$/', $_POST[$arg_list[$i]])) {
                    $_POST[$arg_list[$i]] = 1;
                } else {
                    $_POST[$arg_list[$i]] = 0;
                }
            } else {
                $_POST[$arg_list[$i]] = 0;
            }
        }
    }

    public static function fix_array(&$array)
    {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                self::fix_array($array[$k]);
            } else {
                $v = trim($v);
                if (!preg_match('/^\s*$/', $v)) {
                    $array[$k] = htmlspecialchars($array[$k], ENT_QUOTES);
                    $array[$k] = addslashes($array[$k]);
                } else {
                    $array[$k] = "";
                }
            }
        }
    }

    public static function fix_post()
    {
        if ($numargs = func_num_args()) {
            $arg_list = func_get_args();
        } else {
            // jei nepaduoti lauku pavadinimai, fix'inam visa $_POST'a
            $arg_list = array_keys($_POST);
            $numargs = count($arg_list);
        }
        for ($i = 0; $i < $numargs; $i++) {
            if (isset($_POST[$arg_list[$i]])) {
                if (is_array($_POST[$arg_list[$i]])) {
                    self::fix_array($_POST[$arg_list[$i]]);
                } else {
                    $_POST[$arg_list[$i]] = trim($_POST[$arg_list[$i]]);
                    if (!preg_match('/^\s*$/', $_POST[$arg_list[$i]])) {
                        $_POST[$arg_list[$i]] = htmlspecialchars($_POST[$arg_list[$i]], ENT_QUOTES);
                    //$_POST[$arg_list[$i]] = addslashes($_POST[$arg_list[$i]]);
                    } else {
                        $_POST[$arg_list[$i]] = "";
                    }
                }
            } else {
                $_POST[$arg_list[$i]] = "";
            }
        }
    }

    public static function form_requested($state, $method = false)
    {
        if ($method === false) {
            $method = $_POST;
        }
        if (!isset($method['state']) && isset($method['busena'])) {
            $method['state'] = $method['busena'];
        }
        return (isset($method['state']) && ($method['state'] == $state));
    }
}
