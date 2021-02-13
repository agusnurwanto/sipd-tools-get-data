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


$dkeg=$sipkd->get("select * from (select a.id_skpd,a.id_urusan,a.id_bidang_urusan,a.id_sub_skpd,a.id_program,a.id_giat,a.id_sub_giat,a.rincian,SUM(isnull(b.rincian,0)) detrinc,a.kode_sbl from blkeg a left join blrinc b on a.id_skpd=b.id_skpd and a.id_urusan=b.id_urusan and a.id_bidang_urusan=b.id_bidang_urusan and a.id_sub_skpd=b.id_sub_skpd and a.id_program=b.id_program and a.id_giat=b.id_giat and a.id_sub_giat=b.id_sub_giat
group by a.id_skpd,a.id_urusan,a.id_bidang_urusan,a.id_sub_skpd,a.id_program,a.id_giat,a.id_sub_giat,a.rincian,kode_sbl) z
where rincian-detrinc<>0");
tulis("check data yg berselisih...");
foreach($dkeg as $rkeg){
    tulis("update rincian selisih..");
    $sipkd->exec("delete from blrinc where id_skpd=? and id_sub_skpd=? and id_urusan=? and id_bidang_urusan=? and id_program=? and id_giat=? and id_sub_giat=?",[$rkeg['id_skpd'],$rkeg['id_sub_skpd'],$rkeg['id_urusan'],$rkeg['id_bidang_urusan'],$rkeg['id_program'],$rkeg['id_giat'],$rkeg['id_sub_giat']]);
    $url="https://".DAERAH.".sipd.kemendagri.go.id/daerah/main/budget/belanja/2021/rinci/tampil-rincian/".$id_daerah."/".$rkeg['id_skpd']."?kodesbl=".$rkeg['kode_sbl'];
    tulis($url);
    $data=$client->get($url,$cookie);
    $json=json_decode($data['content'])->data;
    foreach($json as $rdet){
        $param=[$rdet->id_daerah,
                            $rdet->tahun,
                            $rdet->id_unit,
                            $rdet->id_skpd,
                            $rdet->kode_skpd,
                            replace($rdet->kode_skpd,$rdet->nama_skpd),
                            $rdet->id_urusan,
                            $rdet->kode_urusan,
                            replace($rdet->kode_urusan,$rdet->nama_urusan),
                            $rdet->id_bidang_urusan,
                            $rdet->kode_bidang_urusan,
                            replace($rdet->kode_bidang_urusan,$rdet->nama_bidang_urusan),
                            $rdet->id_sub_skpd,
                            $rdet->kode_sub_skpd,
                            replace($rdet->kode_sub_skpd,$rdet->nama_sub_skpd),
                            $rdet->id_program,
                            $rdet->kode_program,
                            replace($rdet->kode_program,$rdet->nama_program),
                            $rdet->id_giat,
                            $rdet->kode_giat,
                            replace($rdet->kode_giat,$rdet->nama_giat),
                            $rdet->id_sub_giat,
                            $rdet->kode_sub_giat,
                            replace($rdet->kode_sub_giat,$rdet->nama_sub_giat),
                            $rdet->pagu,
                            ($rdet->id_akun!==NULL)?$rdet->id_akun:0,
                            $rdet->kode_akun,
                            replace($rdet->kode_akun,$rdet->nama_akun),
                            $rdet->lokus_akun_teks,
                            $rdet->jenis_bl,
                            $rdet->is_paket,
                            $rdet->subs_bl_teks,
                            $rdet->ket_bl_teks,
                            $rdet->id_standar_harga,
                            $rdet->kode_standar_harga,
                            $rdet->nama_standar_harga->nama_komponen,
                            $rdet->satuan,
                            $rdet->spek,
                            $rdet->rincian,
                            $rdet->pajak,
                            $rdet->volume,
                            $rdet->harga_satuan,
                            $rdet->koefisien,
                            $rdet->vol_1,
                            $rdet->sat_1,
                            $rdet->vol_2,
                            $rdet->sat_2,
                            $rdet->vol_3,
                            $rdet->sat_3,
                            $rdet->vol_4,
                            $rdet->sat_4,
                            ($rdet->id_rinci_sub_bl!==NULL)?$rdet->id_rinci_sub_bl:0];
                    $sql="insert into blrinc values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
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