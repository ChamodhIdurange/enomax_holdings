<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$companyname = $_POST['companyname'];
$location = $_POST['location'];
$email = $_POST['email'];
$contact = $_POST['contact'];
$headperson = $_POST['headperson'];

$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $query = "INSERT INTO `tbl_query_company`(`name`, `location`, `email`, `contact`, `headperson`, `status`, `insertdatetime`, `tbl_user_idtbl_user`) VALUES ('$companyname','$location','$email','$contact','$headperson','1','$updatedatetime','$userID')";
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
       

        header("Location:../querycompany.php?action=4");
    }
    else{header("Location:../querycompany.php?action=5");}
}
else{
    $query = "UPDATE `tbl_query_company` SET `name`='$companyname',`location`='$location',`email`='$email',`contact`='$contact',`headperson`='$headperson',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_query_company`='$recordID'";
    if($conn->query($query)==true){  
       
        header("Location:../querycompany.php?action=6");
    }
    else{header("Location:../querycompany.php?action=5");}
}
?>