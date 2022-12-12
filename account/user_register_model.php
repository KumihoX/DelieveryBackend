<?php

class user_register_model
{
    private string $fullName;
    private string $password;
    private string $email;
    private string $birthDate;
    private string $gender;
    private string $address;
    private string $phoneNumber;
    private array $errors = array();

    public function __construct($data){
        $this->check_name($data->fullName);
        $this->check_password($data->password);
        $this->check_email($data->email);
        $this->address = $data->address ?? null;
        $this->birthDate = isset($data->birthDate) ? date('d.m.Y', strtotime($data->birthDate)) : null;
        $this->check_gender($data->gender);
        $this->check_phone($data->phoneNumber);

        if ($this->errors){
            set_http_status(400, "One or more validation errors occurred", $this->errors);
            exit;
        }
    }

    private function check_name($name): void
    {
        if (strlen($name) < 1){
            $this->errors["Name"] = 'Вы ввели слишком короткое имя';
        }
        $this->fullName = $name;
    }

    private function check_password($password): void
    {
        if (strlen($password) < 6){
            $this->errors["Password"] = 'Вы ввели слишком короткий пароль';
        }
        $this->password = hash("sha1", $password);
    }

    private function check_email($email): void
    {
        $valid_email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $exist_email = $GLOBALS['link']->query("SELECT email FROM User WHERE email = '$email'")->fetch_assoc();
        if (!$valid_email) {
            $this->errors["Email"] = 'Вы ввели неккоректный email';
        }
        if($exist_email){
            $this->errors["Email"] = 'Пользователь с таким email уже существует';
        }
        $this->email = $email;
    }

    private function check_gender($gender): void
    {
        include_once 'gender.php';
        if (!(gender::check_gender($gender))){
            $this->errors["Gender"] = 'Некоррекный гендер';
        }
        $this->gender = $gender;
    }

    private function check_phone($phone): void
    {
        $number_without_space = preg_replace('/\s/','', $phone);
        $correct_number_pattern = '/\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}/';

        $phone_is_correct = preg_match($correct_number_pattern, $number_without_space);
        if (!isset($phone)){
            $phone = null;
        }
        else if (!$phone_is_correct){
            $this->errors["Phone"] = 'Некоррекный номер телефона';
        }

        $this->phoneNumber = $phone;
    }

    public function save(): void
    {
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