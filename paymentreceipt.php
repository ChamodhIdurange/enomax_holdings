<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_invoice_payment` WHERE `status`=1";
$result =$conn-> query($sql); 

include "include/topnavbar.php"; 
?>
<style>
    .tableprint {
        table-layout: fixed;
    }
</style>
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
                            <span>Payment Receipt</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Customer</th>
                                            <th>Address</th>
                                            <th>Receipt No</th>
                                            <th class="text-right">Payment</th>
                                            <!-- <th class="text-right">Balance</th> -->
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
<!-- Modal Payment Receipt -->
<div class="modal fade" id="modalpaymentreceipt" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9" id="frame">
                    <iframe class="embed-responsive-item" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('#dataTable').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/paymentreceiptviewlist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_invoice_payment"
                },
                {
                    "data": "date"
                },
                {
                    "data": "name"
                },
                {
                    "data": "address"
                },
                {
                    "data": "receiptno"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var payment=addCommas(parseFloat(full['payment']).toFixed(2));
                        return payment;
                    }
                },
                // {
                //     "targets": -1,
                //     "className": 'text-right',
                //     "data": null,
                //     "render": function(data, type, full) {
                //         var payment=addCommas(parseFloat(full['balance']).toFixed(2));
                //         return payment;
                //     }
                // },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button+='<button class="btn btn-outline-dark btn-sm btnview mr-1" id="'+full['idtbl_invoice_payment']+'"><i class="fas fa-eye"></i></button>';
                        if(full['paymentcomplete']==0  && deletecheck != 0){

                            button+='<button type="button" data-url="process/statuspaymentreceipt.php?record='+full['idtbl_invoice_payment']+'&type=3"  data-actiontype="3" title="Delete" class="btn btn-outline-danger btn-sm mr-1 btntableaction" id="'+full['idtbl_invoice_payment']+'"><i class="far fa-trash-alt"></i></button>';
                        
                            // button+='<a href="process/changepaymentstatus.php?record='+full['idtbl_invoice']+'&method='+full['method']+'&type=3" onclick="return active_confirm()" target="_self" class="btn btn-outline-danger mr-1 btn-sm ';button+='"><i class="fas fa-money-bill-alt"></i></a>';
                        }
                        return button;
                    }
                }
            ]
        } );
        $('#dataTable tbody').on('click', '.btnview', function() {
            var id = $(this).attr('id');
           // alert(id);
            $('#frame').html('');
            $('#frame').html('<iframe class="embed-responsive-item" frameborder="0"></iframe>');
            $('#modalpaymentreceipt iframe').contents().find('body').html("<img src='images/spinner.gif' class='img-fluid' style='margin-top:200px;margin-left:500px;' />");

            // alert(id)
            var src = 'pdfprocess/paymentreceiptpdf.php?paymentinoiceID=' + id;
            //            alert(src);
            var width = $(this).attr('data-width') || 640; // larghezza dell'iframe se non impostato usa 640
            var height = $(this).attr('data-height') || 360; // altezza dell'iframe se non impostato usa 360

            var allowfullscreen = $(this).attr('data-video-fullscreen'); // impostiamo sul bottone l'attributo allowfullscreen se è un video per permettere di passare alla modalità tutto schermo

            // stampiamo i nostri dati nell'iframe
            $("#modalpaymentreceipt iframe").attr({
                'src': src,
                'height': height,
                'width': width,
                'allowfullscreen': ''
            });
            $('#modalpaymentreceipt').modal({
                keyboard: false,
                backdrop: 'static'
            });
        });

        document.getElementById('btnreceiptprint').addEventListener ("click", print);
    });

    function print() {
        printJS({
            printable: 'viewreceiptprint',
            type: 'html',
            style: '@page { size: A5 portrait; margin:0.25cm; }',
            targetStyles: ['*']
        })
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }

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

    function active_confirm() {
        return confirm("Are you sure you payment recived?");
    }
</script>
<?php include "include/footer.php"; ?>

