<?php 
require_once('../connection/db.php');

$cat=$_POST['cat'];
$array = [];

if($cat == "all"){
    $sql="SELECT * FROM `tbl_product` WHERE `status` in ('1','2')";

}else{
$sql="SELECT * FROM `tbl_product` WHERE `tbl_product_category_idtbl_product_category`='$cat' AND `status` in ('1','2')";

}
$result=$conn->query($sql);

while ($row = $result-> fetch_assoc()) { 
    $obj=new stdClass();
    $obj->id=$row['idtbl_product'];
    $obj->name=$row['product_name'];
    array_push($array,$obj);
}


echo json_encode($array);
?>