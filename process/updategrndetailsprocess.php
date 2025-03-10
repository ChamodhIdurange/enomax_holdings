<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];

$grnID=$_POST['grnID'];
$tableData=$_POST['tableData'];
$tot = 0;
$updatedatetime=date('Y-m-d h:i:s');


    

        foreach($tableData as $rowtabledata){
            $productID=$rowtabledata['col_2'];
            $qty=$rowtabledata['col_4'];
            $rowtot = $rowtabledata['col_7'];
            $tot = $tot +$rowtabledata['col_7'];

            $updateGRNDetail="UPDATE  `tbl_grndetail` SET `qty`='$qty', `total` = '$rowtot' WHERE `tbl_product_idtbl_product`='$productID' AND `tbl_grn_idtbl_grn` = '$grnID'";
            $conn->query($updateGRNDetail);

        }

    
        $updateGRN="UPDATE `tbl_grn` SET `total`='$tot', `updatedatetime` = '$updatedatetime' WHERE `idtbl_grn`='$grnID'";
        if($conn->query($updateGRN)==true){
                    
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