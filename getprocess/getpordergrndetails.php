<?php
require_once('../connection/db.php');

$record=$_POST['porderID'];
$statusgrn=$_POST['grnIssueStatus'];

$sqlorder="SELECT `nettotal`, `remark`, `ismaterialpo` FROM `tbl_porder` WHERE `idtbl_porder`='$record'";
$resultorder=$conn->query($sqlorder);
$roworder=$resultorder->fetch_assoc();

if($roworder['ismaterialpo'] == 0){
    $sql="SELECT `pp`.`product_name`, `pd`.`qty`, `pd`.`idtbl_porder_detail` FROM  `tbl_porder` as `p` JOIN `tbl_porder_detail` as `pd` ON (`pd`.`tbl_porder_idtbl_porder` = `p`.`idtbl_porder`) JOIN `tbl_product` as `pp` ON (`pp`.`idtbl_product` = `pd`.`tbl_product_idtbl_product`)  WHERE `p`.`idtbl_porder` = '$record'";
    $result=$conn->query($sql);
    
    $sql1="SELECT `pp`.`product_name`, `gd`.`qty`, `gd`.`idtbl_grndetail`, `g`.`idtbl_grn` FROM `tbl_porder_grn` as `pg` join `tbl_porder` as `p` JOIN `tbl_grn` as `g` on (`g`.`idtbl_grn` = `pg`.`tbl_grn_idtbl_grn`) JOIN `tbl_grndetail` as `gd` on (`gd`.`tbl_grn_idtbl_grn` = `g`.`idtbl_grn`) JOIN `tbl_product` as `pp` ON (`pp`.`idtbl_product` = `gd`.`tbl_product_idtbl_product`) WHERE `pg`.`tbl_porder_idtbl_porder` = '$record' GROUP BY `gd`.`idtbl_grndetail`";
    $result1=$conn->query($sql1);
}else{
    $sql="SELECT `pp`.`materialname`, `pd`.`qty`, `pd`.`idtbl_porder_detail` FROM  `tbl_porder` as `p` JOIN `tbl_porder_detail` as `pd` ON (`pd`.`tbl_porder_idtbl_porder` = `p`.`idtbl_porder`) JOIN `tbl_material` as `pp` ON (`pp`.`idtbl_material` = `pd`.`tbl_material_idtbl_material`)  WHERE `p`.`idtbl_porder` = '$record'";
    $result=$conn->query($sql);
    
    $sql1="SELECT `pp`.`product_name`, `gd`.`qty`, `gd`.`idtbl_grndetail`, `g`.`idtbl_grn` FROM `tbl_porder_grn` as `pg` join `tbl_porder` as `p` JOIN `tbl_grn` as `g` on (`g`.`idtbl_grn` = `pg`.`tbl_grn_idtbl_grn`) JOIN `tbl_grndetail` as `gd` on (`gd`.`tbl_grn_idtbl_grn` = `g`.`idtbl_grn`) JOIN `tbl_product` as `pp` ON (`pp`.`idtbl_product` = `gd`.`tbl_product_idtbl_product`) WHERE `pg`.`tbl_porder_idtbl_porder` = '$record' GROUP BY `gd`.`idtbl_grndetail`";
    $result1=$conn->query($sql1);
}


// $sqlReturn = "SELECT `recieved_status`, `returntype` FROM `tbl_return` WHERE `idtbl_return` = '$record'";
// $resultReturn=$conn->query($sqlReturn);
// $rowReturn = $resultReturn-> fetch_assoc();
// $recievedStatus =  $rowReturn['recieved_status'];
// $type =  $rowReturn['returntype'];

?>


<div class="row">
    <h3>Porder Details</h3>
    <div class="col-md-12">
        <table id="podetailstable" class="table table-striped table-bordered table-sm">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th class="text-right"><?php echo $statusgrn. 'ss'; ?></th>

            </thead>
            <tbody>
                <?php if($roworder['ismaterialpo'] == 0){
                    while($row=$result->fetch_assoc()){ ?>
                <tr>
                    <td><?php echo $row['idtbl_porder_detail'] ?></td>
                    <td><?php echo $row['product_name'] ?></td>
                    <td class="text-right"><?php echo $row['qty'] ?></td>

                </tr>
                <?php }}else{ 
                     while($row=$result->fetch_assoc()){?>
                <tr>
                    <td><?php echo $row['idtbl_porder_detail'] ?></td>
                    <td><?php echo $row['materialname'] ?></td>
                    <td class="text-right"><?php echo $row['qty'] ?></td>

                </tr>
                <?php }} ?>
            </tbody>
        </table>

        <br>

    </div>
    </form>
</div>

<div class="row">
    <h3>GRN Details</h3>
    <table id="grndetailstable" class="table table-striped ">
        <thead>
            <tr>
                <th>#</th>
                <th>GRN ID</th>
                <th>Product</th>
                <th class="text-right">GRN Quantity</th>
                <!-- <th class="text-center">Actions</th> -->
        </thead>
        <tbody>
            <?php if($roworder['ismaterialpo'] == 0){
                while($row=$result1->fetch_assoc()){ ?>
            <tr>
                <td><?php echo $row['idtbl_grndetail'] ?></td>
                <td><?php echo $row['idtbl_grn'] ?></td>
                <td><?php echo $row['product_name'] ?></td>
                <td class="text-center"><?php echo $row['qty'] ?></td>
                <!-- <td><button class="btn btn-outline-primary btn-sm btnView"
                        id="<?php echo $row['idtbl_grndetail'] ?>"><i class = "fa fa-eye"></i></button></td> -->
            </tr>
            <?php }}else{
                while($row=$result1->fetch_assoc()){  ?>
            <tr>
                <td><?php echo $row['idtbl_grndetail'] ?></td>
                <td><?php echo $row['idtbl_grn'] ?></td>
                <td><?php echo $row['materialname'] ?></td>
                <td class="text-center"><?php echo $row['qty'] ?></td>
                <!-- <td><button class="btn btn-outline-primary btn-sm btnView"
                        id="<?php echo $row['idtbl_grndetail'] ?>"><i class = "fa fa-eye"></i></button></td> -->
            </tr>
            <?php }} ?>
        </tbody>
    </table>
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <?php if($statusgrn == 0){?>
        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right px-5" id="btnSaveGrn"><i
                class="far fa-save"></i>&nbsp;Complete GRN</button>
        <?php } ?>
    </div>
    <input type="hidden" id="hiddenpoid" value="<?php echo $record ?>">
</div>

<script>
    $(document).ready(function () {
        // $('#grndetailstable').DataTable();
    });

    $('#btnSaveGrn').click(function () {
        var poNum = $('#hiddenpoid').val();
        $.ajax({
            type: "POST",
            data: {
                poid: poNum,
            },
            url: 'process/statusgrnissue.php',
            success: function (result) { //alert(result);
                // alert("asd");
                location.reload();
            }
        });

    });
</script>