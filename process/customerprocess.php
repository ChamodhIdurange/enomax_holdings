<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}

$cusName=$_POST['cusName'];
$cusType=$_POST['cusType'];
$cusMobile=$_POST['cusMobile'];
$cusNic=$_POST['cusNic'];
$address=$_POST['address'];
$cusVatNum=$_POST['cusVatNum'];
$cusSVat=$_POST['cusSVat'];
$cusEmail=$_POST['cusEmail'];
$formcode=$_POST['formcode'];

$cusArea=$_POST['cusArea'];
$cusNoVisit=$_POST['cusNoVisit'];
$cusVisitDays=$_POST['cusVisitDays'];
$cusCreditlimit=$_POST['cusCreditlimit'];

$cuscredittype=$_POST['cuscredittype'];
$cuscreditdays=$_POST['cuscreditdays'];

$remarks=$_POST['remarks'];
$ref=$_POST['ref'];
$comment=$_POST['comment'];
$personpayment=$_POST['personpayment'];
$deliveryperson=$_POST['deliveryperson'];
$paymentmobile=$_POST['paymentmobile'];
$deliverymobile=$_POST['deliverymobile'];

$buisnesscopy=$_POST['buisnesscopy'];
$dealerboard=$_POST['dealerboard'];
$selfie=$_POST['selfie'];
$productimage=$_POST['productimage'];

//Buisness Copy
if(!empty($_FILES["buisnesscopy"]["name"])){
    $error=array();
    $extension=array("jpeg","jpg","png","gif","JPEG","JPG","PNG","GIF"); 
    $target_path = "../images/uploads/buisnesscopy/";

    $imageRandNum=rand(0,100000000);

    $file_name=$_FILES["buisnesscopy"]["name"];
    $file_tmp=$_FILES["buisnesscopy"]["tmp_name"];
    $ext=pathinfo($file_name,PATHINFO_EXTENSION);

    if(in_array($ext,$extension)){
        if(!file_exists("../images/uploads/customerlist/".$file_name)){
            $filename=basename($file_name,$ext);
            $newFileName=md5($filename).date('Y-m-d').date('h-i-sa').$imageRandNum.".".$ext;
            move_uploaded_file($file_tmp=$_FILES["buisnesscopy"]["tmp_name"],"../images/uploads/customerlist/".$newFileName);
            $image_path=$target_path.$newFileName;
        }
        else{
            $filename=basename($file_name,$ext);
            $newFileName=md5($filename).date('Y-m-d').date('h-i-sa').$imageRandNum.time().".".$ext;
            move_uploaded_file($file_tmp=$_FILES["buisnesscopy"]["tmp_name"],"../images/uploads/customerlist/".$newFileName);
            $image_path=$target_path.$newFileName;
        }
        $buisnessimagepath=substr($image_path,6);
    }
    else{
        array_push($error,"$file_name, ");
    }
}

if(!empty($_FILES["dealerboard"]["name"])){
    $error=array();
    $extension=array("jpeg","jpg","png","gif","JPEG","JPG","PNG","GIF"); 
    $target_path = "../images/uploads/dealerboard/";

    $imageRandNum=rand(0,100000000);

    $file_name=$_FILES["dealerboard"]["name"];
    $file_tmp=$_FILES["dealerboard"]["tmp_name"];
    $ext=pathinfo($file_name,PATHINFO_EXTENSION);

    if(in_array($ext,$extension)){
        if(!file_exists("../images/uploads/customerlist/".$file_name)){
            $filename=basename($file_name,$ext);
            $newFileName=md5($filename).date('Y-m-d').date('h-i-sa').$imageRandNum.".".$ext;
            move_uploaded_file($file_tmp=$_FILES["dealerboard"]["tmp_name"],"../images/uploads/customerlist/".$newFileName);
            $image_path=$target_path.$newFileName;
        }
        else{
            $filename=basename($file_name,$ext);
            $newFileName=md5($filename).date('Y-m-d').date('h-i-sa').$imageRandNum.time().".".$ext;
            move_uploaded_file($file_tmp=$_FILES["dealerboard"]["tmp_name"],"../images/uploads/customerlist/".$newFileName);
            $image_path=$target_path.$newFileName;
        }
        $dealerboardimagepath=substr($image_path,6);
    }
    else{
        array_push($error,"$file_name, ");
    }
}

if(!empty($_FILES["selfie"]["name"])){
    $error=array();
    $extension=array("jpeg","jpg","png","gif","JPEG","JPG","PNG","GIF"); 
    $target_path = "../images/uploads/selfie/";

    $imageRandNum=rand(0,100000000);

    $file_name=$_FILES["selfie"]["name"];
    $file_tmp=$_FILES["selfie"]["tmp_name"];
    $ext=pathinfo($file_name,PATHINFO_EXTENSION);

    if(in_array($ext,$extension)){
        if(!file_exists("../images/uploads/customerlist/".$file_name)){
            $filename=basename($file_name,$ext);
            $newFileName=md5($filename).date('Y-m-d').date('h-i-sa').$imageRandNum.".".$ext;
            move_uploaded_file($file_tmp=$_FILES["selfie"]["tmp_name"],"../images/uploads/customerlist/".$newFileName);
            $image_path=$target_path.$newFileName;
        }
        else{
            $filename=basename($file_name,$ext);
            $newFileName=md5($filename).date('Y-m-d').date('h-i-sa').$imageRandNum.time().".".$ext;
            move_uploaded_file($file_tmp=$_FILES["selfie"]["tmp_name"],"../images/uploads/customerlist/".$newFileName);
            $image_path=$target_path.$newFileName;
        }
        $selfieimagepath=substr($image_path,6);
    }
    else{
        array_push($error,"$file_name, ");
    }
}

