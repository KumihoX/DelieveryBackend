<?php
    header('Content-type: application/json');
    include_once 'router.php';
    global $link;

    $link = new mysqli("127.0.0.1", "delivery_user", "12345678", "delivery_db");

    function get_method(){
        return $_SERVER['REQUEST_METHOD'];
    }

    function get_address(){
        $url = isset($_GET['q']) ? $_GET['q'] : '';
        $url = rtrim($url, '/');
        return explode('/', $url);
    }

    function get_data(){
        $data = new stdClass();
        $data -> params = [];
        $data_get = $_GET;

        foreach ($data_get as $key => $value){
            if ($key != 'q'){
                $data->params[$key] = $value;
            }
        }

        $data -> body = json_decode(file_get_contents('php://input'));

        return $data;
    }

    $address = array_slice(get_address(), 1);
    $data = get_data();
    $method = get_method();
    if ($address != null){
        route($method, $address, $data);
    }


