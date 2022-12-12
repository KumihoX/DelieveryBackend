<?php

enum gender: string {
    case female = "Female";
    case male = "Male";

    public static function check_gender($gender): bool
    {
        return match ($gender) {
            'Female', 'Male' => true,
            default => false,
        };
    }
}
