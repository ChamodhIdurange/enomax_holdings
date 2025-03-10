<?php 
include "include/header.php";  

// $sql="SELECT * FROM `tbl_user` WHERE `status` IN (1,2) AND `idtbl_user`!=1";
// $result =$conn-> query($sql); 



$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 

$sqllocation="SELECT `idtbl_locations`, `locationname` FROM `tbl_locations` WHERE `status`= ('1')";
$resultlocation =$conn-> query($sqllocation); 

$sqllocation2="SELECT `idtbl_locations`, `locationname` FROM `tbl_locations` WHERE `status`= ('1')";
$resultlocation2 =$conn-> query($sqllocation2); 

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
                            <div class="page-header-icon"><i data-feather="map-pin"></i></div>
                            <span>Location Reorder</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-4">
                                <form action="process/productreorderprocess.php" method="post" autocomplete="off">


                                    <label class="small font-weight-bold text-dark">Locations*</label>
                                    <select name="location" id="location" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php if($resultlocation->num_rows > 0) {while ($rowarea = $resultlocation-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowarea['idtbl_locations'] ?>">
                                            <?php echo $rowarea['locationname'] ?></option>
                                        <?php }} ?>
                                    </select>

                                    <label class="small font-weight-bold text-dark">Product*</label>
                                    <select name="product" id="product" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php if($resultproduct->num_rows > 0) {while ($rowarea = $resultproduct-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowarea['idtbl_product'] ?>">
                                            <?php echo $rowarea['product_name'] ?></option>
                                        <?php }} ?>
                                    
                                    </select>

                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Minimum Quantity*</label>
                                        <input type="text" class="form-control form-control-sm" name="minimumqty"
                                            id="minimumqty" required>
                                    </div>

                                    <div class="form-group mt-2">
                                        <button type="submit" id="submitBtn"
                                            class="btn btn-outline-primary btn-sm w-50 fa-pull-right"><i
                                                class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">

                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-8">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Location name</th>
                                                <th class="text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($resultlocation2->num_rows > 0) {while ($row = $resultlocation2-> fetch_assoc()) {?>
                                            <tr>
                                                <td><?php echo $row['idtbl_locations'] ?></td>
                                                <td><?php echo $row['locationname'] ?></td>
                                                <td class="text-right">
                                                    <button class="btn btn-outline-primary btn-sm btnShow"
                                                        name="<?php echo $row['locationname'] ?>"
                                                        id="<?php echo $row['idtbl_locations'] ?>"><i
                                                            data-feather="eye"></i></button>

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
            </div>
        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<!-- View model -->
<div class="modal fade" id="modelview" tabindex="-1" aria-labelledby="modelview" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modaltitle" class="modal-title" id="modelcustomized">Location product details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalviewbody" class="modal-body">

            </div>

        </div>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>
<script>
    $('#dataTable').DataTable();
    $(document).ready(function () {
        var addcheck
        var editcheck
        var statuscheck
        var deletecheck

        $('#dataTable tbody').on('click', '.btnShow', function () {
            var id = $(this).attr('id');
            var name = $(this).attr('name');
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: 'getprocess/getlocationproductdetails.php',
                success: function (result) { //alert(result);
                    $('#modaltitle').html(name + " details");
                    $('#modalviewbody').html(result);
                    $('#modelview').modal('show');
                }
            });
        });

    });
    $('#location').change(function () {
        // alert("asd")

        var locationID = $('#location option:selected').val();
        var value = '';
        var value1 = '';

        loadproducts(locationID, value);
    })

    function loadproducts(locationID, value) {
        $.ajax({
            type: "POST",
            data: {
                locationID: locationID
            },
            url: 'getprocess/getreorderproductlist.php',
            success: function (result) {  //alert(result);
                var objfirst = JSON.parse(result);
                var html1 = '';
                html1 += '<option value="">Select</option>';
                $.each(objfirst, function (i, item) {
                    // alert(objfirst[i].id);
                    html1 += '<option value="' + objfirst[i].productid + '">';
                    html1 += objfirst[i].productname;
                    html1 += '</option>';
                });

                $('#product').empty().append(html1);

                if (value != '') {
                    $('#product').val(value);
                }
            }
        });
    }



    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }
</script>
<?php include "include/footer.php"; ?>