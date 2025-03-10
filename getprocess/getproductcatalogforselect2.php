<?php 
require_once('../connection/db.php');


if(!isset($_POST['searchTerm'])){ 
    $sql="SELECT `idtbl_catalog_category`, `category` FROM `tbl_catalog_category` WHERE `status`=1 LIMIT 5";

}else{
    $search = $_POST['searchTerm'];   
    $sql="SELECT `idtbl_catalog_category`, `category` FROM `tbl_catalog_category` WHERE `status`=1 AND `category` LIKE '%$search%' LIMIT 5";

}
$result=$conn->query($sql);


$arraylist=array();


while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->id=$row['idtbl_catalog_category'];
    $obj->text=$row['category'];
    
    array_push($arraylist, $obj);
}

echo json_encode($arraylist);
?>