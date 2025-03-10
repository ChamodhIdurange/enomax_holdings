<?php 
include "include/header.php";  

$sqlcheckdate="SELECT `date` FROM `tbl_stock_closing` WHERE `status`=1 ORDER BY `idtbl_stock_closing` DESC LIMIT 1";
$resultcheckdate =$conn-> query($sqlcheckdate); 
$rowcheckdate = $resultcheckdate-> fetch_assoc();

if($resultcheckdate->num_rows==0){
    $startdate=date('Y-m-d');
}
else{
    $startdate = date('Y-m-d',strtotime($rowcheckdate['date'] . "+1 days"));
}

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
                            <div class="page-header-icon"><i class="far fa-calendar-times"></i></div>
                            <span>Day End</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form id="formdayend" action="process/dayendprocess.php" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Order Date*</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control dpd1a" placeholder="" name="dayenddate" id="dayenddate" value="<?php echo $startdate; ?>" required>
                                            <div class="input-group-append">
                                                <span class="btn btn-light border-gray-500"><i class="far fa-calendar"></i></span>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="form-group mt-3">
                                        <button type="button" id="submitBtn" class="btn btn-outline-primary btn-sm px-3 fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Day End</button>
                                        <input type="submit" class="d-none" id="hidebtnsubmit">
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-9">
                                <table class="table table-bordered table-striped table-sm nowrap small" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Close Stock Value</th>
                                            <th>Status</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
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
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        var today = '<?php echo date('Y-m-d') ?>';

        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            startDate: '<?php echo $startdate; ?>',
            format: 'yyyy-mm-dd'
        });
        $('#dataTable').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/dayendlist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_stock_closing"
                },
                {
                    "data": "date"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var stockvalue = parseFloat(full['closingstock']).toFixed(2);
                        return addCommas(stockvalue);     
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-center',
                    "data": null,
                    "render": function(data, type, full) {
                        var html = '';
                        if(full['status']==2){
                            html+='Cancel Record';
                        }

                        return html;     
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';

                        if(full['status']==1){
                            button+='<a href="process/statusdayend.php?record='+full['idtbl_stock_closing']+'&type=2" onclick="return delete_confirm()" target="_self" class="btn btn-outline-danger btn-sm ';if(deletecheck==0){button+='d-none';}button+='"><i class="far fa-trash-alt"></i></a>';
                        }
                        
                        return button;
                    }
                }
            ],
            "createdRow": function( row, data, dataIndex){
                if ( data['status']  == 2) {
                    $(row).addClass('table-danger');
                }
            }
        } );      

        $('#submitBtn').click(function(){
            if (!$("#formdayend")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidebtnsubmit").click();
            } else {
                var selectdate = $('#dayenddate').val();

                if(today===selectdate){
                    var r = confirm("Are you sure, You want to end today process ? ");
                    if (r == true) {
                        $("#hidebtnsubmit").click();
                    }
                }
                else{
                    $("#hidebtnsubmit").click();
                }
            }
        });
    });

    function addCommas(nStr){
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    function delete_confirm() {
        return confirm("Are you sure you want to cancel this day process?");
    }

</script>
<?php include "include/footer.php"; ?>
