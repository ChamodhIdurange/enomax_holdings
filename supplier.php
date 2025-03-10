<?php 
include "include/header.php";  

// $sql="SELECT * FROM `tbl_user` WHERE `status` IN (1,2) AND `idtbl_user`!=1";
// $result =$conn-> query($sql); 
$sqlusertypeuser="SELECT * FROM `tbl_supplier`";
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
                            <span>Supplier Management</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-4">
                                <form action="process/supplierprocess.php"method="post"
                                    autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Supplier Name*</label>
                                        <input type="text" class="form-control form-control-sm" name="suppliername"
                                            id="suppliername" required>
                                    </div>

                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Contact one*</label>
                                        <input type="text" class="form-control form-control-sm" name="contactone"
                                            id="contactone" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Contact two*</label>
                                        <input type="text" class="form-control form-control-sm" name="contacttwo"
                                            id="contacttwo" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Email address*</label>
                                        <input type="text" class="form-control form-control-sm" name="email" id="email">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Address*</label>
                                        <input type="text" class="form-control form-control-sm" name="address"
                                            id="address" required>

                                    </div>

                                    <!-- <div class="" id="divchange">
                                        <div class="form-group mb-1">
                                            <hr>
                                            <p><strong>Profile Details</strong></p>
                                            <label class="small font-weight-bold text-dark">User Name*</label>
                                            <input type="text" class="form-control form-control-sm" name="username"
                                                id="username">
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="small font-weight-bold text-dark">NIC*</label>
                                            <input type="text" class="form-control form-control-sm" name="nic" id="nic">
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Password*</label>
                                            <div class="input-group input-group-sm">
                                                <input type="password" name="password" id="password"
                                                    class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="passicon1"><i
                                                            class="fas fa-times text-danger"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Re-Enter Password*</label>
                                            <div class="input-group input-group-sm">
                                                <input type="password" name="re_password" id="re_password"
                                                    class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="passicon2"><i
                                                            class="fas fa-times text-danger"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <p><strong>Company Details</strong></p>
                                        <div class="form-group mb-1">
                                            <label class="small font-weight-bold text-dark">Company Name*</label>
                                            <input type="text" class="form-control form-control-sm" name="companyname"
                                                id="companyname">
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="small font-weight-bold text-dark">Reg No*</label>
                                            <input type="text" class="form-control form-control-sm" name="reg" id="reg">
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="small font-weight-bold text-dark">Contact No*</label>
                                            <input type="text" class="form-control form-control-sm" name="companyno"
                                                id="companyno">
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="small font-weight-bold text-dark">Company Email*</label>
                                            <input type="text" class="form-control form-control-sm" name="companyemail"
                                                id="companyemail">
                                        </div>
                                        <div class="form-group mb-1">
                                            <label class="small font-weight-bold text-dark">Company Address*</label>
                                            <input type="text" class="form-control form-control-sm"
                                                name="companyaddress" id="companyaddress">
                                        </div>
                                    </div> -->

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
                                                <th>Name</th>
                                                <th>Contact one</th>
                                                <th>Contact two</th>
                                                <th>Email</th>
                                                <th class="text-right">Address</th>
                                                <th class="text-right">Actions</th>
                                            </tr>
                                        </thead>
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
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function () {
        var addcheck = '<?php echo $addcheck; ?>';
        var editcheck = '<?php echo $editcheck; ?>';
        var statuscheck = '<?php echo $statuscheck; ?>';
        var deletecheck = '<?php echo $deletecheck; ?>';



        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/supplierlist.php",
                type: "POST", // you can use GET
            },
            "order": [
                [0, "desc"]
            ],
            "columns": [{
                    "data": "idtbl_supplier"
                },
                {
                    "data": "suppliername"
                },
                {
                    "data": "contactone"
                },
                {
                    "data": "contacttwo"
                },
                {
                    "data": "email"
                },
                {
                    "data": "address"
                },

                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {
                        var button = '';
                        if(editcheck=1){
                            button+='<button type="button" class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_supplier']+'"><i class="fas fa-pen"></i></button>';
                        }
                        if(full['status']==1 && statuscheck==1){
                            button+='<button type="button" data-url="process/statussupplier.php?record='+full['idtbl_supplier']+'&type=2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                        }else if(full['status']==2 && statuscheck==1){
                            button+='<button type="button" data-url="process/statussupplier.php?record='+full['idtbl_supplier']+'&type=1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                        }
                        if(deletecheck==1){
                            button+='<button type="button" data-url="process/statussupplier.php?record='+full['idtbl_supplier']+'&type=3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';
                        }
                        return button;
                    }
                }
            ],
            drawCallback: function (settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
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
                    url: 'getprocess/getsupplierdetails.php',
                    success: function (result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#suppliername').val(obj.name);
                        $('#contactone').val(obj.contactone);
                        $('#contacttwo').val(obj.contacttwo);
                        $('#email').val(obj.email);
                        $('#address').val(obj.address);

                        $('#recordOption').val('2');
                        $("#divchange").addClass("d-none");
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                        $('#submitBtn').prop("disabled", false);
                    }
                });
            }
        });
    });


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
