<?php
require_once('dbConnect.php');

$sql = "SELECT `idtbl_account`, `accountno`, `accountname` 
        FROM `tbl_account` 
        WHERE `status` = '1' AND `tbl_user_idtbl_user` = '1'";

$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    $result[] = array(
        "account_id"   => $row['idtbl_account'],
        "accountno"    => $row['accountno'],
        "accountname"  => $row['accountname']
    );
}

echo json_encode($result);
mysqli_close($con);
