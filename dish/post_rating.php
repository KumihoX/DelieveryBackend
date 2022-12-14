<?php
function post_rating($dish_id, $params){
    $rating = $params['rating'];

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
    }
    else{
        $GLOBALS['link']->query
        ("UPDATE Rating SET rating = '$rating' WHERE user = '$email' AND dish = '$dish_id'");
    }
}