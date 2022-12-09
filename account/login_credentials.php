<?php

class login_credentials
{
    private $email;
    private  $password;

    public function __construct($data){
        $this->check_email($data->email);
        $this->check_password($data->password);
    }

    public function check_data(){
        $exist_email = $GLOBALS['link']->query("SELECT email FROM User WHERE email = '$this->email'")->fetch_assoc();
        if (is_null($exist_email)) {
            throw new Exception('Пользователя с таким email не существует');
        }

        $correct_user = $GLOBALS['link']->
        query("SELECT 'id' FROM User WHERE email = '$this->email' AND password = '$this->password'")->fetch_assoc();
        if (is_null($correct_user)) {
            throw new Exception('Пароль неверен');
        }

        include_once 'token_controller.php';
        $token = create_token($correct_user);
        echo json_encode(
            array(
                "message" => 'Вы в системе, мои поздравления!',
                "token" => $token
            )
        );
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
        $this->password = hash("sha1", $password);
    }
}