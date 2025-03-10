<?php 
include "include/header.php";  

// $sql="SELECT * FROM `tbl_user` WHERE `status` IN (1,2) AND `idtbl_user`!=1";
// $result =$conn-> query($sql); 

$record=$_GET['record'];
$type=$_GET['type'];
$persontype = "Customer";
$sqlcustomer="SELECT * FROM `tbl_contact_details` WHERE `person_id` = '$record' AND `status` in (1,2) and `type` = 'Customer'";
$resultcustomer=$conn->query($sqlcustomer);


$sqlcustomerdetails="SELECT `email`, `name`, `phone` FROM `tbl_customer` WHERE `idtbl_customer` = '$record'";
$resultcustomerdetails =$conn-> query($sqlcustomerdetails); 
$resultpoints = $resultcustomerdetails-> fetch_assoc();


$sqlcustomerlocation="SELECT `cl`.`idtbl_customer_location`, `cl`.`address`, `cl`.`ownername`, `cl`.`ownercontact`, `a`.`area` FROM `tbl_customer_location` as `cl` JOIN `tbl_customer` as `c` ON (`c`.`idtbl_customer` = `cl`.`tbl_customer_idtbl_customer`) JOIN `tbl_area` as `a` ON (`a`.`idtbl_area` = `cl`.`tbl_area_idtbl_area`) WHERE `cl`.`tbl_customer_idtbl_customer` = '$record' and `cl`.`status` in ('1','2')";
$resultcustomerlocation =$conn-> query($sqlcustomerlocation); 


$sqlinvoice="SELECT * FROM `tbl_invoice`  WHERE `tbl_customer_idtbl_customer` = '$record' and `status` in ('1','2')";
$resultInvoice =$conn-> query($sqlinvoice); 


$sqlpayment="SELECT `tbl_invoice_payment_has_tbl_invoice`.`payamount`, `tbl_invoice`.`total`, `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`updatedatetime`,`tbl_invoice`.`paymentcomplete` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` WHERE `tbl_invoice`.`tbl_customer_idtbl_customer`='$record' AND `tbl_invoice`.`status`=1";

$resultpayment =$conn-> query($sqlpayment); 



$sqlBank="SELECT * FROM `tbl_bank` WHERE `status` in ('1')";
$resultBank=$conn->query($sqlBank);

$sqlArea="SELECT * FROM `tbl_area` WHERE `status` in ('1')";
$resultArea=$conn->query($sqlArea);

