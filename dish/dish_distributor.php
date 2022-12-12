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
                if (is_null($address[3]))
                {
                    //api/dish/{id}
                }
                else{
                    if (is_null($address[4]) && $address[3] = 'rating')
                    {
                        //api/dish/{id}/rating
                    }
                    else if ($address[3] = 'rating' && $address[4] = 'check')
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