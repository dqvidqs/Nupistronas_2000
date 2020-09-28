<?php 

class xHTMLExtractor{

    function __construct(array $config){
        $this->url = $config['url'] ?? null;
        $this->config = $config;
    }

    public function get_raw_html($url = null): string{
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
            
        curl_close($curl);

        return $result;
    }
    
    private function set_header(array $config): array{
        if($config['cookies']) {$headers[] = 'Cookie: ' . $config['cookies'] ;}
            
        return $headers ?? array();
    }
}
?>