<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];


$tableData=$_POST['tableData'];
$targetmonth=$_POST['targetmonth'];
$employee=$_POST['employee'];
$updatedatetime=date('Y-m-d h:i:s');

$targetmonth = $targetmonth. '-1';

$last_id = mysqli_insert_id($conn); 
foreach($tableData as $rowtabledata){
    $productID=$rowtabledata['col_1'];
    $qty=$rowtabledata['col_3'];
    $insertemptargetdetails="INSERT INTO `tbl_employee_target`(`month`, `targetqty`, `targetqtycomplete`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_employee_idtbl_employee`, `status`, `tbl_product_idtbl_product`) VALUES ('$targetmonth', '$qty', '0', '$updatedatetime','$userID','$employee', '1', '$productID')";
    $conn->query($insertemptargetdetails);
}


$actionObj=new stdClass();
$actionObj->icon='fas fa-check-circle';
$actionObj->title='';
$actionObj->message='Record added Successfully';
$actionObj->url='';
$actionObj->target='_blank';
$actionObj->type='success';
    
echo $actionJSON=json_encode($actionObj);
        

?>