<?php 
include "include/header.php";  


$sqlreturnsupplier="SELECT `e`.`name` as 'asm', `u`.`idtbl_return`, `u`.`returndate`, `u`.`total`, `ua`.`suppliername` FROM `tbl_return` as `u` LEFT JOIN `tbl_supplier` AS `ua` ON (`ua`.`idtbl_supplier` = `u`.`tbl_supplier_idtbl_supplier`) JOIN `tbl_employee` AS `e` ON (`e`.`idtbl_employee` = `u`.`tbl_employee_idtbl_employee`) WHERE `u`.`acceptance_status` = '0' and `u`.`returntype` = '2'";
$resultreturnsupplier =$conn-> query($sqlreturnsupplier);

$sqlreturnsupplieraccepted="SELECT `e`.`name` as 'asm', `u`.`idtbl_return`, `u`.`returndate`, `u`.`total`, `ua`.`suppliername` FROM `tbl_return` as `u` LEFT JOIN `tbl_supplier` AS `ua` ON (`ua`.`idtbl_supplier` = `u`.`tbl_supplier_idtbl_supplier`) JOIN `tbl_employee` AS `e` ON (`e`.`idtbl_employee` = `u`.`tbl_employee_idtbl_employee`) WHERE `u`.`acceptance_status` = '1' and `u`.`returntype` = '2' and `u`.`recieved_status` = '0'";
$resultreturnsupplieraccepted =$conn-> query($sqlreturnsupplieraccepted);

$sqlreturndelivered="SELECT `e`.`name` as 'asm', `u`.`idtbl_return`, `u`.`returndate`, `u`.`total`, `ua`.`suppliername` FROM `tbl_return` as `u` LEFT JOIN `tbl_supplier` AS `ua` ON (`ua`.`idtbl_supplier` = `u`.`tbl_supplier_idtbl_supplier`) JOIN `tbl_employee` AS `e` ON (`e`.`idtbl_employee` = `u`.`tbl_employee_idtbl_employee`) WHERE `u`.`acceptance_status` = '1' and `u`.`returntype` = '2' and `u`.`recieved_status` = '1'";
$resultreturndelivered =$conn-> query($sqlreturndelivered);




