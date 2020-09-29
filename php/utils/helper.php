<?php

function get_raw_tag(string $content, string $start, string $end): array{

    $start = trim($start);
    $end = trim($end);
    $find = array(' ', '/');
    $replace = array('\s', '\/');

    $start = str_replace($find, $replace, $start);
    $end = str_replace($find, $replace, $end);

    $regex = '/'. $start . '(.|\n)*?' . $end . '/m';
    $rr = preg_match_all($regex, $content, $matches, PREG_SET_ORDER);
    return $matches;
}

function get_raw_tag_c(string $content, string $start, string $end, bool $remove_first_tag = false): string{
    $start_pos = strpos($content, $start) + ($remove_first_tag ? strlen($start) : 0);
    if(!$start_pos){
        return '';
    }
    $end_pos = strpos($content, $end, $start_pos) + ($remove_first_tag  ? 0 : strlen($end));
    $content = substr($content, $start_pos, $end_pos - $start_pos);
    return $content;
}

function get_raw_tag_f(string $content, string $start, string $end): string{
    $arr = get_raw_tag($content, $start, $end);
    $value = '';
    $content = array_walk_recursive($arr, function($item, $key) use (&$value){
        if($item && !$value){
            $value = strip_tags($item);
        }
    });
    return $value;
}

function get_href_from_tag(string $tag){
    $regex = '/(?<=href=")(.|\n)*?(?=")/m';
    preg_match_all($regex, $tag, $matches, PREG_SET_ORDER);
    return $matches[0][0];
}

function to_csv(array $map, string $dir, string $file): void{
    $file_csv = fopen($dir . '/' . str_replace('.txt', '.csv', $file), "w");
    foreach($map as $line) {
        fputcsv($file_csv, $line);
    }
    fclose($file_csv);
}

function from_csv(string $dir, string $file): array{
    $map = array();
    if (($handler = fopen($dir . '/' . $file, "r")) !== false) {
        while (($data = fgetcsv($handler, 10000000, ",")) !== false) {
            $map[] = $data;
        }
    }
    fclose($handler);
    return $map;
}

function get_files(string $dir){
    $files = scandir($dir);
    unset($files[0], $files[1]);
    return array_values($files);
}

function trim_c(string $row, array $remove = array()){
    $row = trim($row);

    $remove = array_merge(
        $remove,
        array(
            '&nbsp;'
        )
    );

    $row = str_replace($remove, '', $row);
    return $row;
}

function contain(string $object, $search): bool{
    if(!is_array($search)){
        $search = array($search);
    }

    foreach($search as $row){
        if (strpos($object, $row) !== false) {
            return true;
        }
    }
    return false;
}

function contain_key(string $object, array $search, bool $igone_sensitive = true){
    foreach($search as $key => $row){
        if($igone_sensitive){
            if (strpos(strtolower($object), strtolower($key)) !== false) {
                return $row;
            }
        }else{
            if (strpos($object, $key) !== false) {
                return $row;
            }
        }
    }
    return false;
}

?>