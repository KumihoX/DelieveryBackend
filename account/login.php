<?php
function login($data) {
    include_once 'login_credentials.php';
    $login_data = new login_credentials($data->body);
    $login_data-> check_data();
}
