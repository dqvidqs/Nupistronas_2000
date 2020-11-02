<?php

class View{

    protected $module = null;
    protected $view = null;
    protected $root = null;
    public $_LINEAGE = array();
    public $_ARR = array();

    public function __construct($cfg){
        if($cfg){
            $this->root = $cfg['root'];
            $this->view = $cfg['module'];
        }

        if($cfg['controller'] && $cfg['method']){
            $this->_LINEAGE['controller'] = $cfg['controller'];
            $this->_LINEAGE['method'] = $cfg['method'];
            $this->_LINEAGE['full'] = '/'. $cfg['controller'] . '/' . $cfg['method'];
        }
    }

    public function render(?array $assigned = array(), $plain = null){
        $_ARR['_LINEAGE'] = $this->_LINEAGE;

        $_ARR['_LAYOUT'] = __LAYOUT__;

        if($assigned && count($assigned)){
            $_ARR = array_merge($assigned, $_ARR);
        }

        $this->_ARR = $_ARR;

        $_MODULE = $this->get_module($_ARR, $plain);

        $this->show_page($_MODULE);
        die();
    }

    private function get_module($CORE_ARGUMENT, $CORE_PLAIN){
        ob_start();
            foreach($CORE_ARGUMENT as $key => $value){
                $$key = $value;
            }

            $key = null;
            $value = null;
            $CORE_ARGUMENT = null;

            if($CORE_PLAIN == null && is_file($this->view)){
                include $this->view;
            }elseif(is_file($this->root . "/{$CORE_PLAIN}")){
                include $this->root . "/{$CORE_PLAIN}";
            }
            $_MODULE = ob_get_contents();
            
        ob_end_clean();
        return $_MODULE;
    }

    private function show_page($_MODULE){
        ob_start();
            include __LAYOUT__ . '/module.php';
            $_PAGE = ob_get_contents();
        ob_end_clean();
            
        ob_clean();
        echo $_PAGE;
    }
}
?>