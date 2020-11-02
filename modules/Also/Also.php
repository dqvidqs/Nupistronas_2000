<?php

require_once __PHP__ .'/xExtractor/xExtractor.php';
require_once __PHP__ .'/xList/xList.php';
require_once __PHP__ .'/xProducts/xProducts.php';
require_once __PHP__ .'/xExtractor/xExtractor.php';

class Also extends Controller{

    public function __construct($cfg){
        parent::__construct($cfg);
    }

    public function run(){
        $this->view->render( array(
            'controller' => $this->_LINEAGE['controller'],
            'config' => $this->cfgr
        ) , 'view/view.php');
    }

    public function authorize(){
        $html_ex = new xHTMLExtractor(array(
            // 'contentType' => 'application/json;charset=UTF-8'
            'cookies' => $this->cfgr['cookies']['value']
        ));
        $html = $html_ex->get_raw_html('https://www.also.com/ec/rest/1/5310/en_LT/search/search.json?c=3222&fo=q%2Cc&show=25&s=price%2B&todo=search&from=0&to=1&_=1603650951445', true);

        if($html['http_code'] != 200){
            $this->view->render(array(
                'code' => $html['http_code']
            ), 'view/fault.php');
        }
    }

    public function list(){
        $this->authorize();

        $dir = $this->cfg['list_dir']['value'];
        $cachedir = $this->cfg['cache_dir']['value'];
        $Resdir = $this->cfg['products_dir']['value'];
        $link = $this->cfg['list_link']['value'];

        $xlist = new xList();
        $files = get_files($dir);

        $html_ex = new xHTMLExtractor(array(
            'cookies' => $this->cfg['cookies']['value']
        ));

        foreach($files as $file){
            $ids = explode(PHP_EOL, file_get_contents($dir . '/' . $file));

            foreach($ids as $id){
      
                $json = $html_ex->get_raw_html(str_replace(
                    array('@ID@', '@Q@'), 
                    array( $id, $this->cfg['maxlist']['value']),
                    $link
                ));

                $json = json_decode($json, true);

                foreach($json['materialList'] as $mat){
                    $xlist->add(
                        $id, $mat['materialNo'], 1, $mat['descriptionSapLong'], $mat['manufacturer']
                    );
                }
                
                $xlist->to_file($cachedir . '/' . 'list_' . $id . '.php');
                $xlist->to_file($Resdir . '/' . 'list_' . $id . '.txt', xList::ATTRIBUTES[1]);
                $xlist->clear();
            }
        }

        $this->view->render(array(
            'log' => $xlist->get_log()
        ), 'view/result.php');
    }

