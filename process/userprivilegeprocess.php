<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}

$userlist=addslashes($_POST['userlist']);
$menulist=$_POST['menulist'];
if(!empty($_POST['addcheck'])){$addcheck=$_POST['addcheck'];}else{$addcheck=0;}
if(!empty($_POST['editcheck'])){$editcheck=$_POST['editcheck'];}else{$editcheck=0;}
if(!empty($_POST['statuscheck'])){$statuscheck=$_POST['statuscheck'];}else{$statuscheck=0;}
if(!empty($_POST['removecheck'])){$removecheck=$_POST['removecheck'];}else{$removecheck=0;}

$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    foreach($menulist as $rowmenulist){
        $insert="INSERT INTO `tbl_user_privilege`(`add`, `edit`, `statuschange`, `remove`, `access_status`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_menu_list_idtbl_menu_list`) VALUES ('$addcheck','$editcheck','$statuscheck','$removecheck','1','1','$updatedatetime','$userlist','$rowmenulist')";
        if($conn->query($insert)==true){
            $insertstatus='1';
        }
        else{
            $insertstatus='0';
        }
    }
    
    if($insertstatus==1){  
        header("Location:../userprivilege.php?action=4");
    }
    else{header("Location:../userprivilege.php?action=5");}
}
else{
    foreach($menulist as $rowmenulist){
        $update="UPDATE `tbl_user_privilege` SET `add`='$addcheck',`edit`='$editcheck',`statuschange`='$statuscheck',`remove`='$removecheck',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userlist', `tbl_menu_list_idtbl_menu_list`='$rowmenulist' WHERE `idtbl_user_privilege`='$recordID'";
        if($conn->query($update)==true){
            $updatestatus='1';
        }
        else{
            $updatestatus='0';
        }
    }
    if($updatestatus==1){     
        header("Location:../userprivilege.php?action=6");
    }
    else{header("Location:../userprivilege.php?action=5");}
}
?>