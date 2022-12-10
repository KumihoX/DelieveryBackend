<?php
function logout(): void
{
    include_once 'JWT.php';
    $token = new JWT();
    if (!($token ->check_token())) {
        throw new Exception('Неправильный токен авторизации');
    }
    $token->save_in_black_list();
}
