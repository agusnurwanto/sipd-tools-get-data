<?php
ini_set('max_execution_time',99999999999);
require_once('Client.php');
$client=new Client();
$file=fopen('cookie.txt','r');
$tmp=fread($file,filesize('cookie.txt'));
fclose($file);
$json=json_decode($tmp);
$iddaerah=$json->iddaerah;
$cs=$json->cookie;

$id_skpd='560';

$url="https://sipd.kemendagri.go.id/siap/rak-belanja/tampil-giat/daerah/main/budget/2021/$iddaerah/$id_skpd";
$data=$client->get($url,$cs);
$json=json_decode($data['content'])->data;

foreach($json as $row){
    echo json_encode($row)."\n";
}