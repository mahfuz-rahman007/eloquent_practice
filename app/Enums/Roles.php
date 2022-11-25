<?php

namespace App\Enums;


enum Roles:int {
    case Pending = 1;
    case Approve = 2;
    case Trash = 3;
    case Spam = 4;

    public static function fromName(string $name){
        return constant("self::$name");
    }
}
?>
