<?php
    function register($data){
        $newUser = new user_register_model($data->body);
        $newUser->save();
    }