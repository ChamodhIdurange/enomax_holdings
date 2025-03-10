<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$recoverID=$_POST['recoverID'];

$sqlinovicelist="SELECT `tbl_invoice_idtbl_invoice` FROM `tbl_invoice_diff_has_tbl_invoice` WHERE `tbl_invoice_diff_idtbl_invoice_diff` IN (SELECT `idtbl_invoice_diff` FROM `tbl_invoice_diff` WHERE `status`=1 AND `idtbl_invoice_diff`='$recoverID')";
$resultinovicelist =$conn-> query($sqlinovicelist);

?>
<ul class="list-group">
    <?php while($rowinovicelist = $resultinovicelist-> fetch_assoc()){ ?>
    <li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2 small">
        INV-<?php echo $rowinovicelist['tbl_invoice_idtbl_invoice']; ?>
    </li>
    <?php } ?>
</ul>