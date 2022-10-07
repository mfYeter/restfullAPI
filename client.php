<?php
require_once ('config/funcitons.php');
$RequestURI=explode('/',$_SERVER['REQUEST_URI']);
$RequestType=$_SERVER["REQUEST_METHOD"];
$RequestData = json_decode(file_get_contents("php://input"), true);

 $RequestURI = $RequestURI[3];

switch ($RequestURI) {
    case "customers":
        $curl=curl_init("http://localhost/api/core_api/api_customers.php");
        break;
    case "products":
        $curl=curl_init("http://localhost/api/core_api/api_products.php");
        break;
    case "orders":
        $curl=curl_init("http://localhost/api/core_api/api_orders.php");
        break;
    default:
        echo "";
}


curl_setopt($curl,CURLOPT_CUSTOMREQUEST,$RequestType);
curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($RequestData));
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_HTTPHEADER,array("Content-Type:Application/json; charset=utf-8"));
$Answer=curl_exec($curl);
curl_close($curl);


echo $Answer;

?>
