<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$addeddate=$_POST['addeddate'];
$electrician=$_POST['electrician'];
$addeddate=$_POST['addeddate'];
$totstarpoints=$_POST['totalpoints'];
$tableData=$_POST['tableData'];
$updatedatetime=date('Y-m-d h:i:s');

$sql3="SELECT `star_points` FROM `tbl_electrician` WHERE `idtbl_electrician` = '$electrician'";
$result3=$conn->query($sql3);
    
while ($row = $result3-> fetch_assoc()) {
    $elecstarpoints=  $row['star_points'];
}

    $elecstarpoints = $elecstarpoints + $totstarpoints;
    $query = "INSERT INTO `tbl_electrician_box`(`totalstarpoints`, `tbl_user_idtbl_user`, `tbl_electrician_idtbl_electrician`, `recieveddate`) Values ('$totstarpoints','$userID','$electrician','$addeddate')";
    if($conn->query($query)==true){
        $last_id = mysqli_insert_id($conn); 
        $updateelec="UPDATE `tbl_electrician` SET `star_points`='$elecstarpoints' WHERE `idtbl_electrician`='$electrician'";
        if($conn->query($updateelec)==true){
            
            foreach($tableData as $rowtabledata){
                $productID=$rowtabledata['col_1'];
                $qty=$rowtabledata['col_3'];
                $subtotalpoints=$rowtabledata['col_5'];
        
                $insertpointdetails="INSERT INTO `tbl_starpoints_details`(`tbl_product_idtbl_product`, `quantity`, `starpoints`, `tbl_electrician_box_idtbl_electrician_box`) VALUES ('$productID','$qty','$subtotalpoints','$last_id')";
                $conn->query($insertpointdetails);
            }
            header("Location:../electricianbox.php?action=4");

        }else{
            header("Location:../electricianbox.php?action=5");

        }

    }else{
        header("Location:../electricianbox.php?action=5");
    }



?>