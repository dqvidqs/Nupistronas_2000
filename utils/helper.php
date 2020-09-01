<?php

function get_raw_tag(string $content, string $start, string $end): array{

    $start = trim($start);
    $end = trim($end);
    $find = array(' ', '/');
    $replace = array('\s', '\/');

    $start = str_replace($find, $replace, $start);
    $end = str_replace($find, $replace, $end);

    $regex = '/'. $start . '(.|\n)*?' . $end . '/m';
    // $regex = '/<table\scellpadding="0"\scellspacing="0"\sclass="tdb-table">(.|\n)*?<\/table>/'; 
    $rr = preg_match_all($regex, $content, $matches, PREG_SET_ORDER);
    return $matches;
}

function get_raw_tag_c(string $content, string $start, string $end): string{
    $start_pos = strpos($content, $start);
    if(!$start_pos){
        return '';
    }
    $end_pos = strpos($content, $end, $start_pos);
    $content = substr($content, $start_pos, $end_pos - $start_pos + strlen($end));
    return $content;
}

function get_href_from_tag(string $tag){
    $regex = '/(?<=href=")(.|\n)*?(?=")/m';
    preg_match_all($regex, $tag, $matches, PREG_SET_ORDER);
    return $matches[0][0];
}

function to_csv(array $map, string $dir, string $file){
    $file_csv = fopen($dir . '/' . str_replace('.txt', '.csv', $file), "w");
    foreach($map as $line) {
        fputcsv($file_csv, $line);
    }
    fclose($file_csv);
}

function get_files(string $dir){
    $files = scandir($dir);
    unset($files[0], $files[1]);
    return array_values($files);
}
?>