if(!empty($_FILES["productimage"]["name"])){
    $error=array();
    $extension=array("jpeg","jpg","png","gif","JPEG","JPG","PNG","GIF"); 
    $target_path = "../images/uploads/customerlist/";

    $imageRandNum=rand(0,100000000);

    $file_name=$_FILES["productimage"]["name"];
    $file_tmp=$_FILES["productimage"]["tmp_name"];
    $ext=pathinfo($file_name,PATHINFO_EXTENSION);

    if(in_array($ext,$extension)){
        if(!file_exists("../images/uploads/customerlist/".$file_name)){
            $filename=basename($file_name,$ext);
            $newFileName=md5($filename).date('Y-m-d').date('h-i-sa').$imageRandNum.".".$ext;
            move_uploaded_file($file_tmp=$_FILES["productimage"]["tmp_name"],"../images/uploads/customerlist/".$newFileName);
            $image_path=$target_path.$newFileName;
        }
        else{
            $filename=basename($file_name,$ext);
            $newFileName=md5($filename).date('Y-m-d').date('h-i-sa').$imageRandNum.time().".".$ext;
            move_uploaded_file($file_tmp=$_FILES["productimage"]["tmp_name"],"../images/uploads/customerlist/".$newFileName);
            $image_path=$target_path.$newFileName;
        }
        $productimagepath=substr($image_path,6);
    }
    else{
        array_push($error,"$file_name, ");
    }
}

$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $query = "INSERT INTO `tbl_customer`(`type`, `name`, `nic`, `phone`, `email`, `address`, `vat_num`, `s_vat`, `numofvisitdays`, `creditlimit`, `credittype`, `creditperiod`, `emergencydate`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`, `remarks`, `ref`, `comment`, `paymentpersonname`, `paymentpersonmobile`, `deliverypersonname`, `deliverypersonmobile`, `buisnesscopyimagepath`, `dealerboardimagepath`, `selfieimagepath`, `productimagepath`, `formcode`) VALUES ('$cusType', '$cusName', '$cusNic', '$cusMobile', '$cusEmail', '$address', '$cusVatNum', '$cusSVat', '$cusNoVisit', '$cusCreditlimit','$cuscredittype','$cuscreditdays','','1','$updatedatetime', '$userID', '$cusArea', '$remarks', '$ref', '$comment', '$personpayment', '$paymentmobile', '$deliveryperson', '$deliverymobile', '$buisnessimagepath', '$dealerboardimagepath', '$selfieimagepath', '$productimagepath', '$formcode')";
    if($conn->query($query)==true){
        $customerID=$conn->insert_id;

        foreach($cusVisitDays AS $rowdays){
            $insertdayslist="INSERT INTO `tbl_customer_visitdays`(`dayname`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`) VALUES ('$rowdays','1','$updatedatetime','$userID','$customerID')";
            $conn->query($insertdayslist);
        }

        if($cusType==2){
            $insertcustomerlist="INSERT INTO `tbl_customer_product`(`saleprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_customer_idtbl_customer`) SELECT `saleprice`, 1, '$updatedatetime', $userID, `idtbl_product`, $customerID FROM `tbl_product` WHERE `status`=1";
            $conn->query($insertcustomerlist);
        }

        header("Location:../customer.php?action=4");
    }
    else{
        header("Location:../customer.php?action=5");
    }
}
else{
    $update="UPDATE `tbl_customer` SET `type`='$cusType',`name`='$cusName',`nic`='$cusNic',`phone`='$cusMobile',`email`='$cusEmail',`address`='$address',`vat_num`='$cusVatNum',`s_vat`='$cusSVat',`numofvisitdays`='$cusNoVisit',`creditlimit`='$cusCreditlimit',`credittype`='$cuscredittype',`creditperiod`='$cuscreditdays',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_area_idtbl_area`='$cusArea',`remarks`='$remarks',`ref`='$ref',`comment`='$comment',`paymentpersonname`='$personpayment' ,`paymentpersonmobile`='$paymentmobile' ,`deliverypersonname`='$deliveryperson' ,`deliverypersonmobile`='$deliverymobile',`formcode`='$formcode' WHERE `idtbl_customer`='$recordID'";
    if($conn->query($update)==true){
        $deletedayslist="DELETE FROM `tbl_customer_visitdays` WHERE `tbl_customer_idtbl_customer`='$recordID'";
        $conn->query($deletedayslist);

        foreach($cusVisitDays AS $rowdays){
            $insertdayslist="INSERT INTO `tbl_customer_visitdays`(`dayname`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`) VALUES ('$rowdays','1','$updatedatetime','$userID','$recordID')";
            $conn->query($insertdayslist);
        }

        header("Location:../customer.php?action=6");
    }
    else{
        header("Location:../customer.php?action=5");
    }
}

?>