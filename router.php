<?php
    function route($method, $address, $data): void
    {
        switch($address[0]) {
            case 'account':
                include_once 'account/user_distributor.php';
                user_distributor($method, $address, $data);
                break;

            case 'basket':
                include_once 'basket/basket_distributor.php';
                break;

            case 'dish':
                include_once 'dish/dish_distributor.php';
                break;

            case 'order':
                include_once 'order/order_distributor.php';
                break;

            default:
                break;
        }
    }
