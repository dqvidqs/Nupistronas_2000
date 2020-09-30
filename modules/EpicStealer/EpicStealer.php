<?php

include __PHP__ . '/xHTMLExtractor/xHTMLExtractor.php';
include __PHP__ .'/xDebugger/xDebugger.php';

class EpicStealer extends Controller{

    public function __construct($cfg){
        parent::__construct($cfg);
    }

    public function run(){
        // die('NOTHING TO SHOW!');
        // $ex = new xHTMLExtractor(array(
        //     'url' => 'https://tdo.tdbaltic.com/ecom/index.php/ProductList/9/1/1',
        //     'cookies' => Bootstrap::get_instance()->config['cookies']['value'],
        //     'contentType' => 'application/x-www-form-urlencoded',
        // ));
        // xlog($ex->post(
        //         array(
        //         'Qform__FormState' => '5ef79824f11b4312550c7355a189fa0d',
        //         'Qform__FormId' => 'TDBEcom',
        //         'Qform__FormControl' => 'c424',
        //         'Qform__FormEvent' => 'QClickEvent',
        //         'Qform__FormParameter' => '1',
        //         'Qform__FormCallType' => 'Ajax',
        //         'Qform__FormUpdates' => '',
        //         'Qform__FormCheckableControls' => '',
        //     )
        // // '&c10=0&c11_x=&c11_y=&c12_x=&c12_y=&c282=&c289=&c290=&c128=0&c129=0&c219=0&c220=0&c221=0&c222=0&c223=0&c224=0&c225=0&c226=0&c233=0&c234=0&c235=0&c236=0&c244=&c245=&c246=&c247=&c248=&c249=&c250=&c259=&c261=1&c260=on&c262=0&c269=9&addcompare6013700_x=&addcompare6013700_y=&txtCart601370=&btnCart601370_x=&btnCart601370_y=&addcompare6724901_x=&addcompare6724901_y=&txtCart672490=&btnCart672490_x=&btnCart672490_y=&addcompare6276442_x=&addcompare6276442_y=&txtCart627644=&btnCart627644_x=&btnCart627644_y=&addcompare8190573_x=&addcompare8190573_y=&txtCart819057=&btnCart819057_x=&btnCart819057_y=&addcompare6703504_x=&addcompare6703504_y=&txtCart670350=&btnCart670350_x=&btnCart670350_y=&addcompare6701905_x=&addcompare6701905_y=&txtCart670190=&btnCart670190_x=&btnCart670190_y=&addcompare6703706_x=&addcompare6703706_y=&txtCart670370=&btnCart670370_x=&btnCart670370_y=&addcompare6704107_x=&addcompare6704107_y=&txtCart670410=&btnCart670410_x=&btnCart670410_y=&addcompare8961988_x=&addcompare8961988_y=&txtCart896198=&btnCart896198_x=&btnCart896198_y=&addcompare6861309_x=&addcompare6861309_y=&txtCart686130=&btnCart686130_x=&btnCart686130_y=&addcompare89619910_x=&addcompare89619910_y=&txtCart896199=&btnCart896199_x=&btnCart896199_y=&addcompare89619711_x=&addcompare89619711_y=&txtCart896197=&btnCart896197_x=&btnCart896197_y=&addcompare89417512_x=&addcompare89417512_y=&txtCart894175=&btnCart894175_x=&btnCart894175_y=&addcompare89418113_x=&addcompare89418113_y=&txtCart894181=&btnCart894181_x=&btnCart894181_y=&addcompare47459014_x=&addcompare47459014_y=&txtCart474590=&btnCart474590_x=&btnCart474590_y=&addcompare56987115_x=&addcompare56987115_y=&txtCart569871=&btnCart569871_x=&btnCart569871_y=&addcompare60247016_x=&addcompare60247016_y=&txtCart602470=&btnCart602470_x=&btnCart602470_y=&addcompare43893117_x=&addcompare43893117_y=&txtCart438931=&btnCart438931_x=&btnCart438931_y=&addcompare59779118_x=&addcompare59779118_y=&txtCart597791=&btnCart597791_x=&btnCart597791_y=&addcompare53787119_x=&addcompare53787119_y=&txtCart537871=&btnCart537871_x=&btnCart537871_y=&addcompare60445020_x=&addcompare60445020_y=&txtCart604450=&btnCart604450_x=&btnCart604450_y=&addcompare36251021_x=&addcompare36251021_y=&txtCart362510=&btnCart362510_x=&btnCart362510_y=&addcompare46023022_x=&addcompare46023022_y=&txtCart460230=&btnCart460230_x=&btnCart460230_y=&addcompare61419123_x=&addcompare61419123_y=&txtCart614191=&btnCart614191_x=&btnCart614191_y=&addcompare56185024_x=&addcompare56185024_y=&txtCart561850=&btnCart561850_x=&btnCart561850_y=&addcompare33791125_x=&addcompare33791125_y=&txtCart337911=&btnCart337911_x=&btnCart337911_y=&addcompare53065126_x=&addcompare53065126_y=&txtCart530651=&btnCart530651_x=&btnCart530651_y=&addcompare40081227_x=&addcompare40081227_y=&txtCart400812=&btnCart400812_x=&btnCart400812_y=&addcompare50441028_x=&addcompare50441028_y=&txtCart504410=&btnCart504410_x=&btnCart504410_y=&addcompare67155029_x=&addcompare67155029_y=&txtCart671550=&btnCart671550_x=&btnCart671550_y=&addcompare67155130_x=&addcompare67155130_y=&txtCart671551=&btnCart671551_x=&btnCart671551_y=&addcompare63561231_x=&addcompare63561231_y=&txtCart635612=&btnCart635612_x=&btnCart635612_y=&addcompare53919032_x=&addcompare53919032_y=&txtCart539190=&btnCart539190_x=&btnCart539190_y=&addcompare53779433_x=&addcompare53779433_y=&txtCart537794=&btnCart537794_x=&btnCart537794_y=&addcompare53779334_x=&addcompare53779334_y=&txtCart537793=&btnCart537793_x=&btnCart537793_y=&addcompare67156735_x=&addcompare67156735_y=&txtCart671567=&btnCart671567_x=&btnCart671567_y=&addcompare67156836_x=&addcompare67156836_y=&txtCart671568=&btnCart671568_x=&btnCart671568_y=&addcompare67156937_x=&addcompare67156937_y=&txtCart671569=&btnCart671569_x=&btnCart671569_y=&addcompare67157038_x=&addcompare67157038_y=&txtCart671570=&btnCart671570_x=&btnCart671570_y=&addcompare67157139_x=&addcompare67157139_y=&txtCart671571=&btnCart671571_x=&btnCart671571_y=&addcompare67157240_x=&addcompare67157240_y=&txtCart671572=&btnCart671572_x=&btnCart671572_y=&addcompare63057441_x=&addcompare63057441_y=&txtCart630574=&btnCart630574_x=&btnCart630574_y=&addcompare63057542_x=&addcompare63057542_y=&txtCart630575=&btnCart630575_x=&btnCart630575_y=&addcompare65109043_x=&addcompare65109043_y=&txtCart651090=&btnCart651090_x=&btnCart651090_y=&addcompare67157444_x=&addcompare67157444_y=&txtCart671574=&btnCart671574_x=&btnCart671574_y=&addcompare63057745_x=&addcompare63057745_y=&txtCart630577=&btnCart630577_x=&btnCart630577_y=&addcompare63057646_x=&addcompare63057646_y=&txtCart630576=&btnCart630576_x=&btnCart630576_y=&addcompare63971047_x=&addcompare63971047_y=&txtCart639710=&btnCart639710_x=&btnCart639710_y=&addcompare63057848_x=&addcompare63057848_y=&txtCart630578=&btnCart630578_x=&btnCart630578_y=&addcompare63058049_x=&addcompare63058049_y=&txtCart630580=&btnCart630580_x=&btnCart630580_y=&addcompare63057950_x=&addcompare63057950_y=&txtCart630579=&btnCart630579_x=&btnCart630579_y=&addcompare53793751_x=&addcompare53793751_y=&txtCart537937=&btnCart537937_x=&btnCart537937_y=&addcompare53779052_x=&addcompare53779052_y=&txtCart537790=&btnCart537790_x=&btnCart537790_y=&addcompare53779153_x=&addcompare53779153_y=&txtCart537791=&btnCart537791_x=&btnCart537791_y=&addcompare53793854_x=&addcompare53793854_y=&txtCart537938=&btnCart537938_x=&btnCart537938_y=&addcompare53793955_x=&addcompare53793955_y=&txtCart537939=&btnCart537939_x=&btnCart537939_y=&txtCart898097=&btnCart898097_x=&btnCart898097_y=&addcompare60899057_x=&addcompare60899057_y=&txtCart608990=&btnCart608990_x=&btnCart608990_y=&addcompare53779258_x=&addcompare53779258_y=&txtCart537792=&btnCart537792_x=&btnCart537792_y=&addcompare68151059_x=&addcompare68151059_y=&txtCart681510=&btnCart681510_x=&btnCart681510_y=&Qform__FormState=7b9003b78eb4c946d32798ae9c4032e8&Qform__FormId=TDBEcom&Qform__FormControl=c268&Qform__FormEvent=QClickEvent&Qform__FormParameter=2&Qform__FormCallType=Ajax&Qform__FormUpdates=&Qform__FormCheckableControls=c24 c25 c26 c27 c28 c29 c30 c31 c32 c33 c34 c35 c36 c37 c38 c39 c40 c41 c42 c43 c44 c45 c46 c47 c48 c49 c50 c51 c52 c53 c54 c55 c56 c57 c58 c59 c60 c61 c62 c63 c64 c65 c66 c67 c68 c69 c70 c71 c72 c73 c74 c75 c76 c77 c78 c79 c80 c81 c82 c83 c84 c85 c86 c87 c88 c89 c90 c91 c92 c93 c94 c95 c96 c97 c98 c99 c100 c101 c102 c103 c104 c105 c106 c107 c108 c109 c110 c111 c112 c113 c114 c115 c116 c117 c118 c119 c120 c121 c122 c123 c124 c125 c126 c127 c130 c131 c132 c133 c134 c135 c136 c137 c138 c139 c140 c141 c142 c143 c144 c145 c146 c147 c148 c149 c150 c151 c152 c153 c154 c155 c156 c157 c158 c159 c160 c161 c162 c163 c164 c165 c166 c167 c168 c169 c170 c171 c172 c173 c174 c175 c176 c177 c178 c179 c180 c181 c182 c183 c184 c185 c186 c187 c188 c189 c190 c191 c192 c193 c194 c195 c196 c197 c198 c199 c200 c201 c202 c203 c204 c205 c206 c207 c208 c209 c210 c211 c212 c213 c214 c215 c216 c217 c218 c227 c228 c229 c230 c231 c232 c237 c238 c239 c240 c241 c242 c243 c261 c260 c262'
        // ));
        $this->view->render();
    }

    public function list(){
        die('asd');
        $config = Bootstrap::get_instance()->config;

        $debug = new xDebugger(true, $config['debug']['value']);
        $debug->set_s();

        $tree = file_get_contents($config['tree_collapse_file']['value']);

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
        
        // $ids = array_slice($ids, 0, 3);
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

            $html = $ex->get_raw_html();

            $form_state = get_raw_tag_s($html, 'Qform__FormState" value="', '"', true);
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
        // $hashed = array_slice($hashed, 0, 1);
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

}
?>