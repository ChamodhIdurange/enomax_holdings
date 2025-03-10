<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$customerID=$_POST['customerID'];
$rejectID=$_POST['rejectID'];
$avaproduct=$_POST['avaproduct'];
$avafullqty=$_POST['avafullqty'];
$avaemptyqty=$_POST['avaemptyqty'];
$avabufferqty=$_POST['avabufferqty'];
$invoicedate=$_POST['invoicedate'];
$today=date('Y-m-d');

$updatedatetime=date('Y-m-d h:i:s');

$sqlcheck="SELECT `idtbl_customer_ava_qty` FROM `tbl_customer_ava_qty` WHERE `status`=1 AND `date`='$invoicedate' AND `tbl_customer_idtbl_customer`='$customerID'";
$resultcheck =$conn-> query($sqlcheck);
$rowcheck=$resultcheck->fetch_assoc();

if($resultcheck->num_rows>0){
    $avaqtycusID=$rowcheck['idtbl_customer_ava_qty'];

    $updatestatus=0;

    $i=0;
    foreach($avaproduct as $rowproduct){
        $productID=$rowproduct;
        $fullqty=$avafullqty[$i];
        $emptyqty=$avaemptyqty[$i];
        $bufferqty=$avabufferqty[$i];

        $updatecustomeravailableinfo="UPDATE `tbl_customer_ava_qty_detail` SET `fullqty`='$fullqty',`emptyqty`='$emptyqty',`bufferqty`='$bufferqty',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `tbl_customer_ava_qty_idtbl_customer_ava_qty`='$avaqtycusID' AND `tbl_product_idtbl_product`='$productID'";
        if($conn->query($updatecustomeravailableinfo)==true){
            $updatestatus=1;
        }
        else{
            $updatestatus=0;
        }

        $i++;
    }

    if($updatestatus==1){
        $actionObj=new stdClass();
        $actionObj->icon='fas fa-check-circle';
        $actionObj->title='';
        $actionObj->message='Add Successfully';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='success';

        $obj=new stdClass();
        $obj->action=json_encode($actionObj);
        $obj->actiontype='1';

        echo json_encode($obj);
    }
    else{
        $actionObj=new stdClass();
        $actionObj->icon='fas fa-exclamation-triangle';
        $actionObj->title='';
        $actionObj->message='Record Error';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='danger';

        $obj=new stdClass();
        $obj->action=json_encode($actionObj);
        $obj->actiontype='0';

        echo json_encode($obj);
    }
    
}
else{   
    $insretcustomeravailable="INSERT INTO `tbl_customer_ava_qty`(`date`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_reject_reason_idtbl_reject_reason`) VALUES ('$invoicedate','1','$updatedatetime','$userID','$customerID','$rejectID')";
    if($conn->query($insretcustomeravailable)==true){
        $customeravailableID=$conn->insert_id;
        $i=0;
        foreach($avaproduct as $rowproduct){
            $productID=$rowproduct;
            $fullqty=$avafullqty[$i];
            $emptyqty=$avaemptyqty[$i];
            $bufferqty=$avabufferqty[$i];

            $insertcustomeravailableinfo="INSERT INTO `tbl_customer_ava_qty_detail`(`fullqty`, `emptyqty`, `bufferqty`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_ava_qty_idtbl_customer_ava_qty`, `tbl_product_idtbl_product`) VALUES ('$fullqty','$emptyqty','$bufferqty','1','$updatedatetime','$userID','$customeravailableID','$productID')";
            $conn->query($insertcustomeravailableinfo);

            $i++;
        }

        $actionObj=new stdClass();
        $actionObj->icon='fas fa-check-circle';
        $actionObj->title='';
        $actionObj->message='Add Successfully';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='success';

        $obj=new stdClass();
        $obj->action=json_encode($actionObj);
        $obj->actiontype='1';

        echo json_encode($obj);
    }
    else{
        $actionObj=new stdClass();
        $actionObj->icon='fas fa-exclamation-triangle';
        $actionObj->title='';
        $actionObj->message='Record Error';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='danger';

        $obj=new stdClass();
        $obj->action=json_encode($actionObj);
        $obj->actiontype='0';

        echo json_encode($obj);
    }
}
