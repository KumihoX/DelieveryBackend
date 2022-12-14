<?php
function order_controller($method, $address, $data): void
{
    switch ($method) {
        case 'POST':
            if (count($address) == 1)
            {
                include_once "post_order.php";
                post_order($data->body);
            }
            else if (count($address) == 3 && preg_match($GLOBALS['uuid_pattern'],$address[1]) && ($address[2] == 'status'))
            {
                include_once 'order_delivery.php';
                order_delivery($address[1]);
            }
            else
            {
                set_http_status(404, "This no such path as 'api/$address[0]...'");
            }
            break;
        case 'GET':
            if (count($address) == 1)
            {
                include_once "get_orders_list.php";
                get_orders_list();
            }
            else if (count($address) == 2 && preg_match($GLOBALS['uuid_pattern'],$address[1])){
                include_once "get_order_info.php";
                get_order_info($address[1]);
            }
            else
            {
                set_http_status(404, "This no such path as 'api/$address[0]...'");
            }
            break;
    }
}