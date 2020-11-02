<?php 

require_once 'xProductHeader.php';
require_once __PHP__ .'/xDebugger/xDebugger.php';

class xProducts {

    private $dir = __ROOT__;

    const TYPES = array(
        'add',
        'arg'
    );

    const FILES = array(
        'csv'
    );

    private const HEADER_ATTRIBUTES = array(
        'type',
        'name'
    );

    private $header;
    
    private $body;

    private $map;

    private $debug;

    public function __construct(bool $debug = true) {

        if($debug){
            $this->debug = new xDebugger();
            $this->debug->set_s('INI');
        }

        $this->header = array();
        $this->body = array();
    }

    public function set_dir(string $dir) : void {
        if(is_dir($dir)){
            $len = strlen($dir);
            if(strpos($dir, '/', $len - 1)){
                $dir = substr($dir, 0 , -1);
            }
            $this->dir = $dir;
        }else{
            throw new xException('Dir ' . $dir . ' is not exists!');
        }
    }

    public function add(int $id, string $atribute, $value, string $type = SELF::TYPES[0]) : void{

        if(!$this->header[$atribute]){
            $this->header[$atribute] = array(
                'type' => $type,
                'name' => $atribute
            );
        }

        $this->body[$id][$atribute] = $value;
    }

    public function to_map() : array {

        if(!count($this->header)){
            return array();
        }

        $map = array();
        $row = array();

        foreach($this->header as $h){
            $row[] = $h[SELF::HEADER_ATTRIBUTES[0]] . '|' . $h[SELF::HEADER_ATTRIBUTES[1]];
        }

        $map[] = $row;
        $row = array();

        foreach($this->body as $b){
            foreach($this->header as $key => $h){
                $row[] = $b[$key] ?: '-';
            }

            $map[] = $row;
            $row = array();
        }

        $this->map = $map;

        return $map;
    }

    public function clear(){
        $this->header = array();
        $this->body = array();
        $this->map = array();
    }

    public function to_file(string $filepath = '') : void{

        if(!$filepath){
            $filepath = $this->dir . '/xProducts_result_' . date('YmdHis') . '.csv';
        }

        $ext = end(explode('.', $filepath));

        if(!in_array($ext, SELF::FILES)){
            throw new xException('Extention does not exists in list!');
        }

        switch($ext){
            case 'csv' : 
                $this->to_csv($filepath);
            break;
        }
    }

    private function to_csv(string $filepath) : void{
        $file_csv = fopen($filepath, "w");
        foreach($this->map as $line) {
            fputcsv($file_csv, $line);
        }
        fclose($file_csv);
    }

    public function get_log() : string{
        $this->debug->set_e();
        $this->debug->cal(false);
        return $this->debug ? $this->debug->get_log() : 'FALSE';
    }
}

?>