<?php

require_once 'HTMLExtractor.php';

class xExtractor {

    function __construct(){}

    public function run(){
        $boot = Boot::get_instance();
        $files = get_files($boot->config['products_dir']);
        foreach($files as $file){
            $file_content = file_get_contents($boot->config['products_dir'] .'/'. $file);
            $ids = explode(PHP_EOL, $file_content);

            $ex = new HTMLExtractor(array(
                'cookies' => $boot->config['cookies']
            ));

            $st = microtime(true);
            $map = array();
            $result = array();
            $header = array();
            foreach($ids as $id_key => $row){
                if(!is_dir($boot->config['img_dir'] . "/{$row}")){
                    mkdir($boot->config['img_dir'] . "/{$row}");
                }
                if($row && is_numeric($row)){
                    //gellery
                    $html = $ex->get_raw_html($boot->config['products_link'] . $row);

                    $gallery = get_raw_tag_c($html, '<div id="gallery" style="height: 50px;">', '</div>');
                    $gallery_rows = get_raw_tag($gallery, '<a', '</a>');
                    
                    $img = array();
                    foreach($gallery_rows as $key => $grow){
                        $pic = get_href_from_tag($grow[0]);
                        $img[] = $boot->config['img_dir'] . "/{$row}". '/' . $key . '.jpg';
                        file_put_contents($img[$key], $ex->get_raw_html($pic));
                    }
                    $map['ID'][$id_key] = $row;
                    $map['img'][$id_key] = implode ( $img, ';');
                    sleep($boot->config['sleep']);

                    //main table
                    $main_table = get_raw_tag_c($html, '<table cellpadding="0" cellspacing="0" class="tdb-table">', '</table>');       

                    $main_tables_rows = get_raw_tag($main_table, '<td', '</td>');
                    //info table
                    $table = get_raw_tag_c($html, '<table cellpadding="0" cellspacing="2">', '</table>');       
                    $tables_rows = get_raw_tag($table, '<td', '</td>');
                    //info table

                    $this->get_content($map, $id_key, $tables_rows, $header, $result);
                    $this->get_content($map, $id_key, $main_tables_rows, $header, $result);               
                }
            }
            // $header = $this->fix_header($map);
            $map = $this->reverse_array($map, $result);
            $map = $this->fix_header($map);
            // $rez = $this->reverse_array($map);
            to_csv($map, $boot->config['result_dir'], $file);
        }
        $et = microtime(true);

        die('DONE! ' . ($et - $st));
    }

    private function get_content(&$map, int $id_key, array $content, array $header, array $result,  int $_h = 0, int $_r = 1){
        for($i = 0; $i < count($content); $i++){
            if (strpos($content[$i][0], '<img') !== false || strpos($content[$i][0], '<div') !== false){
                unset($content[$i]);
                $content = array_values($content);
                $i--;
                continue;
            }

            if($i % 2 == $_h){
                $header[] = strip_tags($content[$i][0]);
            }

            if($i % 2 == $_r){
                if (strpos($content[$i][0], '<li>') !== false){
                    $content[$i][0] = str_replace(array('<li>', '</li>'), array('', " "), $content[$i][0]);
                }

                $result[] = strip_tags($content[$i][0]);
            }
        } 

        foreach($header as $key => $title){
            if(isset($result[$key])){
                $map[$title][$id_key] = $result[$key];
            }else{
                $map[$title][$id_key] = '-';
            }
        }
    }

    private function reverse_array(array $map, array $result): array{
        $header = array_keys($map);
        for($i = 0; $i < count(current($map)); $i++){
            if(!isset($result[$i])){
                $result[$i] = array();
            }
            $e = current($map);
            for($j = 0; $j < count($map); $j++){
                
                if(isset($e[$i])){
                    array_push($result[$i], $e[$i]);
                }else{
                    array_push($result[$i], "-");
                }
                $e = next($map);
            }
            reset($map);
        }
        return array_merge(array($header), $result);
    }

    // private function maping(array $map, array $header, array $result): array{
    //     for($i = 0; $i < count(current($map)); $i++){
    //         if(!isset($result[$i])){
    //             $result[$i] = array();
    //         }
    //         $e = current($map);
    //         for($j = 0; $j < count($map); $j++){
                
    //             if(isset($e[$i])){
    //                 array_push($result[$i], $e[$i]);
    //             }else{
    //                 array_push($result[$i], "-");
    //             }
    //             $e = next($map);
    //         }
    //         reset($map);
    //     }
    //     xlog($map);
    //     return $map;
    // }

    private function fix_header(array $map){
        $arr = array();
        foreach($map[0] as $key => $row){
            // xlog($row);
            if(empty(trim($row))){
                $arr[] = $key;
            }
        }
        foreach($map as $key => $row){
            foreach($row as $rkey => $e){
                foreach($arr as $remove){
                    if($remove == $rkey){
                        unset($map[$key][$rkey]);
                    }
                }
            }
        }
        foreach($map as $key => $row){
            $map[$key] = array_values($map[$key]);
        }
        return $map;
    }
}
?>