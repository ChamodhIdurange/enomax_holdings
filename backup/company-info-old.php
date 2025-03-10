<?php

// specify optional header includes in this array.
$optional_header_includes = ['magnific-popup'];

include "include/header.php";  

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
                            <div class="page-header-icon"><i data-feather="trello"></i></div>
                            <span>Company Information</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-md-12">
                                <form class="p-3 m-2" method="post">
                                    <div class="form-group mb-4">
                                        <label for="codeinput" >Code</label>
                                        <input class="form-control" type="text" id="codeinput" name="codeinput" value="CODE00xx" placeholder="Your Code">
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="nameinput" >Company Name</label>
                                        <input class="form-control" type="text" id="nameinput" name="nameinput" value="My Company Name" placeholder="Your Company Name">
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="addressinput" >Address</label>
                                        <textarea class="form-control"  id="addressinput" name="addressinput" value="Company Address Here" placeholder="Your Company Address"> </textarea>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="telinput" >Telephone</label>
                                        <input class="form-control" type="text" id="telinput" name="telinput" value="+94112345678" placeholder="Your Telephone Number">
                                    </div>
                                    <div class="form-group mb-4">
                                        <input class="btn btn-sm btn-primary" type="submit" id="btnsubmit" name="btnsubmit" value="Submit">
                                    </div>

                                </form>
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
        $('.image-link').magnificPopup({type:'image'});
        $('#dataTable tbody').on('click', '.btnEdit', function() {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getcomponent.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);

                        $('#recordID').val(obj.id);
                        $('#style').val(obj.styleId);
                        $('#component').val(obj.component);
                        $('#description').val(obj.description);
                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');

                        $('#currentImage').attr('src','uploads/'+obj.imagepath);
                        $('.tower-file-details').removeClass('d-none');
                        $('#componentimage').removeAttr('required');

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