    public function products(){
        $this->authorize();

        $dir = $this->cfg['products_dir']['value'];
        $cachedir = $this->cfg['cache_dir']['value'];
        $Resdir = $this->cfg['productsRes_dir']['value'];

        $img_dir = $this->cfg['img_dir']['value'];
        $img_exdir = $this->cfg['img_exdir']['value'];

        $link_pics = $this->cfg['pics_link']['value'];
        $link_prd = $this->cfg['product_link']['value'];
        $link_des = $this->cfg['des_link']['value'];

        $ex = new xExtractor();
        
        $html_ex = new xHTMLExtractor(array(
            'cookies' => $this->cfg['cookies']['value'],
        ));

        $products = new xProducts();

        $files = get_files($dir);

        foreach($files as $file){
            $file_name = explode('.', $file)[0];
            $list_id = explode('_', $file_name)[1];
            $file_content = file_get_contents($dir .'/'. $file);
            $ids = explode(PHP_EOL, $file_content);

            foreach($ids as $id){
                if(!$id && is_numeric($id)){
                    continue;
                }

                $html = $html_ex->get_raw_html(str_replace('@ID@', $id, $link_pics));
                $RAW = get_raw_tag_c($html, '<div id="images">', '</div>');

                $RAW = get_href_from_tag($RAW, false);

                $img = array();
                $img_ex = array();

                if(is_array($RAW) && count($RAW) > 0){

                    if(!is_dir($img_dir  . "/{$id}")){
                        mkdir($img_dir  . "/{$id}");
                    }

                    foreach($RAW as $raw_key => $raw){
                        $img[$raw_key] = $img_dir . '/'.  $id . '/' . $raw_key . '.jpg';
                        $img_ex[$raw_key] = $img_exdir . "{$id}". '/' . $raw_key . '.jpg';
                        file_put_contents($img[$raw_key], $html_ex->get_raw_html($raw[0]));
                    }
                }

                $images = implode ( $img_ex, $this->cfg['img_implode']['value'] );

                $products->add($id, 'ID', $id);
                $products->add($id, 'img', $images);


                $html = $html_ex->get_raw_html(str_replace('@ID@', $id, $link_prd));

                $JSON = json_decode($html, true);

                $products->add($id, 'Title', $JSON['materialList'][0]['descriptionSapLong']);
                $products->add($id, 'Category', 'ID' . $list_id . 'ID');
                $products->add($id, 'Brand', $JSON['materialList'][0]['manufacturer']);
                $products->add($id, 'Price', $ex->get_price($JSON['materialList'][0]['hekValue']));
                // xlog($JSON);
                $html = $html_ex->get_raw_html(str_replace('@ID@', $id, $link_des));
                
                $regex = '/<tr(.*?)>(.|\n)*?<\/tr>/m';

                preg_match_all($regex, $html, $RAW, PREG_SET_ORDER, 0);

                $meta_attr = '';
                $value = '';
                // xlogf($RAW);
                foreach($RAW as $raw){
                    if(contain($raw[0], 'click_path')){
                        $meta_attr = strip_tags(trim_c($raw[0]));
                    }else if(contain($raw[0], 'colspan="2"')){
                        $value = trim_c($raw[0]);
                    }else if(contain($raw[0], 'class="odd"') || contain($raw[0], 'class="even"')){
                        if(contain($raw[0], '<br')){
                            $raw[0] = str_replace('<br />', '\r\n', $raw[0]);
                            $raw[0] = str_replace('<br/>', '\r\n', $raw[0]);
                            $raw[0] = str_replace('<br>', '\r\n', $raw[0]);
                        }

                        if(contain($raw[0], '</li>')){
                            $raw[0] = str_replace('</li>', '\r\n', $raw[0]);
                        }

                        $line = str_replace('</td>', '</td>|', $raw[0]);
                        $line = explode('|', strip_tags(trim_c($line)));
                        $attr = remove_chars($line[0], [13, 10, 9]);
                        $meta_attr = remove_chars($meta_attr, [13, 10, 9]);
                        $value = remove_chars($line[1], [13, 10, 9]);

                        $value = str_replace('\r\n', chr(13) . chr(10), $value);

                        $products->add($id, $meta_attr . ' ' . $attr, $value, xProducts::TYPES[1]);
                        $attr = '';
                        $value = '';
                    }

                    if($meta_attr && $value){
                        if(contain($value, '<br')){
                            $value = str_replace('<br />', '\r\n', $value);
                            $value = str_replace('<br/>', '\r\n', $value);
                            $value = str_replace('<br>', '\r\n', $value);
                        }

                        if(contain($value, '</li>')){
                            $value = str_replace('</li>', '\r\n\r\n', $value);
                        }

                        $value = strip_tags($value);
                        $meta_attr = remove_chars($meta_attr, [13, 10, 9]);
                        $value = remove_chars($value, [13, 10, 9]);

                        $value = str_replace('\r\n', chr(13) . chr(10), $value);

                        $products->add($id, $meta_attr, $value, xProducts::TYPES[1]);
                        $meta_attr = '';
                        $value = '';
                    }

                }
            }

            $products->to_map(); 
            $products->to_file($Resdir . '/prducts_' . $list_id . '.csv');
            $products->clear(); 
        }
        // $html = $html_ex->get_raw_html('https://www.also.com/ec/cms5/5310/ProductDetailData.do?prodId=3814246');
        // $html = $html_ex->get_raw_html('https://www.also.com/ec/rest/1/5310/en_LT/search/search.json?q=3814246&fo=q');
        // $html = $html_ex->get_raw_html('https://www.also.com/ec/cms5/5310/ProductDetailData.do?prodId=3814246&todo=extendedSpecs');
        // xlog(json_decode($html, true));

        // xlog($html);
        $this->view->render(array(
            'log' => $products->get_log()
        ), 'view/result.php');
    }

