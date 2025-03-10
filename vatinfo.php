<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_vat_info` WHERE `status` IN (1,2)";
$result =$conn-> query($sql); 

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
                            <div class="page-header-icon"><i class="fas fa-dollar-sign"></i></div>
                            <span>VAT Info</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="process/vatinfoprocess.php" method="post" autocomplete="off">
                                <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Date From*</label>
                                            <div class="input-group input-group-sm">
                                                <input type="date" id="fromdate" name="fromdate" class="form-control form-control-sm" value="<?php echo date('Y-m-d') ?>" required>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-dark">VAT*</label>
                                        <input type="text" class="form-control form-control-sm" name="vat" id="vat" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-dark">NBT</label>
                                        <input type="text" class="form-control form-control-sm" name="nbt" id="nbt">
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-dark">S VAT</label>
                                        <input type="text" class="form-control form-control-sm" name="s_vat" id="s_vat">
                                    </div>
                                    <div class="form-group mt-2">
                                        <button type="submit" id="submitBtn" class="btn btn-outline-primary btn-sm w-50 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
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
                                            <th>Date From</th>
                                            <th>VAT</th>
                                            <th>NBT</th>
                                            <th>S VAT</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($result->num_rows > 0) {while ($row = $result-> fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $row['idtbl_vat_info'] ?></td>
                                            <td><?php echo $row['date_from'] ?></td>
                                            <td><?php echo $row['vat'] ?></td>
                                            <td><?php echo $row['nbt'] ?></td>
                                            <td><?php echo $row['s_vat'] ?></td>
                                            <td class="text-right">
                                                <button class="btn btn-outline-primary btn-sm btnEdit <?php if($editcheck==0){echo 'd-none';} ?>" id="<?php echo $row['idtbl_vat_info'] ?>"><i data-feather="edit-2"></i></button>
                                                <?php if($row['status']==1){ ?>
                                                <a href="process/statusvatinfo.php?record=<?php echo $row['idtbl_vat_info'] ?>&type=2" onclick="return confirm('Are you sure you want to deactive this?');" target="_self" class="btn btn-outline-success btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="check"></i></a>
                                                <?php }else{ ?>
                                                <a href="process/statusvatinfo.php?record=<?php echo $row['idtbl_vat_info'] ?>&type=1" onclick="return confirm('Are you sure you want to active this?');" target="_self" class="btn btn-outline-warning btn-sm <?php if($statuscheck==0){echo 'd-none';} ?>"><i data-feather="x-square"></i></a>
                                                <?php } ?>
                                                <a href="process/statusvatinfo.php?record=<?php echo $row['idtbl_vat_info'] ?>&type=3" onclick="return confirm('Are you sure you want to remove this?');" target="_self" class="btn btn-outline-danger btn-sm <?php if($deletecheck==0){echo 'd-none';} ?>"><i data-feather="trash-2"></i></a>
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
                    url: 'getprocess/getvatinfo.php',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#date_from').val(obj.fromdate);  
                        $('#vat').val(obj.vat);     
                        $('#nbt').val(obj.nbt);     
                        $('#s_vat').val(obj.s_vat);        
                                        

                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
    });

</script>
<?php include "include/footer.php"; ?>
