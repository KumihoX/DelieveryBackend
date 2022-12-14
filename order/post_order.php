<?php
function post_order($data)
{
    include_once 'order_create_dto.php';
    $order_create = new order_create_dto($data);

    include_once 'account/JWT.php';
    $token = new JWT();
    if (!($token ->check_token())) {
        set_http_status(401, "Токен некорректен");
        exit;
    }

    $email = $token->get_email();

    include_once 'UUID.php';
    $uuid = new UUID();
    $uuid = $uuid->get_uuid();

    $order_create->save($email, $uuid);
}
