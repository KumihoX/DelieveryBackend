<?php

function order_delivery($order_id): void
{
    $order_exist = $GLOBALS['link']->
    query("SELECT id FROM OrderTable WHERE id = '$order_id'")->fetch_assoc();

    if (is_null($order_exist)){
        set_http_status(404, "Такого заказа не существует");
        exit;
    }

    include_once 'account/JWT.php';
    $token = new JWT();
    if (!($token ->check_token())) {
        set_http_status(401, "Токен некорректен");
        exit;
    }

    $email = $token->get_email();

    $GLOBALS['link']->query("UPDATE OrderTable SET status = 'Доставлен' WHERE user = '$email' AND id = '$order_id'");
    set_http_status(200, "Статус заказа $order_id изменен на 'Доставлен'");
}
