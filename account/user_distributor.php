<?php
    function user_controller($method, $address, $data){
        switch ($method) {
            case 'POST':
                switch ($address[1]){
                    case 'register':
                        include_once 'register.php';
                        register($data);
                        break;
                    case 'login':
                        include_once 'login.php';
                        break;
                    case 'logout':
                        include_once 'logout.php';
                        break;
                    default:
                        break;
                }
                break;
            case 'GET':
                break;
            case 'PUT':
                break;
        }
    }