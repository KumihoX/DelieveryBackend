<?php
function post_basket($dish_id): void
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

    $count = $GLOBALS['link']->
    query("SELECT amount FROM Basket WHERE (user = '$email' AND dish = '$dish_id')")->fetch_assoc();

    if (is_null($count))
    {
        $GLOBALS['link']->query("INSERT Basket (amount, user, dish)
        values (
            '1',
            '$email',
            '$dish_id'
        )");
        set_http_status(200, "Блюдо добавлено в корзину");
    }
    else
    {
        $GLOBALS['link']->query("UPDATE Basket SET amount = amount + 1 WHERE (dish = '$dish_id' AND user = '$email')");
        set_http_status(200, "Количество увеличено");
    }

}