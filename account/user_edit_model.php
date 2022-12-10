<?php

class user_edit_model
{
    private string $fullName;
    private string $birthDate;
    private string $gender;
    private string $address;
    private string $phoneNumber;

    public function __construct($data){
        $this->check_name($data->fullName);
        $this->address = $data->address ?? null;
        $this->birthDate = isset($data->birthDate) ? date('d.m.Y', strtotime($data->birthDate)) : null;
        $this->check_gender($data->gender);
        $this->check_phone($data->phoneNumber);
    }

    private function check_name($name): void
    {
        if (strlen($name) < 1){
            throw new Exception('Вы ввели слишком короткое имя');
        }
        $this->fullName = $name;
    }

    private function check_gender($gender): void
    {
        if ($gender != 'Male' && $gender != "Female"){
            throw new Exception('Некоррекный гендер');
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
            throw new Exception('Некоррекный номер телефона');
        }

        $this->phoneNumber = $phone;
    }

    public function save($email)
    {
        echo json_encode($this->birthDate);
        $GLOBALS['link']->query(
            "UPDATE User 
                SET 
                fullName = '$this->fullName',
                birthDate = '$this->birthDate',
                gender = '$this->gender',
                address = '$this->address',
                phoneNumber = '$this->phoneNumber'
                WHERE email = '$email'"
        );

    }
}