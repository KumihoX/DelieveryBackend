<?php

class JWT
{
    private $headers;
    private $secret;
    private $data;

    public function __construct()
    {
        $this->headers = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];
        $this->secret = 'scrtcd';
    }

    public function generate(array $payload): string
    {
        $headers = $this->encode(json_encode($this->headers));

        $currentTime = new DateTime();
        $payload['nbf'] = $currentTime->getTimestamp();
        $payload['exp'] = $currentTime->getTimestamp() + 3600;
        $payload['iat'] = $currentTime->getTimestamp();
        $payload['iss'] = "http://localhost/";
        $payload['aud'] = "http://localhost/";
        $payload['sub'] = "Authorization";

        $payload = $this->encode(json_encode($payload));
        $signature = hash_hmac('SHA256', "$headers.$payload", $this->secret, true);
        $signature = $this->encode($signature);

        return "$headers.$payload.$signature";
    }

    private function encode(string $str): string
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '='); // base64 encode string
    }

    public function is_valid(string $jwt): bool
    {
        $token = explode('.', $jwt); // explode token based on JWT breaks
        if (!isset($token[1]) && !isset($token[2])) {
            return false; // fails if the header and payload is not set
        }
        $headers = base64_decode($token[0]); // decode header, create variable
        $payload = base64_decode($token[1]); // decode payload, create variable
        $clientSignature = $token[2]; // create variable for signature

        if (!json_decode($payload)) {
            return false; // fails if payload does not decode
        }

        if (isset(json_decode($payload)->email)) {
            $email_object = json_decode($payload)->email;
            $email = $email_object->email;
            $exist_email = $GLOBALS['link']->
            query("SELECT email FROM User WHERE email = '$email'")->fetch_assoc();
            if (is_null($exist_email)){
                return false;
            }
            $this->data = $email;
        }
        else {
            return false;
        }

        $base64_header = $this->encode($headers);
        $base64_payload = $this->encode($payload);

        $signature = hash_hmac('SHA256', $base64_header . "." . $base64_payload, $this->secret, true);
        $base64_signature = $this->encode($signature);

        return ($base64_signature === $clientSignature);
    }

    public function save_in_black_list($token){
        echo json_encode($this->data);
        echo json_encode($token);
        $GLOBALS['link']->query(
            "INSERT BlackList (email, token) values('$this->data', '$token')"
        );
    }
}
