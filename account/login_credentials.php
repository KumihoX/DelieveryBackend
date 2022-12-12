<?php

class login_credentials
{
    private string $email;
    private string $password;
    private array $errors = array();

    public function __construct($data){
        $this->check_email($data->email);
        $this->check_password($data->password);

        if ($this->errors){
            set_http_status(400, "One or more validation errors occurred", $this->errors);
            exit;
        }
    }

    private function check_email($email): void
    {
        $valid_email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if (!$valid_email) {
            $this->errors["Email"] = 'Вы ввели неккоректный email';
        }
        $this->email = $email;
    }

    private function check_password($password): void
    {
        if (strlen($password) < 6){
            $this->errors["Password"] = 'Вы ввели слишком короткий пароль';
        }
        $this->password = hash("sha1", $password);
    }

    public function user_exist(): bool
    {
        $exist_email = $GLOBALS['link']->query("SELECT email FROM User WHERE email = '$this->email'")->fetch_assoc();
        if (is_null($exist_email)) {
            $this->errors["Email"] = 'Пользователя с таким email не существует';
            set_http_status(400,"One or more validation errors occurred", $this->errors);
            exit;
        }

        $correct_user = $GLOBALS['link']->
        query("SELECT email FROM User WHERE email = '$this->email' AND password = '$this->password'")->fetch_assoc();
        if (is_null($correct_user)) {
            $this->errors["Password"] = 'Пароль неверен';
            set_http_status(400,"One or more validation errors occurred", $this->errors);
            exit;
        }

        return true;
    }
}