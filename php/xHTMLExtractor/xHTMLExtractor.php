<?php 

class xHTMLExtractor{

    function __construct(array $config){
        $this->url = $config['url'] ?? null;
        $this->config = $config;
    }

    public function get_raw_html($url = null, bool $info = false){
        $headers = $this->set_header($this->config);
        if(!$url && !$this->url){
            throw new xException('url does not exists!');
        }
        $curl = curl_init($url ?? $this->url);

        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_COOKIESESSION, $this->config['cookies'] ? 1 : 0);

        $result = curl_exec($curl);
        
        if (curl_error($curl))
            die(curl_error($curl));
        if($info){
            $result_status = curl_getinfo($curl);
        }
            
        curl_close($curl);

        if($info){
            $result_status['result'] = $result;
            return $result_status;
        }

        return $result;
    }
    
    public function post($arr = array(), bool $info = false){
        $headers = $this->set_header($this->config);
        if(!$this->url){
            throw new xException('url does not exists!');
        }

        $curl = curl_init($this->url);

        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($arr) ? http_build_query($arr) : $arr);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($curl, CURLOPT_COOKIESESSION, $this->config['cookies'] ? 1 : 0);

        $result = curl_exec($curl);

        if (curl_error($curl))
            die(curl_error($curl));

        if($info){
            $result_status = curl_getinfo($curl);
        }
        curl_close($curl);

        if($info){
            $result_status['result'] = $result;
            return $result_status;
        }

        return $result;
    }

    private function set_header(array $config): array{
        if(!empty($config['cookies'])) {$headers[] = 'Cookie: ' . $config['cookies'] ;}
        if(!empty($config['contentType'])) {$headers[] = 'Content-Type: ' . $config['contentType'] ;}

        return $headers ?? array();
    }
}
?>