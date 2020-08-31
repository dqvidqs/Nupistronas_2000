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
        $this->root = __DIR__;
        $arr =  explode('/', $url);

        require_once "{$arr[1]}/{$arr[1]}.php";

        $this->config_file = "{$arr[1]}/config.php";
        $this->get_config();
        $this->create_folters();

        $class = new $arr[1]();
        $class->run();  
    }

    private function get_config(): void{
        if(file_exists($this->config_file)){
            $this->config = include "{$this->config_file}";
        }
    }

    private function create_folters(): void{
        foreach($this->config as $key => $row){
            if($row && strpos($key, '_dir') !== false){
                if(!is_dir($this->root . $row)){
                    mkdir($this->root . $row, 0777, true);
                }
            }
        }
    }
}
?>