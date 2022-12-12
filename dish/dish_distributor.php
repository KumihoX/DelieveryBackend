<?php
function dish_controller($method, $address, $data){
    switch ($method) {
        case 'POST':
            switch ($address[3]) {
                case 'rating':
                    break;

                default:
                    set_http_status(404, "This no such path as 'api/dish'");
                    break;
            }
        case 'GET':
            if (count($address) == 1)
            {
                include_once 'dishes_page.php';
                get_dishes_list($data->params);
            }
            else
            {
                if (is_null($address[2]))
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
                else{
                    if (is_null($address[3]) && $address[2] = 'rating')
                    {
                        //api/dish/{id}/rating
                    }
                    else if ($address[2] = 'rating' && $address[3] = 'check')
                    {
                        //api/dish/{id}/rating/check
                    }
                    else
                    {
                        //Ошибочка вышла
                    }
                }
            }
            break;
    }
}