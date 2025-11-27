<!--<script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>-->
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<!-- <script src="assets/demo/chart-area-demo.js"></script> -->
<!--<script src="assets/demo/chart-bar-demo.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>
<!--<script src="assets/demo/datatables-demo.js"></script>-->
<script src="assets/js/jquery-inputformat.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/bootstrap-notify.js"></script>
<script src="assets/js/select2.full.js"></script>
<script src="assets/js/jquery.serializejson.js"></script>
<script src="assets/slick/slick.js"></script>
<script src="assets/js/print.js"></script>
<!-- Include jsPDF and jsPDF autoTable from CDN -->
<script src="assets/js/jspdf.umd.js"></script>
<script src="assets/js/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if(isset($optional_header_includes) && is_array($optional_header_includes)){   ?>
    <!-- Page Specific footer scripts are specified here -->
    <?php if(in_array('magnific-popup',$optional_header_includes)) { ?>
        <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <?php } ?>
<?php } ?>
<script>   
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        setTimeout(tablerowhighlight, 1000);
    });

    function tablerowhighlight(){
        $('table tbody').on('click', 'tr', function(){
            $('table tbody>tr').removeClass('table-warning text-dark');
            $(this).addClass('table-warning text-dark');
        });
    }
    actionText=[];
    var actionText=$('#actiontext').val();
    action(actionText);

    function action(data) {
        if(data!=''){
            var obj=JSON.parse(data);
            var icon, title;

            if(obj.type=='success'){icon='success'; title=obj.message;}
            else if(obj.type=='primary'){icon='success'; title=obj.message;}
            else if(obj.type=='warning'){icon='warning'; title=obj.message;}
            else if(obj.type=='danger'){icon='error'; title=obj.message;}

            Swal.fire({
                position: "top-end",
                icon: icon,
                title: title,
                showConfirmButton: false,
                timer: 2500
            });
        }
    }

    function actionreload(data) {
        if(data!=''){
            var obj=JSON.parse(data);
            var icon, title;

            if(obj.type=='success'){icon='success'; title=obj.message;}
            else if(obj.type=='primary'){icon='success'; title=obj.message;}
            else if(obj.type=='warning'){icon='warning'; title=obj.message;}
            else if(obj.type=='danger'){icon='error'; title=obj.message;}

            Swal.fire({
                position: "top-end",
                icon: icon,
                title: title,
                showConfirmButton: false,
                timer: 2500
            }).then(() => {
                window.location.reload();
            });
        }
    }

    $(document).on("click", ".btntableaction", function () {
        var url = 'https://localhost/enomax_holdings/'+$(this).attr("data-url");
        var actiontype = $(this).attr("data-actiontype");

        var title;

        if(actiontype==1){title='You want to active this?'}
        else if(actiontype==2){title='You want to deactive this?'}
        else if(actiontype==3){title='You want to remove this?'}
        else if(actiontype==4){title='You want to emergency this customer?'}
        else if(actiontype==5){title='Are you sure that you want to complete this order?'}
        else if(actiontype==6){title='Are you sure that you want to confirm this order?'}
        else if(actiontype==7){title='Are you sure you want to Transfer to Stock?'}
        else if(actiontype==8){title='Are you sure you want to accept this return?'}

        Swal.fire({
            title: "Are you sure?",
            text: title,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Confirm",
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href=url;
            }
        });
    });

    function Otherconfirmation(title){
        return Swal.fire({
            title: "Are you sure?",
            text: title,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Confirm",
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-danger'
            }
        }).then((result) => {
            return result.isConfirmed;
        });
    }
</script>
