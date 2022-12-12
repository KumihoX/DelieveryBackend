<?php
function get_profile(): void
{
    include_once 'JWT.php';
    $token = new JWT();
    if (!($token ->check_token())) {
        set_http_status(401, "Токен некорректен");
        exit;
    }

    $email = $token->get_email();

    include_once 'user_dto.php';
    $user = new user_dto($email);
    $user_data = $user->get_data();
    set_http_status();
    echo json_encode($user_data);
}

function put_profile($data): void
{
    include_once 'JWT.php';
    $token = new JWT();
    if (!($token ->check_token())) {
        set_http_status(401, "Токен некорректен");
        exit;
    }

    $email = $token->get_email();

    include_once 'user_edit_model.php';
    $edits = new user_edit_model($data->body);
    $edits->save($email);
    set_http_status(200, "Changes applied");
}
