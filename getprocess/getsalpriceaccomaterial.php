<?php
require_once('../connection/db.php');

$materialId=$_POST['materialId'];

$sqlmaterial="SELECT `saleprice`,`unitprice` FROM `tbl_material` WHERE `idtbl_material`='$materialId'";
$resultmaterial=$conn->query($sqlmaterial);
$rowmaterial=$resultmaterial->fetch_assoc();

if($resultmaterial-> num_rows > 0) {
    $obj=new stdClass();
    $obj->saleprice=$rowmaterial['saleprice'];
    $obj->unitprice=$rowmaterial['unitprice'];
}
else{
    $obj=new stdClass();
    $obj->saleprice='0';
    $obj->unitprice='0';
}
echo json_encode($obj);
?>