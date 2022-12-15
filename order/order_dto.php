<?php

class order_dto
{
    private string $id;
    private string $deliveryTime;
    private string $orderTime;
    private string $status;
    private float $price;
    private string $address;

    private array $dishes;

    public function __construct($order_id, $user){
        $this->dishes = [];

        $order_info = $GLOBALS['link']->query("SELECT id, deliveryTime, orderTime, status, price, address
        FROM OrderTable WHERE id = '$order_id' and user = '$user'")->fetch_assoc();

        if (!is_null($order_info))
        {
            $this->id = $order_info['id'];
            $this->deliveryTime = $order_info['deliveryTime'];
            $this->orderTime = $order_info['orderTime'];
            $this->status = $order_info['status'];
            $this->price = $order_info['price'];
            $this->address = $order_info['address'];

            $this->dishes_list();
        }

        set_http_status(404, "Такой заказ не найден");
        exit;
    }

    public function get_order_info()
    {
        $data_list = [];
        $data_list['id'] = $this->id;
        $data_list['deliveryTime'] = $this->deliveryTime;
        $data_list['orderTime'] = $this->orderTime;
        $data_list['status'] = $this->status;
        $data_list['price'] = $this->price;
        $data_list['address'] = $this->address;
        $data_list['dishes'] = $this->dishes;

        return $data_list;
    }

    private function dishes_list(){
        $dishes_list = $GLOBALS['link']->query("SELECT dish, amount FROM OrderContents WHERE orderId = '$this->id'")->fetch_all();

        include_once 'basket/dish_basket_dto.php';
        foreach ($dishes_list as $value){
            $dish = new dish_basket_dto($value[0], $value[1]);
            $dish_info = $dish->get_data();
            array_push($this->dishes, $dish_info);
        }
    }

}