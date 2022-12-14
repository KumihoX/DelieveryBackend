<?php

class dish_dto
{
    private string $id;
    private string $name;
    private $description;
    private float $price;
    private $image;
    private bool $vegetarian;
    private $rating;

    public function __construct($id){
        $this->id = $id;

        $data = $GLOBALS['link']->query("SELECT  name, description, price, image, vegetarian, rating
        FROM Dish WHERE id = '$id'")->fetch_assoc();

        if (!is_null($data)){
            $this->name = $data['name'];
            $this->description = $data['description'] ?? null;
            $this->price = floatval($data['price']);
            $this->image = $data['image'] ?? null;
            $this->vegetarian = $this->vegetarian_in_bool($data['vegetarian']);
            $this->rating = floatval($data['rating']) ?? null;
        }
        else {
            set_http_status(404, "Такого блюда не существует");
            exit;
        }
    }

    public function get_data(): array
    {
        $data_list = [];
        $data_list['id'] = $this->id;
        $data_list['name'] = $this->name;
        $data_list['description'] = $this->description;
        $data_list['price'] = $this->price;
        $data_list['image'] = $this->image;
        $data_list['vegetarian'] = $this->vegetarian;
        $data_list['rating'] = $this->rating;

        return $data_list;
    }

    private function vegetarian_in_bool($value): bool
    {
        return match ($value) {
            "1" => true,
            default => false,
        };
    }

}