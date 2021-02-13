<?php
ini_set('max_execution_time',99999999999);
require_once('Client.php');
require_once('Sipkd.php');
define('DAERAH','sumbarprov');
$sipkd=new Sipkd();
$client=new Client();

$file=fopen('session.txt','r');
$tmp=fread($file,filesize('session.txt'));
fclose($file);
$json=json_decode($tmp);
$id_daerah=$json->id_daerah;
$cookie=$json->cookie;

//Pendapatan OPD
$sipkd->exec("delete from popd");
$url="https://".DAERAH.".sipd.kemendagri.go.id/daerah/main/budget/pendapatan/2021/ang/tampil-unit/".$id_daerah."/0";
$data=$client->get($url,$cookie);
$json=json_decode($data['content'])->data;
foreach($json as $row){
        $param=[
            $row->id_unit,
            $row->id_skpd,
            $row->kode_skpd,
            replace($row->kode_skpd,$row->nama_skpd->nama_skpd),
            $row->nilaitotal,
            $row->nilaimurni
        ];
        $sql="insert into popd values(?,?,?,?,?,?)";
        $sipkd->exec($sql,$param);
        tulis(json_encode($param));
}

//Pendapatan Rinci
$popd=$sipkd->get("select * from popd");
$sipkd->exec("delete from princ");
foreach($popd as $row){
    $url="https://".DAERAH.".sipd.kemendagri.go.id/daerah/main/budget/pendapatan/2021/ang/tampil-pendapatan/".$id_daerah."/".$row['id_skpd'];
    $data=$client->get($url,$cookie);
    $json=json_decode($data['content'])->data;
    foreach($json as $rdet){
        $param=[
            $rdet->id_pendapatan,
            $rdet->kode_akun,
            $rdet->nama_akun,
            $rdet->uraian,
            $rdet->keterangan,
            $rdet->skpd_koordinator,
            $rdet->urusan_koordinator,
            $rdet->program_koordinator,
            $rdet->total,
            $rdet->nilaimurni,
            $row['id_skpd']
        ];
        $sql="insert into princ values(?,?,?,?,?,?,?,?,?,?,?)";
        $sipkd->exec($sql,$param);
        tulis(json_encode($param));
    }
}
function replace($p1,$p2){
    return str_replace($p1.' ','',$p2);
}
function tulis($param){
    echo $param;
    echo "\n";
}