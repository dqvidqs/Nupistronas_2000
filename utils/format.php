<?php

function xmoney(string $money){
    $points = array(
        ',', '.'
    );
    $money = str_replace(' ', '', $money);
    $value = '';
    for($i = 0; $i < strlen($money); $i++){
        if(is_numeric($money[$i]) || in_array($money[$i], $points)){
            $value .= $money[$i];
        }
    }
    return $value;
}
?>