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
/** */
//Belanja OPD
$sipkd->exec("delete from blopd");
$url="https://".DAERAH.".sipd.kemendagri.go.id/daerah/main/budget/belanja/2021/giat/tampil-unit/".$id_daerah."/0";
$data=$client->get($url,$cookie);
$json=json_decode($data['content'])->data;
foreach($json as $row){
    $sql="insert into blopd values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $param=[$row->id_daerah,
            $row->tahun,
            $row->id_unit,
            $row->id_skpd,
            $row->kode_skpd,
            str_replace($row->kode_skpd.' ','',$row->nama_skpd->nama_skpd),
            $row->total_giat,
            $row->set_pagu_giat,
            $row->set_pagu_skpd,
            $row->pagu_giat,
            $row->rinci_giat,
            $row->totalgiat,
            $row->batasanpagu,
            $row->nilaipagu,
            $row->nilaipagumurni,
            $row->nilairincian,
            $row->realisasi
        ];
    $sipkd->exec($sql,$param);
    tulis(json_encode($param));
}

//Belanja Kegiatan
$sipkd->exec("delete from blkeg");
$blopd=$sipkd->get("select * from blopd");
foreach($blopd as $ropd){
    $url="https://".DAERAH.".sipd.kemendagri.go.id/daerah/main/budget/belanja/2021/giat/tampil-giat/".$id_daerah."/".$ropd['id_sub_skpd'];
    $data=$client->get($url,$cookie);
    $json=json_decode($data['content'])->data;
    foreach($json as $rkeg){
        $sql="insert into blkeg values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $param=[$rkeg->id_daerah,
                $rkeg->tahun,
                $rkeg->id_unit,
                $rkeg->id_skpd,
                $rkeg->kode_skpd,
                replace($rkeg->kode_skpd,$rkeg->nama_skpd),
                $rkeg->id_urusan,
                $rkeg->kode_urusan,
                replace($rkeg->kode_urusan,$rkeg->nama_urusan),
                $rkeg->id_bidang_urusan,
                $rkeg->kode_bidang_urusan,
                replace($rkeg->kode_bidang_urusan,$rkeg->nama_bidang_urusan),
                $rkeg->id_sub_skpd,
                $rkeg->kode_sub_skpd,
                replace($rkeg->kode_sub_skpd,$rkeg->nama_sub_skpd),
                $rkeg->id_program,
                $rkeg->kode_program,
                replace($rkeg->kode_program,$rkeg->nama_program),
                $rkeg->id_giat,
                $rkeg->kode_giat,
                str_replace($rkeg->kode_giat.' ','',$rkeg->nama_giat->nama_giat),
                $rkeg->pagu_giat,
                $rkeg->rinci_giat,
                $rkeg->id_sub_giat,
                $rkeg->kode_sub_giat,
                str_replace($rkeg->kode_sub_giat.' ','',$rkeg->nama_sub_giat->nama_sub_giat),
                $rkeg->pagu,
                $rkeg->pagu_indikatif,
                $rkeg->rincian,
                $rkeg->kode_bl,
                $rkeg->kode_sbl
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