<?php

try{
    $ConnDB = new PDO("mysql:host=localhost;dbname=ideasoft_case;charset=UTF8", "root","");
    $ConnDB->beginTransaction();
}catch(PDOException $hata){
     echo " Bağlantı Hatası " . $hata->getMessage();
    die();
}
?>