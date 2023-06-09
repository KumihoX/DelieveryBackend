<?php
function check_rating($dish_id): void
{
    $dish_exist = $GLOBALS['link']->
    query("SELECT name FROM Dish WHERE id = '$dish_id'")->fetch_assoc();

    if (is_null($dish_exist)){
        set_http_status(404, "Такого блюда не существует");
        exit;
    }

    include_once 'account/JWT.php';
    $token = new JWT();
    if (!($token ->check_token())) {
        set_http_status(401, "Токен некорректен");
        exit;
    }

    $email = $token->get_email();

    $rating_exist = $GLOBALS['link']->query
    ("SELECT rating FROM Rating WHERE dish = '$dish_id' AND user = '$email'") -> fetch_assoc();

    if (is_null($rating_exist)){
        set_http_status();
        echo json_encode(false);
    }

    else{
        set_http_status();
        echo json_encode(true);
    }
}