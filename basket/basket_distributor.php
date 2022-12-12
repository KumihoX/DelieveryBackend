<?php
function basket_controller($method, $address, $data){
    switch ($method) {
        case 'POST':
            break;
        case 'GET':
            include_once "get_basket.php";
            get_basket();
            break;
        case 'DELETE':
            break;
    }
}
