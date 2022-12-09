<?php
    function register($data){
        include_once 'user_register_model.php';
        $new_user = new user_register_model($data->body);
        $new_user->save();
    }