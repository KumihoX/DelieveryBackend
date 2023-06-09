<?php
    function route($method, $address, $data): void
    {
        switch($address[0]) {
            case 'account':
                include_once 'account/user_distributor.php';
                user_controller($method, $address, $data);
                break;

            case 'basket':
                include_once 'basket/basket_distributor.php';
                basket_controller($method, $address, $data);
                break;

            case 'dish':
                include_once 'dish/dish_distributor.php';
                dish_controller($method, $address, $data);
                break;

            case 'order':
                include_once 'order/order_distributor.php';
                order_controller($method, $address, $data);
                break;

            default:
                set_http_status(404, "This no such path as 'api/$address[0]'");
                break;
        }
    }
