<?php

class login_credentials
{
    private $email;
    private  $password;

    public function __construct($data){
        $this->check_email($data->email);
        $this->check_password($data->password);
    }

    private function check_email($email){
        if (strlen($email) < 1){
            throw new Exception('Вы ввели слишком короткий email');
        }
        $this->email = $email;
    }

    private function check_password($password){
        if (strlen($password) < 6){
            throw new Exception('Вы ввели слишком короткий пароль');
        }
        $this->password = $password;
    }
}