<?php
class user_dto
{
    private string $id;
    private string $fullName;
    private string $email;
    private $address;
    private $birthDate;
    private string $gender;
    private $phoneNumber;

    public function __construct($email){
        $data = $GLOBALS['link']->query
        ("SELECT id, fullName, birthDate, gender, address, email, phoneNumber
        FROM User WHERE email = '$email'")->fetch_assoc();

        $this->id = $data['id'];
        $this->fullName = $data['fullName'];
        $this->birthDate = $data['birthDate'];
        $this->gender = $data['gender'];
        $this->address = $data['address'];
        $this->email = $data['email'];
        $this->phoneNumber = $data['phoneNumber'];
    }

    public function get_data(): array
    {
        $data_list = [];
        $data_list['id'] = $this->id;
        $data_list['fullName'] = $this->fullName;
        $data_list['birthDate'] = $this->birthDate;
        $data_list['gender'] = $this->gender;
        $data_list['address'] = $this->address;
        $data_list['email'] = $this->email;
        $data_list['phoneNumber'] = $this->phoneNumber;

        return $data_list;
    }
}