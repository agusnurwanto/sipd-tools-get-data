<?php

class Client{

    public function __construct(){
        
    }

    public function post($url,$param,$cookie){
        $context= stream_context_create([
            "http"=>[
                "method"=>"POST",
                "header"=>"Content-Type: application/x-www-form-urlencoded; charset=UTF-8\r\n".
                          "Cookie: ".$cookie."\r\n".
                          "Content-Length: ".strlen($param)."\r\n",
                "content"=>$param
            ]
        ]);
        $response=file_get_contents($url,false,$context);
        $data=['header'=>$http_response_header,'content'=>$response];
        return $data;
    }

    public function get($url,$cookie=''){
        $opt=[
            'http'=>[
                'method'=>'GET',
                'header'=>"Accept-language: en\r\n".
                          'Cookie: '.$cookie
            ]
        ];
        $context=stream_context_create($opt);
        $result=file_get_contents($url,false,$context);

        $data=['header'=>$http_response_header,'content'=>$result];
        return $data;
    }

    public function Cookie($data){
        $tmp=[];
        for($i=count($data)-1;$i>=0;$i--){
            if(substr($data[$i],0,11)=="Set-Cookie:"){
                $tmp2=explode(';',$data[$i]);
                array_push($tmp,substr($tmp2[0],strlen("Set-Cookie:"),strlen($tmp2[0])-strlen("Set-Cookie:")));
            }
                
        }

        return implode(';',$tmp);
    }

    public function token($data){
        $tmp=explode('<meta name="_token" content="',$data);
        $tmp2=explode('">',$tmp[1]);
        return $tmp2[0];
    }

    public function ptoken($data){
        $tmp=explode('<meta name="csrf-token" content="',$data);
        $tmp2=explode('">',$tmp[1]);
        return $tmp2[0];
    }

    public function cookieArray($header){
        $tmp=[];
        for($i=count($header)-1;$i>=0;$i--){
            if(substr($header[$i],0,11)=="Set-Cookie:"){
                $tmp2=explode(';',$header[$i]);
                $tmp3=substr($tmp2[0],strlen("Set-Cookie:"),strlen($tmp2[0])-strlen("Set-Cookie:"));
                $tmp4=explode('=',$tmp3);
                $tmp[$tmp4[0]]=$tmp4[1];
            }
        }
        return $tmp;
    }

    public function getCookieString($cookie){
        $tmp=[];
        foreach($cookie as $id=>$val){
            array_push($tmp,$id.'='.$val);
        }
        return implode(';',$tmp);
    }
}