<?php
class Format {

    function __construct(){}

    public function run(){

        $boot = Boot::get_instance();

        $target = from_csv($boot->config['targets_dir'], $_POST['target']);

        if(!empty($_POST['additions'])){
            $addition = include $boot->config['additions_dir'] . '/'. $_POST['additions'];
        }

        $defaults = require $boot->config['defaults_dir'] . '/' . get_files($boot->config['defaults_dir'])[0];

        $map = array();
        $push = 0;
        $product_key = $boot->config['number_starts'];
        $add_defaults = true;

        foreach($target[0] as $key => &$header){
            $row = explode('|', $header);
            if($row[0] == 'arg'){

                if($add_defaults){
                    foreach($defaults as $default_key => $default_val){
                        $map[0][$key + $push] = $default_key;
                        $this->append_map_val($map, $default_val, $key, $push);
                    }
                    if(is_array($addition)){
                        foreach($addition as $addition_key => $addition_val){
                            $map[0][$key + $push] = $addition_key;
                            $this->append_map_val($map, $addition_val, $key, $push);
                        }
                    }
                    $add_defaults = false;
                }

                //name
                $map[0][$key + $push] = str_replace('@number@', $product_key, $boot->config['item_name']);
                $this->append_map_val($map, $row[1], $key, $push);

                //value
                $map[0][$key + $push] = str_replace('@number@', $product_key, $boot->config['item_value']);
                $this->append_map($map, $target, $key, $push, true);

                //visible
                $map[0][$key + $push] = str_replace('@number@', $product_key, $boot->config['item_visible']);
                $this->append_map_val($map, (int)$boot->config['item_visible_default'], $key, $push);

                //visible
                $map[0][$key + $push] = str_replace('@number@', $product_key, $boot->config['item_public']);
                $this->append_map_val($map, (int)$boot->config['item_public_default'], $key, $push);
                
                $product_key++;
            }else{
                $header = $row[1];
                $this->append_map($map, $target, $key);
            }
        }
        to_csv($map, $boot->config['result_dir'], 'formatted_' . $_POST['target'] );
        die('DONE!');
    }

    private function append_map(array &$map, array $old_map, $key, int &$push = 0, bool $skip_first = false): void{
        $skip = true;
        foreach($old_map as $map_key => $row){
            if($skip && $skip_first){
                $skip = false;
                continue;
            }
            $map[$map_key][$key + $push] = $row[$key];
        }
        $push++;
    }

    private function append_map_val(array &$map, $value, $key, int &$push, bool $skip_first = true): void{
        $skip = true;
        foreach($map as $map_key => &$row){
            if($skip && $skip_first){
                $skip = false;
                continue;
            }
            $row[$key + $push] = $value;
        }
        $push++;
    }
}
?>