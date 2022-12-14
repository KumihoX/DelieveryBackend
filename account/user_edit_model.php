<?php

class user_edit_model
{
    private string $fullName;
    private $birthDate;
    private string $gender;
    private $address;
    private $phoneNumber;
    private array $errors = array();

    public function __construct($data){
        $this->check_name($data->fullName);
        $this->address = $data->address ?? null;
        $this->check_birth($data->birthDate);
        $this->check_gender($data->gender);
        $this->check_phone($data->phoneNumber);

        if ($this->errors){
            set_http_status(400, "One or more validation errors occurred", $this->errors);
            exit;
        }
    }

    private function check_name($name): void
    {
        if (is_null($name)){
            $this->errors["Name"] = 'Имя отсутствует';
            return;
        }
        if (strlen($name) < 1){
            $this->errors["Name"] = 'Вы ввели слишком короткое имя';
        }
        $this->fullName = $name;
    }

    private function check_birth($birth){
        if (is_null($birth))
        {
            $this->birthDate = null;
            return;
        }

        $current_time = new DateTime();
        $birth = str_replace('T', ' ', $birth);

        $formatted_birth = DateTime::createFromFormat('Y-m-d H:i:s', $birth);
        if (!$formatted_birth) {
            $this->errors['BirthDate'] = "Неккоректная дата рождения";
        } else if ($current_time->getTimestamp() <= $formatted_birth->getTimestamp()) {
            $this->errors['BirthDate'] = "Неккоректная дата рождения: Вы не можете родиться сегодня или в будущем";
        } else {
            $this->birthDate = $formatted_birth->format('Y-m-d H:i:s');
        }
    }

    private function check_gender($gender): void
    {
        if (is_null($gender)){
            $this->errors["Gender"] = 'Гендер отсутствует';
            return;
        }
        include_once 'gender.php';
        if (!gender::check_gender($gender)){
            $this->errors["Gender"] = 'Некоррекный гендер';
        }
        $this->gender = $gender;
    }

    private function check_phone($phone): void
    {
        if (is_null($phone))
        {
            $this->phoneNumber = null;
            return;
        }

        $correct_number_pattern = '/^\+7\s\([0-9]{3}\)\s[0-9]{3}-[0-9]{2}-[0-9]{2}/';

        $phone_is_correct = preg_match($correct_number_pattern, $phone);
        if (!isset($phone)){
            $phone = null;
        }
        else if (!$phone_is_correct){
            $this->errors["Phone"] = 'Некоррекный номер телефона';
        }

        $this->phoneNumber = $phone;
    }

    public function save($email)
    {
        include_once 'check_on_null.php';
        $GLOBALS['link']->query(
            "UPDATE User 
                SET 
                fullName = '$this->fullName',
                birthDate = ".check_on_null($this->birthDate).",
                gender = '$this->gender',
                address = ".check_on_null($this->address).",
                phoneNumber = ".check_on_null($this->phoneNumber)."
                WHERE email = '$email'"
        );
    }
}