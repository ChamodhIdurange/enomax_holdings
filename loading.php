<?php 
include "include/header.php";  

$sql="SELECT * FROM `tbl_dispatch` WHERE `status`=1";
$result=$conn->query($sql);

$sqlproduct="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1";
$resultproduct =$conn-> query($sqlproduct); 

$sqlvehicle="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `type`=0 AND `status`=1";
$resultvehicle =$conn-> query($sqlvehicle); 

$sqldiverlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=4 AND `status`=1";
$resultdiverlist =$conn-> query($sqldiverlist);

$sqlofficerlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=6 AND `status`=1";
$resultofficerlist =$conn-> query($sqlofficerlist);

$sqlreflist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=7 AND `status`=1";
$resultreflist =$conn-> query($sqlreflist);

$sqlarealist="SELECT `idtbl_area`, `area` FROM `tbl_area` WHERE `status`=1";
$resultarealist =$conn-> query($sqlarealist);

$sqlhelperist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=5 AND `status`=1";
$resulthelperist =$conn-> query($sqlhelperist);

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
                            <div class="page-header-icon"><i class="fas fa-truck-loading"></i></div>
                            <span>Vehicle Loading</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" data-toggle="modal" data-target="#modalcreatedispatch"><i class="fas fa-plus"></i>&nbsp;Create Vehicle Load</button>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped table-bordered table-sm" id="loadview">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Load No</th>
                                            <th>Date</th>
                                            <th>Vehicle</th>
                                            <th>Sale Rep</th>
                                            <th>Area</th>
                                            <th>&nbsp;</th>
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
<!-- Modal Dispatch Create -->
<div class="modal fade" id="modalcreatedispatch" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Create Vehicle Dispatch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-5">
                        <form id="formdispatch" autocomplete="off">
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Area*</label>
                                    <select name="area" id="area" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php if($resultarealist->num_rows > 0) {while ($rowarealist = $resultarealist-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowarealist['idtbl_area'] ?>"><?php echo $rowarealist['area'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Lorry No*</label>
                                    <select name="lorrynum" id="lorrynum" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php if($resultvehicle->num_rows > 0) {while ($rowvehicle = $resultvehicle-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowvehicle['idtbl_vehicle'] ?>"><?php echo $rowvehicle['vehicleno'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>                                
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Driver*</label>
                                    <select name="drivername" id="drivername" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php if($resultdiverlist->num_rows > 0) {while ($rowdiverlist = $resultdiverlist-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowdiverlist['idtbl_employee'] ?>"><?php echo $rowdiverlist['name'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Officer*</label>
                                    <select name="officername" id="officername" class="form-control form-control-sm" required>
                                        <option value="">Select</option>
                                        <?php if($resultofficerlist->num_rows > 0) {while ($rowofficerlist = $resultofficerlist-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowofficerlist['idtbl_employee'] ?>"><?php echo $rowofficerlist['name'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>                                
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Helper Name*</label><br>
                                <select name="helpername[]" id="helpername" class="form-control form-control-sm" style="width:100%;" multiple required>
                                    <?php if($resulthelperist->num_rows > 0) {while ($rowhelperist = $resulthelperist-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowhelperist['idtbl_employee'] ?>"><?php echo $rowhelperist['name'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-group mb-1">
                                <label class="small font-weight-bold text-dark">Rep Name*</label>
                                <select name="refname" id="refname" class="form-control form-control-sm" required>
                                    <option value="">Select</option>
                                    <?php if($resultreflist->num_rows > 0) {while ($rowreflist = $resultreflist-> fetch_assoc()) { ?>
                                    <option value="<?php echo $rowreflist['idtbl_employee'] ?>"><?php echo $rowreflist['name'] ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Product*</label>
                                    <select class="form-control form-control-sm" name="product" id="product" required>
                                        <option value="">Select</option>
                                        <?php if($resultproduct->num_rows > 0) {while ($rowproduct = $resultproduct-> fetch_assoc()) { ?>
                                        <option value="<?php echo $rowproduct['idtbl_product'] ?>"><?php echo $rowproduct['product_name'] ?></option>
                                        <?php }} ?>
                                    </select>
                                </div>    
                                <div class="col-5">
                                    <label class="small font-weight-bold text-dark">Qty*</label>
                                    <input type="text" id="qty" name="qty" class="form-control form-control-sm" value="0" required>
                                </div>                   
                            </div>
                            <div class="form-group mt-3">
                                <button type="button" id="formsubmit" class="btn btn-outline-primary btn-sm fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Add Product</button>
                                <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                            </div>
                        </form>
                    </div>
                    <div class="col-7">
                        <table class="table table-striped table-bordered table-sm small" id="tabledispatch">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="d-none">ProductID</th>
                                    <th class="text-center">Qty</th>
                                </tr>
                            </thead>
                            <tbody id="tbodydispatchcreate"></tbody>
                        </table>
                        <div class="form-group mt-2">
                            <hr>
                            <button type="button" id="btncreatedispatch" class="btn btn-outline-primary btn-sm fa-pull-right" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-save"></i>&nbsp;Create Dispatch</button>
                        </div>
                        <div class="form-group mt-3 text-danger small">
                            <span class="badge badge-danger mr-2">&nbsp;&nbsp;</span> Stock quantity warning
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Load -->
<div class="modal fade" id="modaldispatchdetail" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewdispatchprint"></div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Load print -->
<div class="modal fade" id="modalloadprint" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewloadprint"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm fa-pull-right" id="btnloadprint"><i class="fas fa-print"></i>&nbsp;Print Order</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Warning -->
<div class="modal fade" id="warningModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body bg-danger text-white text-center">
                <div id="warningdesc"></div>
            </div>
            <div class="modal-footer bg-danger rounded-0">
                <button type="button" class="btn btn-outline-light btn-sm w-100" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Day End Warning -->
<div class="modal fade" id="warningDayEndModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body bg-danger text-white text-center">
                <div id="viewmessage"></div>
            </div>
            <div class="modal-footer bg-danger rounded-0">
                <a href="dayend.php" class="btn btn-outline-light btn-sm">Go To Day End</a>
            </div>
        </div>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        checkdayendprocess();
        $("#helpername").select2();

        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('#loadview').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/loadinglist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_vehicle_load"
                },
                {
                    "targets": -1,
                    "className": '',
                    "data": null,
                    "render": function(data, type, full) {
                        return 'VL-'+full['idtbl_vehicle_load'];     
                    }
                },
                {
                    "data": "date"
                },
                {
                    "data": "vehicleno"
                },
                {
                    "data": "name"
                },
                {
                    "data": "area"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';

                        if(full['veiwallcustomerstatus']==1){button+='<button class="btn btn-outline-success btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-users"></i></button>';}
                        else{button+='<a href="process/statusloading.php?record='+full['idtbl_vehicle_load']+'&type=2" onclick="return allcustomer_confirm()" target="_self" class="btn btn-outline-orange btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-users"></i></a>';}

                        button+='<button class="btn btn-outline-primary btn-sm mr-1 btnprint" data-toggle="tooltip" data-placement="bottom" title="Print Order" id="'+full['idtbl_vehicle_load']+'" ';if(full['approvestatus']==0){button+='disabled';}button+='><i class="fas fa-print"></i></button>';

                        if(full['approvestatus']==1){button+='<button class="btn btn-outline-success btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-check"></i></button>';}
                        else{button+='<a href="process/statusloading.php?record='+full['idtbl_vehicle_load']+'&type=1" onclick="return order_confirm()" target="_self" class="btn btn-outline-orange btn-sm mr-1 ';if(statuscheck==0){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';}

                        button+='<button class="btn btn-outline-dark btn-sm mr-1 btnloadview" data-toggle="tooltip" data-placement="bottom" title="Print Order" id="'+full['idtbl_vehicle_load']+'" ><i class="far fa-eye"></i></button>';
                        
                        return button;
                    }
                }
            ]
        } );
        $('#loadview tbody').on('click', '.btnloadview', function() {
            var loadID=$(this).attr('id');

            $.ajax({
                type: "POST",
                data: {
                    loadID : loadID
                },
                url: 'getprocess/getloaddetail.php',
                success: function(result) {//alert(result);
                    $('#viewdispatchprint').html(result);
                    $('#modaldispatchdetail').modal('show');
                }
            }); 
        });
        //Create dispatch part
        $("#formsubmit").click(function() {
            if (!$("#formdispatch")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitBtn").click();
            } else {   
                var productID = $('#product').val();
                var product = $("#product option:selected").text();
                var qty = parseFloat($('#qty').val());
                var emptyqty = parseFloat($('#emptyqty').val());
                
                $('#tabledispatch > tbody:last').append('<tr class="pointer"><td>' + product + '</td><td class="d-none">' + productID + '</td></td><td class="text-center">' + qty + '</td></tr>');

                $('#product').val('');
                $('#qty').val('0').prop('readonly', false);
                $('#emptyqty').val('0').prop('readonly', false);


                $('#product').focus();
            }
        });
        $('#modalcreatedispatch').on('hidden.bs.modal', function (e) {
            $('#tabledispatch > tbody').html('');
            $('#product').val('');
            $('#area').val('');
            $('#lorrynum').val('');
            $('#drivername').val('');
            $('#officername').val('');
            $('#refname').val('');
            $('#qty').val('0');
        });
        $('#btncreatedispatch').click(function(){
            jsonObj = [];
            $("#tabledispatch tbody tr").each(function() {
                item = {}
                $(this).find('td').each(function(col_idx) {
                    item["col_" + (col_idx + 1)] = $(this).text();
                });
                jsonObj.push(item);
            });
            // console.log(jsonObj);
        
            var lorrynum = $('#lorrynum').val();
            var drivername = $('#drivername').val();
            var officername = $('#officername').val();
            var refname = $('#refname').val();
            var area = $('#area').val();
            var helpername = $('#helpername').val();

            $.ajax({
                type: "POST",
                data: {
                    tableData: jsonObj,
                    lorryID: lorrynum,
                    driverID: drivername,
                    officerID: officername,
                    refID: refname,
                    areaID: area,
                    helpername:helpername
                },
                url: 'process/loadingprocess.php',
                success: function(result) { //alert(result);
                    $('#modalcreatedispatch').modal('hide');
                    action(result);
                    location.reload();
                }
            });
        });
        $('#tabledispatch').on( 'click', 'tr', function () {
            var r = confirm("Are you sure, You want to remove this product ? ");
            if (r == true) {
                $(this).closest('tr').remove();

                $('#product').focus();
            }
        });
        $('#lorrynum').change(function(){
            var lorryID = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    lorryID : lorryID
                },
                url: 'getprocess/getvehicleavaloadinginfo.php',
                success: function(result) {//alert(result);
                    if(result==1){
                        $('#warningdesc').html("Can't create vehicle loading, please unload the last loading this vehicle.")
                        $('#warningModal').modal('show');
                        $('#formsubmit').prop('disabled', true);
                    }
                }
            }); 
        });
        //Stock check
        $('#qty').keyup(function(){
            if($(this).val()!='0'){
                var qty = parseFloat($(this).val());
            }
            else{
                var qty = parseFloat('0');
            }
            
            var productID = parseFloat($('#product').val());
            var typeID='4';
            var fieldID='qty';

            var stockstatus = checkstock(productID, qty, typeID, fieldID);
        });
        $('#product').change(function(){
            $('#qty').focus();
            $('#qty').select();
        });

        //Print Option
        $('#loadview tbody').on('click', '.btnprint', function() {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                data: {
                    loadID: id
                },
                url: 'getprocess/getvehicleloadingprint.php',
                success: function(result) {
                    $('#viewloadprint').html(result);
                    $('#modalloadprint').modal('show');
                }
            });
        });
        document.getElementById('btnloadprint').addEventListener ("click", print);
    });

    function tabletotal(){
        var sum = 0;
        $(".total").each(function(){
            sum += parseFloat($(this).text());
        });
        
        var showsum = addCommas(parseFloat(sum).toFixed(2));

        $('#divtotal').html('Rs. '+showsum);
        $('#hidetotalorder').val(sum);
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

    function action(data) { //alert(data);
        var obj = JSON.parse(data);
        $.notify({
            // options
            icon: obj.icon,
            title: obj.title,
            message: obj.message,
            url: obj.url,
            target: obj.target
        }, {
            // settings
            element: 'body',
            position: null,
            type: obj.type,
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "center"
            },
            offset: 100,
            spacing: 10,
            z_index: 1031,
            delay: 5000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: null,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: 'class',
            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
        });
    }
    function checkstock(productID, qty, typeID, fieldID){
        $.ajax({
            type: "POST",
            data: {
                productID: productID,
                qty: qty,
                typeID: typeID
            },
            url: 'getprocess/getstockqtyavailability.php',
            success: function(result) { //alert(result);
                if(result==1){
                    $('#'+fieldID).addClass('bg-danger text-white');
                    $("#formsubmit").prop('disabled', true);
                }
                else{
                    $('#'+fieldID).removeClass('bg-danger text-white');
                    $("#formsubmit").prop('disabled', false);
                }
            }
        });
    }
    function order_confirm() {
        return confirm("Are you sure you want to Confirm this loading?");
    }
    function allcustomer_confirm() {
        return confirm("Are you sure you want to show all customers this loading?");
    }
    function print() {
        printJS({
            printable: 'viewloadprint',
            type: 'html',
            style: '@page { size: landscape; margin:0.25cm; }',
            targetStyles: ['*']
        })
    }
    function checkdayendprocess(){
        $.ajax({
            type: "POST",
            data: {
                
            },
            url: 'getprocess/getstatuslastdayendinfo.php',
            success: function(result) { //alert(result);
                if(result==1){
                    $('#viewmessage').html("Can't create anything, because today transaction is end");
                    $('#warningDayEndModal').modal('show');
                }
                else if(result==0){
                    $('#viewmessage').html("Can't create anythind, because yesterday day end process end not yet.");
                    $('#warningDayEndModal').modal('show');
                }
            }
        });
    }
</script>
<?php include "include/footer.php"; ?>
