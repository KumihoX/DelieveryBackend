<?php
function get_token() : string
{
    $headers = getallheaders();
    $auth = $headers['Authorization'];
    $auth_list = explode(' ', $auth);
    return $auth_list[1];
}
