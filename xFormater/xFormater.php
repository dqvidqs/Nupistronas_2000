<?php
class xFormater {

    function __construct(){}

    public function run(){
        $boot = Boot::get_instance();
        
        $targets = get_files($boot->config['targets_dir']);
        $additions = get_files($boot->config['additions_dir']);

        include 'view.php';
        die();
    }
}
?>