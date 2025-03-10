<?php 
require_once('../connection/db.php');

$userID=$_POST['userID'];

$sql="SELECT `imagepath` FROM `tbl_user` WHERE `idtbl_user`='$userID'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();
?>
<img src="<?php echo $row['imagepath'] ?>" class="img-fluid">