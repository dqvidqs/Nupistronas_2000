<?php

class Testing{

    public function run(){
        $dub = new Debugger();
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
        die();
    }
}
?>