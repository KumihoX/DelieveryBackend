<?php

class order_info_dto
{
    private array $orders;

    public function __construct(){
        $this->orders = [];
    }

    public function get_data(): array
    {
        return $this->orders;
    }

    public function add_order($order_id)
    {
        $order_info = $GLOBALS['link']->query("SELECT id, deliveryTime, orderTime, status, price
        FROM OrderTable WHERE id = '$order_id'")->fetch_assoc();

        $order_info['price'] = intval($order_info['price']);
        $this->orders[] = $order_info;
    }
}