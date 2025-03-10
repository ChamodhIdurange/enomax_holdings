<?php 
include "include/header.php";  

$sql="SELECT `idtbl_employee`, `name`, `epfno`, `nic`, `phone`, `tbl_user_type_idtbl_user_type`, `status` FROM `tbl_employee` WHERE `status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlusertypeuser="SELECT `idtbl_user_type`, `type` FROM `tbl_user_type` WHERE `status`=1 AND `idtbl_user_type` NOT IN (1,2)";
$resultusertypeuser=$conn->query($sqlusertypeuser);

$sqlsalesmanager="SELECT `idtbl_sales_manager`, `salesmanagername` FROM `tbl_sales_manager` WHERE `status`=1";
$resultsalesmanager=$conn->query($sqlsalesmanager);

$sqluseraccount="SELECT `idtbl_user`, `name` FROM `tbl_user` WHERE `status`=1";
$resultuseraccount=$conn->query($sqluseraccount);

$sqlarea="SELECT * FROM `tbl_area` WHERE `status`=1 ";
$resultarea=$conn->query($sqlarea);

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
                            <div class="page-header-icon"><i data-feather="settings"></i></div>
                            <span>Employee</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="process/employeeprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Name*</label>
                                        <input type="text" class="form-control form-control-sm" id="empname"
                                            name="empname" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">EPF No</label>
                                        <input type="text" class="form-control form-control-sm" id="empepf"
                                            name="empepf" placeholder="">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">NIC*</label>
                                        <input type="text" class="form-control form-control-sm" id="empnic"
                                            name="empnic" placeholder="" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Mobile*</label>
                                        <input type="text" class="form-control form-control-sm" id="empmobile"
                                            name="empmobile" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Address</label>
                                        <textarea class="form-control form-control-sm" id="empaddress"
                                            name="empaddress"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-dark">Sales Manager*</label>
                                        <select class="form-control form-control-sm" name="salesmanager" id="salesmanager">
                                            <option value="">Select</option>
                                            <?php if($resultsalesmanager->num_rows > 0) {while ($rowsalesmanager = $resultsalesmanager-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowsalesmanager['idtbl_sales_manager'] ?>">
                                                <?php echo $rowsalesmanager['salesmanagername'] ?></option>
                                            <?php }} ?>
                                            
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-dark">User Account*</label>
                                        <select class="form-control form-control-sm" name="useraccount" id="useraccount">
                                            <option value="">Select User Account</option>
                                            <?php if($resultuseraccount->num_rows > 0) {while ($rowuseraccount = $resultuseraccount-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowuseraccount['idtbl_user'] ?>">
                                                <?php echo $rowuseraccount['name'] ?></option>
                                            <?php }} ?>
                                            
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-dark">Emplyee Type*</label>
                                        <select class="form-control form-control-sm" name="emptype" id="emptype"
                                            required>
                                            <option value="">Select</option>
                                            <?php if($resultusertypeuser->num_rows > 0) {while ($rowusertypeuser = $resultusertypeuser-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowusertypeuser['idtbl_user_type'] ?>">
                                                <?php echo $rowusertypeuser['type'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Area*</label>
                                        <select type="text" class="form-control form-control-sm" name="area[]" id="area"
                                            multiple>
                                            <option value="">Select</option>
                                            <?php if($resultarea->num_rows > 0) {while ($rowarea = $resultarea-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowarea['idtbl_area'] ?>">
                                                <?php echo $rowarea['area'] ?></option>

                                            <?php }} ?>
                                        </select>
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
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>EPF</th>
                                            <th>NIC</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($result->num_rows > 0) {while ($row = $result-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['idtbl_employee'] ?></td>
                                            <td><?php echo $row['name'] ?></td>
                                            <td><?php $typeID=$row['tbl_user_type_idtbl_user_type']; $sqltype="SELECT `type` FROM `tbl_user_type` WHERE `idtbl_user_type`='$typeID'"; $resulttype =$conn-> query($sqltype); $rowtype = $resulttype-> fetch_assoc(); echo $rowtype['type']; ?>
                                            </td>
                                            <td><?php echo $row['epfno'] ?></td>
                                            <td><?php echo $row['nic'] ?></td>
                                            <td class="text-right">

                                                <?php if($row['tbl_user_type_idtbl_user_type'] == 8 || $row['tbl_user_type_idtbl_user_type'] == 9){?>
                                                <a href="employeeprofile.php?record=<?php echo $row['idtbl_employee'] ?>&type=3"><button class="btn btn-outline-primary btn-sm btnProfile "
                                                        id="<?php echo $row['idtbl_employee'] ?>"><i
                                                            data-feather="eye"></i></button></a>
                                                <button class="btn btn-outline-secondary btn-sm btnView "
                                                    id="<?php echo $row['idtbl_employee'] ?>"><i
                                                        data-feather="map-pin"></i></button>

                                                <?php }?>
                                                <?php if($editcheck==1){ ?>
                                                    <button
                                                        class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>"
                                                        id="<?php echo $row['idtbl_employee'] ?>"><i
                                                            data-feather="edit-2"></i></button>
                                                    <?php } if($statuscheck==1 && $row['status']==1){ ?>
                                                    <button
                                                        data-url="process/statusemployee.php?record=<?php echo $row['idtbl_employee'] ?>&type=2"
                                                        data-actiontype="2"
                                                        class="btn btn-outline-success btn-sm btntableaction"><i
                                                            data-feather="check"></i></button>
                                                    <?php } else if($statuscheck==1 && $row['status']==2){ ?>
                                                    <button
                                                        data-url="process/statusemployee.php?record=<?php echo $row['idtbl_employee'] ?>&type=1"
                                                        data-actiontype="1"
                                                        class="btn btn-outline-warning btn-sm btntableaction"><i
                                                            data-feather="x-square"></i></button>
                                                    <?php } if($deletecheck==1){ ?>
                                                    <button
                                                        data-url="process/statusemployee.php?record=<?php echo $row['idtbl_employee'] ?>&type=3"
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

    <!-- Modal area details -->
    <div class="modal fade" id="modalareadetails" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="viewmodaltitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div id="viewdetail"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function () {
        $('#area').prop("disabled", true);;
        $('#area').select2();
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
                    url: 'getprocess/getemployee.php',
                    success: function (result) { //alert(result);
                        $("#area option:selected").removeAttr("selected");
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#empname').val(obj.name);
                        $('#empepf').val(obj.epfno);
                        $('#empnic').val(obj.nic);
                        $('#empmobile').val(obj.phone);
                        $('#empaddress').val(obj.address);
                        $('#emptype').val(obj.emptype);
                        $('#salesmanager').val(obj.salesmanager);
                        $('#useraccount').val(obj.useraccountid);

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                        if (obj.emptype == 7 || obj.emptype == 8 || obj.emptype == 9 || obj.emptype == 10 || obj.emptype == 11 || obj.emptype == 12) {
                            $('#area').prop("disabled", false);
                            $('#area').prop('required', true);
                            if (obj.array.length > 0) {

                                for (var i in obj.array) {
                                    var optionVal = obj.array[i].areaid;
                                    $('#area option[value=' + optionVal + ']').attr(
                                        'selected',
                                        true);
                                }
                            } else {
                                $("#area option:selected").removeAttr("selected");
                            }


                        } else {
                            $('#area').prop("disabled", true);
                            $('#area').prop('required', true);
                            $("#area option:selected").removeAttr("selected");
                        }
                        $('#area').trigger('change');
                    }
                });
            }
        });

        $('#dataTable tbody').on('click', '.btnView', function () {
            var id = $(this).attr('id');
            // alert("asd")
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: 'getprocess/getemployeeareadetails.php',
                success: function (result) {
                    // alert(result)
                    $('#viewmodaltitle').html('Area details')
                    $('#viewdetail').html(result);
                    $('#modalareadetails').modal('show');
                }
            });
        });
    });

    $('#emptype').change(function () {
        var typeID = $(this).val();

        if (typeID == 8 || typeID == 9) {
            $('#area').prop("disabled", false);;
            $('#area').prop('required', true);
        } else {
            $('#area').prop("disabled", true);
            $('#area').prop('required', false);
        }


    })
</script>
<?php include "include/footer.php"; ?>