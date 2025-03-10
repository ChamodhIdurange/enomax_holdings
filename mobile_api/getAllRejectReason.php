<?php

require_once('dbConnect.php');

$sql="SELECT * FROM `tbl_reject_reason` WHERE `status`='1'";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    array_push($result, array( "id" => $row['idtbl_reject_reason'], "reason" => $row['reason']));
}

print(json_encode($result));
mysqli_close($con);

?>