<?php
include "include/header.php";  

$sql="SELECT `tbl_subaccount`.*, `tbl_mainclass`.`class`, `tbl_subclass`.`subclass`, `tbl_mainaccount`.`accountname`, `tbl_account_category`.`category` FROM `tbl_subaccount` LEFT JOIN `tbl_mainclass` ON `tbl_mainclass`.`code`=`tbl_subaccount`.`mainclasscode` LEFT JOIN `tbl_subclass` ON `tbl_subclass`.`code`=`tbl_subaccount`.`subclasscode` LEFT JOIN `tbl_mainaccount` ON `tbl_mainaccount`.`code`=`tbl_subaccount`.`mainaccountcode` LEFT JOIN `tbl_account_category` ON `tbl_account_category`.`idtbl_account_category`=`tbl_subaccount`.`tbl_account_category_idtbl_account_category` WHERE `tbl_subaccount`.`status` IN (1,2)";
$result =$conn-> query($sql); 

$sqlmainclass="SELECT `code`, `class` FROM `tbl_mainclass` WHERE `status` IN (1,2)";
$resultmainclass =$conn-> query($sqlmainclass); 

$sqlaccountcate="SELECT `idtbl_account_category`, `category` FROM `tbl_account_category` WHERE `status`=1";
$resultaccountcate =$conn-> query($sqlaccountcate); 

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
                    <div class="page-header-content text-center d-md-flex align-items-center justify-content-between py-3">
                        <div class="d-inline">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="server"></i></div>
                                <span>Account - Sub Account</span>
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
                                <form class="" action="process/accountsubaccountprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Main Class</label>
                                        <select class="form-control form-control-sm" name="mainclass" id="mainclass" required>
                                            <option value="">Select</option>
                                            <?php while($rowmainclass = $resultmainclass->fetch_assoc()){ ?>
                                            <option value="<?php echo $rowmainclass['code'] ?>"><?php echo $rowmainclass['class'].' - '.$rowmainclass['code'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Sub Class</label>
                                        <select class="form-control form-control-sm" name="subclass" id="subclass" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Main Account</label>
                                        <select class="form-control form-control-sm" name="mainaccount" id="mainaccount" required>
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Code</label>
                                        <input class="form-control form-control-sm" type="text" name="code" id="code" minlength="4" maxlength="4" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Sub Account Name</label>
                                        <input class="form-control form-control-sm" type="text" id="subaccount" name="subaccount" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Account Category</label>
                                        <select name="accountcategory" id="accountcategory" class="form-control form-control-sm">
                                            <option value="">Select</option>
                                            <?php while($rowaccountcate = $resultaccountcate->fetch_assoc()){ ?>
                                            <option value="<?php echo $rowaccountcate['idtbl_account_category'] ?>"><?php echo $rowaccountcate['category'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group mt-3">
                                        <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm w-50 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-md-9">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table id="dataTable" class="table table-sm w-100 table-bordered table-striped nowrap">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Main Class</th>
                                                <th>Sub Class</th>
                                                <th>Main Account</th>
                                                <th>Code</th>
                                                <th>Sub Account</th>
                                                <th>Account No</th>
                                                <th>Account Category</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if($result->num_rows > 0){while($row = $result->fetch_assoc()){ ?>
                                            <tr>
                                                <td><?php echo $row['idtbl_subaccount']; ?></td>
                                                <td><?php echo $row['class'].' - '.$row['mainclasscode']; ?></td>
                                                <td><?php echo $row['subclass'].' - '.$row['subclasscode']; ?></td>
                                                <td><?php echo $row['accountname'].' - '.$row['mainaccountcode']; ?></td>
                                                <td><?php echo $row['code']; ?></td>
                                                <td><?php echo $row['subaccountname']; ?></td>
                                                <td><?php echo $row['subaccount']; ?></td>
                                                <td><?php echo $row['category']; ?></td>
                                                <td class="text-right">
                                                    <button class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>" id="<?php echo $row['idtbl_subaccount'] ?>"><i data-feather="edit-2"></i></button>
                                                    <?php if($row['status']==1){ ?>
                                                        <a href="process/statussubaccount.php?record=<?php echo $row['idtbl_subaccount'] ?>&type=2" onclick="return confirm('Are you sure you want to deactive this?');" target="_self" class="btn btn-outline-success btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="check"></i></a>
                                                    <?php }else{ ?>
                                                        <a href="process/statussubaccount.php?record=<?php echo $row['idtbl_subaccount'] ?>&type=1" onclick="return confirm('Are you sure you want to active this?');" target="_self" class="btn btn-outline-warning btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="x-square"></i></a>
                                                    <?php } ?>
                                                    <a href="process/statussubaccount.php?record=<?php echo $row['idtbl_subaccount'] ?>&type=3" onclick="return confirm('Are you sure you want to remove this?');" target="_self" class="btn btn-outline-danger btn-sm <?php if($deletecheck==0){echo 'd-none';} ?>"><i data-feather="trash-2"></i></a>
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
                    url: 'getprocess/getsubaccount.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);

                        $('#recordID').val(obj.id);
                        $('#code').val(obj.code);
                        $('#subaccount').val(obj.subaccountname);
                        $('#accountcategory').val(obj.accoutcategory);
                        $('#mainclass').val(obj.mainclasscode);
                        
                        subclass(obj.mainclasscode, obj.subclasscode);
                        mainaccount(obj.mainclasscode, obj.subclasscode, obj.mainaccountcode);

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');


                    }
                });
            }
        });

        $('#mainclass').change(function(){
            var mainclass=$(this).val();
            subclass(mainclass, '');
        });

        $('#subclass').change(function(){
            var mainclass=$('#mainclass').val();
            var subclass=$(this).val();
            mainaccount(mainclass, subclass, '');
        });
    });

    function subclass(mainclass, value){
        $.ajax({
            type: "POST",
            data: {
                mainclass: mainclass
            },
            url: 'getprocess/getsubclassaccomainclass.php',
            success: function(result) { //alert(result);
                var objfirst = JSON.parse(result);

                var html = '';
                html += '<option value="">Select</option>';
                $.each(objfirst, function(i, item) {
                    //alert(objfirst[i].id);
                    html += '<option value="' + objfirst[i].code + '">';
                    html += objfirst[i].subclass+' - '+objfirst[i].code;
                    html += '</option>';
                });

                $('#subclass').empty().append(html);

                if(value!=''){
                    $('#subclass').val(value);
                }
            }
        });
    }

    function mainaccount(mainclass, subclass, value){
        $.ajax({
            type: "POST",
            data: {
                mainclass: mainclass,
                subclass: subclass
            },
            url: 'getprocess/getmainaccountaccosubmainclass.php',
            success: function(result) { //alert(result);
                var objfirst = JSON.parse(result);

                var html = '';
                html += '<option value="">Select</option>';
                $.each(objfirst, function(i, item) {
                    //alert(objfirst[i].id);
                    html += '<option value="' + objfirst[i].code + '">';
                    html += objfirst[i].accountname+' - '+objfirst[i].code;
                    html += '</option>';
                });

                $('#mainaccount').empty().append(html);

                if(value!=''){
                    $('#mainaccount').val(value);
                }
            }
        });
    }
</script>
<?php include "include/footer.php";?>
