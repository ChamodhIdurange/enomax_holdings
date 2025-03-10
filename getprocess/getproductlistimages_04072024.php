<?php
session_start();
require_once('../connection/db.php');
$sessionusertype = $_SESSION['type'];
$productID = $_POST['productID'];

$sqlcatagary = "SELECT `tbl_catalog_category`.`category` 
FROM `tbl_catalog` 
LEFT JOIN `tbl_catalog_category` ON `tbl_catalog_category`.`idtbl_catalog_category` = `tbl_catalog`.`tbl_catalog_category_idtbl_catalog_category`
WHERE `tbl_catalog`.`idtbl_catalog`='$productID' 
AND `tbl_catalog`.`status`=1;
";
$result = $conn->query($sqlcatagary);
$row = $result->fetch_assoc();
$category = $row['category'];

$count = 0;

$sqldetails = "SELECT `tbl_catalog_details`.`idtbl_catalog_details`, `tbl_catalog_details`.`status`, `tbl_product`.`product_name`, `tbl_product`.`idtbl_product`, `tbl_product`.`retail`,  `tbl_product`.`product_code`, SUM(`tbl_stock`.`qty`) AS `qty`, `tbl_product`.`tbl_sizes_idtbl_sizes`, `tbl_product`.`tbl_size_categories_idtbl_size_categories`, `tbl_sizes`.`name` AS `sizename`, `tbl_size_categories`.`name` AS `catname` FROM `tbl_catalog_details` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_catalog_details`.`product_name` LEFT JOIN `tbl_stock` ON `tbl_stock`.`tbl_product_idtbl_product` = `tbl_product`.`idtbl_product` LEFT JOIN `tbl_sizes` ON `tbl_sizes`.`idtbl_sizes` = `tbl_product`.`tbl_sizes_idtbl_sizes` LEFT JOIN `tbl_size_categories` ON `tbl_size_categories`.`idtbl_size_categories` = `tbl_product`.`tbl_size_categories_idtbl_size_categories` WHERE `tbl_catalog_idtbl_catalog`='$productID' AND `tbl_catalog_details`.`status`IN (1, 2) GROUP BY `tbl_product`.`idtbl_product`";
$resultdetails = $conn->query($sqldetails);


$sql = "SELECT `idtbl_product_image`, `imagepath` FROM `tbl_product_image` WHERE `tbl_catalog_idtbl_catalog`='$productID' AND `status`=1";
$result = $conn->query($sql);
?>
<script>
    var productID = "<?php echo $productID; ?>";
</script>
<style>
    .btndlt,
    .btndlt:hover {
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 250px;
        /* Add any other styling required for your buttons */
    }
</style>
<div class="col-12">
    <div class="page-header-content py-3">
        <h1 class="page-header-title d-flex">
            <span><?php echo $category ?></span>
        </h1>
    </div>
