<?php 
include "include/header.php";  

$sql="SELECT `tbl_paymenttype`.`paymenttype`, `tbl_bank`.`bankname`, `tbl_bank_branch`.`branchname`, `tbl_bank_account`.`accountno`, `tbl_bank_account`.`idtbl_bank_account`, `tbl_bank_account`.`status` FROM `tbl_bank_account` LEFT JOIN `tbl_paymenttype` ON `tbl_paymenttype`.`idtbl_paymenttype`=`tbl_bank_account`.`tbl_paymenttype_idtbl_paymenttype` LEFT JOIN `tbl_bank` ON `tbl_bank`.`idtbl_bank`=`tbl_bank_account`.`tbl_bank_idtbl_bank` LEFT JOIN `tbl_bank_branch` ON `tbl_bank_branch`.`idtbl_bank_branch`=`tbl_bank_account`.`tbl_bank_branch_idtbl_bank_branch` WHERE `tbl_bank_account`.`status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlbank="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`>1";
$resultbank=$conn->query($sqlbank);

$sqlpaymenttype="SELECT `idtbl_paymenttype`, `paymenttype` FROM `tbl_paymenttype` WHERE `status`=1";
$resultpaymenttype=$conn->query($sqlpaymenttype);

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
                            <div class="page-header-icon"><i data-feather="dollar-sign"></i></div>
                            <span>Bank Account</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="process/bankaccountprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Bank*</label>
                                        <select class="form-control form-control-sm" name="bank" id="bank" required>
                                            <option value="">Select</option>
                                            <?php if($resultbank->num_rows > 0) {while ($rowbank = $resultbank-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowbank['idtbl_bank'] ?>"><?php echo $rowbank['bankname'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Bank Branch*</label>
                                        <select class="form-control form-control-sm" name="bankbranch" id="bankbranch" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Payment Type*</label>
                                        <select class="form-control form-control-sm" name="paymenttype" id="paymenttype" required>
                                            <option value="">Select</option>
                                            <?php if($resultpaymenttype->num_rows > 0) {while ($rowpaymenttype = $resultpaymenttype-> fetch_assoc()) { ?>
                                            <option value="<?php echo $rowpaymenttype['idtbl_paymenttype'] ?>"><?php echo $rowpaymenttype['paymenttype'] ?></option>
                                            <?php }} ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Acoount No*</label>
                                        <input type="text" class="form-control form-control-sm" name="accountno" id="accountno" required>
                                    </div>
                                    <div class="form-group mt-3">
                                        <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm px-4 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
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
                                            <th>Account No</th>
                                            <th>Bank</th>
                                            <th>Branch</th>
                                            <th>Payment Type</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($result->num_rows > 0) {while ($row = $result-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['idtbl_bank_account'] ?></td>
                                            <td><?php echo $row['accountno'] ?></td>
                                            <td><?php echo $row['bankname'] ?></td>
                                            <td><?php echo $row['branchname'] ?></td>
                                            <td><?php echo $row['paymenttype'] ?></td>
                                            <td class="text-right">
                                                <button class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>" id="<?php echo $row['idtbl_bank_account'] ?>"><i data-feather="edit-2"></i></button>
                                                <?php if($row['status']==1){ ?>
                                                <a href="process/statusbankaccount.php?record=<?php echo $row['idtbl_bank_account'] ?>&type=2" onclick="return confirm('Are you sure you want to deactive this?');" target="_self" class="btn btn-outline-success btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="check"></i></a>
                                                <?php }else{ ?>
                                                <a href="process/statusbankaccount.php?record=<?php echo $row['idtbl_bank_account'] ?>&type=1" onclick="return confirm('Are you sure you want to active this?');" target="_self" class="btn btn-outline-warning btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="x-square"></i></a>
                                                <?php } ?>
                                                <a href="process/statusbankaccount.php?record=<?php echo $row['idtbl_bank_account'] ?>&type=3" onclick="return confirm('Are you sure you want to remove this?');" target="_self" class="btn btn-outline-danger btn-sm <?php if($deletecheck==0){echo 'd-none';} ?>"><i data-feather="trash-2"></i></a>
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
        $('#dataTable').DataTable();
        $('#dataTable tbody').on('click', '.btnEdit', function() {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getbankaccount.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#accountno').val(obj.accountno);                       
                        $('#bank').val(obj.bank);                       
                        $('#bankbranch').val(obj.bankbranch);                       
                        $('#paymenttype').val(obj.paymenttype);  

                        bankbranch(obj.bank, obj.bankbranch);              

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });

        $('#bank').change(function(){
            var bank = $(this).val();

            bankbranch(bank, '');
        });
    });

    function bankbranch(bank, branch){
        $.ajax({
            type: "POST",
            data: {
                bank: bank
            },
            url: 'getprocess/getbankbranchaccobank.php',
            success: function(result) { 
                var objfirst = JSON.parse(result);
                var html = '';
                html += '<option value="">Select</option>';
                $.each(objfirst, function(i, item) {
                    //alert(objfirst[i].id);
                    html += '<option value="' + objfirst[i].branchid + '">';
                    html += objfirst[i].branch;
                    html += '</option>';
                });

                $('#bankbranch').empty().append(html);

                if(branch!=''){
                    $('#bankbranch').val(branch);
                }
            }
        });
    }

</script>
<?php include "include/footer.php"; ?>
