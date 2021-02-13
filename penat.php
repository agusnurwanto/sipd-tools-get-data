<?php
ini_set('max_execution_time',99999999999);
include "config.php";
require_once('Client.php');
/*--------------------Catatan----------------
daftar pagu belanja opd
https://sipd.kemendagri.go.id/siap/rak-belanja/tampil-unit/daerah/main/budget/2021/180/0

daftar pagu kegiatan opd
https://sipd.kemendagri.go.id/siap/rak-belanja/tampil-giat/daerah/main/budget/2021/180/4

daftar rincian RAK belanja kegiatan
https://sipd.kemendagri.go.id/siap/rak-belanja/tampil-rincian/daerah/main/budget/2021/180/4?kodesbl=4.2209.55.423.1395.8766

*/
$client=new Client();
$username=$username_sipd_penat;
$password=$password_sipd_penat;
$cookie=[];
$iddaerah=IDDAERAH;
$namadaerah=DAERAH;

//login ke sipdpenat
$url="https://sipd.kemendagri.go.id/siap/";
$data=$client->get($url);
$cookie=$client->cookieArray($data['header']);
$token=$client->ptoken($data['content']);
$loginparam="_token=$token&userName=$username&password=$password&tahunanggaran=2021&namaDaerah=$namadaerah&idDaerah=$iddaerah";
$url="https://sipd.kemendagri.go.id/siap/login";
$data=$client->post($url,$loginparam,$client->getCookieString($cookie));
$tmp=$client->cookieArray($data['header']);
foreach($tmp as $id=>$val){
    $cookie[$id]=$val;
}
//convert cookie array ke string
$cs=$client->getCookieString($cookie);

$save=['iddaerah'=>$iddaerah,'cookie'=>$cs];


$file=fopen('cookie.txt','w');
fwrite($file,json_encode($save));
fclose($file);
echo "Session login berhasil dibuat\nSilahkan eksekusi penarikan data!";