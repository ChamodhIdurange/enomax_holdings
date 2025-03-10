<?php 
include "include/header.php";  

$sql="SELECT `u`.*, `ua`.`name` FROM `tbl_catalog_category` AS `u` LEFT JOIN `tbl_size_categories` AS `ua` ON (`ua`.`idtbl_size_categories` = `u`.`tbl_size_categories_idtbl_size_categories`) WHERE `u`.`status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlsizescategories="SELECT `idtbl_size_categories`, `name` FROM `tbl_size_categories` WHERE `status`=1";
$resultsizecategories=$conn->query($sqlsizescategories);

include "include/topnavbar.php"; 
?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i class="fa fa-puzzle-piece" aria-hidden="true"></i></div>
                            <span>Catalog Category</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="process/catalogcategoryprocess.php" method="post" autocomplete="off">
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-dark">Category*</label>
                                        <input type="text" class="form-control form-control-sm" name="category"
                                            id="category" required>
                                    </div>
                                    <div class="form-group">
                                            <label class="small font-weight-bold text-dark">Size Category*</label>
                                            <select class="form-control form-control-sm" name="sizecategory"
                                                id="sizecategory" required>
                                                <option value="">Select</option>
                                                <?php if($resultsizecategories->num_rows > 0) {while ($rowsizes = $resultsizecategories-> fetch_assoc()) { ?>
                                                <option value="<?php echo $rowsizes['idtbl_size_categories'] ?>">
                                                    <?php echo $rowsizes['name'] ?></option>
                                                <?php }} ?>
                                            </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-dark">Sequence*</label>
                                        <input type="number" class="form-control form-control-sm" name="sequence"
                                            id="sequence" required>
                                    </div>
                                    <div class="form-group mt-2">
                                        <button type="submit" id="submitBtn"
                                            class="btn btn-outline-primary btn-sm w-50 fa-pull-right"
                                            <?php if($addcheck==0){echo 'disabled';} ?>><i
                                                class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-9">
                                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Category</th>
                                            <th>Size Category</th>
                                            <th>Sequence</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($result->num_rows > 0) {while ($row = $result-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['idtbl_catalog_category'] ?></td>
                                            <td><?php echo $row['category'] ?></td>
                                            <td><?php echo $row['name'] ?></td>
                                            <td><?php echo $row['sequence'] ?></td>
                                            <td class="text-right">
                                            <?php if($editcheck==1){ ?>
                                                <button
                                                    class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>"
                                                    id="<?php echo $row['idtbl_catalog_category'] ?>"><i
                                                        data-feather="edit-2"></i></button>
                                                <?php } if($statuscheck==1 && $row['status']==1){ ?>
                                                <button
                                                    data-url="process/statuscatalogcategory.php?record=<?php echo $row['idtbl_catalog_category'] ?>&type=2"
                                                    data-actiontype="2"
                                                    class="btn btn-outline-success btn-sm btntableaction"><i
                                                        data-feather="check"></i></button>
                                                <?php } else if($statuscheck==1 && $row['status']==2){ ?>
                                                <button
                                                    data-url="process/statuscatalogcategory.php?record=<?php echo $row['idtbl_catalog_category'] ?>&type=1"
                                                    data-actiontype="1"
                                                    class="btn btn-outline-warning btn-sm btntableaction"><i
                                                        data-feather="x-square"></i></button>
                                                <?php } if($deletecheck==1){ ?>
                                                <button
                                                    data-url="process/statuscatalogcategory.php?record=<?php echo $row['idtbl_catalog_category'] ?>&type=3"
                                                    data-actiontype="3"
                                                    class="btn btn-outline-danger btn-sm btntableaction"><i
                                                        data-feather="trash-2"></i></button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php }} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function () {
        $('#dataTable').DataTable();
        $('#dataTable tbody').on('click', '.btnEdit', async function () {
            var r = await Otherconfirmation("You want to edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getcatalogcategory.php',
                    success: function (result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#category').val(obj.category);
                        $('#sizecategory').val(obj.sizecategory);
                        $('#sequence').val(obj.sequence);

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
    });
</script>
<?php include "include/footer.php"; ?>
