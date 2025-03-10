<?php 
require_once('../connection/db.php');


if(!isset($_POST['searchTerm'])){ 
    $sql="SELECT DISTINCT `common_name` FROM `tbl_product` WHERE `status`=1 LIMIT 5";

}else{
    $search = $_POST['searchTerm'];   
    $sql="SELECT DISTINCT `common_name` FROM `tbl_product` WHERE `status`=1 AND `common_name` LIKE '%$search%'";

}
$result=$conn->query($sql);


$arraylist=array();


while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->id=$row['common_name'];
    $obj->text=$row['common_name'];
    
    array_push($arraylist, $obj);
}



echo json_encode($arraylist);
?>