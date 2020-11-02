<?php
class Bootstrap{

    private static $instance;

    function __construct(){}

    public static function get_instance(){
        if(self::$instance === null){
            self::$instance = new Bootstrap();
        }
        return self::$instance;
    }

    public function call(string $url): void{

        $get =  explode('?', substr($url, 1));
        $arr =  explode('/', substr($get[0], 0));
        
        if(empty(current($arr))){
            $this->root = __MODULES__;
            require_once "modules/run.php";
            $class = new run(array(
                'root' => __MODULES__,
                'module' => __MODULES__ . '/view.php',
                'controller' => 'index',
                'method' => 'run'
            ));
            $class->run();
        }
        else if(!is_dir(__MODULES__ . "/{$arr[0]}")){
            throw new xException("NOT FOUND DIR {$arr[0]}", 1);          
        }else if(!is_file(__MODULES__ . "/{$arr[0]}/{$arr[0]}.php")){
            throw new xException("NOT FOUND FILE {$arr[0]}.php", 1);          
        }

        $this->root = __MODULES__ . "/{$arr[0]}";
        
        if(is_file(__MODULES__ . "/{$arr[0]}/config.php")){
            $this->config_file = __MODULES__ . "/{$arr[0]}/config.php";
            $this->get_config();
        }
        
        require_once __MODULES__ . "/{$arr[0]}/{$arr[0]}.php";
        $class = new $arr[0](array(
            'root' => $this->root ,
            'module' => $this->root . "/view.php",
            'controller' => $arr[0],
            'method' => isset($arr[1]) && !empty($arr[1]) ? $arr[1] : 'run',
        ));
        
        if(isset($arr[1]) && !empty($arr[1])){
            if(method_exists($class, $arr[1])){
                $class->{"{$arr[1]}"}();  
            }else{
                throw new xException("METHOD {$arr[1]}() DO NOT EXISTS!", 1);          
            }
        }else{
            if(method_exists($class, 'run')){
                $class->run();  
            }else{
                throw new xException("METHOD run() DO NOT EXISTS!", 1);          
            }
        }
    }

    private function get_config(): void{
        if(file_exists($this->config_file)){
            $this->config = include "{$this->config_file}";
            $this->config_raw = include "{$this->config_file}";
            $this->set_roots();
        }
    }

    private function set_roots(): void{
        foreach($this->config as $key => $row){
            if($row && contain($key, '_dir')){

                $this->config[$key]['value'] = (strpos($row['value'], '/')  === 0 ? __ROOT__ . "{$row['value']}" : $this->root . "/{$row['value']}");
                
                if(!is_dir($this->config[$key]['value'])){
                    mkdir($this->config[$key]['value'], 0777, true);
                }
            }

            if($row && contain($key, '_file')){
                $this->config[$key]['value'] = (strpos($row['value'], '/')  === 0 ? __ROOT__ . "{$row['value']}"  : $this->root . "/{$row['value']}");
            }
        }
    }
}
?>