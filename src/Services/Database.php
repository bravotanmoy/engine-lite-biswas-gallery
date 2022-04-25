<?php

namespace Elab\Lite\Services;

use Elab\Lite\System\Repository;

class Database
{
    public static function get_all_first($query, $key = false)
    {
        $q = self::query($query);
        $rows = array();
        while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
            if ($key) {
                $rows[$row[$key]] = reset($row);
            } else {
                $rows[] = reset($row);
            }
        }
        return $rows;
    }

    public static function query($query)
    {
        $db = Repository::$db;
        $t1 = microtime(true);
        $result = $db->query($query);
        $t2 = microtime(true);
        $diff = $t2 - $t1;
        Repository::$mysql_debug_total_time += ($t2 - $t1);
        Repository::$mysql_debug_total_queries++;
        return $result;
    }

    public static function get_array($query)
    {
        return mysqli_fetch_array(Database::query($query));
    }

    public static function get_assoc($query)
    {
        $q = self::query($query);
        return $q->fetch_assoc();
    }

    public static function get_assoc_all($query, $key = false)
    {
        $q = self::query($query);
        $rows = array();
        while ($row = mysqli_fetch_array($q, MYSQLI_ASSOC)) {
            if ($key) {
                $rows[$row[$key]] = $row;
            } else {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    public static function get_first($query)
    {
        if ($row = mysqli_fetch_array(Database::query($query))) {
            return $row[0];
        } else {
            return false;
        }
    }

    public static function real_escape_string($str)
    {
        return Repository::$db->real_escape_string($str);
    }
}
