<?php 
include "include/header.php";  

$sql="SELECT  `a`.`idtbl_area`, `d`.`name` as `district`, `a`.`status`, `a`.`area` FROM `tbl_area` as `a` join `tbl_district` as `d` ON (`a`.`tbl_district_idtbl_district` = `d`.`idtbl_district`)  WHERE `a`.`status` IN (1,2)";
$result =$conn-> query($sql); 

$sqldistrict="SELECT * FROM `tbl_district`";
$resultdistrict =$conn-> query($sqldistrict); 

$sqlasm="SELECT `u`.`idtbl_user`,`u`.`name`  FROM `tbl_user` as `u` JOIN `tbl_user_type` as `t` ON (`t`.`idtbl_user_type` = `u`.`tbl_user_type_idtbl_user_type`) WHERE `t`.`type` = 'ASM'";
$resultasm =$conn-> query($sqlasm); 

$sqlcsm="SELECT `u`.`idtbl_user`,`u`.`name`  FROM `tbl_user` as `u` JOIN `tbl_user_type` as `t` ON (`t`.`idtbl_user_type` = `u`.`tbl_user_type_idtbl_user_type`) WHERE `t`.`type` = 'CSM'";
$resultcsm =$conn-> query($sqlcsm); 

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
                            <span>Area</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">

                                <form action="process/areaprocess.php" method="post" autocomplete="off">

                                    <div class="form-group">
                                        <label class="small font-weight-bold text-dark">District*</label>
                                        <select name="district" id="district" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select</option>
                                            <?php if($resultdistrict->num_rows > 0) {while ($rowdistrict = $resultdistrict-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowdistrict['idtbl_district'] ?>">
                                                <?php echo $rowdistrict['name'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="small font-weight-bold text-dark">Area*</label>
                                        <input type="text" class="form-control form-control-sm" name="area" id="area"
                                            required>
                                    </div>

                                    <!-- <div class="form-group">
                                        <label class="small font-weight-bold text-dark">Area sales manager*</label>
                                        <select name="asm" id="asm" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select</option>
                                            <?php if($resultasm->num_rows > 0) {while ($rowasm = $resultasm-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowasm['idtbl_user'] ?>">
                                                <?php echo $rowasm['name'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-dark">CSM*</label>
                                        <select name="csm" id="csm" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select</option>
                                            <?php if($resultcsm->num_rows > 0) {while ($rowcsm = $resultcsm-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowcsm['idtbl_user'] ?>">
                                                <?php echo $rowcsm['name'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div> -->
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
                                            <th>District</th>
                                            <th>Area</th>

                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($result->num_rows > 0) {while ($row = $result-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['idtbl_area'] ?></td>
                                            <td><?php echo $row['district'] ?></td>
                                            <td><?php echo $row['area'] ?></td>
                                            <td class="text-right">
                                                <?php if($editcheck==1){ ?>
                                                <button
                                                    class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>"
                                                    id="<?php echo $row['idtbl_area'] ?>"><i
                                                        data-feather="edit-2"></i></button>
                                                <?php } if($statuscheck==1 && $row['status']==1){ ?>
                                                <button
                                                    data-url="process/statusarea.php?record=<?php echo $row['idtbl_area'] ?>&type=2"
                                                    data-actiontype="2"
                                                    class="btn btn-outline-success btn-sm btntableaction"><i
                                                        data-feather="check"></i></button>
                                                <?php } else if($statuscheck==1 && $row['status']==2){ ?>
                                                <button
                                                    data-url="process/statusarea.php?record=<?php echo $row['idtbl_area'] ?>&type=1"
                                                    data-actiontype="1"
                                                    class="btn btn-outline-warning btn-sm btntableaction"><i
                                                        data-feather="x-square"></i></button>
                                                <?php } if($deletecheck==1){ ?>
                                                <button
                                                    data-url="process/statusarea.php?record=<?php echo $row['idtbl_area'] ?>&type=3"
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
                    url: 'getprocess/getarea.php',
                    success: function (result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#area').val(obj.area);
                        $('#district').val(obj.district);

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
    });
</script>
<?php include "include/footer.php"; ?>