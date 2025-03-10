<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_user` WHERE `status` IN (1,2) AND `idtbl_user`!=1";
$result =$conn-> query($sql); 

$sqlusertypeuser="SELECT `idtbl_user_type`, `type` FROM `tbl_user_type` WHERE `status`=1 AND `idtbl_user_type`!=1";
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
                            <div class="page-header-icon"><i data-feather="user"></i></div>
                            <span>User Account</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="process/useraccountprocess.php" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Account Name*</label>
                                        <input type="text" class="form-control form-control-sm" name="accountname" id="accountname" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Username*</label>
                                        <input type="text" class="form-control form-control-sm" name="username" id="username" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Password*</label>
                                        <input type="password" class="form-control form-control-sm" name="password" id="password" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">User Type*</label>
                                        <select class="form-control form-control-sm" name="usertype" id="usertype" required>
                                            <option value="">Select</option>
                                            <?php if($resultusertypeuser->num_rows > 0) {while ($rowusertypeuser = $resultusertypeuser-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowusertypeuser['idtbl_user_type'] ?>"><?php echo $rowusertypeuser['type'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Images</label>
                                        <input type="file" name="userimages" id="userimages" class="form-control form-control-sm" style="padding-bottom:32px;">
                                    </div>
                                    <div class="form-group mt-3">
                                        <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm px-5 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
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
                                            <th>Username</th>
                                            <th>Type</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($result->num_rows > 0) {while ($row = $result-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['idtbl_user'] ?></td>
                                            <td><?php echo $row['name'] ?></td>
                                            <td><?php echo $row['username'] ?></td>
                                            <td><?php $typeID=$row['tbl_user_type_idtbl_user_type']; $sqltype="SELECT `type` FROM `tbl_user_type` WHERE `idtbl_user_type`='$typeID'"; $resulttype =$conn-> query($sqltype); $rowtype = $resulttype-> fetch_assoc(); echo $rowtype['type']; ?></td>
                                            <td class="text-right">
                                                <button class="btn btn-outline-dark btn-sm btnview" id="<?php echo $row['idtbl_user'] ?>"><i data-feather="image"></i></button>
                                                <?php if($editcheck==1){ ?>
                                                <button
                                                    class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>"
                                                    id="<?php echo $row['idtbl_user'] ?>"><i
                                                        data-feather="edit-2"></i></button>
                                                <?php } if($statuscheck==1 && $row['status']==1){ ?>
                                                <button
                                                    data-url="process/statususeraccount.php?record=<?php echo $row['idtbl_user'] ?>&type=2"
                                                    data-actiontype="2"
                                                    class="btn btn-outline-success btn-sm btntableaction"><i
                                                        data-feather="check"></i></button>
                                                <?php } else if($statuscheck==1 && $row['status']==2){ ?>
                                                <button
                                                    data-url="process/statususeraccount.php?record=<?php echo $row['idtbl_user'] ?>&type=1"
                                                    data-actiontype="1"
                                                    class="btn btn-outline-warning btn-sm btntableaction"><i
                                                        data-feather="x-square"></i></button>
                                                <?php } if($deletecheck==1){ ?>
                                                <button
                                                    data-url="process/statususeraccount.php?record=<?php echo $row['idtbl_user'] ?>&type=3"
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
<!-- Modal Image View -->
<div class="modal fade" id="modalimageview" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <div id="imagelist" class=""></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
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
                    url: 'getprocess/getuseraccount.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#accountname').val(obj.name); 
                        $('#username').val(obj.username);   
                        $('#usertype').val(obj.type);  
                        
                        $('#password').removeAttr("required");

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
        $('#dataTable tbody').on('click', '.btnview', function() {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    userID: id
                },
                url: 'getprocess/getuserimageview.php',
                success: function(result) { //alert(result);
                    $('#imagelist').html(result);
                    $('#modalimageview').modal('show');
                }
            });            
        });
    });
</script>
<?php include "include/footer.php"; ?>
