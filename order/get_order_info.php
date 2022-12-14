<?php
function get_order_info($order_id){

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

    include_once 'order_dto.php';
    $order_info = new order_dto($order_id, $email);

    set_http_status();
    echo json_encode($order_info->get_order_info());
}