<?php
function get_dishes_list($data) {
    $request = create_request('id', $data);
    $dishes_list = $GLOBALS['link']->query($request)->fetch_all();
    $dishes_list_size = count($dishes_list);
    $count_elements = 5;
    $count_pages = ceil($dishes_list_size /$count_elements);

    $page_index = check_page($data['page'] ?? null);
    $dish_index = ($page_index - 1) * $count_elements;

    include_once 'dish_paged_list_dto.php';
    $dish_paged_list = new dish_paged_list_dto();
    if ($page_index <= $count_pages)
    {
        if ($page_index != $count_pages) {
            for ($i = $dish_index; $i < $dish_index + $count_elements; $i++) {
                $dish_paged_list->add_dish($dishes_list[$i][0]);
            }
        } else {
            $count_dishes_on_previous_pages = ($page_index - 1) * $count_elements;
            $count_dishes_on_this_page = $dishes_list_size - $count_dishes_on_previous_pages;

            for ($i = $dish_index; $i < $dish_index + $count_dishes_on_this_page; $i++) {
                $dish_paged_list->add_dish($dishes_list[$i][0]);
            }
        }
        $dish_paged_list->add_pagination($count_elements, $count_pages, $page_index);
        set_http_status();
        echo json_encode($dish_paged_list->get_data());
    }
    else
    {
        set_http_status(404, "Страница не найдена");
        exit;
    }
}

function check_page($page){
    if (is_null($page)){
        $error = array();
        $error["Page"] = "Данные отсутствуют";
        set_http_status(400, "One or more validation errors occurred", $error);
        exit;
    }
    return $page;
}

function add_categories($categories)
{
    include_once "categories.php";
    $request = "";
    if (is_array($categories))
    {
        foreach ($categories as $value) {
            if (categories::check_category($value)) {
                $request = $request . "category = " . "'" . $value . "'" . " OR ";
            }
            else {
                $error = array();
                $error["Category"] = "Категории $value не существует";
                set_http_status(400, "One or more validation errors occurred", $error);
                exit;
            }
        }

        return rtrim($request, " OR ");
    }
    else {
        if (categories::check_category($categories)){
            return "category = ". "'" . $categories . "'";
        }

        else {
            $error = array();
            $error["Category"] = "Категории $categories не существует";
            set_http_status(400, "One or more validation errors occurred", $error);
            exit;
        }
    }

}

function add_vegetarian($vegetarian)
{
    if (!is_null($vegetarian))
    {
        switch ($vegetarian) {
            case "true":
                return " AND (vegetarian = 1)";

            case "false":
                return "";

            default:
                $error = array();
                $error["Vegetarian"] = "Некорректные данные";
                set_http_status(400, "One or more validation errors occurred", $error);
                exit;
        }
    }
    $error = array();
    $error["Vegetarian"] = "Данные отсутствуют";
    set_http_status(400, "One or more validation errors occurred", $error);
    exit;
}

function sorting($sorting){
    switch ($sorting){
        case "NameAsc":
            return "name ASC";

        case "NameDesc":
            return "name DESC";

        case "PriceAsc":
            return "price ASC";

        case "PriceDesc":
            return "price DESC";

        case "RatingAsc":
            return "rating ASC";

        case "RatingDesc":
            return "rating DESC";

        default:
            $error = array();
            $error["Sorting"] = "Сортировки $sorting не существует";
            set_http_status(400, "One or more validation errors occurred", $error);
            exit;
    }
}

function create_request($select_data, $data)
{
    $request = "SELECT " . $select_data . " FROM Dish";
    $categories = $data['category'] ?? null;
    $vegetarian = $data['vegetarian'];
    $sorting = $data['sorting'] ?? null;

    if (is_null($categories) && is_null($sorting) && $vegetarian == "false"){
        return $request;
    }
    else
    {
        $request = $request." WHERE";
        $request = is_null($categories) ? $request : $request . "(" . add_categories($categories) . ")";
        $request = $request . add_vegetarian($vegetarian);
        return is_null($sorting) ? $request : $request . " ORDER BY " . sorting($sorting);
    }
}
