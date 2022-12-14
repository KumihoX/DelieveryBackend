<?php

class JWT
{
    private $headers;
    private $secret;

    private $token;
    private $data;

    public function __construct()
    {
        $this->headers = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];
        $this->secret = 'scrtcd';
    }

    private function encode(string $str): string
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '='); // base64 encode string
    }

    private function get_token_from_header(): void
    {
        $headers = getallheaders();
        $auth = $headers['Authorization'];
        $auth_list = explode(' ', $auth);
        $this->token = $auth_list[1];
    }

    private function generate(array $payload): string
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

    private function token_alive($current_token): bool {
        $token = explode('.', $current_token);
        $payload = base64_decode($token[1]);

        $time = new DateTime();
        $current_time = $time->getTimestamp();

        return ($current_time < json_decode($payload)->exp);
    }


    public function get_token($data)
    {
        $exist_in_black_list = $GLOBALS['link']->
        query("SELECT token FROM BlackList WHERE email = '$data'")->fetch_assoc();

        if (!is_null($exist_in_black_list)){
            if ($this->token_alive($exist_in_black_list['token']))
            {
                $GLOBALS['link']->query("DELETE FROM BlackList WHERE email = '$data'");
                return $exist_in_black_list['token'];
            }

            else
            {
                $GLOBALS['link']->query("DELETE FROM BlackList WHERE email = '$data'");
                return $this->generate(['email' => $data]);
            }
        }
        else{
            return $this->generate(['email' => $data]);
        }
    }

    public function check_token(): bool
    {
        $this->get_token_from_header();

        $token = explode('.', $this->token);
        if (!isset($token[1]) && !isset($token[2])) {
            return false;
        }
        $headers = base64_decode($token[0]);
        $payload = base64_decode($token[1]);
        $clientSignature = $token[2];

        if (!json_decode($payload)) {
            return false;
        }

        if (isset(json_decode($payload)->email)) {
            $email = json_decode($payload)->email;

            $exist_email = $GLOBALS['link']->
            query("SELECT id FROM User WHERE email = '$email'")->fetch_assoc();

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

    public function save_in_black_list(){
        $GLOBALS['link']->query(
            "INSERT BlackList (email, token) values('$this->data', '$this->token')"
        );
    }

    public function get_email() {
        return $this->data;
    }
}
