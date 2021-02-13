<?php
ini_set('max_execution_time',99999999999);
include('config.php');
require_once('Client.php');

//konfigurasi aplikasi
$username=$username_sipd_merah;
$password=$password_sipd_merah;
$cookie="";
/**------------------------- **/
$client=new Client();

//login aplikasi
  //Ambil token
  awal:
    $url="https://".DAERAH.".sipd.kemendagri.go.id/daerah?u2sp5Ras4rOV@p80WIqoYh46Ia80xrVuXnUq3i5ZYIP7oi3Zdz@VVeeED9nWNYKq1G70pmNsblFWGyccggLw2RTKf0dv97HiHdPF4P1oQ2dQWB5p3MxPGoXRA3fOkAqR";
    echo "Ambil data token...\n";
    $data=$client->get($url);
    $cookie=$client->cookie($data['header']);
    $token=$client->token($data['content']);
    echo 'Token : '. $token."\n";
    $param="_token=".$token."&env=main&region=daerah&skrim=".base64_encode("user_name=".$username."&user_password=".$password);
    echo "Login ke SIPD...\n";
    $url="https://".DAERAH.".sipd.kemendagri.go.id/daerah/login";
    $data=$client->post($url,$param,$cookie);
    $result=json_decode($data['content']);
    if($result->result=="success"){
        $id_daerah=$result->id_daerah;
        $id_user=$result->id_user;
        tulis("Message : ".$result->message);
        $tmp=['cookie'=>$cookie,'id_daerah'=>$id_daerah,'id_user'=>$id_user,'token'=>$token];
        $file=fopen('session.txt','w');
        fwrite($file,json_encode($tmp));
        fclose($file);

    }else if($result->result=="userlogged"){
        tulis("Message : ".$result->message);
        tulis("Reset user id : ".$result->id_user);
        $url="https://".DAERAH.".sipd.kemendagri.go.id/daerah/reset?idu=".base64_encode($result->id_user);
        $client->get($url,$cookie);
        goto awal;
    }else{
        tulis("Message : ".$result->message);
        tulis("Lakukan konfigurasi ulang aplikasi!");
    }
//tarik data master urusan

function tulis($param){
    echo $param;
    echo "\n";
}
//tarik data master bidang urusan
