<?php
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT `a`.`area`, `d`.`name` FROM `tbl_area` as `a` join `tbl_employee_area` as `ea` ON (`ea`.`tbl_area_idtbl_area` = `a`.`idtbl_area`) JOIN `tbl_employee` as `e` ON (`e`.`idtbl_employee` = `ea`.`tbl_employee_idtbl_employee`) JOIN `tbl_district` as `d` ON (`d`.`idtbl_district` = `a`.`tbl_district_idtbl_district`) WHERE `ea`.`tbl_employee_idtbl_employee` = '$record'";
$result=$conn->query($sql);


?>

<div class="row">
    <table  class="table table-striped table-bordered table-sm">
        <thead>
            <tr>
                <th>District</th>
                <th>Area</th>

            </tr>
        </thead>
        <tbody>
            <?php while($row=$result->fetch_assoc()){ ?>
            <tr>
                <td><?php echo $row['name'] ?></td>
                <td><?php echo $row['area'] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>

</script>