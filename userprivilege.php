<?php 
include "include/header.php";  

if($_SESSION['type']==1){
    $sqluserlist="SELECT `idtbl_user`, `name` FROM `tbl_user` WHERE `status`=1";
    $resultuserlist =$conn-> query($sqluserlist);

    $sql="SELECT * FROM `tbl_user_privilege` WHERE `status` IN (1,2)";
    $result =$conn-> query($sql);
}
else{
    $sqluserlist="SELECT `idtbl_user`, `name` FROM `tbl_user` WHERE `status`=1 AND `idtbl_user`!=1";
    $resultuserlist =$conn-> query($sqluserlist);

    $sql="SELECT * FROM `tbl_user_privilege` WHERE `status` IN (1,2) AND `tbl_user_idtbl_user`!=1";
    $result =$conn-> query($sql);
}

$sqlmenulist="SELECT `idtbl_menu_list`, `menu` FROM `tbl_menu_list` WHERE `status`=1";
$resultmenulist =$conn-> query($sqlmenulist);

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
                            <div class="page-header-icon"><i data-feather="user-check"></i></div>
                            <span>User Privilege</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="process/userprivilegeprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">User*</label>
                                        <select type="text" class="form-control form-control-sm" name="userlist" id="userlist" required>
                                            <option value="">Select</option>
                                            <?php if($resultuserlist->num_rows > 0) {while ($rowuserlist = $resultuserlist-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowuserlist['idtbl_user'] ?>"><?php echo $rowuserlist['name'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Access Menu*</label>
                                        <select type="text" class="form-control form-control-sm" name="menulist[]" id="menulist" required multiple>
                                            <?php if($resultmenulist->num_rows > 0) {while ($rowmenulist = $resultmenulist-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowmenulist['idtbl_menu_list'] ?>"><?php echo $rowmenulist['menu'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-dark">User Privilege*</label><br>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" value="1" id="addcheck" name="addcheck">
                                            <label class="custom-control-label" for="addcheck">
                                                Add Privilege
                                            </label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" value="1" id="editcheck" name="editcheck">
                                            <label class="custom-control-label" for="editcheck">
                                                Edit Privilege
                                            </label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" value="1" id="statuscheck" name="statuscheck">
                                            <label class="custom-control-label" for="statuscheck">
                                                Status Privilege
                                            </label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" value="1" id="removecheck" name="removecheck">
                                            <label class="custom-control-label" for="removecheck">
                                                Delete Privilege
                                            </label>
                                        </div>
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
                                            <th>User</th>
                                            <th>Menu</th>
                                            <th>Add</th>
                                            <th>Edit</th>
                                            <th>Active | Deactive</th>
                                            <th>Delete</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($result->num_rows > 0) {while ($row = $result-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['idtbl_user_privilege'] ?></td>
                                            <td><?php $user=$row['tbl_user_idtbl_user']; $sqluser="SELECT `name` FROM `tbl_user` WHERE `idtbl_user`='$user' AND `status`=1"; $resultuser =$conn-> query($sqluser); $rowuser = $resultuser-> fetch_assoc(); echo $rowuser['name']; ?></td>
                                            <td><?php $menu=$row['tbl_menu_list_idtbl_menu_list']; $sqlmenushow="SELECT `menu` FROM `tbl_menu_list` WHERE `idtbl_menu_list`='$menu' AND `status`=1"; $resultmenushow =$conn-> query($sqlmenushow); $rowmenushow = $resultmenushow-> fetch_assoc(); echo $rowmenushow['menu']; ?></td>
                                            <td class="text-center"><?php if($row['add']==1){echo '<i class="text-success mt-2" data-feather="check-circle"></i>';}else{echo '<i class="text-danger mt-2" data-feather="x-circle"></i>';} ?></td>
                                            <td class="text-center"><?php if($row['edit']==1){echo '<i class="text-success mt-2" data-feather="check-circle"></i>';}else{echo '<i class="text-danger mt-2" data-feather="x-circle"></i>';} ?></td>
                                            <td class="text-center"><?php if($row['statuschange']==1){echo '<i class="text-success mt-2" data-feather="check-circle"></i>';}else{echo '<i class="text-danger mt-2" data-feather="x-circle"></i>';} ?></td>
                                            <td class="text-center"><?php if($row['remove']==1){echo '<i class="text-success mt-2" data-feather="check-circle"></i>';}else{echo '<i class="text-danger mt-2" data-feather="x-circle"></i>';} ?></td>
                                            <td class="text-right">
                                            <?php if($editcheck==1){ ?>
                                                <button
                                                    class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>"
                                                    id="<?php echo $row['idtbl_user_privilege'] ?>"><i
                                                        data-feather="edit-2"></i></button>
                                                <?php } if($statuscheck==1 && $row['status']==1){ ?>
                                                <button
                                                    data-url="process/statususerprivilege.php?record=<?php echo $row['idtbl_user_privilege'] ?>&type=2"
                                                    data-actiontype="2"
                                                    class="btn btn-outline-success btn-sm btntableaction"><i
                                                        data-feather="check"></i></button>
                                                <?php } else if($statuscheck==1 && $row['status']==2){ ?>
                                                <button
                                                    data-url="process/statususerprivilege.php?record=<?php echo $row['idtbl_user_privilege'] ?>&type=1"
                                                    data-actiontype="1"
                                                    class="btn btn-outline-warning btn-sm btntableaction"><i
                                                        data-feather="x-square"></i></button>
                                                <?php } if($deletecheck==1){ ?>
                                                <button
                                                    data-url="process/statususerprivilege.php?record=<?php echo $row['idtbl_user_privilege'] ?>&type=3"
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
    $(document).ready(function() {
        $("#menulist").select2();

        $('#dataTable').DataTable({
            "order": [[ 0, "desc" ]]
        });
        $('#dataTable tbody').on('click', '.btnEdit', async function () {
            var r = await Otherconfirmation("You want to edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getuserprivilege.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#userlist').val(obj.user);

                        var menulist = obj.menu;
                        var menulistoption = [];
                        $.each(menulist, function(i, item) {
                            menulistoption.push(menulist[i].menulistID);
                        });

                        $('#menulist').val(menulistoption);
                        $('#menulist').trigger('change');

                        if(obj.add==1){$('#addcheck').prop('checked', true);}
                        if(obj.edit==1){$('#editcheck').prop('checked', true);}
                        if(obj.statuschange==1){$('#statuscheck').prop('checked', true);}
                        if(obj.remove==1){$('#removecheck').prop('checked', true);}

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
    });

</script>
<?php include "include/footer.php"; ?>