$sqlCustomerBank="SELECT `b`.`bankname`, `b`.`code`,`a`.`branchname`,`a`.`accountnumber`, `a`.`status`, `a`.`idtbl_bank_account_details`, `a`.`account_name` FROM `tbl_bank` as `b` JOIN `tbl_bank_account_details` AS `a` ON (`a`.`tbl_bank_idtbl_bank` = `b`.`idtbl_bank`) WHERE `a`.`status` in ('1') AND `a`.`person_id` = '$record' and `a`.`type` = 'Customer'";
$resultCustomerBank=$conn->query($sqlCustomerBank);

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
                            <span><?php echo $resultpoints['name'] ?>'s Profile</span>
                        </h1>
                    </div>
                </div>
                <input type="hidden" id='hiddencustomerId' value="<?php echo $record; ?>">
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
                                                class="font-weight-bold"><?php echo $resultpoints['name']; ?></span><span
                                                class="text-black-50"><?php  echo $resultpoints['phone'] ?></span><span><?php echo $resultpoints['email']; ?>
                                            </span><span> <button type="button"
                                                    class="btn btn-outline-primary btn-sm fa-pull-right ml-2"
                                                    id="btnImgView"><i
                                                        class="fas fa-image"></i>&nbsp;View</button></span></div>
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
                                                    aria-selected="false">Delivery Locations</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="invoice-tab" data-toggle="tab" href="#invoice"
                                                    role="tab" aria-controls="invoice" aria-selected="false">Invoice</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment"
                                                    role="tab" aria-controls="payment" aria-selected="false">Payment</a>
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
                                                                        <?php if($resultcustomer->num_rows > 0) {while ($row = $resultcustomer-> fetch_assoc()) { ?>
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
                                                                                <button
                                                                                    data-url="process/statuscontactdetails.php?record=<?php echo $row['idtbl_contact_details'] ?>&eledID=<?php echo $record ?>&type=<?php echo $persontype ?>"
                                                                                    data-actiontype="3"
                                                                                    class="btn btn-outline-danger btn-sm btntableaction"><i
                                                                                        data-feather="trash-2"></i></button>
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
                                                                        <?php if($resultCustomerBank->num_rows > 0) {while ($row = $resultCustomerBank-> fetch_assoc()) { ?>
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
                                                    <div class="row">
                                                        <div class="col-md-12 text-right">
                                                            <button class="btn btn-outline-primary btn-sm "
                                                                data-toggle="modal" data-target="#modelLocation">
                                                                <i class="far fa-save"></i>&nbsp;Add Location
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="scrollbar pb-3" id="style-2">
                                                                <table
                                                                    class="table table-bordered table-striped table-sm nowrap"
                                                                    id="dataTableLocation">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>City</th>
                                                                            <th>Address</th>
                                                                            <th>Owner Name</th>
                                                                            <th>Owner Contact</th>
                                                                            <th>Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if($resultcustomerlocation->num_rows > 0) {while ($row = $resultcustomerlocation-> fetch_assoc()) { ?>
                                                                        <tr>
                                                                            <td><?php echo $row['idtbl_customer_location'] ?>
                                                                            </td>
                                                                            <td><?php echo $row['area'] ?></td>
                                                                            <td><?php echo $row['address'] ?></td>
                                                                            <td><?php echo $row['ownername'] ?></td>
                                                                            <td><?php echo $row['ownercontact'] ?></td>
                                                                            <td class="text-right">
                                                                                <button
                                                                                    class="btn btn-outline-primary btn-sm btnEditLocation mr-1 "
                                                                                    id="<?php echo $row['idtbl_customer_location'] ?>"><i
                                                                                        class=" fas fa-edit"></i></button>


                                                                                        <button
                                                                                    data-url="process/statuslocationdetails.php?record=<?php echo $row['idtbl_customer_location'] ?>&customerID=<?php echo $record ?>"
                                                                                    data-actiontype="3"
                                                                                    class="btn btn-outline-danger btn-sm btntableaction"><i
                                                                                        data-feather="trash-2"></i></button>
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
                                            <div class="tab-pane fade" id="invoice" role="tabpanel"
                                                aria-labelledby="invoice-tab">
                                                <div class="inputrow">

                                                    <br>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="scrollbar pb-3" id="style-2">
                                                                <table
                                                                    class="table table-bordered table-striped table-sm nowrap"
                                                                    id="dataTableInvoice">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <th>Date</th>
                                                                            <th>Total</th>
                                                                            <th>Payment Completed</th>
                                                                            <th>Ref ID</th>
                                                                            <!-- <th>Actions</th> -->

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if($resultInvoice->num_rows > 0) {while ($row = $resultInvoice-> fetch_assoc()) { ?>
                                                                        <tr>
                                                                            <td><?php echo $row['idtbl_invoice'] ?></td>
                                                                            <td><?php echo $row['date'] ?></td>
                                                                            <td class="text-right">
                                                                                Rs.<?php echo $row['total'] ?>.00</td>
                                                                            <td><?php if($row['paymentcomplete']==0){ ?>
                                                                                No
                                                                                <?php }elseif($row['paymentcomplete']==1){ ?>
                                                                                Yes
                                                                                <?php } ?>
                                                                            </td>
                                                                            <td><?php echo $row['ref_id'] ?></td>
                                                                            <!-- <td class="text-right">
                                                                                <button
                                                                                    class="btn btn-outline-primary btn-sm btnInvoiceView mr-1 "
                                                                                    id="<?php echo $row['idtbl_invoice'] ?>"><i
                                                                                        class=" fas fa-eye"></i></button>
                                                                            </td> -->


                                                                        </tr>
                                                                        <?php }} ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="tab-pane fade" id="payment" role="tabpanel"
                                                aria-labelledby="payment-tab">
                                                <div class="inputrow">

                                                    <br>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="scrollbar pb-3" id="style-2">
                                                                <table
                                                                    class="table table-bordered table-striped table-sm nowrap"
                                                                    id="dataTableInvoice">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Invoice</th>
                                                                            <th>Total</th>
                                                                            <th>Payment</th>
                                                                            <th>Balance</th>
                                                                            <th>Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php if($resultpayment->num_rows > 0) {while ($row = $resultpayment-> fetch_assoc()) { ?>
                                                                        <tr>
                                                                            <td>INV_<?php echo $row['idtbl_invoice'] ?>
                                                                            </td>
                                                                            <td class="text-right">
                                                                                <?php echo number_format($row['total'],2)  ?>
                                                                            </td>
                                                                            <td class="text-right">
                                                                                <?php echo number_format($row['payamount'],2) ?>
                                                                            </td>
                                                                            <td class="text-right">
                                                                                <?php echo  number_format($row['total']-$row['paymentcomplete'],2) ?>
                                                                            </td>
                                                                            <td class="text-right">
                                                                                <?php echo $row['updatedatetime'] ?>
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

                            <input type="hidden" value="Customer" id="usertypebank" name="usertypebank">
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
<!-- Location Details Modal -->
<div class="modal fade" id="modelLocation" tabindex="-1" aria-labelledby="modelLocation" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modelLocation">Location Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="contactform" action="process/locationinfoprocess.php" enctype="multipart/form-data"
                            method="post" autocomplete="off">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Area*</label>
                                        <select class="form-control form-control-sm" name="area" id="area" required>
                                            <option value="">Select</option>
                                            <?php if($resultArea->num_rows > 0) {while ($rowcategory = $resultArea-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowcategory['idtbl_area'] ?>">
                                                <?php echo $rowcategory['area'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Address</label>
                                        <textarea class="form-control form-control-sm" id="address"
                                            name="address"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Owner Name*</label>
                                        <input type="text" class="form-control form-control-sm" name="locationowner"
                                            id="locationowner" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Owner Contact*</label>
                                        <input type="text" class="form-control form-control-sm" name="locationcontact"
                                            id="locationcontact" required>
                                    </div>
                                </div>
                            </div>




                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mt-2">
                                        <button type="submit" id="submitBtnLocation"
                                            class="btn btn-outline-primary btn-sm w-50 fa-pull-right"><i
                                                class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" value=<?php echo $record ?> id="customerID" name="customerID">
                            <input type="hidden" value="0" id="recordIDLocation" name="recordIDLocation">
                            <!-- <input type="hidden" value=<?php echo $type ?> id="typebank" name="typebank"> -->
                            <input type="hidden" name="recordOptionLocation" id="recordOptionLocation" value="1">
                            <!--    <input type="text" name="quotationid" id="quotationid" value=""> -->
                        </form>
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

                            <input type="hidden" value="Customer" id="usertype" name="usertype">
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
<!-- Modal Image View -->
<div class="modal fade" id="modalimageview" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div id="imagelist" class=""></div>
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
    $(document).ready(function () {
        var addcheck
        var editcheck
        var statuscheck
        var deletecheck

        $('#btnImgView').click(function () {
            var id = $('#hiddencustomerId').val();
            loadlistimages(id);
            $('#modalimageview').modal('show');
        })
        $('#dataTable tbody').on('click', '.btnEditContact', function () {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                var type = "Customer"
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

        function loadlistimages(customerId) {
            $('#imagelist').addClass('text-center');
            $('#imagelist').html('<img src="images/spinner.gif" class="img-fluid">');

            $.ajax({
                type: "POST",
                data: {
                    customerId: customerId
                },
                url: 'getprocess/getCustomerListImages.php',
                success: function (result) { //alert(result);
                    $('#imagelist').html(result);
                }
            });
        }

        $('#dataTablebank tbody').on('click', '.btnEditBank', function () {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                //alert(id)
                var type = "Customer"
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





                        $('#submitBtnbank').html(
                            '<i class="far fa-save"></i>&nbsp;Update');
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
