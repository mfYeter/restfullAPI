<?php
require_once ('../config/connect.php');
require_once ('../config/funcitons.php');
header("Content-Type:Application/json; charset=utf-8");
$RequestType=$_SERVER["REQUEST_METHOD"];
$RequestData = json_decode(file_get_contents("php://input"),true);



    //// REQUEST METHOD GET
    if($RequestType=="GET"){
    $Orders = array();
    $ViewDetailsOrders = array();
    if (!isset($RequestData[0]['id'])) {
        $QueryOrders = $ConnDB->prepare("SELECT * FROM `orders` ORDER BY `id` ASC");
        $QueryOrders->execute();
        $ResultOrders = $QueryOrders->FetchAll(PDO::FETCH_OBJ);

        foreach ($ResultOrders as $Details) {
            $OrdersArr = array(
                "id" => $Details->id,
                "CustomerID" => $Details->CustomerID,
                "items" => array(),
                "discounts"=>array(),
                "Total" => $Details->total,
                "TotalDiscount"=>$Details->totalDiscount,
                "discountedTotal"=>$Details->discountedTotal);
            array_push($Orders, $OrdersArr);
            $QueryOrdersDetails = $ConnDB->prepare("SELECT * FROM `orderdetails` WHERE `orderId`=?");
            $QueryOrdersDetails->execute([$Details->id]);
            $ResultOrdersDetails = $QueryOrdersDetails->FetchAll(PDO::FETCH_OBJ);
            if (array_key_exists("id", $ResultOrders) == array_key_exists("orderID", $ResultOrdersDetails)) {
                foreach ($ResultOrdersDetails as $Details) {
                    $OrdersArr['items'][] = array(
                        "productId" => $Details->productId,
                        "quantity" => $Details->quantity,
                        "unitPrice" => $Details->unitPrice,
                        "Total" => $Details->Total
                    );

                    $OrdersDiscountDetailsQuery=$ConnDB->prepare("SELECT * FROM discountsdetails where `orderId`=?");
                    $OrdersDiscountDetailsQuery->execute([$Details->id]);
                    $OrdersDiscountDetailsResult=$OrdersDiscountDetailsQuery->FetchAll(PDO::FETCH_OBJ);
                    if(is_array($OrdersDiscountDetailsResult) || is_object($OrdersDiscountDetailsResult)){

                    foreach ($OrdersDiscountDetailsResult as $DisDetails){

                        $OrdersArr['discounts'][] = array(
                            "discountReason"=>$DisDetails->discountReason,
                            "discountAmount"=>$DisDetails->discountAmount,
                        );
                    }
                    }
                }
            }
            array_push($ViewDetailsOrders, $OrdersArr);



        }

        echo json_encode(array($ViewDetailsOrders),
            JSON_UNESCAPED_UNICODE);
        http_response_code(200);
        header("HTTP/1.1 200 'Ok");
        exit();

    }

    if (OrdersGetControl($RequestData)) {
        foreach ($RequestData as $key => $values) {
            $id = Guvenlik($values['id']);
            $QueryOrders = $ConnDB->prepare("SELECT * FROM `orders`  WHERE `id`=? ORDER BY `id` ASC");
            $QueryOrders->execute([$id]);
            $Result = $QueryOrders->rowCount();
            $DataOrders = $QueryOrders->Fetch(PDO::FETCH_OBJ);

            $OrdersArr = array(
                "id" => $DataOrders->id,
                "CustomerID" => $DataOrders->CustomerID,
                "items" => array(),
                "Total" => $DataOrders->Total);
            array_push($Orders, $OrdersArr);

            $QueryOrdersDetails = $ConnDB->prepare("SELECT * FROM `orderdetails` WHERE `orderId`=?");
            $QueryOrdersDetails->execute([$DataOrders->id]);
            $ResultOrdersDetails = $QueryOrdersDetails->FetchAll(PDO::FETCH_OBJ);
            if (array_key_exists("id", $RequestData) == array_key_exists("orderID", $ResultOrdersDetails)) {
                foreach ($ResultOrdersDetails as $Details) {
                    $OrdersArr['items'][] = array(
                        "productId" => $Details->productId,
                        "quantity" => $Details->quantity,
                        "unitPrice" => $Details->unitPrice,
                        "Total" => $Details->Total
                    );
                }
            }
            array_push($ViewDetailsOrders, $OrdersArr);

        }

        if ($Result > 0) {
            echo json_encode(array($ViewDetailsOrders), JSON_UNESCAPED_UNICODE);
            http_response_code(405);
            header("HTTP/1.1 405  'Method Not Allowed");
        } else {
            echo json_encode(array(
                "Status" => "Error",
                "Code" => 400,
                "Message" => "Bad Request, Data is not Found.",
            ), JSON_UNESCAPED_UNICODE);
            http_response_code(400);
            header("HTTP/1.1 400 'Bad Request");
            exit();
        }
    }
}

    //// REQUEST METHOD POST
    elseif($RequestType=="POST") {

        if (OrdersPostControl($RequestData)) {
            $CustomersArr = array();
            $ItemsArr = array();
            $CheckOrder=array();

            foreach ($RequestData as $key1 => $Data1) {
                $SameCategoryArr = array();
                $SubTotal = 0;
                $Data1Arr = array(
                    "CustomerID" => $Data1['customerId'],
                    "items" => array(),
                    "TotCategory" => array(),
                    "Subtotal" => "",
                    "Discount" => array(),
                    "total" => "",
                    "totalDiscount" => "",
                    "discountedTotal" => "",


                );
                array_push($CustomersArr, $Data1Arr);



                foreach ($Data1['items'] as $key2 => $Data2) {
                    $Query = $ConnDB->prepare("SELECT * FROM `products` where id=?");
                    $Query->execute([$Data2['productId']]);
                    $Result = $Query->FetchAll(PDO::FETCH_ASSOC);
                    $Count = $Query->rowcount();
                    if ($Count <= 0) {
                        echo json_encode(array(
                            "Status" => "Error",
                            "Code" => 400,
                            "Message" => "Bad Request, items => $key2. " . $Data1['items'][$key2]['productId'] . $productId . " ProductCode is insufficient in stock.",
                        ), JSON_UNESCAPED_UNICODE);
                        http_response_code(400);
                        header("HTTP/1.1 400 'Bad Request");
                        $ConnDB->rollBack();
                        exit();
                    }


                    foreach ($Result as $key3 => $Data3) {

                        $Data1Arr['items'][] = array(
                            "productId" => $Data2["productId"],
                            "quantity" => $Data2["quantity"],
                            "Category" => $Data3['CategoryID'],
                            "Price" => $Data3['Price'],
                            "subtotal" => $Data2["quantity"] * $Data3['Price'],

                        );

                        $SubTotal = $SubTotal + ($Data2['quantity'] * $Data3['Price']);
                        $Data1Arr['Subtotal'] = $SubTotal;


                        for ($i = 1; $i <= $Data2['quantity']; $i++) {
                            array_push($SameCategoryArr, $Data3['CategoryID']);
                        }

                    }

                    $CategoryCount = array_count_values($SameCategoryArr);
                    $Data1Arr['TotCategory'] = $CategoryCount;


                }


                if (array_filter($Data1Arr['TotCategory'], "DiscountControl_Cat_1") and array_key_exists("1", $Data1Arr['TotCategory'])) {
                    $Data1Arr['Discount'][] = array(
                        "discountReason" => "1_Category_Get_%20",
                        "discountAmount" => $Val = (($SubTotal / 100) * 20),

                    );
                    $Data1Arr['totalDiscount'] = $Val = (($SubTotal / 100) * 20);
                    $Data1Arr['discountedTotal'] = ($SubTotal / 100) * 20;

                } else {
                    $Data1Arr['total'] = $SubTotal;
                }


                if (array_filter($Data1Arr['TotCategory'], "DiscountControl_Cat_2") and array_key_exists("2", $Data1Arr['TotCategory'])) {
                    $Data1Arr['Discount'][] = array(
                        "discountReason" => "2_Category_Get_1_Free",
                        "discountAmount" => ($Data2['quantity'] -1) * $Data3['Price'],
                    );
                    $Data1Arr['totalDiscount'] = ($Data2['quantity'] - 1) * $Data3['Price'];
                    $Data1Arr['discountedTotal'] = ($SubTotal - (($Data2['quantity'] - 1) * $Data3['Price']));
                } else {

                    $Data1Arr['total'] = $SubTotal;
                }

                if (DiscountControl_Cat3($Data1Arr['Subtotal']) >= 1000) {
                    $Data1Arr['Discount'][] = array(
                        "discountReason" => "1000_over_get_%10",
                        "discountAmount" => ($SubTotal / 100) * 10
                    );
                    $Data1Arr['totalDiscount'] = ($SubTotal / 100) * 10 + $Val;
                    $Data1Arr['discountedTotal'] = $SubTotal - ($SubTotal / 100) * 10 - $Val;
                } else {
                    $Data1Arr['total'] = $SubTotal;
                }

                array_push($ItemsArr, $Data1Arr);

            }


//


            foreach ($ItemsArr as $Key1 => $Data1) {
                $CustomerID = $Data1['CustomerID'];
                $Total = $SubTotal;
                $totalDiscount = $Data1['totalDiscount'];
                $discountedTotal = $Data1['discountedTotal'];

                $CusInfoQuery = $ConnDB->prepare("INSERT INTO `orders`(`CustomerID`, `total`, `totalDiscount`, `discountedTotal`) VALUES (?,?,?,?)");
                $CusInfoQuery->execute([$CustomerID, $Total, $totalDiscount, $discountedTotal]);
                $CusInfoResult = $CusInfoQuery->rowCount();
                $LastInsertId = $ConnDB->lastInsertId();
                if ($CusInfoResult > 0) {
                    if(is_array($Data1) || is_object($Data1)){
                    foreach ($Data1 as $Key2 => $Data2) {
                        if(is_array($Data2) || is_object($Data2)){
                        foreach ($Data2 as $key3 => $Data3) {
                            if(is_array($Data3) || is_object($Data3)){
                                if(isset($Data3['productId'], $Data3['quantity'],$Data3['Price'],$Data3['Price']))
                                {
                                    $productId = $Data3['productId'];
                                     $quantity = $Data3['quantity'];
                                     $Price = $Data3['Price'];
                                     $subtotal = $Data3['subtotal'];

                                }
                                if(isset($Data3['discountReason'], $Data3['discountAmount'])){
                                      $discountReason= $Data3['discountReason'];
                                      $discountAmount= $Data3['discountAmount'];
                                }

                                //STOK KONTROL ET
                                $CheckStockQuery = $ConnDB->prepare("SELECT `Stock` FROM `products` WHERE `id`=?");
                                $CheckStockQuery->execute([$productId]);
                                $CheckStockResult = $CheckStockQuery->Fetch(PDO::FETCH_OBJ);
                                $Stock = $CheckStockResult->Stock - $quantity;


                                if ($Stock < 0) {
                                    echo json_encode(array(
                                        "Status" => "Error",
                                        "Code" => 400,
                                        "Message" => "Bad Request, items=> Key $key3. $productId ProductCode  Insufficient stock.",
                                    ), JSON_UNESCAPED_UNICODE);
                                    http_response_code(400);
                                    header("HTTP/1.1 400 'Bad Request");
                                    $ConnDB->rollBack();
                                    exit();
                                } else {
                                    $UpdateStockQuery = $ConnDB->prepare("UPDATE `products` SET `Stock`=? WHERE `id`=?");
                                    $UpdateStockQuery->execute([$Stock, $productId]);
                                    $UpdateStockResult = $UpdateStockQuery->rowCount();
                                    if ($UpdateStockResult == 0) {
                                        $ConnDB->rollBack();

                                    }

                                    $Total = $Price * $quantity;
                                    $AddOrderDetails = $ConnDB->prepare("INSERT INTO `orderdetails`(`customerId`, `orderId`, `productId`, `quantity`, `unitPrice`, `Total`) VALUES (?,?,?,?,?,?)");
                                    $AddOrderDetails->execute([$CustomerID, $LastInsertId, $productId, $quantity, $Price, $Total]);
                                    $AddOrderResult = $AddOrderDetails->rowCount();
                                    if ($AddOrderResult == 0) {
                                        $ConnDB->rollBack();
                                        exit();
                                    }

                                    if(isset($discountReason, $discountAmount)){
                                    $AddDiscount = $ConnDB->prepare("INSERT INTO `discountsdetails`(`orderId`, `discountReason`, `discountAmount`, `subtotal`) VALUES (?,?,?,?)");
                                    $AddDiscount->execute([$LastInsertId, $discountReason, $discountAmount, $discountedTotal]);
                                    $AddDiscountResult = $AddDiscount->rowCount();
                                    if ($AddDiscountResult == 0) {
                                        $ConnDB->rollBack();
                                        exit();
                                    }else{

                                            $CheckOrderQuery=$ConnDB->prepare("SELECT * from `orders` WHERE `id`=?");
                                            $CheckOrderQuery->execute([$LastInsertId]);
                                            $CheckOrderResult=$CheckOrderQuery->Fetch(PDO::FETCH_OBJ);
                                                    $CheckOrderArr=array(
                                                        "orderId"=>$CheckOrderResult->id,
                                                        "discounts"=>array(),
                                                        "totalDiscount"=>$CheckOrderResult->totalDiscount,
                                                        "discountedTotal"=>$CheckOrderResult->discountedTotal,
                                                    );


                                                    $CheckOrderDetais=$ConnDB->prepare("SELECT * FROM `discountsdetails` WHERE `Id`=?");
                                                    $CheckOrderDetais->execute([$CheckOrderResult->id]);
                                                    $CheckOrderDetaisResult=$CheckOrderDetais->FetchAll(PDO::FETCH_OBJ);
                                                    foreach ($CheckOrderDetaisResult as $OrDetais){
                                                    $CheckOrderArr['discounts'][]=array(
                                                            "discountReason"=>$OrDetais->discountReason,
                                                            "discountAmount"=>$OrDetais->discountAmount,
                                                            "subtotal"=>$OrDetais->subtotal,

                                                    );
                                                        array_push($CheckOrder,$CheckOrderArr);
                                                    }

                                    }
                                    }


                                }




                            }





                        }
                        }

                    }
                }

                }else{
                    $ConnDB->rollBack();
                }

            }





            echo json_encode(array(
                "Status"=>"OK",
                "Code"=>201,
                "Message"=>"Data is Created.",
                "Created"=>array($CheckOrder)
            ),JSON_UNESCAPED_UNICODE);
            http_response_code(201);
            header("HTTP/1.1 201 'Created");
            $ConnDB->commit();
            exit();



        }

    }

    //// REQUEST METHOD PUT
    elseif($RequestType=="PUT") {

    }

    //// REQUEST METHOD DELETE
    elseif($RequestType == "DELETE") {

    }

?>

