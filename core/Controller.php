<?php

class Controller{

    protected $view = null;
    protected $module = null;
    protected $root = null;
    protected $config = null;

    public function __construct($cfg = null){

        if($cfg){
            $this->root = $cfg['root'];
            $this->module = $cfg['module'];
        }

        $this->view = new View($cfg);

        $this->cfgr = Bootstrap::get_instance()->config_raw;
        $this->cfg = Bootstrap::get_instance()->config;

        if($cfg['controller'] && $cfg['method']){
            $this->_LINEAGE['controller'] = $cfg['controller'];
            $this->_LINEAGE['method'] = $cfg['method'];
            $this->_LINEAGE['full'] = '/'. $cfg['controller'] . '/' . $cfg['method'];
        }
    }

    public function save_config(){
        $config_raw = Bootstrap::get_instance()->config_raw;
        $config_file = Bootstrap::get_instance()->config_file;
        foreach($config_raw as $key => $row){
            $config_raw[$key]['value'] = $_POST[$key];
        }
        file_put_contents($config_file, "<?php return " . var_export($config_raw, true) . " ?>");
        header('Location: /' . $this->_LINEAGE['controller'] . '?saved=true');
    }
}
?>