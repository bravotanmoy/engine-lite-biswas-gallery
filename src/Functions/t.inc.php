<?php

function t($key)
{
    $result = \Elab\Lite\System\Translator::translate($key);

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
