<?php
error_reporting(0);
$IpAdresi         = $_SERVER["REMOTE_ADDR"];
$ZamanDamgasi     = time();
$TarihSaat        = date("Y-m-d", $ZamanDamgasi);

function CheckDateVal($date, $format = 'Y-m-d'){
    $dt = DateTime::createFromFormat($format, $date);
    if($dt && $dt->format($format)===$date){
        return true;
    }else{
        return false;
    }

}

function SayiFiltre($Deger){
    $BoslukSil    = trim($Deger);
    $TaglariSil   = strip_tags($BoslukSil);
    $EtkisizYap   = htmlspecialchars($TaglariSil);
    $Temizle      = StrSil($EtkisizYap);
    $Sonuc        = $Temizle;
    return $Sonuc;
}

function Guvenlik($Deger){
    $BoslukSil    = trim($Deger);
    $TaglariSil   = strip_tags($BoslukSil);
    $EtkisizYap   = htmlspecialchars($TaglariSil);
    $Sonuc        = $EtkisizYap;
    return $Sonuc;
}

function DiscountControl_Cat_1($Data){

    if($Data>=2){
        return true;
    }

}


function DiscountControl_Cat_2($Data){
    if($Data>=6){
        return true;
    }

}

function DiscountControl_Cat3($Data){

    if($Data>=1000){
        return true;
    }

}



function CustomerGetControl($Data){
    foreach ($Data as $key=>$values){
        if(!is_array($values)){
            echo json_encode(array(
                "Status"=>"Error",
                "Code"=>406,
                "Message"=>"Method Not Allowed  Data is not Array",
            ),JSON_UNESCAPED_UNICODE);
            http_response_code(405);
            header("405 => 'Method Not Allowed");
            exit();
        }else{
        if(!array_key_exists('id',$values)){
            echo json_encode(array(
                "Status"=>"Error",
                "Code"=>400,
                "Message"=>"Method Not Allowed, ID not Found.",
            ),JSON_UNESCAPED_UNICODE);
            http_response_code(400);
            header("400 => 'Bad Request");
            exit();
        }}

        if(!is_numeric($values['id'])){
            echo json_encode(array(
                "Status"=>"Error",
                "Code"=>406,
                "Message"=>"Method Not Allowed, ID is not numeric array($key)",
            ),JSON_UNESCAPED_UNICODE);
            http_response_code(405);
            header("405 => 'Method Not Allowed");
            exit();
        }
        return true;
    }
}

