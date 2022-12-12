<?php
function delete_basket($dish_id)
{
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
        set_http_status(404, "Такого блюда нет в корзине");
    }
    else
    {
        if ($count['amount'] == '1')
        {
            $GLOBALS['link']->query("DELETE FROM Basket WHERE (dish = '$dish_id' AND user = '$email')");
        }
        else
        {
            $GLOBALS['link']->query("UPDATE Basket SET amount = amount - 1 WHERE (dish = '$dish_id' AND user = '$email')");
        }
        set_http_status(200, "Amount reduced");
    }
}