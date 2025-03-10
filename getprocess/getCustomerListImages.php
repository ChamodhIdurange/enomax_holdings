<?php 
require_once('../connection/db.php');

$customerId=$_POST['customerId'];

$sql="SELECT `dealerboardimagepath` FROM `tbl_customer` WHERE `idtbl_customer`='$customerId' AND `status`=1";
$result=$conn->query($sql);
$row=$result->fetch_assoc();
?>
<img src="../<?php echo $row['dealerboardimagepath'] ?>" class="img-fluid">