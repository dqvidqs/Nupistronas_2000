<?php
class Boot{

    private static $instance;

    function __construct(){}

    public static function get_instance(){
        if(self::$instance === null){
            self::$instance = new Boot();
        }
        return self::$instance;
    }

    public function call(string $url): void{
        $this->root = __ROOT__;
        $arr =  explode('/', substr($url, 1));

        if(!is_dir("{$arr[0]}")){
            throw new xException("NOT FOUND DIR {$arr[0]}", 1);          
        }else if(!is_file("{$arr[0]}/{$arr[0]}.php")){
            throw new xException("NOT FOUND FILE {$arr[0]}.php", 1);          
        }else if(!is_file("{$arr[0]}/{$arr[0]}.php")){
            throw new xException("NOT FOUND FILE config.php", 1);     
        }

        require_once "{$arr[0]}/{$arr[0]}.php";
        $this->config_file = "{$arr[0]}/config.php";
        $this->get_config();

        $class = new $arr[0]();
        $class->run();  
    }

    private function get_config(): void{
        if(file_exists($this->config_file)){
            $this->config = include "{$this->config_file}";
            $this->create_folters();
        }
    }

    private function create_folters(): void{
        foreach($this->config as $key => $row){
            if($row && strpos($key, '_dir') !== false){
                if(!is_dir($this->root . $row)){
                    mkdir($this->root . $row, 0777, true);
                }
                $this->config[$key] = $this->root . (strpos($row, '/')  === 0 ? $row : "/{$row}");
            }
        }
    }
}
?>