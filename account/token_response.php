<?php

class token_response
{
    private string $token;

    public function __construct($data){
        $this->token = $data;
    }

    public function get_token(): array
    {
        $token = [];
        $token['token']=$this->token;
        return $token;
    }
}