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

function xlogf($data): void{

    file_put_contents('debbug.txt', var_export($data, true));
    xlog($data);
    // die('log in debbug.txt');
}

function xlogc(string $data){

    for ( $pos = 0; $pos < strlen($data); $pos++ ) {
        xlogw(ord($data[$pos]) . ' => '. $data[$pos]);

        // $byte = substr($data, $pos);
        // xlog($byte);
        // xlogw('Byte ' . $pos . ' of $str has value ' . ord($byte) . PHP_EOL);
    }

    xlog($data);
}
?>