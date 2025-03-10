<?php 
include "include/header.php";  

$sql="SELECT `idtbl_query_company`, `name`, `location`, `email`, `contact`, `headperson`, `status` FROM `tbl_query_company` WHERE `status` IN (1,2)";
$result =$conn-> query($sql); 

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
                            <span>Query Company</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="process/querycompanyprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Company Name*</label>
                                        <input type="text" class="form-control form-control-sm" id="companyname"
                                            name="companyname" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Location</label>
                                        <input type="text" class="form-control form-control-sm" id="location"
                                            name="location" placeholder="">
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Email*</label>
                                        <input type="text" class="form-control form-control-sm" id="email"
                                            name="email" placeholder="" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Contact*</label>
                                        <input type="text" class="form-control form-control-sm" id="contact"
                                            name="contact" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Head Person</label>
                                        <input type="text" class="form-control form-control-sm" id="headperson"
                                            name="headperson" required>
                                    </div>
    
                                    <div class="form-group mt-2">
                                        <button type="submit" id="submitBtn"
                                            class="btn btn-outline-primary btn-sm w-50 fa-pull-right"
                                            <?php ?>><i
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
                                            <th>Location</th>
                                            <th>Contact</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($result->num_rows > 0) {while ($row = $result-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['idtbl_query_company'] ?></td>
                                            <td><?php echo $row['name'] ?></td>
                                            <td><?php echo $row['location'] ?></td>
                                            <td><?php echo $row['contact'] ?></td>

                                            <td class="text-right">
                                                <button
                                                    class="btn btn-outline-primary btn-sm btnEdit <?php ?>"
                                                    id="<?php echo $row['idtbl_query_company'] ?>"><i
                                                        data-feather="edit-2"></i></button>
                                                <?php if($row['status']==1){ ?>
                                                <a href="process/statusquerycompany.php?record=<?php echo $row['idtbl_query_company'] ?>&type=2"
                                                    onclick="return confirm('Are you sure you want to deactive this?');"
                                                    target="_self"
                                                    class="btn btn-outline-success btn-sm <?php ?>"><i
                                                        data-feather="check"></i></a>
                                                <?php }else{ ?>
                                                <a href="process/statusquerycompany.php?record=<?php echo $row['idtbl_query_company'] ?>&type=1"
                                                    onclick="return confirm('Are you sure you want to active this?');"
                                                    target="_self"
                                                    class="btn btn-outline-warning btn-sm <?php ?>"><i
                                                        data-feather="x-square"></i></a>
                                                <?php } ?>
                                                <a href="process/statusquerycompany.php?record=<?php echo $row['idtbl_query_company'] ?>&type=3"
                                                    onclick="return confirm('Are you sure you want to remove this?');"
                                                    target="_self"
                                                    class="btn btn-outline-danger btn-sm <?php  ?>"><i
                                                        data-feather="trash-2"></i></a>
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
        $('#dataTable').DataTable();

        $('#dataTable tbody').on('click', '.btnEdit', function () {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getquerycompany.php',
                    success: function (result) {// alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#companyname').val(obj.name);
                        $('#location').val(obj.location);
                        $('#email').val(obj.email);
                        $('#contact').val(obj.contact);
                        $('#headperson').val(obj.headperson);

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                       
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