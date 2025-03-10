<?php 
require_once('../connection/db.php');


if(!isset($_POST['searchTerm'])){ 
    $sql="SELECT `idtbl_customer`, `name` FROM `tbl_customer`  WHERE `status`=1 LIMIT 5";

}else{
    $search = $_POST['searchTerm'];   
    $sql="SELECT `idtbl_customer`, `name` FROM `tbl_customer`  WHERE `status`=1 AND `name` LIKE '%$search%'";

}
$result=$conn->query($sql);


$arraylist=array();


while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->id=$row['idtbl_customer'];
    $obj->text=$row['name'];
    
    array_push($arraylist, $obj);
}



echo json_encode($arraylist);
?>