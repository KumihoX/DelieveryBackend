<?php
function set_http_status($status = 200, $message = null, $errors = null)
{
    switch ($status){
        case 200:
            $status_http = "HTTP/1.0 200 OK";
            break;

        case 400:
            $status_http = "HTTP/1.0 400 Bad request";//в данных
            break;

        case 401:
            $status_http = "HTTP/1.0 401 Unauthorized";
            break;

        case 403:
            $status_http = "HTTP/1.0 403 Forbidden";
            break;

        case 404:
            $status_http = "HTTP/1.0 404 Not found"; //в пути
            break;

        case 500:
            $status_http = "HTTP/1.0 500 Internal Server Error";
            break;
    }

    header($status_http);
    include_once "response.php";
    $response = new response($status, $message, $errors);

    $response->send_response();
}
