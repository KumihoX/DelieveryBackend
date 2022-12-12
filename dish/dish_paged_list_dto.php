<?php

class dish_paged_list_dto
{
    private array $dishes;
    private $pagination;

    public function __construct(){
        include_once 'dish_dto.php';
        include_once 'page_info_model.php';

        $this->dishes = [];
    }

    public function get_data(): array
    {
        $data_list = [];
        $data_list['dishes'] = $this->dishes;
        $data_list['pagination'] = $this->pagination;

        return $data_list;
    }

    public function add_dish($id)
    {
        $dish = new dish_dto($id);
        $dish_data = $dish->get_data();

        array_push($this->dishes, $dish_data);
    }

    public function add_pagination($size, $count, $current)
    {
        $page = new page_info_model($size, $count, $current);
        $page_data = $page->get_data();

        $this->pagination = $page_data;
    }
}