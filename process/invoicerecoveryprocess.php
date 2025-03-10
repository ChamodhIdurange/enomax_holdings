<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$today=date('Y-m-d');
$invoicelist=$_POST['invoicedata'];
$totrefill=$_POST['totrefill'];
$totnew=$_POST['totnew'];

$total=$totnew+$totrefill;

$updatedatetime=date('Y-m-d h:i:s');

$insertrecovery="INSERT INTO `tbl_invoice_diff`(`date`, `total`, `paymentrecovery`, `recoveramount`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$today','$total','0','0','1','$updatedatetime','$userID')";
if($conn->query($insertrecovery)==true){
    $recoveryID=$conn->insert_id;

    foreach($invoicelist as $rowinvoicedata){
        $invoiceID=$rowinvoicedata['invoiceID'];

        $inserthastable="INSERT INTO `tbl_invoice_diff_has_tbl_invoice`(`tbl_invoice_diff_idtbl_invoice_diff`, `tbl_invoice_idtbl_invoice`) VALUES ('$recoveryID','$invoiceID')";
        $conn->query($inserthastable);

        $updateinvoice="UPDATE `tbl_invoice` SET `companydiffsend`='1' WHERE `idtbl_invoice`='$invoiceID'";
        $conn->query($updateinvoice);
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

?>