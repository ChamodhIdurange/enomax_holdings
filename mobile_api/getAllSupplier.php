<?php
require_once('dbConnect.php');

$sql="SELECT `idtbl_supplier`,`suppliername`,`contactone`,`supcode`,`email` FROM `tbl_supplier` WHERE `status`=1";
$result = mysqli_query($con, $sql);

$lorryArray = array();
while ($row = mysqli_fetch_array($result)) {
    array_push($lorryArray, array("id" => $row['idtbl_supplier'], "name" => $row['suppliername'],"contactone" => $row['contactone'], "supcode" => $row['supcode'],"email" => $row['email']));
}
echo json_encode($lorryArray);

?>