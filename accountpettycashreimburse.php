<?php
include "include/header.php";
include "include/topnavbar.php";

$accountsql="SELECT `idtbl_account`, `account`, `accountno` FROM `tbl_account` WHERE `status`=1 AND `tbl_account_type_idtbl_account_type` = 3";
$resultaccount =$conn-> query($accountsql); 

$banksql="SELECT `u`.`tbl_bank_idtbl_bank`, `ua`.`bankname` FROM `tbl_account` AS `u` LEFT JOIN `tbl_bank` AS `ua` ON (`ua`.`idtbl_bank` = `u`.`tbl_bank_idtbl_bank`) WHERE `u`.`status`=1 AND `u`.`tbl_account_type_idtbl_account_type` = 1 GROUP BY `u`.`tbl_account_type_idtbl_account_type`";
$resultbank =$conn-> query($banksql); 

$pettycashaccountsql="SELECT `u`.`idtbl_pettycash_reimburse`, `u`.`amount`, `u`.`status`, CONCAT(`ua`.`account`, ' - ',`ua`.`accountno`) AS `petty_cash_account`, `ub`.`bankname`, CONCAT(`uc`.`account`, ' - ', `uc`.`accountno`) AS `bank_account` FROM `tbl_pettycash_reimburse` AS `u` LEFT JOIN `tbl_account` AS `ua` ON (`ua`.`idtbl_account` = `u`.`tbl_account_petty_cash_account`) LEFT JOIN `tbl_bank` AS `ub` ON (`ub`.`idtbl_bank` = `u`.`tbl_bank_idtbl_bank`) LEFT JOIN `tbl_account` AS `uc` ON (`uc`.`idtbl_account` = `u`.`tbl_account_bank_account`)";
$resultpettycashaccount =$conn-> query($pettycashaccountsql);

?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div
                        class="page-header-content d-md-flex text-right align-items-center justify-content-between py-3">
                        <div class="d-inline">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i class="fas fa-university"></i></div>
                                <span>Petty Cash Reimburse</span>
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
                                <form class="m-2" action="process/accountpettycashreimburseprocess.php" method="post"
                                    autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Petty Cash Account</label>
                                        <select class="form-control form-control-sm" id="pettyCashAccount"
                                            name="pettyCashAccount" required>
                                            <option value="">Select</option>
                                            <?php while($rowaccount = $resultaccount->fetch_assoc()){ ?>
                                            <option value="<?php echo $rowaccount['idtbl_account'] ?>">
                                                <?php echo $rowaccount['account'].' - '.$rowaccount['accountno'] ?>
                                            </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Bank</label>
                                        <select class="form-control form-control-sm" id="bank" name="bank" required>
                                            <option value="">Select</option>
                                            <?php while($rowbank = $resultbank->fetch_assoc()){ ?>
                                            <option value="<?php echo $rowbank['tbl_bank_idtbl_bank'] ?>">
                                                <?php echo $rowbank['bankname'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Bank Account</label>
                                        <select class="form-control form-control-sm" id="bankAccount" name="bankAccount"
                                            required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Amount</label>
                                        <input class="form-control form-control-sm input-integer-decimal" type="text"
                                            id="amount" name="amount" required>
                                    </div>
                                    <div class="form-group mt-3">
                                        <button type="submit" id="submitBtn"
                                            class="btn btn-outline-primary btn-sm px-4 fa-pull-right"
                                            <?php if($addcheck==0){echo 'disabled';} ?>><i
                                                class="far fa-save"></i>&nbsp;Add</button>
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
                                                <th>Bank</th>
                                                <th>Bank Account</th>
                                                <th>Amount</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>statusaccountpettycashreimburse
                                            <?php if($resultpettycashaccount->num_rows > 0){ while($row = $resultpettycashaccount->fetch_assoc()){ ?>
                                            <tr <?php if($row['status']==3){?>
                                                style="background-color:darkred; color:white;" <?php }?>>
                                                <td><?php echo $row['idtbl_pettycash_reimburse']; ?></td>
                                                <td><?php echo $row['petty_cash_account']; ?></td>
                                                <td><?php echo $row['bankname']; ?></td>
                                                <td><?php echo $row['bank_account']; ?></td>
                                                <td><?php echo number_format($row['amount'], 2);?></td>
                                                <td class="text-right">
                                                    <?php if($row['status']==3){ ?>
                                                    <span>Cancelled</span>
                                                    <?php }else{ ?>
                                                    <button
                                                        data-url="process/statusaccountpettycashreimburse.php?record=<?php echo $row['idtbl_pettycash_reimburse'] ?>&type=3"
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
    $(document).ready(function () {

        $('.input-integer-decimal').inputNumber({
            maxDecimalDigits: 2
        });
        $('#dataTable').DataTable();

        $('#bank').change(function () {
            var bankID = $(this).val();
            $.ajax({
                type: "POST",
                data: {
                    recordID: bankID
                },
                url: 'getprocess/getbankaccount.php',
                success: function (result) { //alert(result);
                    var obj = JSON.parse(result);
                    $('#bankAccount').empty();
                    $('#bankAccount').append('<option value="">Select</option>');

                    $.each(obj, function (index, item) {
                        $('#bankAccount').append($('<option>', {
                            value: item.idtbl_account,
                            text: item.account + ' - ' + item.accountno
                        }));
                    });

                    $('#bank').trigger('bankAccountLoaded');
                }
            });
        });
    });
</script>
<?php
// specify optional header includes in this array.
$optional_footer_includes = ['magnific-popup'];

include "include/footer.php";
?>
