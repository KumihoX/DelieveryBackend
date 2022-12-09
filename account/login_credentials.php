<?php

class login_credentials
{
    private string $email;
    private string $password;

    public function __construct($data){
        $this->check_email($data->email);
        $this->check_password($data->password);
    }

    private function check_email($email): void
    {
        $valid_email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$valid_email) {
            throw new Exception('Вы ввели неккоректный email');
        }
        $this->email = $email;
    }

    private function check_password($password): void
    {
        if (strlen($password) < 6){
            throw new Exception('Вы ввели слишком короткий пароль');
        }
        $this->password = hash("sha1", $password);
    }

    public function user_exist(): bool
    {
        $exist_email = $GLOBALS['link']->query("SELECT email FROM User WHERE email = '$this->email'")->fetch_assoc();
        if (is_null($exist_email)) {
            throw new Exception('Пользователя с таким email не существует');
        }

        $correct_user = $GLOBALS['link']->
        query("SELECT email FROM User WHERE email = '$this->email' AND password = '$this->password'")->fetch_assoc();
        if (is_null($correct_user)) {
            throw new Exception('Пароль неверен');
        }

        return true;
    }
}