<?php
include "include/header.php";
include "include/topnavbar.php";

$accounttypesql="SELECT `idtbl_account_type`, `accounttype` FROM `tbl_account_type` WHERE `status`=1";
$resultaccounttype =$conn-> query($accounttypesql); 

$banksql="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1";
$resultbank =$conn-> query($banksql); 

$accountsql = "SELECT `u`.`idtbl_account`, `u`.`account`, `u`.`accountno`, `u`.`status`, `ua`.`accounttype`, `ub`.`bankname` FROM `tbl_account` AS `u` LEFT JOIN `tbl_account_type` AS `ua` ON(`ua`.`idtbl_account_type` = `u`.`tbl_account_type_idtbl_account_type`) LEFT JOIN `tbl_bank` AS `ub` ON(`ub`.`idtbl_bank` = `u`.`tbl_bank_idtbl_bank`) WHERE `u`.`status` IN (1,2)";
$resultaccount = $conn->query($accountsql);

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
                                <span>Account</span>
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
                                <form class="m-2" action="process/accountprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Account Type</label>
                                        <select class="form-control form-control-sm" id="accountType" name="accountType" >
                                            <option value="">Select</option>
                                            <?php while($rowaccountype = $resultaccounttype->fetch_assoc()){ ?>
                                            <option value="<?php echo $rowaccountype['idtbl_account_type'] ?>"><?php echo $rowaccountype['accounttype'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Bank</label>
                                        <select class="form-control form-control-sm" id="bank" name="bank" >
                                            <option value="">Select</option>
                                            <?php while($rowbank = $resultbank->fetch_assoc()){ ?>
                                            <option value="<?php echo $rowbank['idtbl_bank'] ?>"><?php echo $rowbank['bankname'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Account Name</label>
                                        <input class="form-control form-control-sm" type="text" id="accountName" name="accountName" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Account No</label>
                                        <input class="form-control form-control-sm" type="text" id="accountNo" name="accountNo" required>
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
                                                <th>Account</th>
                                                <th>Account No</th>
                                                <th>Account Type</th>
                                                <th>Bank</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($resultaccount->num_rows > 0){ while($row = $resultaccount->fetch_assoc()){ ?>
                                            <tr>
                                                <td><?php echo $row['idtbl_account']; ?></td>
                                                <td><?php echo $row['account']; ?></td>
                                                <td><?php echo $row['accountno']; ?></td>
                                                <td><?php echo $row['accounttype']; ?></td>
                                                <td><?php echo $row['bankname']; ?></td>
                                                <td class="text-right">
                                                <?php if($editcheck==1){ ?>
                                                <button
                                                    class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>"
                                                    id="<?php echo $row['idtbl_account'] ?>"><i
                                                        data-feather="edit-2"></i></button>
                                                <?php } if($statuscheck==1 && $row['status']==1){ ?>
                                                <button
                                                    data-url="process/statusaccount.php?record=<?php echo $row['idtbl_account'] ?>&type=2"
                                                    data-actiontype="2"
                                                    class="btn btn-outline-success btn-sm btntableaction"><i
                                                        data-feather="check"></i></button>
                                                <?php } else if($statuscheck==1 && $row['status']==2){ ?>
                                                <button
                                                    data-url="process/statusaccount.php?record=<?php echo $row['idtbl_account'] ?>&type=1"
                                                    data-actiontype="1"
                                                    class="btn btn-outline-warning btn-sm btntableaction"><i
                                                        data-feather="x-square"></i></button>
                                                <?php } if($deletecheck==1){ ?>
                                                <button
                                                    data-url="process/statusaccount.php?record=<?php echo $row['idtbl_account'] ?>&type=3"
                                                    data-actiontype="3"
                                                    class="btn btn-outline-danger btn-sm btntableaction"><i
                                                        data-feather="trash-2"></i></button>
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
        $('#dataTable').DataTable();
        $('#dataTable tbody').on('click', '.btnEdit', async function () {
            var r = await Otherconfirmation("You want to edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: 'getprocess/getaccount.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);

                        $('#recordID').val(obj.id);
                        $('#accountType').val(obj.acountType);
                        $('#bank').val(obj.bank);
                        $('#accountName').val(obj.accountName);
                        $('#accountNo').val(obj.accountNo);
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
