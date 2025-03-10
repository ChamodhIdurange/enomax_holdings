<?php 
include "include/header.php";  

$sql="SELECT `idtbl_employee`, `name`, `epfno`, `nic`, `phone`, `tbl_user_type_idtbl_user_type`, `status` FROM `tbl_employee` WHERE `status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlusertypeuser="SELECT `idtbl_user_type`, `type` FROM `tbl_user_type` WHERE `status`=1 AND `idtbl_user_type` NOT IN (1,2)";
$resultusertypeuser=$conn->query($sqlusertypeuser);

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
                                        <input type="text" class="form-control form-control-sm" id="empname" name="empname" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">EPF No</label>
                                        <input type="text" class="form-control form-control-sm" id="empepf" name="empepf" placeholder="">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">NIC*</label>
                                        <input type="text" class="form-control form-control-sm" id="empnic" name="empnic" placeholder="" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Mobile*</label>
                                        <input type="text" class="form-control form-control-sm" id="empmobile" name="empmobile" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Address</label>
                                        <textarea class="form-control form-control-sm" id="empaddress" name="empaddress"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-dark">Emplyee Type*</label>
                                        <select class="form-control form-control-sm" name="emptype" id="emptype" required>
                                            <option value="">Select</option>
                                            <?php if($resultusertypeuser->num_rows > 0) {while ($rowusertypeuser = $resultusertypeuser-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowusertypeuser['idtbl_user_type'] ?>"><?php echo $rowusertypeuser['type'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group mt-2">
                                        <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm w-50 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
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
                                            <td><?php $typeID=$row['tbl_user_type_idtbl_user_type']; $sqltype="SELECT `type` FROM `tbl_user_type` WHERE `idtbl_user_type`='$typeID'"; $resulttype =$conn-> query($sqltype); $rowtype = $resulttype-> fetch_assoc(); echo $rowtype['type']; ?></td>
                                            <td><?php echo $row['epfno'] ?></td>
                                            <td><?php echo $row['nic'] ?></td>
                                            <td class="text-right">
                                                <button class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>" id="<?php echo $row['idtbl_employee'] ?>"><i data-feather="edit-2"></i></button>
                                                <?php if($row['status']==1){ ?>
                                                <a href="process/statusemployee.php?record=<?php echo $row['idtbl_employee'] ?>&type=2" onclick="return confirm('Are you sure you want to deactive this?');" target="_self" class="btn btn-outline-success btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="check"></i></a>
                                                <?php }else{ ?>
                                                <a href="process/statusemployee.php?record=<?php echo $row['idtbl_employee'] ?>&type=1" onclick="return confirm('Are you sure you want to active this?');" target="_self" class="btn btn-outline-warning btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="x-square"></i></a>
                                                <?php } ?>
                                                <a href="process/statusemployee.php?record=<?php echo $row['idtbl_employee'] ?>&type=3" onclick="return confirm('Are you sure you want to remove this?');" target="_self" class="btn btn-outline-danger btn-sm <?php if($deletecheck==0){echo 'd-none';} ?>"><i data-feather="trash-2"></i></a>
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
    $(document).ready(function() {
        $('#dataTable').DataTable();
        $('#dataTable tbody').on('click', '.btnEdit', function() {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getemployee.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#empname').val(obj.name);
                        $('#empepf').val(obj.epfno);
                        $('#empnic').val(obj.nic);
                        $('#empmobile').val(obj.phone);
                        $('#empaddress').val(obj.address);                
                        $('#emptype').val(obj.emptype);                

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
    });

</script>
<?php include "include/footer.php"; ?>
