<?php 
include "include/header.php";  

$sqlcustomer="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1";
$resultcustomer =$conn-> query($sqlcustomer);

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
                            <div class="page-header-icon"><i data-feather="file"></i></div>
                            <span>Sale Report Product</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <form id="searchform">
                                    <div class="form-row">
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">Date*</label>
                                            <div class="input-group input-group-sm mb-3">
                                                <input type="text" class="form-control dpd1a rounded-0" id="fromdate" name="fromdate" value="<?php echo date('Y-m-d') ?>" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text rounded-0" id="inputGroup-sizing-sm"><i data-feather="calendar"></i></span>
                                                </div>
                                                <input type="text" class="form-control dpd1a rounded-0 border-left-0" id="todate" name="todate" value="<?php echo date('Y-m-d') ?>" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">&nbsp;</label><br>
                                            <button class="btn btn-outline-dark btn-sm rounded-0 px-4" type="button" id="formSearchBtn"><i class="fas fa-search"></i>&nbsp;Search</button>
                                        </div>
                                    </div>
                                    <input type="submit" class="d-none" id="hidesubmit">
                                </form>
                            </div>
                            <div class="col-12">
                                <hr class="border-dark">
                                <div id="targetviewdetail"></div>   
                                <canvas id="salechart"></canvas>                          
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
        $('.dpd1a').datepicker('remove');
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            format: 'yyyy-mm-dd'
        });

        $('#formSearchBtn').click(function(){
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {   
                var validfrom = $('#fromdate').val();
                var validto = $('#todate').val();
                var customer = $('#customer').val();

                $('#targetviewdetail').html('<div class="card border-0 shadow-none bg-transparent"><div class="card-body text-center"><img src="images/spinner.gif" alt="" srcset=""></div></div>');

                $.ajax({
                    type: "POST",
                    data: {
                        validfrom: validfrom,
                        validto: validto,
                        customer: customer
                    },
                    url: 'getprocess/getproductsalereportaccoperiod.php',
                    success: function(result) {//alert(result);
                        var obj = JSON.parse(result);

                        $('#targetviewdetail').html(obj.html);

                        var productone = obj.productone;
                        var producttwo = obj.producttwo;
                        var productthree = obj.productthree;
                        var productfour = obj.productfour;
                        var xline = obj.days;
                        
                        var ctx = document.getElementById('salechart').getContext('2d');
                        var chart = new Chart(ctx, {
                            // The type of chart we want to create
                            type: 'line',

                            // The data for our dataset
                            data: {
                                labels: xline,
                                datasets: [{
                                    label: '12.5 KG Filled',
                                    backgroundColor: 'rgba(0, 97, 242, 0.00)',
                                    borderColor: '#00ac69',
                                    borderWidth: 1,
                                    data: productone
                                }, {
                                    label: '37.5 KG Filled',
                                    backgroundColor: 'rgba(0, 97, 242, 0.00)',
                                    borderColor: '#e81500',
                                    borderWidth: 1,
                                    data: producttwo
                                }, {
                                    label: '5 KG Filled',
                                    backgroundColor: 'rgba(0, 97, 242, 0.00)',
                                    borderColor: '#1f2d41',
                                    borderWidth: 1,
                                    data: productthree
                                }, {
                                    label: '2 KG Filled',
                                    backgroundColor: 'rgba(0, 97, 242, 0.00)',
                                    borderColor: '#0061f2',
                                    borderWidth: 1,
                                    data: productfour
                                }]
                            },

                            // Configuration options go here
                            options: {
                                scales: {
                                    yAxes: [{
                                        display: true,
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Product sale'
                                        },
                                        ticks: {
                                            min: 0,
                                        }
                                    }]
                                },
                                responsive: true
                            }
                        });
                    }
                });
            }
        });
    });
</script>
<?php include "include/footer.php"; ?>
