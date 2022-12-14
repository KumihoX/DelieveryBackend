<?php
function get_orders_list(){
    include_once 'account/JWT.php';
    $token = new JWT();
    if (!($token ->check_token())) {
        set_http_status(401, "Токен некорректен");
        exit;
    }

    $email = $token->get_email();

    $users_orders = $GLOBALS['link']->query("SELECT id FROM OrderTable WHERE user = '$email'")->fetch_all();

    include_once 'order_info_dto.php';
    $order_info = new order_info_dto();

    foreach ($users_orders as $value){
        $order_info->add_order($value[0]);
    }

    echo json_encode($order_info->get_data());
}