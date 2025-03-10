<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];

$grnnum=$_POST['grnnum'];
$batchno=$_POST['batchno'];
$porderID=$_POST['ponumber'];
$grndate=$_POST['grndate'];
$grninvoice=$_POST['grninvoice'];
$grndispatch=$_POST['grndispatch'];
$grnnettotal=$_POST['grnnettotal'];
$grnnettotalwithoutvat=$_POST['grnnettotalwithoutvat'];
$taxamount=$_POST['taxamount'];
$tableData=$_POST['tableData'];

$updatedatetime=date('Y-m-d h:i:s');

$insertgrn="INSERT INTO `tbl_grn`(`date`, `total`, `vatamount`, `nettotal`, `invoicenum`, `dispatchnum`, `batchno`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`) VALUES ('$grndate','$grnnettotalwithoutvat','$taxamount','$grnnettotal','$grninvoice','$grndispatch','$batchno','1','$updatedatetime','$userID','$porderID')";
if($conn->query($insertgrn)==true){
    $grnid=$conn->insert_id;

    foreach($tableData as $rowtabledata){
        $product=$rowtabledata['col_2'];
        $unitprice=$rowtabledata['col_3'];
        $saleprice=$rowtabledata['col_4'];
        $newqty=$rowtabledata['col_7'];
        $total=$rowtabledata['col_8'];

        $insretgrndetail="INSERT INTO `tbl_grndetail`(`date`, `type`, `qty`, `unitprice`, `saleprice`, `total`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_grn_idtbl_grn`, `tbl_product_idtbl_product`) VALUES ('$grndate','0','$newqty','$unitprice','$saleprice','$total','1','$updatedatetime','$userID','$grnid','$product')";
        $conn->query($insretgrndetail);
        
        $updateorder="UPDATE `tbl_porder` SET `grnissuestatus`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$porderID'";
        $conn->query($updateorder);
    }
    
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-check-circle';
    $actionObj->title='';
    $actionObj->message='Add Successfully';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='success';

    echo $actionJSON=json_encode($actionObj);
}
else{
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Record Error';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    echo $actionJSON=json_encode($actionObj);
}