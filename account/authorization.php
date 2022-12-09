<?php
function authorization($data): void
{
    include_once 'JWT.php';
    $jwt = new JWT;
    $token = $jwt->generate(['email' => $data->email]);

    echo json_encode(
        array(
            //"message" => 'Вы в системе, мои поздравления!',
            "token" => $token
        )
    );
}