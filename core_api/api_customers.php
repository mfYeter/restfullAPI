<?php
require_once ('../config/connect.php');
require_once ('../config/funcitons.php');
header("Content-Type:Application/json; charset=utf-8");
$RequestType=$_SERVER["REQUEST_METHOD"];
$RequestData = json_decode(file_get_contents("php://input"),true);



    //// REQUEST METHOD GET
    if($RequestType=="GET"){
        if(!isset($RequestData[0]['id'])){
            $Query=$ConnDB->prepare("SELECT * FROM `customers` ORDER BY `ID` ASC");
            $Query->execute();
            $Result=$Query->FetchAll(PDO::FETCH_ASSOC);
            echo json_encode(array($Result),
                JSON_UNESCAPED_UNICODE);
            http_response_code(200);
            header("HTTP/1.1 200 'Ok");
            exit();
        }

        if(CustomerGetControl($RequestData)){
            $ResultAr=array();
            foreach ($RequestData as $key=>$values){
                $id=Guvenlik($values['id']);
                $Query=$ConnDB->prepare("SELECT * FROM `customers` WHERE `ID`=? ORDER BY `ID` ASC");
                $Query->execute([$id]);
                $Result=$Query->rowCount();
                $Data=$Query->Fetch(PDO::FETCH_ASSOC);
                array_push($ResultAr,$Data);
            }
            if($Result>0){
            echo json_encode(array($ResultAr),JSON_UNESCAPED_UNICODE);
            http_response_code(405);
            header("HTTP/1.1 405  'Method Not Allowed");
            }else{
                echo json_encode(array(
                    "Status"=>"Error",
                    "Code"=>400,
                    "Message"=>"Bad Request, Data is not Found.",
                ),JSON_UNESCAPED_UNICODE);
                http_response_code(400);
                header("HTTP/1.1 400 'Bad Request");
                exit();
            }

        }


    }

    //// REQUEST METHOD POST
    elseif($RequestType=="POST"){
        if(CustomerPostControl($RequestData)){
            foreach ($RequestData as $key=>$value){
                $CustomerName = Guvenlik($value["CustomerName"]);
                $Since = Guvenlik($value["Since"]);
                $Revenue = Guvenlik($value["Revenue"]);
                $Query=$ConnDB->prepare("INSERT INTO `customers`(`CustomerName`, `Since`, `Revenue`) VALUES (?,?,?)");
                $Query->execute([$CustomerName,$Since,$Revenue]);
                $Result=$Query->rowCount();
            }
            if($Result>0){
                echo json_encode(array(
                    "Status"=>"OK",
                    "Code"=>201,
                    "Message"=>"Data is Created.",
                ),JSON_UNESCAPED_UNICODE);
                http_response_code(201);
                header("HTTP/1.1 201 'Created");
                $ConnDB->commit();
                exit();
            }else{
                echo json_encode(array(
                    "Status"=>"Error",
                    "Code"=>400,
                    "Message"=>"Bad Request",
                ),JSON_UNESCAPED_UNICODE);
                http_response_code(400);
                header("HTTP/1.1 400 'Bad Request");
                exit();
            }
        }
    }

    //// REQUEST METHOD PUT
    elseif($RequestType=="PUT") {
    if(CustomerPutControl($RequestData)){
        foreach ($RequestData as $key=>$value){
            $id=Guvenlik($value["id"]);
            $CustomerName = Guvenlik($value["CustomerName"]);
            $Since = Guvenlik($value["Since"]);
            $Revenue = Guvenlik($value["Revenue"]);
            $Query=$ConnDB->prepare("UPDATE `customers` SET `CustomerName`=?,`Since`=?,`Revenue`=? WHERE `id`=?");
            $Query->execute([$CustomerName,$Since,$Revenue,$id]);
            $Result=$Query->rowCount();
        }
        if($Result>0){
            echo json_encode(array(
                "Status"=>"OK",
                "Code"=>201,
                "Message"=>"Data is updated.",
            ),JSON_UNESCAPED_UNICODE);
            http_response_code(200);
            header("HTTP/1.1 200 'Updated.");
            $ConnDB->commit();
            exit();
        }else{
            echo json_encode(array(
                "Status"=>"Error",
                "Code"=>400,
                "Message"=>"Bad Request",
            ),JSON_UNESCAPED_UNICODE);
            http_response_code(400);
            header("HTTP/1.1 400 'Bad Request");
            exit();
        }
    }


    }

    //// REQUEST METHOD DELETE
    elseif($RequestType == "DELETE") {
    if(CustomerDeleteControl($RequestData)){
        foreach ($RequestData as $key=>$value) {
            $id = Guvenlik($value["id"]);
            $Query=$ConnDB->prepare("DELETE FROM `customers` WHERE `id`=?");
            $Query->execute([$id]);
            $Result=$Query->rowCount();
        }
        if($Result>0){
            echo json_encode(array(
                "Status"=>"Ok",
                "Code"=>200,
                "Message"=>"Deleted.",
            ),JSON_UNESCAPED_UNICODE);
            http_response_code(200);
            header("HTTP/1.1 200 'Ok");
            $ConnDB->commit();
            exit();

        }else{
            echo json_encode(array(
                "Status"=>"Error",
                "Code"=>400,
                "Message"=>"Bad Request.",
            ),JSON_UNESCAPED_UNICODE);
            http_response_code(400);
            header("HTTP/1.1 400 'BAD REQUEST");
            exit();
        }
    }
    } else {
        echo json_encode(array(
            "Status"=>"Error",
            "Code"=>400,
            "Message"=>"Bad Request.",
        ),JSON_UNESCAPED_UNICODE);
        http_response_code(400);
        header("HTTP/1.1 400 'BAD REQUEST");
        exit();

}

?>

