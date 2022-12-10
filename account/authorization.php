<?php
function authorization($data): void
{
    include_once 'JWT.php';
    $jwt = new JWT;
    $token = $jwt->get_token($data->email);

    include_once 'token_response.php';
    $token_response = new token_response($token);

    echo json_encode($token_response->get_token());
}