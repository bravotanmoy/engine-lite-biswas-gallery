<?php

function dd()
{
    $_ = func_get_args();
    call_user_func_array(array('Kint', 'dump'), $_);
    die;
}