</div>
<div class="col-12">
    <table class="table table-striped table-sm mb-3"> <!-- Added mb-3 for spacing -->
        <thead>
            <tr>
                <th scope="col">#</th>
                <!-- <th scope="col">Catalog ID</th> -->
                <th scope="col">Item Code</th>
                <th scope="col">Item Name</th>
                <th scope="col">Price</th>
                <th scope="col" class="text-center">Available Qty</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($sessionusertype == 1 || $sessionusertype == 2) {
                while ($row1 = $resultdetails->fetch_assoc()) {
                    $count++
            ?>
                    <tr>
                        <th scope="row"><?php echo $count ?></th>
                        <!-- <td>CTL/0<?php echo $row1['idtbl_catalog_details'] ?></td> -->
                        <td><?php echo $row1['product_code'] ?></td>
                        <td><?php echo $row1['product_name'] ?></td>
                        <td><?php echo number_format($row1['retail'], 2) ?></td>
                        <td class="text-center"><?php echo $row1['qty'] ?></td>
                        <td>
                            <?php if ($row1['status'] == 1) { ?>
                                <button id="<?php echo $row1['idtbl_catalog_details'] ?>" onclick="return confirm('Are you sure you want to deactive this?');" class="btn btn-outline-success btn-sm btnstatus2"><i class="fa fa-check" aria-hidden="true"></i></button>
                            <?php } else { ?>
                                <button id="<?php echo $row1['idtbl_catalog_details'] ?>" onclick="return confirm('Are you sure you want to active this?');" class="btn btn-outline-warning btn-sm btnstatus1"><i class="fa fa-times" aria-hidden="true"></i></a>
                                <?php } ?>
                                <!-- <a href="process/statuscatalog.php?record=<?php echo $row1['idtbl_catalog'] ?>&type=3" onclick="return confirm('Are you sure you want to remove this?');" target="_self" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a> -->
                                <!-- <button class="btn btn-outline-primary btn-sm btnEdit" id="test"><i class="fas fa-save"></i></button> . '(' . $row1['sizename'] . $row1['catname'] . ')' -->
                        </td>
                    </tr>
                    <?php }
            } else {

                while ($row1 = $resultdetails->fetch_assoc()) {
                    if ($row1['status'] == 1) {
                        $count++
                    ?>
                        <tr>
                            <th scope="row"><?php echo $count ?></th>
                            <!-- <td>CTL/0<?php echo $row1['idtbl_catalog_details'] ?></td> -->
                            <td><?php echo $row1['product_code'] ?></td>
                            <td><?php echo $row1['product_name'] ?></td>
                            <td><?php echo number_format($row1['unitprice'], 2) ?></td>
                            <td class="text-center"><?php echo $row1['qty'] ?></td>
                            <td>
                                <?php if ($row1['status'] == 1) { ?>
                                    <a class="btn btn-outline-success btn-sm"><i class="fa fa-check" aria-hidden="true"></i></a>
                                <?php } else { ?>
                                    <a class="btn btn-outline-warning btn-sm"><i class="fa fa-times" aria-hidden="true"></i></a>
                                <?php } ?>
                                <!-- <a href="process/statuscatalog.php?record=<?php echo $row1['idtbl_catalog'] ?>&type=3" onclick="return confirm('Are you sure you want to remove this?');" target="_self" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i></a> -->
                                <!-- <button class="btn btn-outline-primary btn-sm btnEdit" id="test"><i class="fas fa-save"></i></button> . '(' . $row1['sizename'] . $row1['catname'] . ')' -->
                            </td>
                        </tr>
            <?php }
                }
            } ?>



        </tbody>
    </table>

</div>
<div class="col-12">
    <hr class="border-dark">
    <div class="page-header-content py-3">
        <h3 class="page-header-title d-flex">
            <span>Product Images</span>
        </h3>
    </div>
</div>
<div class="col-12 d-flex flex-wrap gap-3">
    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="card mb-3" style="width: 18rem; margin-right: 10px; margin-top:10px">
            <img src="<?php echo $row['imagepath'] ?>" class="card-img-top" width="200" height="200">

            <div class="card-body d-flex flex-column">

                <div class="mt-auto">
                    <?php
                    if ($sessionusertype == 1 || $sessionusertype == 2) { ?>
                        <button class="btn btn-outline-danger btn-sm d-flex justify-content-center btndlt" onclick="return confirm('Are you sure you want to remove this?');" id="<?php echo $row['idtbl_product_image'] ?>">
                            <i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Delete
                        </button>
                    <?php } ?>
                </div>
            </div>

        </div>

    <?php } ?>
</div>
<!-- <div class="col-12">
    <form method="post" id="image" autocomplete="off" enctype="multipart/form-data">
        <label for="productimage" class="custom-file-upload btn btn-outline-primary btn-sm">
            Browse
        </label>
        <input type="file" name="productimage[]" id="productimage" multiple>
    </form>
</div> -->
<script>
    $(document).ready(function() {
        $('.btndlt').click(function() {
            var id = $(this).attr('id');

            $.ajax({
                type: "POST",
                data: {
                    id: id
                },
                url: 'process/imageuploadcatalog.php',
                success: function(result) {
                    loadlistimages(); 
                }
            });
        });

        $('.btnstatus2').click(function() {
            var id = $(this).attr('id');
          //  alert(id);
            var value = 2;
          //  alert(value);
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                    value:value
                },
                url: 'process/statuscatalogdetails.php',
                success: function(result) {
                    loadlistimages(); // Call without parameters as it uses the global productID
                }
            });
        });

        $('.btnstatus1').click(function() {
            var id = $(this).attr('id');
           // alert(id);
            var value = 1;
           // alert(value);
            $.ajax({
                type: "POST",
                data: {
                    id: id,
                    value:value
                },
                url: 'process/statuscatalogdetails.php',
                success: function(result) {
                    loadlistimages(); // Call without parameters as it uses the global productID
                }
            });
        });
    });

    function loadlistimages() {
        $('#imagelist').addClass('text-center');
        $('#imagelist').html('<img src="images/spinner.gif" class="img-fluid">');

        $.ajax({
            type: "POST",
            data: {
                productID: productID,
            },
            url: 'getprocess/getproductlistimages.php',
            success: function(result) {
                $('#imagelist').removeClass('text-center');
                $('#imagelist').html(result);
            }
        });
    }
</script>