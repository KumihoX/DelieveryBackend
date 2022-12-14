<?php
function get_order_info($order_id){
    include_once 'account/JWT.php';
    $token = new JWT();
    if (!($token ->check_token())) {
        set_http_status(401, "Токен некорректен");
        exit;
    }

    $email = $token->get_email();

    include_once 'order_dto.php';
    $order_info = new order_dto($order_id, $email);

    echo json_encode($order_info->get_order_info());
}