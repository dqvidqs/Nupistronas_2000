<?php
require_once __PHP__ .'/xExtractor/xExtractor.php';
require_once __PHP__ .'/xDebugger/xDebugger.php';

class TDBaltic extends Controller{

    public function __construct($cfg){
        parent::__construct($cfg);
    }
    
    public function run(){
        $this->view->render(array(
            'config' => Bootstrap::get_instance()->config_raw,
            'layout' => __LAYOUT__,
            'controller' => 'TDBaltic'
        ), 'view.php');
    }

    public function execute(){
        $all_result = '';
        $boot = Bootstrap::get_instance();
        $this->order = include $boot->config['order_file']['value'];

        $all = new xDebugger(true, $boot->config['debug']['value']);
        $all->set_s('ALL');
        $dub = new xDebugger(true, $boot->config['debug']['value']);
        $files = get_files($boot->config['products_dir']['value']);

        $ex = new xExtractor();

        $html_ex = new xHTMLExtractor(array(
            'cookies' => $boot->config['cookies']['value']
        ));

        foreach($files as $file_index => $file){

            if($file_index != 0){
                sleep($boot->config['sleep']['value']);
            }

            $file_content = file_get_contents($boot->config['products_dir']['value'] .'/'. $file);
            $ids = explode(PHP_EOL, $file_content);
            
            $map = array();
            $result = array();
            $header = array();
            
            foreach($ids as $id_key => $row){
                if(!$row){
                    continue;
                }
                if($id_key != 0){
                    sleep($boot->config['sleep']['value']);
                }

                $dub->set_s('FILE: ' . $file . '; ID: <a target="_blank" href="' . $boot->config['products_link']['value'] . $row . '">' . $row . '</a>; STARTED!: <br>');

                if(!is_dir($boot->config['img_dir']['value'] . "/{$row}")){
                    mkdir($boot->config['img_dir']['value'] . "/{$row}");
                }
                if($row && is_numeric($row)){
                    //gellery
                    $html = $html_ex->get_raw_html($boot->config['products_link']['value'] . $row);
                    $title = get_raw_tag_f($html, '<div class="pageTitle">', '</div>');
                    $price = $ex->get_price(get_raw_tag_c($html, '<span class="product_info_price">', '</span>', true));
                    $gallery = get_raw_tag_c($html, '<div id="gallery" style="height: 50px;">', '</div>');
                    $gallery_rows = get_raw_tag($gallery, '<a', '</a>');
                    
                    $img = array();
                    $img_ex = array();
                    foreach($gallery_rows as $key => $grow){
                        $pic = get_href_from_tag($grow[0]);
                        $img[] = $boot->config['img_dir']['value'] . "/{$row}". '/' . $key . '.jpg';
                        $img_ex[] = $boot->config['img_exdir']['value'] . "{$row}". '/' . $key . '.jpg';
                        file_put_contents($img[$key], $html_ex->get_raw_html($pic));
                    }
                    $map['add|ID'][$id_key] = $row;
                    $map['add|Title'][$id_key] = $title ?? '-';
                    $map['add|Price'][$id_key] = $price;
                    $map['add|img'][$id_key] = implode ( $img_ex, $boot->config['img_implode']['value'] );

                    //main table
                    $main_table = get_raw_tag_c($html, '<table cellpadding="0" cellspacing="0" class="tdb-table">', '</table>');       

                    $main_tables_rows = get_raw_tag($main_table, '<td', '</td>');
                    //info table
                    $table = get_raw_tag_c($html, '<table cellpadding="0" cellspacing="2">', '</table>');       
                    $tables_rows = get_raw_tag($table, '<td', '</td>');
                    //info table

                    $map = $ex->get_content($map, $id_key, $tables_rows, $header, $result, $prefix = 'add');
                    $map = $ex->get_content($map, $id_key, $main_tables_rows, $header, $result, $prefix = 'arg'); 
                }
                $dub->set_e();
                $all_result .= $dub->cal(false);
            }
            // $header = $this->fix_header($map);
            $map = $ex->reverse_array($map);
            $map = $ex->fix_header($map);
            $map = $ex->set_header_object($map);
            $map = $ex->order($map);
            to_csv($map, $boot->config['result_dir']['value'], $file);
        }
        $all->set_e();
        $all_result .= $all->cal(false);
        $this->view->render(array(
            'result' => $all_result,
        ), 'result.php');
    }

