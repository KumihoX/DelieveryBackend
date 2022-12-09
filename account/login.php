<?php
function login($data) {
    include_once 'login_credentials.php';
    $login_data = new login_credentials($data->body);

    if ($login_data-> user_exist()) {
        include_once 'authorization.php';
        authorization($data->body);
    }
}
