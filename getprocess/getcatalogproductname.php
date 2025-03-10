<?php
session_start();
require_once('../connection/db.php');

ini_set('max_execution_time', 3600); //3600 seconds = 60 minutes

$userID=$_SESSION['userid'];

if(!isset($_POST["searchTerm"])){
    $sqlagent="SELECT `product_name`, `idtbl_product` FROM `tbl_product` WHERE `status`=1 ORDER BY `idtbl_product` ASC LIMIT 5";
    $resultagent=$conn->query($sqlagent);
}
else{
    $searchTerm=$_POST["searchTerm"];
    
    if(!empty($searchTerm)){
        $sqlagent="SELECT `product_name`, `idtbl_product` FROM `tbl_product` WHERE `status`=1 AND `product_name` LIKE '%$searchTerm%' ORDER BY `product_name` ASC LIMIT 5";
        $resultagent=$conn->query($sqlagent);
    }
    else{
        $sqlagent="SELECT `product_name`, `idtbl_product` FROM `tbl_product` WHERE `status`=1 ORDER BY `idtbl_product` ASC LIMIT 5";
        $resultagent=$conn->query($sqlagent);
    }
}

$data=array();

while($rowagent=$resultagent->fetch_assoc()) {
    $data[]=array("id"=>$rowagent['idtbl_product'], "text"=>$rowagent['product_name']);
}

echo json_encode($data);