    public function save_config(){
        $config_raw = Bootstrap::get_instance()->config_raw;
        $config_file = Bootstrap::get_instance()->config_file;
        foreach($config_raw as $key => $row){
            $config_raw[$key]['value'] = $_POST[$key];
        }
        file_put_contents($config_file, "<?php return " . var_export($config_raw, true) . " ?>");
        header('Location: /TDBaltic?saved=true');
    }

    public function update(){
        $config = Bootstrap::get_instance()->config;

        $debug = new xDebugger(true, $config['debug']['value']);
        $debug->set_s('ALL');

        $files = get_files($config['products_dir']['value']);

        $ex = new xExtractor();

        $html_ex = new xHTMLExtractor(array(
            'cookies' => $config['cookies']['value']
        ));

        $not_contain = array(
            'Prekės kodas', 'EAN kodas', 'Garantija', 'months'
        );

        $map = array(array(
            'Prekės kodas' , 'Kaina', 'Kiekis'
        ));
        foreach($files as $file_index => $file){
            $file_content = file_get_contents($config['products_dir']['value'] .'/'. $file);
            $ids = explode(PHP_EOL, $file_content);
            $errors = array();

            foreach($ids as $id_key => $row){
                $error = false;
                if(!$row){
                    continue;
                }
                if($id_key != 0){
                    sleep($config['sleep']['value']);
                }
                if($row && is_numeric($row)){
                    //gellery
                    $html = $html_ex->get_raw_html($config['products_link']['value'] . $row);
                    // xlog($row);
                    $price = $ex->get_price(get_raw_tag_c($html, '<span class="product_info_price">', '</span>', true));
                    // xlog($price);
                    if(!is_numeric($price)){
                        // throw new xException("ID: {$row} PRICE NOT FOUND!");
                        $error = true;
                        $price = "ID: {$row} PRICE NOT FOUND!";
                    }
                    $table = get_raw_tag_c($html, '<table cellpadding="0" cellspacing="2">', '</table>');       
                    $code = get_raw_tag_s($table, '<td>', '<\/td>', true); 
                    if(!is_array($code) || empty($code[1][0])){
                        // throw new xException("ID: {$row} CODE NOT FOUND!");
                        $error = true;
                        $code = "ID: {$row} CODE NOT FOUND!";
                    }else{
                        $code = $code[1][0];
                        foreach($not_contain as $n_contain){
                            if(contain($code, $n_contain)){
                                // throw new xException("ID: {$row} CODE NOT FOUND!");
                                $error = true;
                                $code = "ID: {$row} CODE NOT FOUND!";
                            }
                        }
                    }
                    $table = get_raw_tag_c($html, '<div id="GoodsArticleListStock_ctl" style="display:inline;">', '</div>');
                    $quantity = get_raw_tag_s($table, '<td class="table-td table-td nowrap"', '<\/td>', true);
                    if(!is_array($quantity) || empty($quantity[0][0])){
                        // throw new xException("ID: {$row} QUANTITY NOT FOUND!");
                        $error = true;
                        $quantity = "ID: {$row} QUANTITY NOT FOUND!";
                    }else{
                        $quantity = $quantity[0][0];
                    }

                    if(!$error){
                        $map[] = array(
                            strip_tags($code), $price, strip_tags($quantity)
                        );
                    }else{
                        $errors[] = array(
                            $code, $price, $quantity
                        );
                    }
                }
            }
        }
        to_csv($map, $config['result_update_dir']['value'], 'ALL_UPDATED_PRODUCTS.txt');
        $debug->set_e();
        xlogw($errors);
        die($debug->cal(false));
    }
}
?>