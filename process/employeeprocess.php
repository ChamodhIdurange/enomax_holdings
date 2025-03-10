<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$empname = $_POST['empname'];
$empepf = $_POST['empepf'];
$empnic = $_POST['empnic'];
$empmobile = $_POST['empmobile'];
$empaddress = $_POST['empaddress'];
$emptype = $_POST['emptype'];
$salesmanager = $_POST['salesmanager'];
$useraccount = $_POST['useraccount'];

$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $query = "INSERT INTO `tbl_employee`(`name`, `epfno`, `nic`, `phone`, `address`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_user_type_idtbl_user_type`, `tbl_sales_manager_idtbl_sales_manager`, `useraccountid`) VALUES ('$empname','$empepf','$empnic','$empmobile','$empaddress','1','$updatedatetime','$userID','$emptype', '$salesmanager', '$useraccount')";
    if($conn->query($query)==true){
        $last_id = mysqli_insert_id($conn); 

        if(!empty($_POST['area'])){
            $area = $_POST['area'];
            foreach($area as $ar){    
                $insert="INSERT INTO `tbl_employee_area`(`tbl_area_idtbl_area`, `tbl_employee_idtbl_employee`) VALUES ('$ar','$last_id')";
                if($conn->query($insert)==true){ 
                    
                }
            }
        }
       

        header("Location:../employee.php?action=4");
    }
    else{header("Location:../employee.php?action=5");}
}
else{
    $query = "UPDATE `tbl_employee` SET `name`='$empname',`epfno`='$empepf',`nic`='$empnic',`phone`='$empmobile',`address`='$empaddress',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID', `tbl_user_type_idtbl_user_type`='$emptype', `tbl_sales_manager_idtbl_sales_manager` = '$salesmanager', `useraccountid` = '$useraccount' WHERE `idtbl_employee`='$recordID'";
    if($conn->query($query)==true){
        $delete="DELETE FROM `tbl_employee_area` WHERE `tbl_employee_idtbl_employee` = '$recordID'";

        if($conn->query($delete)==true){
            if(!empty($_POST['area'])){
                $area = $_POST['area'];
                foreach($area as $ar){    
                    $insert="INSERT INTO `tbl_employee_area`(`tbl_area_idtbl_area`, `tbl_employee_idtbl_employee`) VALUES ('$ar','$recordID')";
                    if($conn->query($insert)==true){ 
                        
                    }
                }
            }
        }
        
  
       
        header("Location:../employee.php?action=6");
    }
    else{header("Location:../employee.php?action=5");}
}
?>