// $sqlreturncustomerdetails="SELECT `u`.`idtbl_return`, `u`.`returndate`, `u`.`total`,`ua`.`name` FROM `tbl_return` AS `u` LEFT JOIN `tbl_customer` AS `ua` ON (`ua`.`idtbl_customer` = `u`.`tbl_customer_idtbl_customer`) LEFT JOIN `tbl_supplier` AS `us` ON (`us`.`idtbl_supplier` = `u`.`tbl_supplier_idtbl_supplier`) WHERE `u`.`acceptance_status` = '0' and `u`.`returntype` = '3'";
// $resultreturncustomerdetails =$conn-> query($sqlreturncustomerdetails);


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
                            <div class="page-header-icon"><i data-feather="corner-down-left"></i></div>
                            <span>Supplier Return</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                                    aria-controls="home" aria-selected="true">New Returns</a>
                            </li>

                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="supplierreturn-tab" data-toggle="tab" href="#supplierreturn"
                                    role="tab" aria-controls="supplierreturn" aria-selected="false">Accepted Returns</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="customerreturn-tab" data-toggle="tab" href="#customerreturn"
                                    role="tab" aria-controls="customerreturn" aria-selected="false">Good Recieved</a>
                            </li>
                   
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="inputrow">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="scrollbar pb-3" id="style-2">
                                                <table class="table table-bordered table-striped table-sm nowrap"
                                                    id="dataTable">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>ASM Name</th>
                                                            <th>Supplier name</th>
                                                            <th>Date</th>
                                                            <th>Total</th>
                                                            <th>Actions</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if($resultreturnsupplier->num_rows > 0) {while ($row = $resultreturnsupplier-> fetch_assoc()) { ?>
                                                        <tr>

                                                            <td><?php echo $row['idtbl_return'] ?></td>
                                                            <td><?php echo $row['asm'] ?></td>
                                                            <td><?php echo $row['suppliername'] ?></td>
                                                            <td><?php echo $row['returndate'] ?></td>
                                                            <td class="text-right">Rs.<?php echo $row['total'] ?>.00
                                                            </td>
                                                            <td> <button class="btn btn-primary btn-sm rounded btnView"
                                                                    id="<?php echo $row['idtbl_return']; ?>"><i
                                                                        class="fas fa-eye"></i></button>
                                                                <a href="process/statusacceptreturn.php?record=<?php echo $row['idtbl_return'] ?>&type=2"
                                                                    onclick="return confirm('Are you sure you want to accept this return?');"
                                                                    target="_self"
                                                                    class="btn btn-outline-danger btn-sm"><i
                                                                        data-feather="x-square"></i></a></td>
                                                        </tr>
                                                        <?php }} ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="supplierreturn" role="tabpanel"
                                aria-labelledby="supplierreturn-tab">
                                <div class="inputrow">

                                    <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="scrollbar pb-3" id="style-2">
                                                <table class="table table-bordered table-striped table-sm nowrap"
                                                    id="dataTable">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>ASM Name</th>
                                                            <th>Supplier name</th>
                                                            <th>Date</th>
                                                            <th>Total</th>
                                                            <th>Actions</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if($resultreturnsupplieraccepted->num_rows > 0) {while ($row = $resultreturnsupplieraccepted-> fetch_assoc()) { ?>
                                                        <tr>

                                                            <td><?php echo $row['idtbl_return'] ?></td>
                                                            <td><?php echo $row['asm'] ?></td>
                                                            <td><?php echo $row['suppliername'] ?></td>
                                                            <td><?php echo $row['returndate'] ?></td>
                                                            <td class="text-right">Rs.<?php echo $row['total'] ?>.00
                                                            </td>
                                                            <td> <button class="btn btn-primary btn-sm rounded btnView"
                                                                    id="<?php echo $row['idtbl_return']; ?>"><i
                                                                        class="fas fa-eye"></i></button>
                                                                <button
                                                                 
                                                                    target="_self"
                                                                    class="btn btn-outline-success btn-sm"><i
                                                                        data-feather="check" disabled></i></button>
                                                                        <a href="process/statusdeliverycomplete.php?record=<?php echo $row['idtbl_return'] ?>&type=2"
                                                                    onclick="return confirm('Are you sure that the delivery is complete?');"
                                                                    target="_self"
                                                                    class="btn btn-outline-danger btn-sm"><i
                                                                        data-feather="truck"></i></a></td>
                                                        </tr>
                                                        <?php }} ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane fade" id="customerreturn" role="tabpanel"
                                aria-labelledby="customerreturn-tab">
                                <div class="inputrow">

                                    <br>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="scrollbar pb-3" id="style-2">
                                                <table class="table table-bordered table-striped table-sm nowrap"
                                                    id="dataTable">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>ASM Name</th>
                                                            <th>Supplier name</th>
                                                            <th>Date</th>
                                                            <th>Total</th>
                                                            <th>Actions</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if($resultreturndelivered->num_rows > 0) {while ($row = $resultreturndelivered-> fetch_assoc()) { ?>
                                                        <tr>

                                                            <td><?php echo $row['idtbl_return'] ?></td>
                                                            <td><?php echo $row['asm'] ?></td>
                                                            <td><?php echo $row['suppliername'] ?></td>
                                                            <td><?php echo $row['returndate'] ?></td>
                                                            <td class="text-right">Rs.<?php echo $row['total'] ?>.00
                                                            </td>
                                                            <td> <button class="btn btn-outline-primary btn-sm rounded btnView"
                                                                    id="<?php echo $row['idtbl_return']; ?>"><i
                                                                        class="fas fa-eye"></i></button>
                                                                <button
                                                                    onclick="return confirm('Are you sure you want to accept this return?');"
                                                                    target="_self"
                                                                    class="btn btn-outline-success btn-sm" disabled><i
                                                                        data-feather="check" ></i></button>
                                                                        <button
                                                                    onclick="return confirm('Are you sure you want to accept this return?');"
                                                                    target="_self"
                                                                    class="btn btn-outline-success btn-sm" disabled><i
                                                                        data-feather="truck" ></i></button>
                                                             
                                                                <button</td>
                                                        </tr>
                                                        <?php }} ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- Modal return details -->
                            <div class="modal fade" id="modalreturndetails" data-backdrop="static" data-keyboard="false"
                                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                            <!-- Modal quantity check -->
                            <div class="modal fade" id="modalquantitycheck" data-backdrop="static" data-keyboard="false"
                                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                                                    <div id="quantitydetail"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
    $('#returntype').change(function () {
        var type = $(this).val();

        if (type == 1) {
            $('#customerdiv').removeClass('d-none');
            $('#customer').prop('required', true);

            $('#supplierdiv').addClass('d-none');
            $('#supplier').prop('required', false);
        } else if (type == 2) {
            $('#customerdiv').addClass('d-none');
            $('#customer').prop('required', false);

            $('#supplierdiv').removeClass('d-none');
            $('#supplier').prop('required', true);
        } else {
            $('#customerdiv').addClass('d-none');
            $('#customer').prop('required', false);
            $('#supplierdiv').addClass('d-none');
            $('#supplier').prop('required', false);
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
            url: 'getprocess/getreturndetails.php',
            success: function (result) {
                // alert(result)
                $('#viewmodaltitle').html('Return No ' + id)
                $('#viewdetail').html(result);
                $('#modalreturndetails').modal('show');
            }
        });
    });
    $('#dataTable tbody').on('click', '.btnQuantity', function () {
        var id = $(this).attr('id');

        $.ajax({
            type: "POST",
            data: {
                recordID: id
            },
            url: 'getprocess/getquantitydetails.php',
            success: function (result) {
                // alert(result)
                $('#viewmodaltitle').html('Return No ' + id)
                $('#quantitydetail').html(result);
                $('#modalquantitycheck').modal('show');
            }
        });
    });

    function accept_confirm() {
        return confirm("Are you sure you want to Accept this?");
    }

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }

    function company_confirm() {
        return confirm("Are you sure this product send to company?");
    }

    function warehouse_confirm() {
        return confirm("Are you sure this product back to warehouse?");
    }

    function customer_confirm() {
        return confirm("Are you sure this product breturn back to customer?");
    }
</script>
<?php include "include/footer.php"; ?>