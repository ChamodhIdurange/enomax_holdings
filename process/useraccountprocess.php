<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$name=$_POST['accountname'];
$username=$_POST['username'];
if(!empty($_POST['password'])){$password=$_POST['password'];$password = md5($password);}else{$password='';}
$usertype=$_POST['usertype'];
$updatedatetime=date('Y-m-d h:i:s');

$userimagepath='';

if(!empty($_FILES["userimages"]["name"])){
    $error=array();
    $extension=array("jpeg","jpg","png","gif","JPEG","JPG","PNG","GIF"); 
    $target_path = "../images/uploads/userimage/";

    $imageRandNum=rand(0,100000000);

    $file_name=$_FILES["userimages"]["name"];
    $file_tmp=$_FILES["userimages"]["tmp_name"];
    $ext=pathinfo($file_name,PATHINFO_EXTENSION);

    if(in_array($ext,$extension)){
        if(!file_exists("../images/uploads/userimage/".$file_name)){
            $filename=basename($file_name,$ext);
            $newFileName=md5($filename).date('Y-m-d').date('h-i-sa').$imageRandNum.".".$ext;
            move_uploaded_file($file_tmp=$_FILES["userimages"]["tmp_name"],"../images/uploads/userimage/".$newFileName);
            $image_path=$target_path.$newFileName;
        }
        else{
            $filename=basename($file_name,$ext);
            $newFileName=md5($filename).date('Y-m-d').date('h-i-sa').$imageRandNum.time().".".$ext;
            move_uploaded_file($file_tmp=$_FILES["userimages"]["tmp_name"],"../images/uploads/userimage/".$newFileName);
            $image_path=$target_path.$newFileName;
        }
        $userimagepath=substr($image_path,3);
    }
    else{
        array_push($error,"$file_name, ");
    }
}

if($recordOption==1){
    $insert="INSERT INTO `tbl_user`(`name`, `username`, `password`, `imagepath`, `status`, `updatedatetime`, `tbl_user_type_idtbl_user_type`) VALUES ('$name','$username','$password','$userimagepath','1','$updatedatetime','$usertype')";
    if($conn->query($insert)==true){        
        header("Location:../useraccount.php?action=4");
    }
    else{header("Location:../useraccount.php?action=5");}
}
else{
    if($userimagepath!=''){
        if($password!=''){
            $update="UPDATE `tbl_user` SET `name`='$name',`username`='$username',`password`='$password', `imagepath`='$userimagepath',`updatedatetime`='$updatedatetime',`tbl_user_type_idtbl_user_type`='$usertype' WHERE `idtbl_user`='$recordID'";
        }
        else{
            $update="UPDATE `tbl_user` SET `name`='$name',`username`='$username', `imagepath`='$userimagepath',`updatedatetime`='$updatedatetime',`tbl_user_type_idtbl_user_type`='$usertype' WHERE `idtbl_user`='$recordID'";
        }
    }
    else{
        if($password!=''){
            $update="UPDATE `tbl_user` SET `name`='$name',`username`='$username',`password`='$password',`updatedatetime`='$updatedatetime',`tbl_user_type_idtbl_user_type`='$usertype' WHERE `idtbl_user`='$recordID'";
        }
        else{
            $update="UPDATE `tbl_user` SET `name`='$name',`username`='$username',`updatedatetime`='$updatedatetime',`tbl_user_type_idtbl_user_type`='$usertype' WHERE `idtbl_user`='$recordID'";
        }
    }
    
    if($conn->query($update)==true){     
        header("Location:../useraccount.php?action=6");
    }
    else{header("Location:../useraccount.php?action=5");}
}
?>