    public function _run(){
        //LIST!
        $html_ex = new xHTMLExtractor(array(
            'cookies' => 'JSESSIONID=4BD35D38A15554F81C9C62BB294BB816; ROUTEID=.p1; pegasos.lang.5310=en; T_5310=1; _mkto_trk=id:833-IPQ-934&token:_mch-also.com-1603651871675-62502; pegasos.lang.6000=en; T_6000=1; wt_cdbeid=1; wt_nv=0; wt_mcp_sid=2020776977; wt_geid=68934a3e9455fa72420237eb; C_6000=1; wt3_sid=%3B590341552297239%3B292630696524616; AUTH=f30326fe45eb4c909cf86ba650fa9489; wt3_eid=%3B292630696524616%7C2160365179550117263%232160373745765933682%3B590341552297239%7C2160373739800222980%232160373741039869889; wt_ttv2_s_292630696524616=9988; wt_ttv2_s_292630696524616=9988; wt_rla=292630696524616%2C18%2C1603737416485%3B590341552297239%2C3%2C1603737398049',
            // 'cookies' => 'ROUTEID=.p3; JSESSIONID=BDB09475B3DEF4FFD09F289CF2169DC7; pegasos.lang.6000=en; T_6000=1; wt_nv_s=1; wt_nv=1; wt_geid=68934a3e9455fa72420237eb; _mkto_trk=id:833-IPQ-934&token:_mch-also.com-1603636130564-25112; C_6000=1; T_5310=1; wt3_sid=%3B590341552297239%3B292630696524616; wt_adby=-331868692~0|1635174227483#; C_5310=1; pegasos.lang.0=de; AUTH=876d783a4e6b4654a6ac89d1aee3c268; wt_ttv2_s_292630696524616=9970; wt_ttv2_s_292630696524616=9970; wt_mcp_sid=3337267473; wt_pli_session=9656; wt_pli_session=9656; wt_pli_view=2147832%7C1~3529140%7C1~3604995%7C1~3643556%7C1~3644238%7C1~3814246%7C1~3822671%7C25; wt_cdbeid=1; wt3_eid=%3B590341552297239%7C2160363612100505859%232160364207694803072%3B292630696524616%7C2160363614600638218%232160364863670988107; wt_rla=590341552297239%2C3%2C1603642070627%3B292630696524616%2C32%2C1603647159474',
            // 'contentType' => 'application/json;charset=UTF-8'
        ));
        // $html = $html_ex->get_raw_html('https://www.also.com/ec/rest/1/5310/en_LT/search/search.json?c=3222&fo=q%2Cc&show=25&s=price%2B&todo=search&from=0&to=25&_=1603650951445', true);
        // xlog(json_decode($html, true));
        // xlog($html);
//--------------
        //PRDUCT!
        $html_ex = new xHTMLExtractor(array(
            'cookies' => 'JSESSIONID=4BD35D38A15554F81C9C62BB294BB816; ROUTEID=.p1; pegasos.lang.5310=en; T_5310=1; _mkto_trk=id:833-IPQ-934&token:_mch-also.com-1603651871675-62502; pegasos.lang.6000=en; T_6000=1; wt_cdbeid=1; wt_nv=0; wt_mcp_sid=2020776977; wt_geid=68934a3e9455fa72420237eb; C_6000=1; wt3_sid=%3B590341552297239%3B292630696524616; AUTH=f30326fe45eb4c909cf86ba650fa9489; wt3_eid=%3B292630696524616%7C2160365179550117263%232160373745765933682%3B590341552297239%7C2160373739800222980%232160373741039869889; wt_ttv2_s_292630696524616=9988; wt_ttv2_s_292630696524616=9988; wt_rla=292630696524616%2C18%2C1603737416485%3B590341552297239%2C3%2C1603737398049',
            // 'cookies' => 'ROUTEID=.p3; JSESSIONID=BDB09475B3DEF4FFD09F289CF2169DC7; pegasos.lang.6000=en; T_6000=1; wt_nv_s=1; wt_nv=1; wt_geid=68934a3e9455fa72420237eb; _mkto_trk=id:833-IPQ-934&token:_mch-also.com-1603636130564-25112; C_6000=1; T_5310=1; wt3_sid=%3B590341552297239%3B292630696524616; wt_adby=-331868692~0|1635174227483#; C_5310=1; pegasos.lang.0=de; AUTH=876d783a4e6b4654a6ac89d1aee3c268; wt_ttv2_s_292630696524616=9970; wt_ttv2_s_292630696524616=9970; wt_mcp_sid=3337267473; wt_pli_session=9656; wt_pli_session=9656; wt_pli_view=2147832%7C1~3529140%7C1~3604995%7C1~3643556%7C1~3644238%7C1~3814246%7C1~3822671%7C25; wt_cdbeid=1; wt3_eid=%3B590341552297239%7C2160363612100505859%232160364207694803072%3B292630696524616%7C2160363614600638218%232160364863670988107; wt_rla=590341552297239%2C3%2C1603642070627%3B292630696524616%2C32%2C1603647159474',
            'contentType' => 'application/json;charset=UTF-8'
        ));


        
        // BASIC CONTENT
        $html = $html_ex->get_raw_html('https://www.also.com/ec/cms5/5310/ProductDetailData.do?prodId=3814246', true);
        
        //JSON
        // $html = $html_ex->get_raw_html('https://www.also.com/ec/rest/1/5310/en_LT/search/search.json?q=1767421&fo=q&show=25&s=relevance-&todo=search');
        
        //DESCRIPTION
        // $html = $html_ex->get_raw_html('https://www.also.com/ec/cms5/5310/ProductDetailData.do?prodId=1767421&todo=extendedSpecs');

        //GET https://www.also.com/ec/webservice/rest-public/configuration/clientConfiguration?salesorgNo=5310&_=1603643358441
        // $html = $html_ex->get_raw_html('https://www.also.com/ec/rest/1/5310/en_LT/search/search.json?q=3814246&fo=q&show=25&s=relevance-&todo=search&_=1603643358440');
        // $html = $html_ex->get_raw_html('https://www.also.com/ec/cms5/5310/ProductDetailData.do?prodId=3814246&context=ASN3&keyword=');
        // $html = $html_ex->get_raw_html('https://www.also.com/ec/rest/1/5310/en_LT/search/search.json?c=3426&fo=q%2Cc&show=50&s=availableQuantity-&todo=search&from=0&to=50&_=1603636570840');
        //https://www.also.com/ec/rest/1/5310/en_LT/search/search.json?c=3426&fo=q%2Cc&show=50&s=availableQuantity-&todo=search&from=0&to=50&_=1603636570840 
        //https://www.also.com/ec/cms5/5310/ProductDetailData.do?prodId=2147832&context=ASN3&keyword=

        xlog($html);
        
        // xlog(json_decode($html, true));
    }
}

?>