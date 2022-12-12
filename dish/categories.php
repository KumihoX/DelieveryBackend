<?php
enum categories: string {
    case wok = "Wok";
    case pizza = "Pizza";
    case soup = "Soup";
    case dessert = "Dessert";
    case drink = "Drink";
    public static function check_category($category): bool
    {
        return match ($category) {
            'Wok', 'Dessert', 'Pizza', 'Soup', 'Drink' => true,
            default => false,
        };
    }
}