<?php
require_once('dbConnect.php');

$sql = "SELECT `idtbl_product_free`, `qtycount`, `freecount`, `issueproductid`, `freeproductid` FROM `tbl_product_free` WHERE `status`=1 ";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    array_push($result, array("id" => $row['idtbl_product_free'], "qtycount" => $row['qtycount'], "freecount" => $row['freecount'], "issueproductid" => $row['issueproductid'], "freeproductid" => $row['freeproductid']));
}

print(json_encode($result));
mysqli_close($con);

?>
