<?php
function logout(): void
{
    include_once 'JWT.php';
    $token = new JWT();
    if (!($token ->check_token())) {
        set_http_status(401, "Токен некорректен");
        exit;
    }
    $token->save_in_black_list();
    set_http_status(200, "Logged out");
}
