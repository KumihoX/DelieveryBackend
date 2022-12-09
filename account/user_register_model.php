<?php

class user_register_model
{
    private $fullName;
    private $password;
    private $email;
    private $address;
    private $birthDate;
    private $gender;
    private $phoneNumber;

    public function __construct($data){
        $this->check_name($data->fullName);
        $this->check_password($data->password);
        $this->check_email($data->email);
        $this->address = isset($data->address) ? $data->address : null;
        $this->birthDate = isset($data->birthDate) ? date('d.m.Y', strtotime($data->birthDate)) : null;
        $this->check_gender($data->gender);
        $this->check_phone($data->phoneNumber);
    }

    private function check_name($name) {
        if (strlen($name) < 1){
            throw new Exception('Вы ввели слишком короткое имя');
        }
        $this->fullName = $name;
    }

    private function check_password($password){
        if (strlen($password) < 6){
            throw new Exception('Вы ввели слишком короткий пароль');
        }
        $this->password = hash("sha1", $password);
    }

    private function check_email($email){
        $valid_email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $exist_email = $GLOBALS['link']->query("SELECT email FROM User WHERE email = '$email'")->fetch_assoc();
        if (!$valid_email) {
            throw new Exception('Вы ввели неккоректный email');
        }
        if($exist_email){
            throw new Exception('Пользователь с таким email уже существует');
        }
        $this->email = $email;
    }

    private function check_gender($gender){
        if ($gender != 'Male' && $gender != "Female"){
            throw new Exception('Некоррекный гендер');
        }
        $this->gender = $gender;
    }

    private function check_phone($phone){
        $number_without_space = preg_replace('/\s/','', $phone);
        $correct_number_pattern = '/\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}/';

        $phone_is_correct = preg_match($correct_number_pattern, $number_without_space);
        if (!isset($phone)){
            $phone = null;
        }
        else if (!$phone_is_correct){
            throw new Exception('Некоррекный номер телефона');
        }

        $this->phoneNumber = $phone;
    }

    public function save() {
        $GLOBALS['link']->query(
            "INSERT User (id, fullName, email, password, address, birthDate, gender, phoneNumber)
                values(
                    UUID(),
                    '$this->fullName',
                    '$this->email',
                    '$this->password',
                    '$this->address',
                    '$this->birthDate',
                    '$this->gender',
                    '$this->phoneNumber'
                )"
        );
    }
}