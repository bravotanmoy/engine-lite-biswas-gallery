<?php

namespace Elab\Lite\Services;

use Elab\Lite\System\Repository;

class Debug
{
    /**
     * Reikia zinoti, kad visur pries kreipiantis i debug() funkcija, pries tai reikia patikrinti, ar ijungtas debuginim'as:
     * if (get_debug_mode()){
     *    debug("");
     * }
     *
     */
    public static function debug($var, $force = false)
    {
        if ($force || self::get_debug_mode() > 0) {
            if (php_sapi_name() != 'cli') {
                echo "<pre class=\"debug\">";
            } else {
                //echo "\n----\n";
            }
            if ($var === false) {
                echo "false\n";
            } elseif ($var === true) {
                echo "true\n";
            } elseif ($var === null) {
                echo "null\n";
            } else {
                print_r($var);
                echo "\n";
            }
            if (php_sapi_name() != 'cli') {
                echo "</pre>";
            } else {
                //echo "\n----\n";
            }
        }
    }

    /**
     * Isvalo buferi, isveda info apie kintamaji, ir baigia darba. Matysim tik ta info, ir jokio pasalinio kontento.
     *
     * @param unknown_type $var
     */
    public static function debug_exit($var, $force = false)
    {
        if (self::get_debug_mode() > 0) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            self::debug($var, $force);
            exit;
        }
    }

    /**
     * grazina debug'o lygi, jei 0 - isjungta.
     * Reikia zinoti, kad visur pries kreipiantis i debug() funkcija, pries tai reikia patikrinti, ar ijungtas debuginim'as:
     * if (get_debug_mode()){
     * 	debug("");
     * }
     *
     * @return unknown
     */
    public static function get_debug_mode()
    {
        return isset(Repository::$debug_mode) ? Repository::$debug_mode : (defined('DEBUG_MODE') ? DEBUG_MODE : 0);
    }

    public static function print_trace($output = true, $html = null, $skip=1)
    {
        if (!isset($html)) {
            $html = php_sapi_name() != 'cli';
        }
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $id = self::generate_trace_id();
        $result = '';
        if ($html) {
            $result .= "<div class='trace' id='trace_$id'><div>Stack Trace:</div>";
        }
        $trace = array_slice($trace, $skip);
        $path = getcwd();
        foreach ($trace as $key => $item) {
            if ($html) {
                echo "<div class='trace_item' id='trace_item_{$id}_{$key}'>";
            }
            $line = str_pad('#'.($key+1), '4', ' ', STR_PAD_RIGHT);
            if (isset($item['class'], $item['type'])) {
                $line .= "$item[class]$item[type]";
            }
            if (isset($item['function'])) {
                $line .= "$item[function]()";
            }
            if (!empty($item['file'])) {
                if (!$html) {
                    $line = str_pad($line, 60, ' ', STR_PAD_RIGHT);
                }
                $file = strpos($item['file'], $path) === 0 ? substr($item['file'], strlen($path)+1) : $item['file'];
                $line .= "$file:$item[line]";
            }
            $result .= $line;
            if (!$html) {
                $result .="\n";
            }
            if ($html) {
                $result .= '</div>';
            }
        }
        if ($html) {
            $result .= '</div>';
        }
        if ($output) {
            echo $result;
        } else {
            return $result;
        }
    }

    private static function generate_trace_id()
    {
        list($usec, $sec) = explode(" ", microtime());
        $result = ((float) $usec + (float) $sec);
        $result *= 100;
        return $result;
    }
}
