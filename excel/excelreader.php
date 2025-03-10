<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
require_once "Classes/PHPExcel.php";
$userID=$_SESSION['userid'];


$updatedatetime=date('Y-m-d h:i:s');
    
$tmpfname = "customerlist.xlsx";
$excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
$excelObj = $excelReader->load($tmpfname);
$worksheet = $excelObj->getSheet(0);
$lastRow = $worksheet->getHighestRow();

for ($row = 1; $row <= $lastRow; $row++) {
    $cusname=$worksheet->getCell('A'.$row)->getValue();
    $custype=$worksheet->getCell('B'.$row)->getValue();
    $cusnic=$worksheet->getCell('C'.$row)->getValue();
    $cusarea=$worksheet->getCell('D'.$row)->getValue();
    $cusmobile=$worksheet->getCell('E'.$row)->getValue();
    $cusaddress=$worksheet->getCell('F'.$row)->getValue();
    $cusvat=$worksheet->getCell('G'.$row)->getValue();
    $cussvat=$worksheet->getCell('H'.$row)->getValue();
    $cusemail=$worksheet->getCell('I'.$row)->getValue();
    $cuscredit=$worksheet->getCell('J'.$row)->getValue();
    $cusdays=$worksheet->getCell('K'.$row)->getValue();
    $cuslimit=$worksheet->getCell('L'.$row)->getValue();
    $cusvisit=$worksheet->getCell('M'.$row)->getValue();
    $cusday=$worksheet->getCell('N'.$row)->getValue();
    
    $query = "INSERT INTO `tbl_customer`(`type`, `name`, `nic`, `phone`, `email`, `address`, `vat_num`, `s_vat`, `numofvisitdays`, `creditlimit`, `credittype`, `creditperiod`, `emergencydate`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`) VALUES ('$custype', '$cusname', '$cusnic', '$cusmobile', '$cusemail', '$cusaddress', '$cusvat', '$cussvat', '$cusvisit', '$cuslimit','$cuscredit','$cusdays','','1','$updatedatetime', '$userID', '$cusarea')";
    if($conn->query($query)==true){
        $customerID=$conn->insert_id;

        $explodelist=explode(',', $cusday);
        foreach($explodelist as $rowdays){
            $insertdayslist="INSERT INTO `tbl_customer_visitdays`(`dayname`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`) VALUES ('$rowdays','1','$updatedatetime','$userID','$customerID')";
            $conn->query($insertdayslist);
        }

        if($custype==2){
            $insertproductlist="INSERT INTO `tbl_customer_product`(`newsaleprice`, `refillsaleprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_customer_idtbl_customer`) SELECT `newsaleprice`, `refillsaleprice`, 1, '$updatedatetime', $userID, `idtbl_product`, $customerID FROM `tbl_product` WHERE `status`=1";
            $conn->query($insertproductlist);
        }
    }
}