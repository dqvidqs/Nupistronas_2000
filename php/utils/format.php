<?php

function xmoney(string $money){

    $points = array(
        ',', '.'
    );

    $value = '';
    for($i = 0; $i < strlen($money); $i++){
        if(is_numeric($money[$i]) || in_array($money[$i], $points)){
            $value .= $money[$i];
        }
    }
    
    return $value;
}

function xnumber_int(string $int){
    $value = '';
    for($i = 0; $i < strlen($int); $i++){
        if(is_numeric($int[$i])){
            $value .= $int[$i];
        }
    }
    
    return $value;
}
?>