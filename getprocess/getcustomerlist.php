<?php
require_once('../connection/db.php');

$searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : null;

if (!isset($searchTerm)) {
    $sql = "SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1 ORDER BY `name` ASC LIMIT 5";
    $result=$conn->query($sql);
} else {
    if (!empty($searchTerm)) {
        $sql = "SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1 AND `name` LIKE '%$searchTerm%' ORDER BY `name` ASC";
        $result=$conn->query($sql);
    } else {
        $sql = "SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1 ORDER BY `name` ASC LIMIT 5";
        $result=$conn->query($sql);
    }
}

while($row=$result->fetch_assoc()){
    $data[]=array("id"=>$row['idtbl_customer'], "text"=>$row['name']);
}
echo json_encode($data);
?>
