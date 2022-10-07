<?php
require_once ('../config/connect.php');
require_once ('../config/funcitons.php');
header("Content-Type:Application/json; charset=utf-8");
$RequestType=$_SERVER["REQUEST_METHOD"];
$RequestData = json_decode(file_get_contents("php://input"),true);



    //// REQUEST METHOD GET
    if($RequestType=="GET"){
        if(!isset($RequestData[0]['id'])){
            $Query=$ConnDB->prepare("SELECT * FROM `products` ORDER BY `id` ASC");
            $Query->execute();
            $Result=$Query->FetchAll(PDO::FETCH_ASSOC);
            echo json_encode(array($Result),
                JSON_UNESCAPED_UNICODE);
            http_response_code(200);
            header("HTTP/1.1 200 'Ok");
            exit();
        }

        if(ProductsGetControl($RequestData)){
            $ResultAr=array();
            foreach ($RequestData as $key=>$values){
                $id=Guvenlik($values['id']);
                $Query=$ConnDB->prepare("SELECT * FROM `products` WHERE `id`=? ORDER BY `ID` ASC");
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
        if(ProductsPostControl($RequestData)){
                foreach ($RequestData as $key=>$value){
                    $ProductName = Guvenlik($value["ProductName"]);
                    $CategoryID = Guvenlik($value["CategoryID"]);
                    $Price = Guvenlik($value["Price"]);
                    $Stock =Guvenlik(($value["Stock"]));
                    $Query=$ConnDB->prepare("INSERT INTO `products`(`ProductName`, `CategoryID`, `Price`, `Stock`) VALUES (?,?,?,?)");
                    $Query->execute([$ProductName,$CategoryID,$Price,$Stock]);
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
        if(ProductsPutControl($RequestData)){
            foreach ($RequestData as $key=>$value){
                $id=Guvenlik($value["id"]);
                $ProductName = Guvenlik($value["ProductName"]);
                $CategoryID = Guvenlik($value["CategoryID"]);
                $Price = Guvenlik($value["Price"]);
                $Stock = Guvenlik($value["Stock"]);
                $Query=$ConnDB->prepare("UPDATE `products` SET `ProductName`=?,`CategoryID`=?,`Price`=?,`Stock`=? WHERE `id`=?");
                $Query->execute([$ProductName,$CategoryID,$Price,$Stock,$id]);
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
            if(ProductsDeleteControl($RequestData)){
        foreach ($RequestData as $key=>$value) {
            $id = Guvenlik($value["id"]);
            $Query=$ConnDB->prepare("DELETE FROM `products` WHERE `id`=?");
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

