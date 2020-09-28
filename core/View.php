<?php

class View{

    protected $module = null;
    protected $view = null;
    protected $root = null;

    public function __construct($cfg){
        if($cfg){
            $this->root = $cfg['root'];
            $this->view = $cfg['module'];
        }
    }

    public function render(array $assigned_0111111111 = array(), $plain = null){
        if(count($assigned_0111111111)){
            foreach($assigned_0111111111 as $key_0111111111 => $value_0111111111){
                $$key_0111111111 = $value_0111111111;
            }
        }
        // xlog(ob_get_clean());
        ob_start();
            if($plain == null && is_file($this->view)){
                include $this->view;
            }elseif(is_file($this->root . "/{$plain}")){
                include $this->root . "/{$plain}";
            }
            $module = ob_get_contents();
        ob_end_clean(); 
        ob_start();
            include __LAYOUT__ . '/module.php';
            $page = ob_get_contents();
        ob_end_clean();

        ob_clean();

        echo $page;
        die();
    }
}
?>