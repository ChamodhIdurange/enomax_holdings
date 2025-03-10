<?php 
require_once('../connection/db.php');

$orderID=$_POST['orderID'];

$sql="SELECT `tbl_porder_has_tbl_expences_type`.`expensesvalueid`, `tbl_porder_has_tbl_expences_type`.`expencevalue`, `tbl_expences_type`.`expencestype` FROM `tbl_porder_has_tbl_expences_type` LEFT JOIN `tbl_expences_type` ON `tbl_expences_type`.`idtbl_expences_type`=`tbl_porder_has_tbl_expences_type`.`tbl_expences_type_idtbl_expences_type` WHERE `tbl_porder_has_tbl_expences_type`.`tbl_porder_idtbl_porder`='$orderID' AND `tbl_porder_has_tbl_expences_type`.`status`='1'";
$result=$conn->query($sql);
?>
<table class="table table-striped table-bordered table-sm small" id="expenlisttable">
    <thead>
        <tr>
            <th>#</th>
            <th>Expenses Type</th>
            <th>Expenses</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php $i=1; while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['expensesvalueid'] ?></td>
            <td><?php echo $row['expencestype'] ?></td>
            <td><?php echo $row['expencevalue'] ?></td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm btnexpendelete" id="<?php echo $row['expensesvalueid'] ?>"><i class="far fa-trash-alt"></i></button>
            </td>
        </tr>
        <?php $i++;} ?>
    </tbody>
</table>