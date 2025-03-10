
<?php
require_once('dbConnect.php');

$sql = "SELECT `idtbl_bank`,`bankname` FROM  `tbl_bank` WHERE `status`='1' AND  `tbl_user_idtbl_user`= '1'  ; ";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    array_push($result, array( "bank_id" => $row['idtbl_bank'], "bankname" => $row['bankname']));
}

print(json_encode($result));
mysqli_close($con);
?>