<?php

require_once 'HTMLExtractor.php';

class xExtractor {

    function __construct(){}

    public function run(){
        $boot = Boot::get_instance();

        $file_content = file_get_contents(__DIR__ . '\\' . $boot->config['products_file']);
        $ids = explode(PHP_EOL, $file_content);

        $ex = new HTMLExtractor(array(
            'cookies' => $boot->config['cookies']
        ));

        $add_header = true;
        $result = array();
        $header = array();
        $st = microtime(true);
        $map = array();

        foreach($ids as $id_key => $row){
            if(!is_dir($boot->root . $boot->config['img_dir'] . "/{$row}")){
                mkdir($boot->root . $boot->config['img_dir'] . "/{$row}");
            }
            if($row && is_numeric($row)){
                sleep($boot->config['sleep']);

                $html = $ex->get_raw_html($boot->config['products_link'] . $row);

                //main table
                $main_table = get_raw_tag_c($html, '<table cellpadding="0" cellspacing="0" class="tdb-table">', '</table>');       
                $main_tables_rows = get_raw_tag($main_table, '<td', '</td>');

                //gellery
                $gallery = get_raw_tag_c($html, '<div id="gallery" style="height: 50px;">', '</div>');
                $gallery_rows = get_raw_tag($gallery, '<a', '</a>');
                
                //info table
                $table = get_raw_tag_c($html, '<table cellpadding="0" cellspacing="2">', '</table>');       
                $tables_rows = get_raw_tag($table, '<td', '</td>');
                //info table
                $this->get_content($map, $id_key, $tables_rows, $header, $result, $add_header);
                $this->get_content($map, $id_key, $main_tables_rows, $header, $result, $add_header);
                //gellery
                $img = array();
                foreach($gallery_rows as $key => $grow){
                    $pic = get_href_from_tag($grow[0]);
                    $img[] = $boot->root . $boot->config['img_dir'] . "/{$row}". '/' . $key . '.jpg';
                    file_put_contents($img[$key], $ex->get_raw_html($pic));
                }
                $map['img'][$id_key] = implode ( $img, ';');
            }
            $add_header = false;
        }
        $header = array_keys ( $map ); 
        
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
        $rez = $this->reverse_array($map);
        
        $file_csv = fopen($boot->root . $boot->config['result_dir'] . '/' . $boot->config['result_file'], "w");
        foreach($rez as $line) {
            fputcsv($file_csv, $line);
        }
        fclose($file_csv);

        $et = microtime(true);

        die('DONE! ' . ($et - $st));
    }

    private function get_content(&$map, int $id_key, array $content, array $header, array $result, bool $add_header = false,  int $_h = 0, int $_r = 1){

        for($i = 0; $i < count($content); $i++){
            if (strpos($content[$i][0], '<img') !== false){
                unset($content[$i]);
                $content = array_values($content);
                $i--;
                continue;
            }

            if($i % 2 == $_h){
                $header[] = strip_tags($content[$i][0]);
            }

            if($i % 2 == $_r){
                $result[] = strip_tags($content[$i][0]);
            }
        } 

        foreach($header as $key => $title){
            $map[$title][$id_key] =  $result[$key];
        }
    }

    private function reverse_array(array $map): array{
        $header = array_keys ( $map ); 
        
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
        $rez = array_merge(array($header), $result);
        return $rez;
    }
}
?>