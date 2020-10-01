<?php

include __PHP__ . '/xHTMLExtractor/xHTMLExtractor.php';
include __PHP__ .'/xDebugger/xDebugger.php';

class EpicStealer extends Controller{

    public function __construct($cfg){
        parent::__construct($cfg);
    }

    public function run(){
        $this->view->render();
    }

    public function list(){
        // die('asd');
        $config = Bootstrap::get_instance()->config;

        $debug = new xDebugger(true, $config['debug']['value']);
        $debug->set_s();

        $tree = file_get_contents($config['tree_file']['value']);

        $ids_raw = get_raw_tag_s($tree, '/ext:tree-node-id="', '"');

        foreach($ids_raw as $id){
            $ids[] = $id[1];
        }

        array_to_file($config['result_dir']['value'] . '/' . $config['result_list']['value'], $ids);

        $debug->set_e();
        die($debug->cal(false));
    }

    public function page(){
        $config = Bootstrap::get_instance()->config;

        $debug = new xDebugger(true, $config['debug']['value']);
        $debug->set_s();

        $ids = include $config['result_dir']['value'] . '/' . $config['result_list']['value'];
        
        $ids = array_slice($ids, 0, 3);
        // xlog($ids);
        // $id = 9 ;
        // 3001 NONE
        // 9 MANY
        // 1085 TWO
        $map = array();

        foreach($ids as $id){
            $ex = new xHTMLExtractor(array(
                'url' => $config['list_link']['value'] . $id,
                'cookies' => Bootstrap::get_instance()->config['cookies']['value']
            ));

            $html = $ex->get_raw_html($config['list_link']['value'] . $id . '/1/1');;

            $form_state = get_raw_tag_s($html, 'Qform__FormState" value="', '"', true);
            // xlog($form_state);
            if(!is_array($form_state) || empty($form_state[0][1])){
                throw new xException("Qform__FormState do not exists! LIST ID: {$id}", 1);
            }
            $form_state = $form_state[0][1];

            $form_control = get_raw_tag_s($html, 'r"><span id="', '" class="paginator"', true);
            if(!is_array($form_control) || empty($form_control[0][1])){
                throw new xException("Qform__FormControl do not exists! LIST ID: {$id}", 1);
            }
            $form_control = $form_control[0][1];

            $last_page = get_raw_tag($html, '<span class="page">', '</span');
            $last_page = end($last_page);
            if(is_array($last_page)){
                $last_page = strip_tags($last_page[0]);
                $last_page = xnumber_int($last_page);
            }
            $last_page = $last_page ? $last_page : 1;

            $map[] = array(
                'id' => $id,
                'form_state' => $form_state, 
                'form_cotrol' => $form_control, 
                'pages' => $last_page
            );
        }
        array_to_file($config['result_dir']['value'] . '/' . $config['result_list_hashed']['value'], $map);

        $debug->set_e();
        die($debug->cal(false));
    }

    public function products(){
        $config = Bootstrap::get_instance()->config;

        $count = 0;
        $debug = new xDebugger(true, $config['debug']['value']);
        $debug->set_s();

        $map = array();
        $txt_porduct_ids = array();

        $hashed = include $config['result_dir']['value'] . '/' . $config['result_list_hashed']['value'];
        $hashed = array_slice($hashed, 2, 1);
        // xlog($hashed);

        $ex = new xHTMLExtractor(array(
            'url' => $config['root_link']['value'],
            'cookies' => Bootstrap::get_instance()->config['cookies']['value'],
            'contentType' => 'application/x-www-form-urlencoded',
        ));

        foreach($hashed as $row){
            $ex->get_raw_html($config['list_link']['value'] . $row['id'] . '/1/1');
            for($i = 1; $i <= $row['pages']; $i++){
                // xlog($row);
                $html = $ex->post(array(
                    'Qform__FormState' => $row['form_state'],
                    'Qform__FormId' => 'TDBEcom',
                    'Qform__FormControl' => $row['form_cotrol'],
                    'Qform__FormEvent' => 'QClickEvent',
                    'Qform__FormParameter' => $i,
                    'Qform__FormCallType' => 'Ajax',
                    'Qform__FormUpdates' => '',
                    'Qform__FormCheckableControls' => '',
                ));

                // xlog($html);

                $re = '/<a href="https:\/\/tdo\.tdbaltic\.com\/ecom\/ProductInfo\/(.*?)" id="(.*?)<\/a>/m';
                preg_match_all($re, $html, $matches, PREG_SET_ORDER, 0);


                if(empty($matches)){
                    throw new xException("SOMETHING WRONG! ID: {$row['id']} PAGE: {$i}");
                }

                $count += count($matches);

                foreach($matches as $matched){
                    
                    if($matched[0]){
                        $product_id = get_href_from_tag($matched[0]);
                        $product_id = get_raw_tag_c($product_id, $config['product_link']['value'], '/', true);
                        $txt_porduct_ids[] = $product_id;
                        $text = explode('|', strip_tags($matched[0]));
                        $group = trim_c($text[0]);
                        $name = trim_c($text[1]);

                        $map[$group][$row['id']][$product_id] = array(
                            'list_id' => $row['id'],
                            'page' => $i,
                            'group' => $group,
                            'name' => $name,
                            'product_id' => $product_id
                        );

                    }
                    
                }
            }
        }
        $debug->set_e();
        $log = $debug->cal(false);

        array_to_file($config['result_dir']['value'] . '/' . $config['result_list_ext']['value'], 
        array(
            'count' => $count,
            'debug' => strip_tags($log),
            'result' => $map
        ));

        foreach($map as $m_g_key => $m_group){
            foreach($m_group as $m_list_key => $m_list){
                $list_product_txt = ''; 
                foreach($m_list as $p_id){
                    $list_product_txt .= $p_id['product_id'] . PHP_EOL;
                }
                $file = $m_g_key . '_' . $m_list_key. '.txt';
                file_put_contents($config['result_text_dir']['value'] . '/' . $file, $list_product_txt);
            }
        }
        die($log);
    }

}
?>