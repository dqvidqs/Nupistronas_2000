<?php

require_once 'HTMLExtractor.php';
require_once 'ProductHeader.php';

class xExtractor {

    function __construct(){}

    public function run(){
        $boot = Boot::get_instance();
        
        $all = new Debugger(true, $boot->config['debug']);
        $all->set_s('ALL');
        $dub = new Debugger(true, $boot->config['debug']);
        $files = get_files($boot->config['products_dir']);

        $ex = new HTMLExtractor(array(
            'cookies' => $boot->config['cookies']
        ));

        foreach($files as $file_index => $file){

            if($file_index != 0){
                sleep($boot->config['sleep']);
            }
            
            $file_content = file_get_contents($boot->config['products_dir'] .'/'. $file);
            $ids = explode(PHP_EOL, $file_content);
            
            $map = array();
            $result = array();
            $header = array();
            
            foreach($ids as $id_key => $row){
                if($id_key != 0){
                    sleep($boot->config['sleep']);
                }

                $dub->set_s('FILE: ' . $file . '; ID: '. $row, 'STARTED!: '. $row . '<br>');

                if(!is_dir($boot->config['img_dir'] . "/{$row}")){
                    mkdir($boot->config['img_dir'] . "/{$row}");
                }
                if($row && is_numeric($row)){
                    //gellery
                    $html = $ex->get_raw_html($boot->config['products_link'] . $row);
                    $title = get_raw_tag_f($html, '<div class="pageTitle">', '</div>');
                    $price = xmoney(get_raw_tag_c($html, '<span class="product_info_price">', '</span>', true));
                    $gallery = get_raw_tag_c($html, '<div id="gallery" style="height: 50px;">', '</div>');
                    $gallery_rows = get_raw_tag($gallery, '<a', '</a>');
                    
                    $img = array();
                    foreach($gallery_rows as $key => $grow){
                        $pic = get_href_from_tag($grow[0]);
                        $img[] = $boot->config['img_dir'] . "/{$row}". '/' . $key . '.jpg';
                        file_put_contents($img[$key], $ex->get_raw_html($pic));
                    }
                    $map['ID'][$id_key] = $row;
                    $map['Title'][$id_key] = $title ?? '-';
                    $map['Price'][$id_key] = $price;
                    $map['img'][$id_key] = implode ( $img, $boot->config['img_implode'] );

                    //main table
                    $main_table = get_raw_tag_c($html, '<table cellpadding="0" cellspacing="0" class="tdb-table">', '</table>');       

                    $main_tables_rows = get_raw_tag($main_table, '<td', '</td>');
                    //info table
                    $table = get_raw_tag_c($html, '<table cellpadding="0" cellspacing="2">', '</table>');       
                    $tables_rows = get_raw_tag($table, '<td', '</td>');
                    //info table

                    $map = $this->get_content($map, $id_key, $tables_rows, $header, $result);
                    $map = $this->get_content($map, $id_key, $main_tables_rows, $header, $result); 
                }
                $dub->set_e();
            }
            $dub->cal();
            // $header = $this->fix_header($map);
            $map = $this->reverse_array($map, $result);
            $map = $this->fix_header($map);
            $map = $this->set_header_object($map);
            // $rez = $this->reverse_array($map);
            to_csv($map, $boot->config['result_dir'], $file);
        }
        $all->set_e();
        $all->cal();
        die('DONE!');
    }

    private function get_content(array $map, int $id_key, array $content, array $header, array $result,  int $_h = 0, int $_r = 1): array{
        for($i = 0; $i < count($content); $i++){
            if (contain($content[$i][0], array('<img', 'div'))){
                unset($content[$i]);
                $content = array_values($content);
                $i--;
                continue;
            }

            if($i % 2 == $_h){
                $header[] = trim_c(strip_tags($content[$i][0]));
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

    private function reverse_array(array $map, array $result): array{
        $header = array_keys($map);
        for($i = 0; $i < count(current($map)); $i++){
            if(!isset($result[$i])){
                $result[$i] = array();
            }
            $e = current($map);
            for($j = 0; $j < count($map); $j++){
                
                array_push($result[$i], $e[$i]);
  
                $e = next($map);
            }
            reset($map);
        }
        return array_merge(array($header), $result);
    }

    private function maping(array $map, int $id_key, array $header, array $result): array{
        foreach($header as $key => $title){
            if(isset($result[$key])){
                $map[$title][$id_key] = $result[$key];
            }else{
                $map[$title][$id_key] = '-';
            }
        }
        return $map;
    }

    private function fix_header(array $map): array{
        $arr = array();
        foreach($map[0] as $key => $row){
            // xlog($row);
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
    
    private function set_header_object(array $map, int $index = 0): array{
        $header = array_values($map[$index]);

        foreach($header as $key => $row){
            $header[$key] = new ProductHeader();
            $header[$key]->value = $row;
        }

        $map[$index] = $header;

        return $map;
    }
}
?>