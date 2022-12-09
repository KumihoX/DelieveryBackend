<?php
function create_token($id) {
    $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    $payload = ['id' => $id];
    $secretKey = "scrtcd";

    $currentTime = new DateTime();
    $payload['nbf'] = $currentTime->getTimestamp();
    $payload['exp'] = $currentTime->getTimestamp() + 3600;
    $payload['iat'] = $currentTime->getTimestamp();
    $payload['iss'] = "http://localhost/";
    $payload['aud'] = "http://localhost/";


    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($header)));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));

    $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $secretKey, true);

    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
}

function get_id_from_token($token): string
{
    $payload = getTokenPayload($token);
    return $payload['id'];
}