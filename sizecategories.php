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
                            <span>Size Categories</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-4">
                                <form action="process/sizecategoriesprocess.php"method="post"
                                    autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Name*</label>
                                        <input type="text" class="form-control form-control-sm" name="name"
                                            id="name" required>
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
                                                <th>Name</th>
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
        var addcheck
        var editcheck
        var statuscheck
        var deletecheck


        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/sizecategorylist.php",
                type: "POST", // you can use GET
            },
            "order": [
                [0, "desc"]
            ],
            "columns": [{
                    "data": "idtbl_size_categories"
                },
                {
                    "data": "name"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {
                        var button = '';
                        if(editcheck=1){
                            button+='<button type="button" class="btn btn-primary btn-sm btnEdit mr-1" id="'+full['idtbl_size_categories']+'"><i class="fas fa-pen"></i></button>';
                        }
                        if(full['status']==1 ){
                            button+='<button type="button" data-url="process/statussizecategories.php?record='+full['idtbl_size_categories']+'&type=2" data-actiontype="2" class="btn btn-success btn-sm mr-1 btntableaction"><i class="fas fa-check"></i></button>';
                        }else if(full['status']==2){
                            button+='<button type="button" data-url="process/statussizecategories.php?record='+full['idtbl_size_categories']+'&type=1" data-actiontype="1" class="btn btn-warning btn-sm mr-1 text-light btntableaction"><i class="fas fa-times"></i></button>';
                        }
                            button+='<button type="button" data-url="process/statussizecategories.php?record='+full['idtbl_size_categories']+'&type=3" data-actiontype="3" class="btn btn-danger btn-sm text-light btntableaction"><i class="fas fa-trash-alt"></i></button>';


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
                    url: 'getprocess/getsizecategorydetails.php',
                    success: function (result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#name').val(obj.name);

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
