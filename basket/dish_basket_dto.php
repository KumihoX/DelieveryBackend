<?php

class dish_basket_dto
{
    private string $id;
    private string $name;
    private float $price;
    private float $totalPrice;
    private int $amount;
    private string $image;


    public function __construct($id, $amount){
        $this->id = $id;

        $data = $GLOBALS['link']->query("SELECT  name, price, image
        FROM Dish WHERE id = '$id'")->fetch_assoc();

        $this->name = $data['name'];
        $this->price = $data['price'];
        $this->image = $data['image'];
        $this->amount = $amount;
        $this->totalPrice = $this->count_total_price();
    }

    public function get_data(){
        $data_list = [];
        $data_list['id'] = $this->id;
        $data_list['name'] = $this->name;
        $data_list['price'] = $this->price;
        $data_list['totalPrice'] = $this->totalPrice;
        $data_list['amount'] = $this->amount;
        $data_list['image'] = $this->image;

        return $data_list;
    }

    private function count_total_price(){
        return $this->amount * $this->price;
    }
}