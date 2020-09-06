<?php

function xlog($data, bool $die = true): void{
    echo '<pre>' . print_r($data, true) . '</pre>';
    if($die){
        die();
    }
}

function xlogw($data): void{
    xlog($data, false);
}

function xlogf(string $data): void{
    file_put_contents('debbug.txt', $data);
    die('log in debbug.txt');
}
?>