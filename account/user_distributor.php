<?php
    function user_distributor($method, $address, $data): void
    {
        switch ($method) {
            case 'POST':
                switch ($address[1]){
                    case 'register':
                        include_once 'register.php';
                        register($data);
                        break;
                    case 'login':
                        include_once 'login.php';
                        login($data);
                        break;
                    case 'logout':
                        include_once 'logout.php';
                        logout();
                        break;
                    default:
                        break;
                }
                break;
            case 'GET':
                include_once 'profile.php';
                get_profile();
                break;
            case 'PUT':
                include_once 'profile.php';
                put_profile($data);
                break;
        }
    }