<?php
function post_rating($dish_id, $params){
    $rating = $params['ratingScore'];

    if (is_null($rating)){
        set_http_status(400, "Рейтинг не задан");
        exit;
    }

    if ($rating > 10){
        set_http_status(400, "Рейтинг должен быть меньше или равен 10");
        exit;
    }

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

    $user_rating = $GLOBALS['link']->query
    ("SELECT rating FROM Rating WHERE dish = '$dish_id' and user = '$email'")->fetch_assoc();

    if (is_null($user_rating)) {
        $GLOBALS['link']->query
        ("INSERT Rating (rating, user, dish) values('$rating', '$email', '$dish_id')");
        set_http_status(200, "Рейтинг добавлен");
    }
    else{
        $GLOBALS['link']->query
        ("UPDATE Rating SET rating = '$rating' WHERE user = '$email' AND dish = '$dish_id'");
        set_http_status(200, "Рейтинг обновлен");
    }
}