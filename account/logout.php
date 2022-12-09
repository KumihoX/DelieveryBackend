<?php
function logout(): void
{
    include_once 'get_token_from_header.php';
    $token = get_token();

    include_once 'JWT.php';
    $jwt = new JWT;
    $token_is_valid = $jwt->is_valid($token);

    if ($token_is_valid) {
        $jwt->save_in_black_list($token);
    }
    else {
       echo "Token died";
    }
}
