<?php

require_once __PHP__ .'/xDebugger/xDebugger.php';

class Testing extends Controller{

    public function __construct($cfg){
        parent::__construct($cfg);
    }

    public function run(){
        $dub = new xDebugger();
        $dub->set_s();
        for($i = 0; $i < 10000; $i++){
            trim_c('wasd    wwww wwww  tekstas tekstas wwwww', array('wwww'));
        }
        $dub->set_s();
        for($i = 0; $i < 1000; $i++){
            trim_c('wasd    wwww wwww  tekstas tekstas wwwww', array('wwww'));
        }
        $dub->set_s();
        for($i = 0; $i < 100000; $i++){
            trim_c('wasd    wwww wwww  tekstas tekstas wwwww', array('wwww'));
        }
        $dub->set_e();
        $dub->set_e();
        $dub->set_e();
        $string = $dub->cal();
        $this->view->render(array(
            'result' => $string
        ));
    }
}
?>