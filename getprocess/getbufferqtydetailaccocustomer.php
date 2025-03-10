<?php 
require_once('../connection/db.php');

$customerID=$_POST['customerID'];
$addcheck=$_POST['addcheck'];
$invoicedate=$_POST['invoicedate'];

$sqlreject="SELECT `idtbl_reject_reason`, `reason` FROM `tbl_reject_reason` WHERE `status`=1";
$resultreject =$conn-> query($sqlreject);

$sqlalready="SELECT `idtbl_customer_ava_qty` FROM `tbl_customer_ava_qty` WHERE `status`=1 AND `date`='$invoicedate' AND `tbl_customer_idtbl_customer`='$customerID'";
$resultalready =$conn-> query($sqlalready);
$rowalready=$resultalready->fetch_assoc();

$sql="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1 AND `tbl_product_category_idtbl_product_category`=1";
$result=$conn->query($sql);
while($row=$result->fetch_assoc()){
    $productavaID=$row['idtbl_product'];
    $sqlproductqtycus="SELECT `fullqty` FROM `tbl_customer_stock` WHERE `status`=1 AND `tbl_customer_idtbl_customer`='$customerID' AND `tbl_product_idtbl_product`='$productavaID'";
    $resultproductqtycus=$conn->query($sqlproductqtycus);
    $rowproductqtycus=$resultproductqtycus->fetch_assoc();

    $avaenterID=$rowalready['idtbl_customer_ava_qty'];

    $sqlalreadyproduct="SELECT `fullqty`, `emptyqty` FROM `tbl_customer_ava_qty_detail` WHERE `status`=1 AND `tbl_customer_ava_qty_idtbl_customer_ava_qty`='$avaenterID' AND `tbl_product_idtbl_product`='$productavaID'";
    $resultalreadyproduct=$conn->query($sqlalreadyproduct);
    $rowalreadyproduct=$resultalreadyproduct->fetch_assoc();
?>
<div class="form-row">  
    <div class="col-5">
        <label class="small font-weight-bold text-dark">Product</label>
        <input type="text" name="" id="" class="form-control form-control-sm form-control-plaintext" value="<?php echo $row['product_name'] ?>">
        <input type="hidden" name="cusavaproduct[]" value="<?php echo $productavaID ?>">
    </div>
    <div class="col">
        <label class="small font-weight-bold text-dark">Full</label>
        <input type="number" min="0" name="cusavafull[]" class="form-control form-control-sm" value="<?php if($resultalreadyproduct->num_rows>0){echo $rowalreadyproduct['fullqty'];}else{echo '0';} ?>">
    </div>
    <div class="col">
        <label class="small font-weight-bold text-dark">Empty</label>
        <input type="number" min="0" name="cusavaempty[]" class="form-control form-control-sm" value="<?php if($resultalreadyproduct->num_rows>0){echo $rowalreadyproduct['emptyqty'];}else{echo '0';} ?>">
    </div>
    <div class="col">
        <label class="small font-weight-bold text-dark">Buffer</label>
        <input type="text" name="cusavabuffer[]" class="form-control form-control-sm" value="<?php if($resultproductqtycus->num_rows>0){echo $rowproductqtycus['fullqty'];}else{echo '0';} ?>" readonly>
    </div>
</div>
<?php } ?>
<div class="form-group mb-1">
    <label class="small font-weight-bold text-dark">Reason for reject</label>
    <select class="form-control form-control-sm" name="reject" id="reject" required>
        <?php if($resultreject->num_rows > 0) {while ($rowreject = $resultreject-> fetch_assoc()) { ?>
        <option value="<?php echo $rowreject['idtbl_reject_reason'] ?>"><?php echo $rowreject['reason']; ?></option>
        <?php }} ?>
    </select>
</div>
<div class="form-group mt-3">
    <button type="button" id="submitavabtn" class="btn btn-outline-primary btn-sm px-5 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
</div>