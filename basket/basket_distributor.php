<?php
function basket_controller($method, $address, $data){
    switch ($method) {
        case 'GET':
            if (count($address) != 1){
                set_http_status(404, "This no such path");
                return;
            }
            include_once "get_basket.php";
            get_basket();
            break;

        case 'POST':
            if (count($address) == 3 && $address[1] == "dish")
            {
                if (preg_match($GLOBALS['uuid_pattern'],$address[2]))
                {
                    include_once "post_basket.php";
                    post_basket($address[2]);
                }
                else
                {
                    set_http_status(400, "Incorrect UUID");
                    exit;
                }
            }
            else
            {
                set_http_status(404, "This no such path");
                break;
            }
            break;

        case 'DELETE':
            if (count($address) == 3 && $address[1] == "dish")
            {
                if (preg_match($GLOBALS['uuid_pattern'],$address[2]))
                {
                    include_once "delete_basket.php";
                    delete_basket($address[2], $data->params);
                }
                else
                {
                    set_http_status(400, "Incorrect UUID");
                    exit;
                }
            }
            else
            {
                set_http_status(404, "This no such path");
                break;
            }
            break;
    }
}
