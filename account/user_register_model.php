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
        //TODO: Сделать проверку на соответствие даты типу $date-time
        $this->birthDate = isset($data->birthDate) ? $data->birthDate : null;
        $this->check_gender($data->gender);
        //TODO: Сделать проверку на соответствие телефона типу $tel
        $this->phoneNumber = isset($data->phoneNumber) ? $data->phoneNumber : null;
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
        $this->password = $password;
    }

    //TODO: Проверка email на соответствие стандартным требованиям
    private function check_email($email){
        if (strlen($email) < 1){
            throw new Exception('Вы ввели слишком короткий email');
        }
        $this->email = $email;
    }

    private function check_gender($gender){
        if ($gender != 'Male' && $gender != "Female"){
            throw new Exception('Некоррекный гендер');
        }
        $this->gender = $gender;
    }

    public function save() {
        $GLOBALS['link']->query(
            "INSERT User (id, full_name, password, email, address, birth_date, gender, phone_number)
                values(
                    UUID(),
                    '$this->fullName',
                    '$this->password',
                    '$this->email',
                    '$this->address',
                    '$this->birthDate',
                    '$this->gender',
                    '$this->phoneNumber'
                )"
        );
    }
}