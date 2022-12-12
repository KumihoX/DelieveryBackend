<?php
function get_dish($id){
    include_once "dish_dto.php";
    $dish = new dish_dto($id);

    echo json_encode($dish->get_data());
}
