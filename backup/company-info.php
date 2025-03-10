<?php

// specify optional header includes in this array.
$optional_header_includes = ['magnific-popup'];
include_once "connection/db.php";

include "include/header.php";  

include "include/topnavbar.php";

echo $sqlcompanyinfo = "
        SELECT * FROM `tbl_company` where   `status` IN (1,2) ;
";
$resultcompanyinfo =$conn-> query($sqlcompanyinfo);

?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content d-md-flex align-items-center text-right justify-content-between py-3">
                        <div class="d-inline">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="trello"></i></div>
                                <span>Company Information</span>
                            </h1>
                        </div>
                        <button class="btn btn-sm form-control-sm btn-primary py-0 float-md-right d-none" type="button" data-toggle="modal" data-target="#addCompanyModal"><i data-feather="plus-circle"></i>  Add Company</button>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-md-3">
                                <form class="" action="process/companyinfoprocess.php" method="post">
                                    <div class="form-group form-group-sm mb-4">
                                        <label for="code" >Code</label>
                                        <input class="form-control form-control-sm" type="text" id="code" name="code" required value="" placeholder=" Company Code">
                                    </div>
                                    <div class="form-group form-group-sm mb-4">
                                        <label for="name" >Company Name</label>
                                        <input class="form-control form-control-sm" type="text" id="name" name="name" required value="" placeholder="Your Bank Name">
                                    </div>
                                    <div class="form-group form-group-sm mb-4">
                                        <label for="phone" >Telephone</label>
                                        <input class="form-control form-control-sm" type="text" id="phone" name="phone" required value="" placeholder="Your Contact Number">
                                    </div>
                                    <div class="form-group form-group-sm mb-4">
                                        <label for="address" >Address</label>
                                        <textarea class="form-control form-control-sm" type="text" id="address" name="address" required value="" placeholder="Your Address"></textarea>
                                    </div>

                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                    <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm w-50 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>


                                </form>
                                </div>
                            <div class="col-md-9">
                                <div class="table-responsive-sm ">
                                    <table class="table  table-sm w-100 table-bordered table-striped" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Code</th>
                                                <th>Company Name</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($resultcompanyinfo->num_rows > 0){ while ($row = $resultcompanyinfo->fetch_assoc())   {  ?>
                                                     <tr>
                                                        <td><?php echo $row['idtbl_company'];  ?></td>
                                                        <td><?php echo $row['code'];  ?></td>
                                                        <td><?php echo $row['name'];  ?></td>
                                                        <td><?php echo $row['phone'];  ?></td>
                                                        <td><?php echo $row['address1'];  ?></td>

                                                         <td class="text-right">
                                                             <button class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>" id="<?php echo $row['idtbl_company'] ?>"><i data-feather="edit-2"></i></button>
                                                             <?php if($row['status']==1){ ?>
                                                                 <a href="process/statuscompanyinfo.php?record=<?php echo $row['idtbl_company'] ?>&type=2" onclick="return confirm('Are you sure you want to deactive this?');" target="_self" class="btn btn-outline-success btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="check"></i></a>
                                                             <?php }else{ ?>
                                                                 <a href="process/statuscompanyinfo.php?record=<?php echo $row['idtbl_company'] ?>&type=1" onclick="return confirm('Are you sure you want to active this?');" target="_self" class="btn btn-outline-warning btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="x-square"></i></a>
                                                             <?php } ?>
                                                             <a href="process/statuscompanyinfo.php?record=<?php echo $row['idtbl_company'] ?>&type=3" onclick="return confirm('Are you sure you want to remove this?');" target="_self" class="btn btn-outline-danger btn-sm <?php if($deletecheck==0){echo 'd-none';} ?>"><i data-feather="trash-2"></i></a>
                                                         </td>
                                                    </tr>
                                                <?php }
                                                ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <div class="modal fade" id="addCompanyModal" tabindex="-1" role="dialog" aria-labelledby="addBankModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCompanyModalLabel">Add New Company</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm form-control-sm btn-secondary" type="button" data-dismiss="modal">Close</button>
                        <button class="btn btn-sm form-control-sm btn-primary"  data-dismiss="modal" onclick="alert('Data added')" type="button">Add New</button></div>
                </div>
            </div>
        </div>
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
                var id = $(this).data('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getcompanyinfo.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);

                        $('#recordID').val(obj.id);
                        $('#code').val(obj.code);
                        $('#name').val(obj.name);
                        $('#address').val(obj.address);
                        $('#phone').val(obj.phone);
                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');

                    }
                });
            }
        });
    });


</script>

<?php

// specify optional header includes in this array.
$optional_footer_includes = ['magnific-popup'];

include "include/footer.php";
?>
