<?php
require_once('dbConnect.php');

$refId=$_POST['refId'];
$currentYear = date('Y');
$sql = "SELECT month(date) as month,sum(total) as total from tbl_invoice WHERE year(date)='$currentYear' AND ref_id='$refId' group by month(date) order by month(date)";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    
    array_push($result, array("month" => $row['month'], "value" => $row['total']));
}
print(json_encode($result));
mysqli_close($con);

?>