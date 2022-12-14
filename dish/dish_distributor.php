<?php
function dish_controller($method, $address, $data){
    switch ($method) {
        case 'POST':
            if (count($address) != 3) {
                set_http_status(404, "This no such path");
                return;
            }
            if (!preg_match($GLOBALS['uuid_pattern'], $address[1]) || !($address[2] == 'rating')) {
                set_http_status(404, "This no such path as 'api/$address[0]/$address[1]/$address[2]'");
                return;
            }
            include_once 'post_rating.php';
            post_rating($address[1], $data->params);
            break;

        case 'GET':
            if (count($address) == 1)
            {
                include_once 'dishes_page.php';
                get_dishes_list($data->params);
            }
            else
            {
                if (count($address) == 2)
                {
                    if (preg_match($GLOBALS['uuid_pattern'],$address[1])){
                        include_once "get_dish.php";
                        get_dish($address[1]);
                    }
                    else {
                        set_http_status(400, "Incorrect UUID");
                        exit;
                    }
                }

                else if (count($address) == 4)
                {
                    if ($address[2] = 'rating' && $address[3] = 'check')
                    {
                        include_once 'check_rating.php';
                        check_rating($address[1]);
                    }
                    else
                    {
                        set_http_status(404, "This no such path as 
                        'api/$address[0]/$address[1]/$address[2]/$address[3]' with $method");
                        exit;
                    }
                }
                else
                {
                    set_http_status(404, "This no such path");
                }
            }
            break;
    }
}