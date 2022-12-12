<?php

class page_info_model
{
    private int $size;
    private int $count;
    private int $current;

    public function __construct($size, $count, $current){
        $this->size = $size;
        $this->count = $count;
        $this->current = $current;
    }

    public function get_data(): array
    {
        $data_list = [];
        $data_list['size'] = $this->size;
        $data_list['count'] = $this->count;
        $data_list['current'] = $this->current;

        return $data_list;
    }
}