<?php

require_once __PHP__ .'/xHTMLExtractor/xHTMLExtractor.php';
require_once 'ProductHeader.php';

class xExtractor {

    function __construct(){}

    public function run(){
  
    }

    public function get_content(array $map, int $id_key, array $content, array $header, array $result, string $prefix = 'arg',  int $_h = 0, int $_r = 1): array{
        for($i = 0; $i < count($content); $i++){
            if (contain($content[$i][0], array('<img', 'div'))){
                unset($content[$i]);
                $content = array_values($content);
                $i--;
                continue;
            }

            if($i % 2 == $_h){
                $header[] = "{$prefix}|" . trim_c(strip_tags($content[$i][0]));
            }

            if($i % 2 == $_r){
                if(contain($content[$i][0], '<LI')){
                    $content[$i][0] = str_replace('</LI>', "\r\n", $content[$i][0]);
                    $content[$i][0] = str_replace('<LI>', '', $content[$i][0]);
                }
                $result[] = strip_tags($content[$i][0]);
            }
        } 
        return $this->maping($map, $id_key, $header, $result);
    }

    public function reverse_array(array $map): array{
        $header = array_keys($map);
        $result = array();
        for($i = 0; $i < count(current($map)); $i++){
            $result[$i] = array();
            $e = current($map);
            for($j = 0; $j < count($map); $j++){
                if(isset($e[$i])){
                    array_push($result[$i], $e[$i]);
                }else{
                    array_push($result[$i], '-');
                }
              
                $e = next($map);
            }
            reset($map);
        }
        return array_merge(array($header), $result);
    }

    public function maping(array $map, int $id_key, array $header, array $result): array{
        foreach($header as $key => $title){
            if(isset($result[$key])){
                $map[$title][$id_key] = $result[$key];
            }
        }
        return $map;
    }

    public function fix_header(array $map): array{
        $arr = array();
        foreach($map[0] as $key => $row){
            if(empty(trim($row))){
                $arr[] = $key;
            }
        }

        if(count($arr) > 0){
            foreach($map as $key => $row){
                foreach($row as $rkey => $e){
                    foreach($arr as $remove){
                        if($remove == $rkey){
                            unset($map[$key][$rkey]);
                        }
                    }
                }
            }
        }

        return $map;
    }
    
    public function set_header_object(array $map, int $index = 0): array{
        $header = array_values($map[$index]);

        foreach($header as $key => $row){
            $header[$key] = new ProductHeader();
            $header[$key]->value = $row;
            if(isset($this->order[$row])){
                $header[$key]->order = $this->order[$row];
            }else{
                $header[$key]->order =  Bootstrap::get_instance()->config['default_order'];
            }
        }

        $map[$index] = $header;

        return $map;
    }

    public function order(array $map): array{
        for($cicle = 0; $cicle < count($map[0]); $cicle++){
            for($i = 0; $i < count($map[0]) - 1; $i++){
                if($map[0][$i]->order > $map[0][$i + 1]->order){
                    for($e = 0; $e < count($map); $e++){
                        $obj = $map[$e][$i];
                        $map[$e][$i] = $map[$e][$i + 1];
                        $map[$e][$i + 1] = $obj;
                    }
                }
            }
        }
        return $map;
    }

    public function get_price($money){
        $money = xmoney($money);

        $boot = Bootstrap::get_instance();

        if($money != 0){
            $money = ($money + $money * $boot->config['vat']['value']) * $boot->config['price_multiplier']['value'] + $boot->config['price_add']['value'];
        }else{
            return 0;
        }

        return number_format($money, 2, '.', '');
    }
}
?>