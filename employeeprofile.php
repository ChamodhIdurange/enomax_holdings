<?php 
include "include/header.php";  

// $sql="SELECT * FROM `tbl_user` WHERE `status` IN (1,2) AND `idtbl_user`!=1";
// $result =$conn-> query($sql); 

$record=$_GET['record'];
$type=$_GET['type'];
$persontype = "Employee";
$sqlemployeecontact="SELECT * FROM `tbl_contact_details` WHERE `person_id` = '$record' AND `status` in (1,2) and `type` = 'Employee'";
$resultemployeecontact=$conn->query($sqlemployeecontact);


$sqlemployeedetails="SELECT `nic`, `name`, `phone` FROM `tbl_employee` WHERE `idtbl_employee` = '$record'";
$resultemployeedetails =$conn-> query($sqlemployeedetails); 
$resultempdetails = $resultemployeedetails-> fetch_assoc();


$sqlcustomerlocation="SELECT FROM `tbl_porder` AS `u` LEFT JOIN `tbl_porder_otherinfo` AS `ua` ON (`ua`.`porderid` = `u`.`idtbl_porder`) LEFT JOIN `tbl_area` AS `ub` ON (`ub`.`idtbl_area` = `ua`.`areaid`) LEFT JOIN `tbl_customer` AS `uc` ON (`uc`.`idtbl_customer` = `ua`.`customerid`) LEFT JOIN `tbl_employee` AS `ud` ON (`ud`.`idtbl_employee` = `ua`.`repid`) `u`.`confirmstatus` IN (1,0,2) AND `u`.`status`=1 AND `u`.`potype`=1";
$resultcustomerlocation =$conn-> query($sqlcustomerlocation); 




$sqlBank="SELECT * FROM `tbl_bank` WHERE `status` in ('1')";
$resultBank=$conn->query($sqlBank);

$sqlArea="SELECT * FROM `tbl_area` WHERE `status` in ('1')";
$resultArea=$conn->query($sqlArea);

$sqlEmployeeBank="SELECT `b`.`bankname`, `b`.`code`,`a`.`branchname`,`a`.`accountnumber`, `a`.`status`, `a`.`idtbl_bank_account_details`, `a`.`account_name` FROM `tbl_bank` as `b` JOIN `tbl_bank_account_details` AS `a` ON (`a`.`tbl_bank_idtbl_bank` = `b`.`idtbl_bank`) WHERE `a`.`status` in ('1') AND `a`.`person_id` = '$record' and `a`.`type` = 'Employee'";
$resultEmployeeBank=$conn->query($sqlEmployeeBank);

$sqlTarget="SELECT * FROM `tbl_employee_target` as `t` JOIN `tbl_product` AS `p` ON (`p`.`idtbl_product` = `t`.`tbl_product_idtbl_product`) WHERE `t`.`status` in ('1') AND `t`.`tbl_employee_idtbl_employee` = '$record'";
$resultTarget=$conn->query($sqlTarget);

