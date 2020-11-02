<?php 

require_once __PHP__ .'/xDebugger/xDebugger.php';

class xList {

    private $count = 0;

    private $dir = __ROOT__;

    protected $debug;

    protected $map = array();

    const FILES = array(
        'php',
        'txt'
    );

    const HEAD = array(
        'count',
        'debug',
        'map'
    );

    const ATTRIBUTES = array(
        'list_id',
        'product_id',
        'page',
        'name',
        'group',
        'extra'
    );

    public function __construct(bool $debug = true) {

        if($debug){
            $this->debug = new xDebugger();
            $this->debug->set_s('INI');
        }

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

    public function add(int $list_id, int $product_id, int $page, string $name, string $group, $exta = null) : void{

        $this->map[$list_id][$group][$product_id] = array(
            SELF::ATTRIBUTES[0] => $list_id,
            SELF::ATTRIBUTES[1] => $product_id,
            SELF::ATTRIBUTES[2] => $page,
            SELF::ATTRIBUTES[3] => $name,
            SELF::ATTRIBUTES[4] => $group,
            SELF::ATTRIBUTES[5] => $exta
        );

        $this->count++;
    }

    public function load(string $filepath) : void {

        $data = include $filepath;

        $count = $data[SELF::HEAD[0]];

        // TODO
        // if(is_numeric($count)){
            
        // }

        $map = $data[SELF::HEAD[2]];

        // if(!is_array($map)){

        // }

        $this->map = $map;
        $this->count = $count;
    }

    public function to_array(string $attribute) : array {

        if(!in_array($attribute, SELF::ATTRIBUTES)){
            throw new xException('Attribute does not exists!');
        }

        $array = array();

        array_walk_recursive($this->map, function($value, $key) use ($attribute, &$array){
            if($key == $attribute){
                $array[] = $value;
            }
        }, $array);

        return $array;
    }

    public function clear(){
        $this->count = 0;
        $this->map = array();
        // $this->debug->set_s('INI');
    }

    public function to_file(string $filepath = '', string $attribute = '') : void {

        if(!$filepath){
            $filepath = $this->dir . '/xList_result_' . date('YmdHis') . '.php';
        }

        $ext = end(explode('.', $filepath));
        if(!in_array($ext, SELF::FILES)){
            throw new xException('Extention does not exists in list!');
        }

        switch($ext){
            case 'php' :
                $this->to_php($filepath);
            break;
            case 'txt' :
                if(!$attribute){
                    throw new xException('Attribute does not exists!');
                }else if(!in_array($attribute, SELF::ATTRIBUTES)){
                    throw new xException('Attribute does not exists in list!');
                }
                $this->to_txt($filepath, $attribute);
            break;
        }
    }

    private function to_php(string $filepath) : void {
        $cal = false;

        array_to_file($filepath, array(
            SELF::HEAD[0] => $this->count,
            SELF::HEAD[1] => $cal,
            SELF::HEAD[2] => $this->map
        ));
    }

    private function to_txt(string $filepath, string $attribute) : void {
        $content = '';

        array_walk_recursive($this->map, function($value, $key) use ($attribute, &$content){
            if($key == $attribute){
                $content .= $value . PHP_EOL;
            }
        }, $content);

        file_put_contents($filepath, $content);
    }

    public function get_log() : string{
        $this->debug->set_e();
        $this->debug->cal(false);
        return $this->debug ? $this->debug->get_log() : 'FALSE';
    }

}
?>