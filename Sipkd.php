<?php
include "config.php";

class Sipkd{

    public function __construct(){
        $this->conn=new PDO("sqlsrv:server=$host_sql_server ; Database=$db_sql_server", $username_sql_server, $password_sql_server);
        $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );  
    }

    public function get($sql,$param=null){
        if($param==null){
            $result=$this->conn->prepare($sql);
            $result->execute();
            $data=$result->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }else{
            $result=$this->conn->prepare($sql);
            $result->execute($param);
            $data=$result->fetchAll(PDO::FETCH_ASSOC);
            return $data;
        }
    }

    public function exec($sql,$param=null){
        try{
        if($param==null){
            
            $result=$this->conn->prepare($sql);
            $result->execute();
            return $result;
        }else{
            $result=$this->conn->prepare($sql);
            $result->execute($param);
            return $result;
        }
        }catch(Exception $e){
            echo $e."\n";
            return null;
        }
    }
}