function CustomerPostControl($Data){
    $CustomersValues=array(
        "CustomerName"=>"",
        "Since"=>"",
        "Revenue"=>""
    );

    foreach ($Data as $key=>$value) {
        $TotalData = count($value);
        $CustomerName = Guvenlik($value["CustomerName"]);
        $Since = Guvenlik($value["Since"]);
        $Revenue = Guvenlik($value["Revenue"]);

        if ($TotalData <=3) {
            if(array_diff_key($CustomersValues,$value)){
                echo json_encode(array(
                    "Status"=>"Error",
                    "Code"=>406,
                    "Message"=>"Method Not Allowed",
                    "Needed Keys"=>array_diff_key($CustomersValues,$value),
                ),JSON_UNESCAPED_UNICODE);
                http_response_code(405);
                header("HTTP/1.1 405 Method Not Allowed");
                exit();
            }
        }

        if (array_diff_key($value,$CustomersValues)) {
                echo json_encode(array(
                    "Status" => "Error",
                    "Code" => 406,
                    "Message" => "Not Acceptable, $key. Array Is Have Much Keys",
                ), JSON_UNESCAPED_UNICODE);
                http_response_code(406);
                header("HTTP/1.1 406 Not Acceptable");
                exit();
            }

        if (array_key_exists("id", $value)) {
            echo json_encode(array(
                "Status"=>"Error",
                "Code"=>406,
                "Message"=>"Not Acceptable, $key. Array Invalid data -> id",
            ),JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

        if ($CustomerName == "") {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array Customer name is null",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

        if ($Since<>"" and !CheckDateVal($Since)) {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array Date is not Acceptable format <> Y-d-m",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }else{
            $Since = $TarihSaat;
        }

        if($Revenue=="" or !is_numeric($Revenue)){
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array Revenue is not Acceptable format <> 0.00",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }else{
            $Revenue= str_replace(",","",number_format($Revenue,2,".",","));
        }
    }
    return true;
}

function CustomerPutControl($Data){
    $KeyID=0;
    $CustomersValues=array(
        "id"=>"",
        "CustomerName"=>"",
        "Since"=>"",
        "Revenue"=>""
    );

    foreach ($Data as $key=>$value){
            $TotalData=count($value);
            $id = Guvenlik($value["id"]);
            $CustomerName = Guvenlik($value["CustomerName"]);
            $Since = Guvenlik($value["Since"]);
            $Revenue = Guvenlik($value["Revenue"]);

        if($KeyID==$value['id']) {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. in Array have the same other key.",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }else{
            $KeyID=$value['id'];
        }

       if($TotalData<=4){
        if(!array_key_exists("id",$value)) {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. in Array id key is not found.",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

           if(array_diff_key($CustomersValues,$value)) {
            $AnahtarKontrol=array_diff_key($CustomersValues,$value);
               echo json_encode(array(
                   "Status" => "Error",
                   "Code" => 406,
                   "Message" => "Not Acceptable, $key. in Array some key is not found.",
                   "Need Keys"=>array($AnahtarKontrol),
               ), JSON_UNESCAPED_UNICODE);
               http_response_code(406);
               header("HTTP/1.1 406 Not Acceptable");
               exit();
           }

       }else{
           echo json_encode(array(
               "Status" => "Error",
               "Code" => 406,
               "Message" => "Not Acceptable, $key. in Array is have much keys.",
               "All Keys"=>array($CustomersValues),
           ), JSON_UNESCAPED_UNICODE);
           http_response_code(406);
           header("HTTP/1.1 406 Not Acceptable");
           exit();
       }


        if ($id == "") {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array id is not found.",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

        if (!CheckDateVal($Since)) {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array Date is not Acceptable format <> Y-d-m",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

        if ($CustomerName == "") {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array CustomerName is not Acceptable.",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

        if (!is_numeric($id) and !is_int($id)) {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array id is not numeric.",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

    }

    return true;
}

function CustomerDeleteControl($Data){
    $NeedValues = array("id" => "");
    foreach ($Data as $key => $value) {
        $TotalData = count($value);
        $id = Guvenlik($value["id"]);
        if ($TotalData==1) {
            if (!array_key_exists("id", $value)) {
                echo json_encode(array(
                    "Status" => "Error",
                    "Code" => 406,
                    "Message" => "Not Acceptable, $key. Array in id is not found.",
                ), JSON_UNESCAPED_UNICODE);
                http_response_code(406);
                header("HTTP/1.1 406 Not Acceptable");
                exit();
            }
        }else{
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Please add id information only.",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

        if (!is_numeric($id) and !is_int($id)) {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array id is not numeric.",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }
    }
    return true;
}

function ProductsGetControl($Data){
    foreach ($Data as $key=>$values){
        if(!is_array($values)){
            echo json_encode(array(
                "Status"=>"Error",
                "Code"=>406,
                "Message"=>"Method Not Allowed  Data is not Array",
            ),JSON_UNESCAPED_UNICODE);
            http_response_code(405);
            header("405 => 'Method Not Allowed");
            exit();
        }else{
            if(!array_key_exists('id',$values)){
                echo json_encode(array(
                    "Status"=>"Error",
                    "Code"=>400,
                    "Message"=>"Method Not Allowed, ID not Found.",
                ),JSON_UNESCAPED_UNICODE);
                http_response_code(400);
                header("400 => 'Bad Request");
                exit();
            }}

        if(!is_numeric($values['id'])){
            echo json_encode(array(
                "Status"=>"Error",
                "Code"=>406,
                "Message"=>"Method Not Allowed, ID is not numeric array($key)",
            ),JSON_UNESCAPED_UNICODE);
            http_response_code(405);
            header("405 => 'Method Not Allowed");
            exit();
        }
        return true;
    }
}

function ProductsPostControl($Data){
    $CustomersValues=array(
        "ProductName"=>"",
        "CategoryID"=>"",
        "Price"=>"",
        "Stock"=>"",
    );



    foreach ($Data as $key=>$value) {
        foreach ($value as $key=>$Data3){
            if(!array_key_exists($key, $CustomersValues)){
                echo json_encode(array(
                    "Status"=>"Error",
                    "Code"=>406,
                    "Message"=>"Method Not Allowed, $key. Array is not Acceptable data format ",
                    "Needed Keys"=>array_diff_key($CustomersValues,$value),
                ),JSON_UNESCAPED_UNICODE);
                http_response_code(405);
                header("HTTP/1.1 405 Method Not Allowed");
                exit();
            }else{

            }
        }

        $TotalData = count($value);
        $ProductName = Guvenlik($value["ProductName"]);
        $CategoryID = Guvenlik($value["CategoryID"]);
        $Price = Guvenlik($value["Price"]);
        $Stock = Guvenlik($value["Stock"]);

        if ($TotalData <=4) {
            if(array_diff_key($CustomersValues,$value)){
                echo json_encode(array(
                    "Status"=>"Error",
                    "Code"=>406,
                    "Message"=>"Method Not Allowed",
                    "Needed Keys"=>array_diff_key($CustomersValues,$value),
                ),JSON_UNESCAPED_UNICODE);
                http_response_code(405);
                header("HTTP/1.1 405 Method Not Allowed");
                exit();
            }
        }

        if (array_diff_key($value,$CustomersValues)) {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array Is Have Much Keys",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

        if (array_key_exists("id", $value)) {
            echo json_encode(array(
                "Status"=>"Error",
                "Code"=>406,
                "Message"=>"Not Acceptable, $key. Array Invalid data -> id",
            ),JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

        if ($ProductName == "") {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array Customer name is null",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

        if($Price=="" or !is_numeric($Price)){
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array Price is not Acceptable format <> 0.00",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

        if ($Stock == "" or !is_numeric($Stock)) {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array Stock is not Acceptable format <> integer",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }


        if($CategoryID=="" or !is_numeric($CategoryID)){
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array Category ID is not Acceptable format <> integer",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }
    }
    return true;
}

function ProductsPutControl($Data){
    $KeyID=0;
    $CustomersValues=array(
        "id"=>"",
        "ProductName"=>"",
        "CategoryID"=>"",
        "Price"=>"",
        "Stock"=>""
    );

    foreach ($Data as $key=>$value){
        $TotalData=count($value);
        $id = Guvenlik($value["id"]);
        $ProductName = Guvenlik($value["ProductName"]);
        $CategoryID = Guvenlik($value["CategoryID"]);
        $Price = Guvenlik($value["Price"]);
        $Stock = Guvenlik($value["Stock"]);

        if($KeyID==$value['id']) {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. in Array have the same other key.",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }else{
            $KeyID=$value['id'];
        }

        if($TotalData<=5){
            if(!array_key_exists("id",$value)) {
                echo json_encode(array(
                    "Status" => "Error",
                    "Code" => 406,
                    "Message" => "Not Acceptable, $key. in Array id key is not found.",
                ), JSON_UNESCAPED_UNICODE);
                http_response_code(406);
                header("HTTP/1.1 406 Not Acceptable");
                exit();
            }

            if(array_diff_key($CustomersValues,$value)) {
                $AnahtarKontrol=array_diff_key($CustomersValues,$value);
                echo json_encode(array(
                    "Status" => "Error",
                    "Code" => 406,
                    "Message" => "Not Acceptable, $key. in Array some key is not found.",
                    "Need Keys"=>array($AnahtarKontrol),
                ), JSON_UNESCAPED_UNICODE);
                http_response_code(406);
                header("HTTP/1.1 406 Not Acceptable");
                exit();
            }

        }else{
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. in Array is have much keys.",
                "All Keys"=>array($CustomersValues),
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }


        if ($id == "") {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array id is not found.",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }


        if ($ProductName == "") {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array CustomerName is not Acceptable.",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

        if (!is_numeric($CategoryID) and !is_int($CategoryID)) {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array Category ID is not numeric.",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

        if($Price=="" or !is_numeric($Price)){
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array Price is not Acceptable format <> 0.00",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

        if ($Stock == "" or !is_numeric($Stock)) {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array Stock is not Acceptable format <> integer",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

        if (!is_numeric($id) and !is_int($id)) {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array id is not numeric.",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

    }

    return true;
}

function ProductsDeleteControl($Data){
    $NeedValues = array("id" => "");
    foreach ($Data as $key => $value) {
        $TotalData = count($value);
        $id = Guvenlik($value["id"]);
        if ($TotalData==1) {
            if (!array_key_exists("id", $value)) {
                echo json_encode(array(
                    "Status" => "Error",
                    "Code" => 406,
                    "Message" => "Not Acceptable, $key. Array in id is not found.",
                ), JSON_UNESCAPED_UNICODE);
                http_response_code(406);
                header("HTTP/1.1 406 Not Acceptable");
                exit();
            }
        }else{
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Please add id information only.",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }

        if (!is_numeric($id) and !is_int($id)) {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key. Array id is not numeric.",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }
    }
    return true;
}

function OrdersGetControl($Data){
    foreach ($Data as $key=>$values){
        if(!is_array($values)){
            echo json_encode(array(
                "Status"=>"Error",
                "Code"=>406,
                "Message"=>"Method Not Allowed  Data is not Array",
            ),JSON_UNESCAPED_UNICODE);
            http_response_code(405);
            header("405 => 'Method Not Allowed");
            exit();
        }else{
            if(!array_key_exists('id',$values)){
                echo json_encode(array(
                    "Status"=>"Error",
                    "Code"=>400,
                    "Message"=>"Method Not Allowed, ID not Found.",
                ),JSON_UNESCAPED_UNICODE);
                http_response_code(400);
                header("400 => 'Bad Request");
                exit();
            }}

        if(!is_numeric($values['id'])){
            echo json_encode(array(
                "Status"=>"Error",
                "Code"=>406,
                "Message"=>"Method Not Allowed, ID is not numeric array($key)",
            ),JSON_UNESCAPED_UNICODE);
            http_response_code(405);
            header("405 => 'Method Not Allowed");
            exit();
        }
        return true;
    }
}

function OrdersPostControl($Data){
    $PostValues=array(
        "CustomerId"=>"",
        "items"=>array("productId"=>"","quantity"=>""),

    );

    foreach ($Data as $key=>$values){

        if(!is_array($values)){
            echo json_encode(array(
                "Status"=>"Error",
                "Code"=>406,
                "Message"=>"Method Not Allowed  Data is not Array",
            ),JSON_UNESCAPED_UNICODE);
            http_response_code(405);
            header("405 => 'Method Not Allowed");
            exit();
        }else{

            if(array_key_exists('id',$values)){
                echo json_encode(array(
                    "Status"=>"Error",
                    "Code"=>400,
                    "Message"=>"Method Not Allowed, Do not Allowed ID in Post.",
                ),JSON_UNESCAPED_UNICODE);
                http_response_code(400);
                header("400 => 'Bad Request");
                exit();
            }

            if(!array_key_exists('customerId',$values)){
                echo json_encode(array(
                    "Status"=>"Error",
                    "Code"=>400,
                    "Message"=>"Method Not Allowed, Customer ID not Found.",
                ),JSON_UNESCAPED_UNICODE);
                http_response_code(400);
                header("400 => 'Bad Request");
                exit();
            }

            if(!is_numeric($values['customerId'])){
                echo json_encode(array(
                    "Status"=>"Error",
                    "Code"=>406,
                    "Message"=>"Method Not Allowed, Customer ID is not numeric array($key)",
                ),JSON_UNESCAPED_UNICODE);
                http_response_code(405);
                header("405 => 'Method Not Allowed");
                exit();
            }


            if(!array_key_exists('items',$values)){
                echo json_encode(array(
                    "Status"=>"Error",
                    "Code"=>400,
                    "Message"=>"Method Not Allowed, items not Found array($key).",
                ),JSON_UNESCAPED_UNICODE);
                http_response_code(400);
                header("400 => 'Bad Request");
                exit();
            }



        }


    }
    /// SİPARİŞ İÇİ MÜKERER MÜŞTERİ ID VE MÜKERER ÜRÜN ID KONTROLÜ

    $CustomerValue =0;
    foreach ($Data as $key1=>$inValues){
        if($CustomerValue==$Data[$key1]['customerId']) {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 406,
                "Message" => "Not Acceptable, $key1. in Array have the same Customer ID key.",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(406);
            header("HTTP/1.1 406 Not Acceptable");
            exit();
        }else{
            $CustomerValue=$Data[$key1]['customerId'];
        }

        foreach ($inValues as $key2 => $val) {
            if(is_array($val) || is_object($val)){
            $KeyValue = 0;
            foreach ($val as $key3 => $val2) {
                if ($KeyValue == $Data[$key1]['items'][$key3]['productId']) {
                    echo json_encode(array(
                        "Status" => "Error",
                        "Code" => 406,
                        "Message" => "Not Acceptable, $key1. in Array have the same other Product Code.",
                    ), JSON_UNESCAPED_UNICODE);
                    http_response_code(406);
                    header("HTTP/1.1 406 Not Acceptable");
                    exit();
                } else {
                    $KeyValue = $Data[$key1]['items'][$key3]['productId'];
                }

            }

            }
        }

    }

    return true;


}

?>
