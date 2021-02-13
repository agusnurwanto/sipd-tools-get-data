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
//$token=$json->token;

//OPD
$sipkd->exec("delete from opd");
$url='https://'.DAERAH.'.sipd.kemendagri.go.id/daerah/main/budget/skpd/2021/tampil-skpd/'.$id_daerah.'/0';
$data=$client->get($url,$cookie);
$json=json_decode($data['content'])->data;
foreach($json as $row){
    $sql="insert into opd values(?,?,?,?,?)";
    $param=[
        $row->id_unit,
        $row->id_skpd,
        $row->kode_skpd,
        $row->nama_skpd,
        $row->is_skpd
    ];
    $sipkd->exec($sql,$param);
    tulis(json_encode($param));
}

//Bidang Urusan
$sipkd->exec("delete from bidang");
$url="https://".DAERAH.".sipd.kemendagri.go.id/daerah/main/budget/urusan/2021/tampil-urusan/".$id_daerah."/0";
$data=$client->get($url,$cookie);
$json=json_decode($data['content'])->data;
foreach($json as $row){
    $sql="insert into bidang values(?,?,?,?,?,?,?,?)";
    $param=[
        $row->id_bidang_urusan,
        $row->id_urusan,
        $row->id_fungsi,
        $row->kode_urusan,
        str_replace($row->kode_urusan.' ','',$row->nama_urusan),
        $row->kode_bidang_urusan,
        str_replace($row->kode_bidang_urusan.' ','',$row->nama_bidang_urusan),
        $row->is_locked
    ];
    $sipkd->exec($sql,$param);
    tulis(json_encode($param));
}

//Program
$sipkd->exec("delete from program");
$url="https://".DAERAH.".sipd.kemendagri.go.id/daerah/main/budget/program/2021/tampil-program/".$id_daerah."/0";
$data=$client->get($url,$cookie);
$json=json_decode($data['content'])->data;
foreach($json as $row){
    $sql="insert into program values(?,?,?,?,?,?,?,?,?,?)";
    $param=[
        $row->id_urusan,
        $row->kode_urusan,
        str_replace($row->kode_urusan.' ','',$row->nama_urusan),
        $row->id_bidang_urusan,
        $row->kode_bidang_urusan,
        str_replace($row->kode_bidang_urusan.' ','',$row->nama_bidang_urusan),
        $row->id_program,
        $row->kode_program,
        str_replace($row->kode_program.' ','',$row->nama_program),
        $row->is_locked
    ];
    $sipkd->exec($sql,$param);
    tulis(json_encode($param));
}

//Subgiat
$sipkd->exec("delete from subgiat");
$url="https://".DAERAH.".sipd.kemendagri.go.id/daerah/main/budget/subgiat/2021/tampil-sub-giat/".$id_daerah."/0?filter_program=&filter_giat=&filter_sub_giat=";
$data=$client->get($url,$cookie);
$json=json_decode($data['content'])->data;
foreach($json as $row){
    $sql="insert into subgiat values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $param=[
        $row->id_urusan,
        $row->kode_urusan,
        str_replace($row->kode_urusan.' ','',$row->nama_urusan),
        $row->id_bidang_urusan,
        $row->kode_bidang_urusan,
        str_replace($row->kode_bidang_urusan.' ','',$row->nama_bidang_urusan),
        $row->id_program,
        $row->kode_program,
        str_replace($row->kode_program.' ','',$row->nama_program),
        $row->id_giat,
        $row->kode_giat,
        str_replace($row->kode_giat.' ','',$row->nama_giat),
        $row->id_sub_giat,
        $row->kode_sub_giat,
        str_replace($row->kode_sub_giat.' ','',$row->nama_sub_giat),
        $row->is_locked
    ];
    $sipkd->exec($sql,$param);
    tulis(json_encode($param));
}

//Akun
$url="https://".DAERAH.".sipd.kemendagri.go.id/daerah/main/budget/akun/2021/tampil-akun/".$id_daerah."/0";
$data=$client->get($url,$cookie);
$json=json_decode($data['content'])->data;
foreach($json as $row){
    $sql="insert into Akun values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $param=[$row->id_akun,
                    $row->tahun,
                    $row->id_daerah,
                    $row->kode_akun,
                    $row->nama_akun,
                    $row->is_pendapatan,
                    $row->is_bl,
                    $row->is_pembiayaan,
                    $row->is_locked,
                    $row->set_input,
                    $row->is_gaji_asn,
                    $row->is_barjas,
                    $row->is_bunga,
                    $row->is_subsidi,
                    $row->is_bagi_hasil,
                    $row->is_bankeu_umum,
                    $row->is_bankeu_khusus,
                    $row->is_btt,
                    $row->is_hibah_brg,
                    $row->is_hibah_uang,
                    $row->is_sosial_brg,
                    $row->is_sosial_uang,
                    $row->is_bos,
                    $row->is_modal_tanah];
            $sipkd->exec($sql,$param);
            tulis(json_encode($param));
}


function tulis($param){
    echo $param;
    echo "\n";
}