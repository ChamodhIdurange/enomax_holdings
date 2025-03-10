<?php
include "include/header.php";
include "include/topnavbar.php";

$pettycashaccountsql="SELECT `idtbl_account`, `account`, `accountno` FROM `tbl_account` WHERE `status`=1 AND `tbl_account_type_idtbl_account_type` = 3";
$resultpettycashaccount =$conn-> query($pettycashaccountsql); 

$expensesaccountsql="SELECT `idtbl_account`, `account`, `accountno` FROM `tbl_account` WHERE `status`=1 AND `tbl_account_type_idtbl_account_type` = 2";
$resultexpensesaccount =$conn-> query($expensesaccountsql);

$pettycashexpensesccountsql="SELECT `u`.`idtbl_pettycash_expenses`, `u`.`amount`, `u`.`narration`, `u`.`status`, CONCAT(`ua`.`account`, ' - ',`ua`.`accountno`) AS `petty_cash_account`, CONCAT(`ub`.`account`, ' - ', `ub`.`accountno`) AS `expenses_account` FROM `tbl_pettycash_expenses` AS `u` LEFT JOIN `tbl_account` AS `ua` ON (`ua`.`idtbl_account` = `u`.`tbl_account_petty_cash_account`) LEFT JOIN `tbl_account` AS `ub` ON (`ub`.`idtbl_account` = `u`.`tbl_account_expenses_account`)";
$resultpettycashexpensesaccount =$conn-> query($pettycashexpensesccountsql);

?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content d-md-flex text-right align-items-center justify-content-between py-3">
                        <div class="d-inline">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i class="fas fa-university"></i></div>
                                <span>Petty Cash Expenses</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-md-3">
                                <form class="m-2" action="process/accountpettycashexpensesprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Petty Cash Account</label>
                                        <select class="form-control form-control-sm" id="pettyCashAccount" name="pettyCashAccount" required>
                                            <option value="">Select</option>
                                            <?php while($row = $resultpettycashaccount->fetch_assoc()){ ?>
                                            <option value="<?php echo $row['idtbl_account'] ?>"><?php echo $row['account'].' - '.$row['accountno'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Available Balance</label>
                                        <input class="form-control form-control-sm" type="text" id="availableBalance" name="availableBalance" readonly>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Expenses Account</label>
                                        <select class="form-control form-control-sm" id="expensesAccount" name="expensesAccount" required>
                                            <option value="">Select</option>
                                            <?php while($row = $resultexpensesaccount->fetch_assoc()){ ?>
                                            <option value="<?php echo $row['idtbl_account'] ?>"><?php echo $row['account'].' - '.$row['accountno'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Amount</label>
                                        <input class="form-control form-control-sm input-integer-decimal" type="text" id="amount" name="amount" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Narration</label>
                                        <input class="form-control form-control-sm" type="text" id="narration" name="narration">
                                    </div>
                                    <div class="form-group mt-3">
                                        <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm px-4 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-md-9">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table id="dataTable" class="table table-sm w-100 table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Petty Cash Account</th>
                                                <th>Expenses Account</th>
                                                <th>Amount</th>
                                                <th>Narration</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($resultpettycashexpensesaccount->num_rows > 0){ while($row = $resultpettycashexpensesaccount->fetch_assoc()){ ?>
                                            <tr <?php if($row['status']==3){?> style="background-color:darkred; color:white;" <?php }?>>
                                                <td><?php echo $row['idtbl_pettycash_expenses']; ?></td>
                                                <td><?php echo $row['petty_cash_account']; ?></td>
                                                <td><?php echo $row['expenses_account']; ?></td>
                                                <td><?php echo number_format($row['amount'], 2);?></td>
                                                <td><?php echo $row['narration']; ?></td>
                                                <td class="text-right">
                                                    <?php if($row['status']==3){ ?>
                                                        <span>Cancelled</span>
                                                    <?php }else{ ?>
                                                        <button
                                                    data-url="process/statusaccountpettycashexpenses.php?record=<?php echo $row['idtbl_pettycash_expenses'] ?>&type=3"
                                                    data-actiontype="3"
                                                    class="btn btn-outline-danger btn-sm btntableaction"><i
                                                        data-feather="x"></i></button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php }}?>
                                        </tbody>
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
$(document).ready(function() {

    $('.input-integer-decimal').inputNumber({
        maxDecimalDigits: 2
    });

    $('#dataTable').DataTable();

    $('#pettyCashAccount').change(function(){
        var pettyCashAccountID = $(this).val();
        $.ajax({
            type: "POST",
            data: {
                recordID: pettyCashAccountID
            },
            url: 'getprocess/getpettycashaccountbalance.php',
            success: function(result) { //alert(result);
                var obj = JSON.parse(result);
                $('#availableBalance').val(obj.balance);
                checkBalance();
            }
        });
    });

    $('#amount').keyup(function(){
        checkBalance();
    });

    function checkBalance() {
        var amount = parseFloat($('#amount').val());
        var availableBalance = parseFloat($('#availableBalance').val());
        if (amount > availableBalance) {
            alert('Amount exceeds available balance.');
            $('#amount').val('');
        }
    }
});

</script>
<?php
// specify optional header includes in this array.
$optional_footer_includes = ['magnific-popup'];

include "include/footer.php";
?>
