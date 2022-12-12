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
                        set_http_status(404, "This no such path as 'api/$address[0]/$address[1]' with $method");
                        break;
                }
                break;
            case 'GET':
                switch ($address[1])
                {
                    case 'profile':
                        include_once 'profile.php';
                        get_profile();
                        break;

                    default:
                        set_http_status(404, "This no such path as 'api/$address[0]/$address[1]' with $method");
                        break;
                }
                break;
            case 'PUT':
                switch ($address[1])
                {
                    case 'profile':
                        include_once 'profile.php';
                        put_profile($data);
                        break;

                    default:
                        set_http_status(404, "This no such path as 'api/$address[0]/$address[1]' with $method");
                        break;
                }
        }
    }