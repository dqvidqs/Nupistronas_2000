<?php
class Translating{
    public function run(){
        $boot = Boot::get_instance();

        $all = new Debugger(true, $boot->config['debug']);
        $all->set_s('ALL');
        $dub = new Debugger(true, $boot->config['debug']);
        
        $files = get_files($boot->config['file_dir']);
        $this->voc = include $boot->config['vocabulary_file'];
        $this->map_v = include $boot->config['map_vocabulary_file'];

        foreach($files as $file){
            if(contain($file, $boot->config['ignore'])){
                continue;
            }

            $dub->set_s('FILE: '. $file);

            $map = array();
            $handler = fopen($boot->config['file_dir'] . "/{$file}", "r");

            while (($array_data = fgetcsv($handler) ) !== false ) {
                $temp_array = $this->translate_by_map($array_data);
                $map[] = $this->translate_by_vocabulary($temp_array);
            }

            to_csv($map, $boot->config['file_dir'],  $boot->config['ignore'] . $file);

            $dub->set_e();
            $dub->cal();
        }
        $all->set_e();
        $all->cal();
        die('DONE!');
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

}
?>