$sql="SELECT * FROM `tbl_offer` WHERE `status` in ('1','2')";
$result=$conn->query($sql);


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
                            <span><?php echo $resultempdetails['name'] ?>'s Profile</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="container rounded bg-white mt-5 mb-5">
                                <div class="row">
                                    <div class="col-md-3 border-right">
                                        <div class="d-flex flex-column align-items-center text-center p-3 py-2"><img
                                                class="rounded-circle mt-5" width="150px"
                                                src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"><span
                                                class="font-weight-bold"><?php echo $resultempdetails['name']; ?></span><span
                                                class="text-black-50"><?php  echo $resultempdetails['phone'] ?></span><span>NIC:
                                                <?php echo $resultempdetails['nic']; ?>
                                            </span></div>
                                    </div>
                                    <div class="col-md-9 border-right">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home"
                                                    role="tab" aria-controls="home" aria-selected="true">Contact
                                                    Info</a>
                                            </li>

                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="supplier-tab" data-toggle="tab" href="#supplier"
                                                    role="tab" aria-controls="supplier" aria-selected="false">Bank
                                                    Info</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="gainedpoints-tab" data-toggle="tab"
                                                    href="#gainedpoints" role="tab" aria-controls="gainedpoints"
                                                    aria-selected="false">Purchasing Orders</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="targetinfo-tab" data-toggle="tab"
                                                    href="#targetinfo" role="tab" aria-controls="targetinfo"
                                                    aria-selected="false">Target info</a>
                                            </li>

                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                                aria-labelledby="home-tab">
                                                <div class="inputrow">
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-md-12 text-right">
                                                            <button class="btn btn-outline-primary btn-sm "
                                                                data-toggle="modal" data-target="#modelcontactdetails">
                                                                <i class="far fa-save"></i>&nbsp;Add contact details
                                                            </button>

                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="scrollbar pb-3" id="style-2">

                                                                <table
                                                                    class="table table-bordered table-striped table-sm nowrap"
                                                                    id="dataTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Contact owner</th>
                                                                            <th>Relation</th>
                                                                            <th>Number</th>
                                                                            <th>Email address</th>
                                                                            <th>Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if($resultemployeecontact->num_rows > 0) {while ($row = $resultemployeecontact-> fetch_assoc()) { ?>
                                                                        <tr>
                                                                            <td><?php echo $row['idtbl_contact_details'] ?>
                                                                            </td>
                                                                            <td><?php echo $row['contact_owner'] ?></td>
                                                                            <td><?php echo $row['relation'] ?></td>
                                                                            <td><?php echo $row['number'] ?></td>
                                                                            <td><?php echo $row['email'] ?></td>

                                                                            <td class="text-right">
                                                                                <button
                                                                                    class="btn btn-outline-primary btn-sm btnEditContact mr-1 "
                                                                                    id="<?php echo $row['idtbl_contact_details'] ?>"><i
                                                                                        class=" fas fa-edit"></i></button>
                                                                                <a href="process/statuscontactdetails.php?record=<?php echo $row['idtbl_contact_details'] ?>&eledID=<?php echo $record ?>&type=<?php echo $persontype ?>"
                                                                                    onclick="return confirm('Are you sure you want to remove this?');"
                                                                                    target="_self"
                                                                                    class="btn btn-outline-danger btn-sm"><i
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
                                            <div class="tab-pane fade" id="supplier" role="tabpanel"
                                                aria-labelledby="supplier-tab">
                                                <div class="inputrow">

                                                    <br>
                                                    <div class="row">
                                                        <div class="col-md-12 text-right">
                                                            <button class="btn btn-outline-primary btn-sm "
                                                                data-toggle="modal" data-target="#modelbankdetails">
                                                                <i class="far fa-save"></i>&nbsp;Add bank details
                                                            </button>

                                                        </div>

                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="scrollbar pb-3" id="style-2">
                                                                <table
                                                                    class="table table-bordered table-striped table-sm nowrap"
                                                                    id="dataTablebank">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Bank Name</th>
                                                                            <th>Bank Code</th>
                                                                            <th>Branch</th>
                                                                            <th>Account name</th>
                                                                            <th>Account number</th>
                                                                            <th>Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if($resultEmployeeBank->num_rows > 0) {while ($row = $resultEmployeeBank-> fetch_assoc()) { ?>
                                                                        <tr>
                                                                            <td><?php echo $row['idtbl_bank_account_details'] ?>
                                                                            </td>
                                                                            <td><?php echo $row['bankname'] ?></td>
                                                                            <td><?php echo $row['code'] ?></td>
                                                                            <td><?php echo $row['branchname'] ?></td>
                                                                            <td><?php echo $row['account_name'] ?></td>
                                                                            <td><?php echo $row['accountnumber'] ?></td>

                                                                            <td class="text-right">
                                                                                <button
                                                                                    class="btn btn-outline-primary btn-sm btnEditBank mr-1 "
                                                                                    id="<?php echo $row['idtbl_bank_account_details'] ?>"><i
                                                                                        class=" fas fa-edit"></i></button>

                                                                                <a href="process/statusbankdetails.php?record=<?php echo $row['idtbl_bank_account_details'] ?>&eledID=<?php echo $record ?>&type=<?php echo $persontype ?>"
                                                                                    onclick="return confirm('Are you sure you want to remove this?');"
                                                                                    target="_self"
                                                                                    class="btn btn-outline-danger btn-sm"><i
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
                                            <div class="tab-pane fade" id="gainedpoints" role="tabpanel"
                                                aria-labelledby="gainedpoints-tab">
                                                <div class="inputrow">
                                                    <br>

                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="scrollbar pb-3" id="style-2">
                                                            <table
                                                                class="table table-bordered table-striped table-sm nowrap"
                                                                id="dataTableCustomerPO">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Date</th>
                                                                        <th>Order No</th>
                                                                        <th>Area</th>
                                                                        <th>Customer</th>
                                                                        <th class="text-right">Subtotal</th>
                                                                        <th class="text-right">Discount</th>
                                                                        <th class="text-right">Nettotal</th>
                                                                        <th class="text-center">Confirm</th>
                                                                        <th class="text-center">Ship</th>
                                                                        <th class="text-center">Delivery</th>
                                                                        <th class="text-center">Tracking No</th>
                                                                        <th class="text-right">Actions</th>
                                                                    </tr>
                                                                </thead>

                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="targetinfo" role="tabpanel"
                                            aria-labelledby="targetinfo-tab">
                                            <div class="inputrow">

                                                <br>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="scrollbar pb-3" id="style-2">
                                                            <table
                                                                class="table table-bordered table-striped table-sm nowrap"
                                                                id="dataTableTarget">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Date</th>
                                                                        <th>Product</th>
                                                                        <th>Target</th>
                                                                        <th>Achived</th>
                                                                        <th>Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if($resultTarget->num_rows > 0) {while ($row = $resultTarget-> fetch_assoc()) { ?>
                                                                    <tr>
                                                                        <td><?php echo $row['idtbl_employee_target'] ?></td>
                                                                        <td><?php echo $row['month'] ?></td>
                                                                        <td><?php echo $row['product_name'] ?></td>
                                                                        <td><?php echo $row['targetqty'] ?></td>
                                                                        <td><?php echo $row['targetqtycomplete'] ?></td>
                                                                        <?php if($row['targetqty'] <= $row['targetqtycomplete']){ ?>
                                                                            <td class = "text-success" >Completed</td>
                                                                        <?php }else{ ?>
                                                                            <td class = "text-danger" >Pending</td>
                                                                        <?php } ?>
                                                                    </tr>
                                                                    <?php }} ?>
                                                                </tbody>
                                                            </table>
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
                    </div>
                </div>

            </div>
    </div>
</div>
</div>
</main>

<!-- Invoice Details Modal -->
<div class="modal fade" id="modelinvoicedetails" tabindex="-1" aria-labelledby="modelinvoicedetails" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelinvoicedetails">Invoice Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="modalinvoicebody" class="modal-body">

            </div>

        </div>
    </div>
</div>

<!-- Bank Details Modal -->
<div class="modal fade" id="modelbankdetails" tabindex="-1" aria-labelledby="modelbankdetails" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelbankdetails">Bank Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="contactform" action="process/personalbankinfoprocess.php"
                            enctype="multipart/form-data" method="post" autocomplete="off">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Bank Name*</label>
                                        <select class="form-control form-control-sm" name="bank" id="bank" required>
                                            <option value="">Select</option>
                                            <?php if($resultBank->num_rows > 0) {while ($rowcategory = $resultBank-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowcategory['idtbl_bank'] ?>">
                                                <?php echo $rowcategory['bankname'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Branch Name*</label>
                                        <input type="text" class="form-control form-control-sm" name="branchname"
                                            id="branchname" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Account name*</label>
                                        <input type="text" class="form-control form-control-sm" name="accountname"
                                            id="accountname" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Account number*</label>
                                        <input type="text" class="form-control form-control-sm" name="accountnumber"
                                            id="accountnumber" required>
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mt-2">
                                        <button type="submit" id="submitBtnbank"
                                            class="btn btn-outline-primary btn-sm w-50 fa-pull-right"><i
                                                class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" value="Employee" id="usertypebank" name="usertypebank">
                            <input type="hidden" value=<?php echo $record ?> id="recordbank" name="recordbank">
                            <input type="hidden" value="0" id="recordIDBank" name="recordIDBank">
                            <!-- <input type="hidden" value=<?php echo $type ?> id="typebank" name="typebank"> -->
                            <input type="hidden" name="recordOptionBank" id="recordOptionBank" value="1">
                            <!--    <input type="text" name="quotationid" id="quotationid" value=""> -->
                        </form>
                    </div>


                </div>
            </div>

        </div>
    </div>
</div>
<!-- Modal order view -->
<div class="modal fade" id="modalorderview" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h6 class="modal-title" id="viewmodaltitle"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-sm small" id="tableorderview">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="d-none">ProductID</th>
                            <th class="text-center"> Qty</th>
                            <th class=""> Free Product</th>
                            <th class="d-none"> Freeproductid</th>
                            <th class="text-center"> Free Qty</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <div class="row">
                    <div class="col-9 text-right">
                        <h5 class="font-weight-600">Subtotal</h5>
                    </div>
                    <div class="col-3 text-right">
                        <h5 class="font-weight-600" id="divsubtotalview">Subtotal: Rs. 0.00</h5>
                    </div>
                    <div class="col-9 text-right">
                        <h5 class="font-weight-600">Discount</h5>
                    </div>
                    <div class="col-3 text-right">
                        <h5 class="font-weight-600" id="divdiscountview">Rs. 0.00</h5>
                    </div>
                    <div class="col-9 text-right">
                        <h1 class="font-weight-600">Nettotal</h1>
                    </div>
                    <div class="col-3 text-right">
                        <h1 class="font-weight-600" id="divtotalview">Rs. 0.00</h1>
                    </div>
                    <div class="col-12">
                        <h6 class="title-style"><span>Remark Information</span></h6>
                    </div>
                    <div class="col-12">
                        <div id="remarkview"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Details Modal -->
<div class="modal fade" id="modelcontactdetails" tabindex="-1" aria-labelledby="modelcontactdetails" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelcontactdetails">Contact Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="contactform" action="process/contactinfoprocess.php" enctype="multipart/form-data"
                            method="post" autocomplete="off">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Contact person*</label>
                                        <input type="text" class="form-control form-control-sm" name="ownername"
                                            id="ownername" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Contact Number*</label>
                                        <input type="text" class="form-control form-control-sm" name="number"
                                            id="number" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Relation*</label>
                                        <input type="text" class="form-control form-control-sm" name="relation"
                                            id="relation" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Email*</label>
                                        <input type="text" class="form-control form-control-sm" name="email" id="email"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mt-2">
                                        <button type="submit" id="submitBtnContact"
                                            class="btn btn-outline-primary btn-sm w-50 fa-pull-right"><i
                                                class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" value="Employee" id="usertype" name="usertype">
                            <input type="hidden" value="0" id="recordID" name="recordID">
                            <input type="hidden" value=<?php echo $record ?> id="record" name="record">
                            <!-- <input type="hidden" value=<?php echo $type ?> id="type" name="type"> -->
                            <input type="hidden" name="recordOptionContact" id="recordOptionContact" value="1">

                            <!--    <input type="text" name="quotationid" id="quotationid" value=""> -->
                        </form>
                    </div>


                </div>
            </div>

        </div>
    </div>
</div>
<?php include "include/footerbar.php"; ?>
</div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $('#dataTable').DataTable();
    $('#dataTablebank').DataTable();
    $('#dataTableTarget').DataTable();
    $(document).ready(function () {
        var addcheck
        var editcheck
        var statuscheck
        var deletecheck

        var userID = $("#recordbank").val();

        $('#dataTable tbody').on('click', '.btnEditContact', function () {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                var type = "Employee"
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id,
                        type: type
                    },
                    url: 'getprocess/getelectritioncontact.php',
                    success: function (result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#ownername').val(obj.name);
                        $('#relation').val(obj.relation);
                        // $('#usertype').val(obj.type);
                        $('#number').val(obj.number);
                        $('#email').val(obj.email);




                        $('#submitBtnContact').html(
                            '<i class="far fa-save"></i>&nbsp;Update');
                        $('#recordOptionContact').val('2');
                        $('#modelcontactdetails').modal('show');
                    }
                });
            }
        });

        $('#dataTablebank tbody').on('click', '.btnEditBank', function () {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                //alert(id)
                var type = "Employee"
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id,
                        type: type
                    },
                    url: 'getprocess/getelectritionbank.php',
                    success: function (result) {
                        // alert(result);
                        var obj = JSON.parse(result);
                        $('#recordIDBank').val(obj.id);
                        $('#bank').val(obj.bankname);
                        $('#branchname').val(obj.branchname);
                        // $('#usertype').val(obj.type);
                        $('#accountnumber').val(obj.accountnumber);
                        $('#accountname').val(obj.accountname);





                        $('#submitBtnbank').html('<i class="far fa-save"></i>&nbsp;Update');
                        $('#recordOptionBank').val('2');
                        $('#modelbankdetails').modal('show');
                    }
                });
            }
        });

        $('#dataTableLocation tbody').on('click', '.btnEditLocation', function () {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                //alert(id)

                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id,
                    },
                    url: 'getprocess/getcustomerlocation.php',
                    success: function (result) {
                        //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordIDLocation').val(obj.id);
                        $('#area').val(obj.idarea);
                        $('#address').val(obj.address);
                        // $('#usertype').val(obj.type);
                        $('#locationowner').val(obj.ownername);
                        $('#locationcontact').val(obj.ownercontact);
                        $('#accountname').val(obj.area);





                        $('#submitBtnLocation').html(
                            '<i class="far fa-save"></i>&nbsp;Update');
                        $('#recordOptionLocation').val('2');
                        $('#modelLocation').modal('show');
                    }
                });
            }
        });

        $('#dataTableInvoice tbody').on('click', '.btnInvoiceView', function () {
            // alert("asd")
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: 'getprocess/getinvoiceprofiledetails.php',
                success: function (result) {
                    alert(result);

                    $('#modalinvoicebody').html(result);
                    $('#modelinvoicedetails').modal('show');
                }
            });

        });


    });

    $('#dataTableCustomerPO').DataTable({
        "destroy": true,
        "processing": true,
        "serverSide": true,
        ajax: {
            url: "scripts/empProfilePOlist.php?recordID=<?php echo $_GET['record'] ?>",
            type: "POST", // you can use GET
        },
        "order": [
            [0, "desc"]
        ],
        "columns": [{
                "data": "idtbl_porder"
            },
            {
                "data": "orderdate"
            },
            {
                "targets": -1,
                "className": '',
                "data": null,
                "render": function (data, type, full) {
                    return 'PO0' + full['idtbl_porder'];
                }
            },
            {
                "data": "area"
            },
            {
                "data": "cusname"
            },
            {
                "targets": -1,
                "className": 'text-right',
                "data": null,
                "render": function (data, type, full) {
                    return parseFloat(full['subtotal']).toFixed(2);
                }
            },
            {
                "targets": -1,
                "className": 'text-right',
                "data": null,
                "render": function (data, type, full) {
                    return parseFloat(full['disamount']).toFixed(2);
                }
            },
            {
                "targets": -1,
                "className": 'text-right',
                "data": null,
                "render": function (data, type, full) {
                    return parseFloat(full['nettotal']).toFixed(2);
                }
            },
            {
                "targets": -1,
                "className": 'text-center',
                "data": null,
                "render": function (data, type, full) {
                    var html = '';
                    if (full['confirmstatus'] == 1) {
                        html += '<i class="fas fa-check text-success"></i>&nbsp;Confirm';
                    } else if (full['confirmstatus'] == 2) {
                        html += '<i class="fas fa-times text-danger"></i>&nbsp;Cancelled';
                    } else {
                        html += '<i class="fas fa-times text-danger"></i>&nbsp;Not Confirm';
                    }
                    return html;
                }
            },
            {
                "targets": -1,
                "className": 'text-center',
                "data": null,
                "render": function (data, type, full) {
                    var html = '';
                    if (full['shipstatus'] == 1) {
                        html += '<i class="fas fa-check text-success"></i>&nbsp;Shipped';
                    } else {
                        html += '<i class="fas fa-times text-danger"></i>&nbsp;Not Shipped';
                    }
                    return html;
                }
            },
            {
                "targets": -1,
                "className": 'text-center',
                "data": null,
                "render": function (data, type, full) {
                    var html = '';
                    if (full['deliverystatus'] == 1) {
                        html += '<i class="fas fa-check text-success"></i>&nbsp;Delivered';
                    } else {
                        html += '<i class="fas fa-times text-danger"></i>&nbsp;Not Delivered';
                    }
                    return html;
                }
            },
            {
                "data": "trackingno"
            },
            {
                "targets": -1,
                "className": 'text-right',
                "data": null,
                "render": function (data, type, full) {
                    var button = '';
                    button += '<button class="btn btn-outline-dark btn-sm btnview mr-1" id="' + full[
                        'idtbl_porder'] + '"><i class="far fa-eye"></i></button>';

                    return button;
                }
            }
        ]
    });

    // Order view part
    $('#dataTableCustomerPO tbody').on('click', '.btnview', function () {
        var id = $(this).attr('id');

        $.ajax({
            type: "POST",
            data: {
                orderID: id
            },
            url: 'getprocess/getcusorderlistaccoorderid.php',
            success: function (result) { //alert(result);
                var obj = JSON.parse(result);

                $('#divsubtotalview').html(obj.subtotal);
                $('#divdiscountview').html(obj.disamount);
                $('#divtotalview').html(obj.nettotalshow);
                $('#remarkview').html(obj.remark);
                $('#viewmodaltitle').html('Order No: PO-' + id);

                var objfirst = obj.tablelist;
                $.each(objfirst, function (i, item) {
                    //alert(objfirst[i].id);

                    $('#tableorderview > tbody:last').append('<tr><td>' + objfirst[i]
                        .productname + '</td><td class="d-none">' + objfirst[i]
                        .productid + '</td><td class="text-center">' + objfirst[i]
                        .newqty + '</td><td class="">' + objfirst[i].freeproduct +
                        '</td><td class="d-none">' + objfirst[i].freeproductid +
                        '</td><td class="text-center">' + objfirst[i].freeqty +
                        '</td><td class="text-right total">' + objfirst[i].total +
                        '</td></tr>');
                });
                $('#modalorderview').modal('show');
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