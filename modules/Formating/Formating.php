<?php
class Formating extends Controller{

    public function __construct($cfg){
        parent::__construct($cfg);
    }

    public function run(){
        $boot = Bootstrap::get_instance();
        
        $targets = get_files($boot->config['targets_dir']['value']);
        $additions = get_files($boot->config['additions_dir']['value']);
        $this->view->render(array(
            'targets' => $targets,
            'additions' => $additions,
            'config' => $boot->config_raw,
            'layout' => __LAYOUT__,
            'controller' => 'Formating'
        ), 'view.php');
    }

    public function format_all(){
        $boot = Bootstrap::get_instance();
        $_POST['skip'] = true;
        $targets = get_files($boot->config['targets_dir']['value']);
        foreach($targets as $target){
            $_POST['target'] = $target;
            $this->format();
        }
        die('DONE!');
    }

    public function format(){

        $boot = Bootstrap::get_instance();

        $target = from_csv($boot->config['targets_dir']['value'], $_POST['target']);
        $addition = null;
        if(!empty($_POST['additions'])){
            $addition = include $boot->config['additions_dir']['value'] . '/'. $_POST['additions'];
        }

        $defaults = require $boot->config['defaults_dir']['value'] . '/' . get_files($boot->config['defaults_dir']['value'])[0];

        $map = array();
        $push = 0;
        $product_key = $boot->config['number_starts']['value'];
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
                $map[0][$key + $push] = str_replace('@number@', $product_key, $boot->config['item_name']['value']);
                $this->append_map_val($map, $row[1], $key, $push);

                //value
                $map[0][$key + $push] = str_replace('@number@', $product_key, $boot->config['item_value']['value']);
                $this->append_map($map, $target, $key, $push, true);

                //visible
                $map[0][$key + $push] = str_replace('@number@', $product_key, $boot->config['item_visible']['value']);
                $this->append_map_val($map, (int)$boot->config['item_visible_default']['value'], $key, $push);

                //visible
                $map[0][$key + $push] = str_replace('@number@', $product_key, $boot->config['item_public']['value']);
                $this->append_map_val($map, (int)$boot->config['item_public_default']['value'], $key, $push);
                
                $product_key++;
            }else{
                $header = $row[1];
                $this->append_map($map, $target, $key);
            }
        }
        to_csv($map, $boot->config['result_dir']['value'], 'formatted_' . $_POST['target'] );
        if(!$_POST['skip']){
            $this->view->render(array(
                'result' => 'DONE!'
            ), 'result.php');
        }
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
        
    public function save_config(){
        $config_raw = Bootstrap::get_instance()->config_raw;
        $config_file = Bootstrap::get_instance()->config_file;
        foreach($config_raw as $key => $row){
            $config_raw[$key]['value'] = $_POST[$key];
        }
        file_put_contents($config_file, "<?php return " . var_export($config_raw, true) . " ?>");
        header('Location: /Formating?saved=true');
    }
}
?>