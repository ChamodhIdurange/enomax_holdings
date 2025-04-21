<?php
require_once('../connection/db.php');

$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : null;

if (!isset($searchTerm)) {
    $sql = "SELECT `idtbl_customer`, `customer` FROM `tbl_customer` WHERE `status`=1 ORDER BY `customer` ASC LIMIT 5";
    $result=$conn->query($sql);
} else {
    if (!empty($searchTerm)) {
        $sql = "SELECT `idtbl_customer`, `customer` FROM `tbl_customer` WHERE `status`=1 AND `customer` LIKE '%$searchTerm%' ORDER BY `customer` ASC";
        $result=$conn->query($sql);
    } else {
        $sql = "SELECT `idtbl_customer`, `customer` FROM `tbl_customer` WHERE `status`=1 ORDER BY `customer` ASC LIMIT 5";
        $result=$conn->query($sql);
    }
}

while($row=$result->fetch_assoc()){
    $data[]=array("id"=>$row['idtbl_customer'], "text"=>$row['customer']);
}
echo json_encode($data);
?>
