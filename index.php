<?php
    include_once  "headers.php";

    header('Content-type: application/json');
    include_once 'router.php';
    global $link;
    global $uuid_pattern;

    $link = new mysqli("127.0.0.1", "delivery_user", "12345678", "delivery_db");
    $uuid_pattern = "/[0-9a-f]{8}-[0-9a-f]{4}-[0-5][0-9a-f]{3}-[089ab][0-9a-f]{3}-[0-9a-f]{12}/";

    function get_method(){
        return $_SERVER['REQUEST_METHOD'];
    }

    function get_address(): array
    {
        $url = $_GET['q'] ?? '';
        $url = rtrim($url, '/');
        return explode('/', $url);
    }

    function get_data(): stdClass
    {
        $data = new stdClass();
        $data -> params = [];

        $list_of_params = [];
        $params = explode('&',$_SERVER['QUERY_STRING']);
        foreach ($params as $key => $value){
            $param = explode('=', $value);
            $param_name = $param[0];
            $param_val = $param[1];

            if (!isset($list_of_params[$param_name])){
                $list_of_params[$param_name] = [];
            }
            array_push($list_of_params[$param_name], $param_val);
        }

        foreach ($list_of_params as $key => $value) {
            if (count($value) == 1) {
                $data->params[$key] = $value[0];
                continue;
            }
            $data->params[$key] = $value;
        }

        $data -> body = json_decode(file_get_contents('php://input'));

        return $data;
    }

    //Реализация с учетом api/
    $address = array_slice(get_address(), 1);
    $data = get_data();
    $method = get_method();
    if ($address != null)
    {
        route($method, $address, $data);
    }
    else
    {
        set_http_status(404, "This no such path as 'api'");
    }


