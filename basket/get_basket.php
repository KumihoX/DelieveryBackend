<?php
function get_basket(): void
{
    include_once 'account/JWT.php';
    $token = new JWT();
    if (!($token ->check_token())) {
        set_http_status(401, "Токен некорректен");
        exit;
    }

    $email = $token->get_email();

    include_once "dish_basket_dto.php";
    $data = $GLOBALS['link']->query("SELECT  dish, amount
        FROM Basket WHERE user = '$email'")->fetch_all();

    $dishes_in_basket = array();
    foreach ($data as $value) {
        $basket = new dish_basket_dto($value[0], $value[1]);
        $dishes_in_basket[] = $basket->get_data();
    }

    echo json_encode($dishes_in_basket);
}