<?php

namespace Elab\Lite\Services;

use Elab\Lite\System\Repository;

class Response
{
    public static function redirect($params, $code = '302')
    {
        Repository::$db->close();
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        $url = is_array($params) ? $params['url'] : $params;
        if (is_array($params) && @$_GET['display']) {
            // --> is $url pasalinam '?display=...'
            if (preg_match('/\?(.*)$/', $url, $m)) {
                parse_str($m[1], $query_data);
                unset($query_data['display']);
                $query_string = http_build_query($query_data);
                $url = preg_replace('/\?(.*)$/', '?' . $query_string, $url);
            } // <---
            header('Content-type: application/json');
            echo json_encode(array(
                'url' => $url,
                'reload' => @$params['reload'] ? true : false,
                'params' => $params,
            ));
            die();
        }
        if (@$_GET['display']) {
            // jei atnaujiname puslapio dali su ajax'u, mum nereikia toje vietoje viso puslapio.
            // Jei nepaduotas ?display=..., irasom pagal $_GET['display']
            preg_match('/^(.*?)(?:\?(.*?))?(#.*)?$/', $url, $m);
            $base_url = $m[1];
            $query_string = @$m[2] ?: '';
            $hash = @$m[3] ?: '';
            parse_str($query_string, $query_data);

            $query_data['display'] = @$query_data['display'] ?: $_GET['display'];
            $query_string = http_build_query($query_data);
            $url = $base_url . '?' . $query_string . $hash;
        }
        if ($code == '301') {
            Header("HTTP/1.1 301 Moved Permanently");
        }
        header("Location: $url");
        exit;
    }
}
