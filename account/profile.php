<?php
function get_profile(){
    include_once 'JWT.php';
    $token = new JWT();
    if (!($token ->check_token())) {
        throw new Exception('Неправильный токен авторизации');
    }

    $email = $token->get_email();

    include_once 'user_dto.php';
    $user = new user_dto($email);
    $user_data = $user->get_data();
    echo json_encode($user_data);
}

function put_profile($data)
{
    include_once 'JWT.php';
    $token = new JWT();
    if (!($token ->check_token())) {
        throw new Exception('Неправильный токен авторизации');
    }

    $email = $token->get_email();

    include_once 'user_edit_model.php';
    $edits = new user_edit_model($data->body);
    $edits->save($email);
}
