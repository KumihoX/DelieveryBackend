<?php

class order_create_dto
{
    private string $deliveryTime;
    private string $address;
    private array $errors = array();

    public function __construct($data){
        $this->check_delivery_time($data->deliveryTime);
        $this->check_address($data->address);

        if ($this->errors){
            set_http_status(400, "One or more validation errors occurred", $this->errors);
            exit;
        }
    }

    private function check_delivery_time($deliveryTime)
    {
        if (is_null($deliveryTime)){
            $this->errors["DeliveryTime"] = 'Дата доставки отсутствует';
            return;
        }
        $current_time = new DateTime();
        $deliveryTime = str_replace('T', ' ', $deliveryTime);
        $formattedDate = DateTime::createFromFormat('Y-m-d H:i:s', $deliveryTime);
        if (!$formattedDate) {

            $this->errors['DeliveryTime'] = "Некорректная дата доставки";
        }
        else if ($formattedDate->getTimestamp() - $current_time->getTimestamp() < 3600)
        {
            $this->errors['DeliveryTime'] = "Минимальное время для доставки: час";
        }
        else {
            $this->deliveryTime = $formattedDate->format('Y-m-d H:i:s');
        }
    }

    private function check_address($address): void
    {
        if (is_null($address)){
            $this->errors["Address"] = 'Адрес отсутствует';
            return;
        }
        if (strlen($address) < 1){
            $this->errors["Address"] = 'Вы ввели некорректный адрес';
        }
        $this->address = $address;
    }

    private function price_calculation($user, $order_id){
        $dish_id = $GLOBALS['link']->query("SELECT dish, amount FROM Basket WHERE user = '$user'")->fetch_all();
        $GLOBALS['link']->query("DELETE FROM Basket WHERE user = '$user'");

        $order_total_price = 0;
        foreach ($dish_id as $value)
        {
            $dish_price = $GLOBALS['link']->query("SELECT price FROM Dish WHERE id = '$value[0]'")->fetch_assoc();
            $dish_total_price = $dish_price['price'] * $value[1];

            $order_total_price = $order_total_price + $dish_total_price;

            $GLOBALS['link']->query(
                "INSERT OrderContents (orderId, dish, amount)
                values(
                    '$order_id',
                    '$value[0]',
                    '$value[1]'
                )");
        }

        $GLOBALS['link']->query("UPDATE OrderTable SET price = '$order_total_price' WHERE id = '$order_id'");
    }

    public function save($user, $order_id)
    {
        $current_time = new DateTime();
        $order_time = $current_time->format("Y-m-d H:m:s");

        $GLOBALS['link']->query(
            "INSERT OrderTable (orderTime, deliveryTime, address, user, status, id)
                values(
                    '$order_time',
                    '$this->deliveryTime',
                    '$this->address',
                    '$user',
                    'В работе',
                    '$order_id'
                )"
        );

        $this->price_calculation($user, $order_id);
    }

}