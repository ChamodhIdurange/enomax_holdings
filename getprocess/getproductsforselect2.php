<?php 
require_once('../connection/db.php');

if(!isset($_POST['searchTerm'])){ 
    $fetchData = "SELECT * FROM `tbl_product` WHERE `status` = 1 ORDER BY `product_name` LIMIT 5";
  }else{ 
    $search = $_POST['searchTerm'];   
    $fetchData = "SELECT * FROM `tbl_product` WHERE `status` = 1 AND `product_name` like '%".$search."%' LIMIT 5";
  } 
  $result=$conn->query($fetchData);
  $data = array();
  while ($row = $result-> fetch_assoc()) {    
    $data[] = array("id"=>$row['idtbl_product'], "text"=>$row['product_name']);
  }
echo json_encode($data);

?>