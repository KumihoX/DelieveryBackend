<?php

function order_delivery($order_id){
    include_once 'account/JWT.php';
    $token = new JWT();
    if (!($token ->check_token())) {
        set_http_status(401, "Токен некорректен");
        exit;
    }

    $email = $token->get_email();

    $GLOBALS['link']->query("UPDATE OrderTable SET status = 'Доставлен' WHERE user = '$email' AND id = '$order_id'");
}
