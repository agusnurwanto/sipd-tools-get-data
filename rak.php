<?php
ini_set('max_execution_time',99999999999);
require_once('Client.php');
require_once('Sipkd.php');
$client=new Client();
$sipkd=new Sipkd();
$file=fopen('cookie.txt','r');
$tmp=fread($file,filesize('cookie.txt'));
fclose($file);
$json=json_decode($tmp);
$iddaerah=$json->iddaerah;
$cs=$json->cookie;

$dkeg=$sipkd->get("select distinct a.* from blkeg a left join blrak b on a.id_skpd=b.id_skpd and a.id_sub_skpd=b.id_sub_skpd and a.id_bidang_urusan=b.id_bidang_urusan and a.id_program=b.id_program and a.id_giat=b.id_giat and a.id_sub_giat=b.id_sub_giat
where b.id_skpd is null");
foreach($dkeg as $row){
    $url="https://sipd.kemendagri.go.id/siap/rak-belanja/tampil-rincian/daerah/main/budget/2021/$iddaerah/".$row['id_sub_skpd']."?kodesbl=".$row['id_skpd'].".".$row['id_sub_skpd'].".".$row['id_bidang_urusan'].".".$row['id_program'].".".$row['id_giat'].".".$row['id_sub_giat'];
    $data=$client->get($url,$cs);
    $json=json_decode($data['content'])->data;
    foreach($json as $rak){
        $sql="insert into blrak values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $param=[
            $rak->id_skpd,
            $rak->id_sub_skpd,
            $rak->id_bidang_urusan,
            $rak->id_program,
            $rak->id_giat,
            $rak->id_sub_giat,
            ($rak->id_akun!==NULL)?$rak->id_akun:0,
            $rak->kode_akun,
            replace($rak->kode_akun,$rak->nama_akun),
            $rak->bulan_1,
            $rak->bulan_2,
            $rak->bulan_3,
            $rak->bulan_4,
            $rak->bulan_5,
            $rak->bulan_6,
            $rak->bulan_7,
            $rak->bulan_8,
            $rak->bulan_9,
            $rak->bulan_10,
            $rak->bulan_11,
            $rak->bulan_12
        ];
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