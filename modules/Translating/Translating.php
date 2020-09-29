<?php

require_once __PHP__ .'/xDebugger/xDebugger.php';

class Translating extends Controller{

    public function __construct($cfg){
        parent::__construct($cfg);
    }

    public function run(){
        $this->view->render(array(
            'config' => Bootstrap::get_instance()->config_raw,
            'layout' => __LAYOUT__,
            'controller' => 'Translating'
        ), 'view.php');
    }

    public function translate(){
        $all_reuslt = '';
        
        $boot = Bootstrap::get_instance();

        $all = new xDebugger(true, $boot->config['debug']['value']);
        $all->set_s('ALL');
        $dub = new xDebugger(true, $boot->config['debug']['value']);
        
        $files = get_files($boot->config['file_dir']['value']);
        $this->voc = include $boot->config['vocabulary_file']['value'];
        $this->map_v = include $boot->config['map_vocabulary_file']['value'];

        foreach($files as $file){
            if(contain($file, $boot->config['ignore'])){
                continue;
            }

            $dub->set_s('FILE: '. $file);

            $map = array();
            $handler = fopen($boot->config['file_dir']['value'] . "/{$file}", "r");

            while (($array_data = fgetcsv($handler) ) !== false ) {
                $temp_array = $this->translate_by_map($array_data);
                $map[] = $this->translate_by_vocabulary($temp_array);
            }

            to_csv($map, $boot->config['file_dir']['value'],  $boot->config['ignore']['value'] . $file);

            $dub->set_e();
            $all_reuslt .= $dub->cal();
        }
        $all->set_e();
        $all_reuslt .= $all->cal();
        $this->view->render(array(
            'result' => $all_reuslt
        ), 'result.php');
    }

    private function translate_by_map(array $array){
        foreach($array as $key => $element){
            if(isset($this->map_v[$element])){
                $array[$key] = $this->map_v[$element];
            }
        }
        return $array;
    }

    private function translate_by_vocabulary(array $array): array{
        $translated_lines = array();

        foreach($array as $key => $element){
            $words = explode(' ', $element);
            $translated = array();

            foreach($words as $word){
                if($replace = contain_key($word, $this->voc)){
                    if(ctype_upper($word[0])){
                        $translated[] = strtoupper($replace[0]) . substr($replace, 1);
                    }else{
                        $translated[] = $replace;                        
                    }
                }else{
                    $translated[] = $word;                        
                }
            }
            $translated_lines[] = implode(' ', $translated);
        }
        return $translated_lines;
    }
    
    public function save_config(){
        $config_raw = Bootstrap::get_instance()->config_raw;
        $config_file = Bootstrap::get_instance()->config_file;
        foreach($config_raw as $key => $row){
            $config_raw[$key]['value'] = $_POST[$key];
        }
        file_put_contents($config_file, "<?php return " . var_export($config_raw, true) . " ?>");
        header('Location: /Translating?saved=true');
    }

}
?>