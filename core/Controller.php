<?php

class Controller{

    protected $view = null;
    protected $module = null;
    protected $root = null;

    public function __construct($cfg = null){

        if($cfg){
            $this->root = $cfg['root'];
            $this->module = $cfg['module'];
        }

        $this->view = new View($cfg);
    }
}
?>