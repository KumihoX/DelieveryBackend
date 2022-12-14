<?php

class user_register_model
{
    private string $fullName;
    private string $password;
    private string $email;
    private $birthDate;
    private string $gender;
    private $address;
    private $phoneNumber;
    private array $errors = array();

    public function __construct($data){

        $this->check_name($data->fullName);
        $this->check_password($data->password);
        $this->check_email($data->email);
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
            $this->errors["Name"] = 'Вы не ввели имя';
            return;
        }

        if (strlen($name) < 1)
        {
            $this->errors["Name"] = 'Вы ввели слишком короткое имя';
            return;
        }
        $this->fullName = $name;
    }

    private function check_password($password): void
    {
        if (is_null($password)){
            $this->errors["Password"] = 'Вы не ввели пароль';
            return;
        }
        if (strlen($password) < 6)
        {
            $this->errors["Password"] = 'Вы ввели слишком короткий пароль';
            return;
        }
        $this->password = hash("sha1", $password);
    }

    private function check_email($email): void
    {
        if (is_null($email)){
            $this->errors["Email"] = 'Вы не ввели email';
            return;
        }
        $valid_email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $exist_email = $GLOBALS['link']->query("SELECT email FROM User WHERE email = '$email'")->fetch_assoc();
        if (!$valid_email) {
            $this->errors["Email"] = 'Вы ввели неккоректный email';
            return;
        }
        if($exist_email){
            $this->errors["Email"] = 'Пользователь с таким email уже существует';
            return;
        }
        $this->email = $email;
    }

    private function check_birth($birth){
        if (is_null($birth))
        {
            $this->birthDate = null;
            return;
        }

        if (empty($birth))
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
            $this->errors["Gender"] = 'Отсутствует пол';
            return;
        }

        include_once 'gender.php';
        if (!(gender::check_gender($gender))){
            $this->errors["Gender"] = 'Некоррекный гендер';
            return;
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

        if (empty($phone))
        {
            $this->phoneNumber = null;
            return;
        }

        $correct_number_pattern = '/^\+7\s{1}\([0-9]{3}\)\s{1}[0-9]{3}-[0-9]{2}-[0-9]{2}/';

        $phone_is_correct = preg_match($correct_number_pattern, $phone);
        if (!$phone_is_correct){
            $this->errors["Phone"] = 'Некоррекный номер телефона';
            return;
        }

        $this->phoneNumber = $phone;
    }

    public function save(): void
    {
        include_once 'check_on_null.php';
        $GLOBALS['link']->query(
            "INSERT User (id, fullName, email, password, address, birthDate, gender, phoneNumber)
                values(
                    UUID(),
                    '$this->fullName',
                    '$this->email',
                    '$this->password',
                    ".check_on_null($this->address).",
                    ".check_on_null($this->birthDate).",
                    '$this->gender',
                    ".check_on_null($this->phoneNumber)."
                )"
        );
    }
}