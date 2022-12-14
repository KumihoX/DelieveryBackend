<?php
function check_on_null($string){
    if (is_null($string)){
        return 'NULL';
    }
    return "'$string'";
}