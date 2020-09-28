<?php

class run extends Controller{

    public function __construct($cfg){
        parent::__construct($cfg);
    }

    public function run(){
        $modules = get_files(__MODULES__);
        foreach($modules as $key => $module){
            if(contain($module, '.php')){
                unset($modules[$key]);
            }
        }
        
        $this->view->render(array(
            'main_modules' => $modules
        ));
    }
}