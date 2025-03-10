<?php 
include "include/header.php";  

$addcheck=0;
$editcheck=0;
$statuscheck=0;
$deletecheck=0;


$sqlbank="SELECT `idtbl_bank`, `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`>1";
$resultbank =$conn-> query($sqlbank); 


$sqlmain="SELECT idtbl_company_branch as id, code, concat(code, ' ', branch) as name FROM `tbl_company_branch`";
$resultmain =$conn-> query($sqlmain); 

$sqlsub="SELECT `tbl_account_allocation`.`idtbl_account_allocation` as id, `tbl_account_allocation`.tbl_company_branch_idtbl_company_branch as group_id, `tbl_account_allocation`.`subaccountno` AS `code`, CONCAT(`tbl_account_allocation`.`subaccountno`, ' ', `tbl_subaccount`.`subaccountname`) as `name` FROM `tbl_account_allocation` INNER JOIN `tbl_subaccount` ON `tbl_account_allocation`.`subaccountno`=`tbl_subaccount`.`subaccount` WHERE `tbl_account_allocation`.`status`=1 ORDER BY group_id, id";
$resultsub =$conn-> query($sqlsub); 

$listmain = array();
$listsub = array();

if($resultmain->num_rows>0){
	while($rowmain=$resultmain->fetch_assoc()){
		$listmain[]=array('id'=>$rowmain['id'], 'code'=>$rowmain['code'], 'name'=>$rowmain['name']);
	}
}

if($resultsub->num_rows>0){
	while($rowsub=$resultsub->fetch_assoc()){
		$listsub[]=array('id'=>$rowsub['id'], 'code'=>$rowsub['code'], 'name'=>$rowsub['name'], 'group_id'=>$rowsub['group_id']);
	}
}

/*
$sqlvehicle="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `type`=0 AND `status`=1";
$resultvehicle =$conn-> query($sqlvehicle); 

$sqlvehicletrailer="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `type`=1 AND `status`=1";
$resultvehicletrailer =$conn-> query($sqlvehicletrailer); 

$sqldiverlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=4 AND `status`=1";
$resultdiverlist =$conn-> query($sqldiverlist);

$sqlofficerlist="SELECT `idtbl_employee`, `name` FROM `tbl_employee` WHERE `tbl_user_type_idtbl_user_type`=6 AND `status`=1";
$resultofficerlist =$conn-> query($sqlofficerlist);
*/
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
                            <div class="page-header-icon"><i data-feather="list"></i></div>
                            <span>Payment Details</span>
                            <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right dropdown-toggle" id="btnorderacts" style="position:absolute; right:10px;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-plus"></i>&nbsp;Create</button>
                            <div class="dropdown-menu" aria-labelledby="btnorderacts">
                            	<a class="dropdown-item" id="btnordercreate" data-refid="-1" href="javascript:void(0);">Cash/Cheque</a>
                            	<a class="dropdown-item" id="btnOtherCreate" data-refid="-1" href="javascript:void(0);">Other Cheque Payments</a>
                            </div>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12">
                                <!--div class="row">
                                    <div class="col">
                                        
                                    </div>
                                </div>
                                <hr-->
                                <table class="table table-bordered table-striped table-sm nowrap small" id="tableHeaders">
                                    <thead>
                                        <tr>
                                            <th>Paid To</th>
                                            <th>Credit Branch</th>
                                            <th>Credit Account</th>
                                            <th>Narration</th>
                                            <th class="text-right">Amount</th>
                                            <th class="text-center">Actions</th>
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
<!-- Modal Create Order -->
<div class="modal fade" id="modalcreateorder" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <form id="createorderform" autocomplete="off">
                            <div class="row">
                            	<div class="col-md-6">
                                <!-- detail -->
                                	<div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Debit Branch*</label>
                                        <select class="form-control form-control-sm nest_head" data-findnest="debitnest" id="drp_debit_branch" name="drp_debit_branch">
                                        	<option value="">Select</option>
                                            <?php if(count($listmain)>0){
												foreach($listmain as $rowmain){?>
                                            
                                            <option value="<?php echo $rowmain['id']; ?>" data-colcode="<?php echo $rowmain['code']; ?>"><?php echo $rowmain['name']; ?></option>
                                            <?php }
											} ?>
                                            
                                            
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Debit Account*</label>
                                        <select class="form-control form-control-sm" data-nestname="debitnest" id="drp_debit_account" name="drp_debit_account">
                                        	<option value="">Select</option>
                                            <?php if(count($listsub)>0){
												foreach($listsub as $rowsub){?>
                                            
                                            <option class="nestopt d-none" value="<?php echo $rowsub['id']; ?>" data-nestcode="<?php echo $rowsub['group_id']; ?>" data-colcode="<?php echo $rowsub['code']; ?>" disabled="disabled"><?php echo $rowsub['name']; ?></option>
                                            <?php }
											} ?>
                                            
                                            
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Narration*</label>
                                        <input type="text" id="pay_narration" name="pay_narration" class="form-control form-control-sm" value="" required>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Invoice No*</label>
                                        <input type="number" id="pay_invno" name="pay_invno" class="form-control form-control-sm" value="" required maxlength="10">
                                    </div>
                                    <div class="form-group mb-2" style="margin-bottom:0px !Important">
                                        <label class="small font-weight-bold text-dark">Amount*</label>
                                        <div class="form-row">
                                        	<div class="form-group col-md-6" style="margin-bottom:0px;">
                                            	<div class="i-checks" style="line-height:9px;">
                                                	<input type="radio" name="rad_pay_method" id="rad_cash" value="" checked="checked" class="form-control-custom radio-custom" style="width:12px; height:12px;" data-togval="1" />
                                                    <label class="small" for="rad_cash">Cash</label>
                                                </div>
                                                <div class="i-checks" style="line-height:10px;">
                                                	<input type="radio" name="rad_pay_method" id="rad_cheque" value="" class="form-control-custom radio-custom" style="width:12px; height:12px;" data-togval="0" />
                                                    <label class="small" for="rad_cheque">Cheque</label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6" style="margin-bottom:0px;">
                                            	<input type="text" id="pay_amount" name="pay_amount" class="form-control form-control-sm" value="0.00" required style="text-align:right;">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                <!-- detail -->
                                </div>
                                <div class="col-md-6">
                                <!-- header -->
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Credit Branch*</label>
                                        <select class="form-control form-control-sm nest_head" data-findnest="creditnest" id="drp_credit_branch" name="drp_credit_branch">
                                        	<option value="">Select</option>
                                            <?php if(count($listmain)>0){
												foreach($listmain as $rowmain){?>
                                            
                                            <option value="<?php echo $rowmain['id']; ?>" data-colcode="<?php echo $rowmain['code']; ?>"><?php echo $rowmain['name']; ?></option>
                                            <?php }
											} ?>
                                            
                                            
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Credit Account*</label>
                                        <select class="form-control form-control-sm" data-nestname="creditnest" id="drp_credit_account" name="drp_credit_account">
                                        	<option value="">Select</option>
                                            <?php if(count($listsub)>0){
												foreach($listsub as $rowsub){?>
                                            
                                            <option class="nestopt d-none" value="<?php echo $rowsub['id']; ?>" data-nestcode="<?php echo $rowsub['group_id']; ?>" data-colcode="<?php echo $rowsub['code']; ?>" disabled="disabled"><?php echo $rowsub['name']; ?></option>
                                            <?php }
											} ?>
                                            
                                            
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Narration*</label>
                                        <input type="text" id="creditor_narration" name="creditor_narration" class="form-control form-control-sm" value="" required>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Paid To*</label>
                                        <input type="text" id="pay_creditor" name="pay_creditor" class="form-control form-control-sm" value="" required>
                                    </div>
                                    
                                <!-- header -->
                                </div>
                                
                            </div>
                            
                            
                            
                            
                            
                            
                            
                            <div class="form-group mt-3">
                                <button type="submit" id="formsubmit" class="btn btn-outline-primary btn-sm fa-pull-right" <?php // if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Add</button>
                                <!--input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none"-->
                            </div>
                            
                            <input type="hidden" name="hrefid" id="hrefid" value="">
                            <input type="hidden" name="payment_complete" id="payment_complete" value="">
                            
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        <table class="table table-striped table-bordered table-sm small" id="tableDetails">
                            <thead>
                                <tr>
                                    <th>Branch</th>
                                    <th>Account</th>
                                    <th>Narration</th>
                                    <th>Invoice No</th>
                                    <th class="text-right">Amount</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        
                        <!--hr-->
                        
                        <div style="position: absolute; bottom: 0px; margin-top: 0px !important; margin-bottom: 0px; right: 15px;" class="form-group mt-3">
                        	<button type="button" class="btn btn-secondary btn-sm fa-pull-right" id="btn_complete"><i class="fas fa-save"></i>&nbsp;Save All</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create Other -->
<div class="modal fade" id="modalCreateOther" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <form id="frmOtherPayment" autocomplete="off">
                            <div class="row">
                            	<div class="col-md-6">
                                <!-- detail -->
                                	<div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Debit Branch*</label>
                                        <select class="form-control form-control-sm nest_head" data-findnest="op_debitnest" id="op_debit_branch" name="op_debit_branch">
                                        	<option value="">Select</option>
                                            <?php if(count($listmain)>0){
												foreach($listmain as $rowmain){?>
                                            
                                            <option value="<?php echo $rowmain['id']; ?>" data-colcode="<?php echo $rowmain['code']; ?>"><?php echo $rowmain['name']; ?></option>
                                            <?php }
											} ?>
                                            
                                            
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Debit Account*</label>
                                        <select class="form-control form-control-sm" data-nestname="op_debitnest" id="op_debit_account" name="op_debit_account">
                                        	<option value="">Select</option>
                                            <?php if(count($listsub)>0){
												foreach($listsub as $rowsub){?>
                                            
                                            <option class="nestopt d-none" value="<?php echo $rowsub['id']; ?>" data-nestcode="<?php echo $rowsub['group_id']; ?>" data-colcode="<?php echo $rowsub['code']; ?>" disabled="disabled"><?php echo $rowsub['name']; ?></option>
                                            <?php }
											} ?>
                                            
                                            
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Narration*</label>
                                        <input type="text" id="op_pay_narration" name="op_pay_narration" class="form-control form-control-sm" value="" required>
                                    </div>
                                    
                                    
                                    
                                <!-- detail -->
                                </div>
                                <div class="col-md-6">
                                <!-- header -->
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Credit Branch*</label>
                                        <select class="form-control form-control-sm nest_head" data-findnest="op_creditnest" id="op_credit_branch" name="op_credit_branch">
                                        	<option value="">Select</option>
                                            <?php if(count($listmain)>0){
												foreach($listmain as $rowmain){?>
                                            
                                            <option value="<?php echo $rowmain['id']; ?>" data-colcode="<?php echo $rowmain['code']; ?>"><?php echo $rowmain['name']; ?></option>
                                            <?php }
											} ?>
                                            
                                            
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Credit Account*</label>
                                        <select class="form-control form-control-sm" data-nestname="op_creditnest" id="op_credit_account" name="op_credit_account">
                                        	<option value="">Select</option>
                                            <?php if(count($listsub)>0){
												foreach($listsub as $rowsub){?>
                                            
                                            <option class="nestopt d-none" value="<?php echo $rowsub['id']; ?>" data-nestcode="<?php echo $rowsub['group_id']; ?>" data-colcode="<?php echo $rowsub['code']; ?>" disabled="disabled"><?php echo $rowsub['name']; ?></option>
                                            <?php }
											} ?>
                                            
                                            
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Narration*</label>
                                        <input type="text" id="op_creditor_narration" name="op_creditor_narration" class="form-control form-control-sm" value="" required>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Paid To*</label>
                                        <input type="text" id="op_pay_creditor" name="op_pay_creditor" class="form-control form-control-sm" value="" required>
                                    </div>
                                    
                                <!-- header -->
                                </div>
                                
                            </div>
                            
                            <div class="form-row mt-3">
                            	<div class="col">
                                	<h6 class="small title-style font-weight-bold">
                                    	<span>Payment Details</span>
                                    </h6>
                                </div>
                            </div>
                            
                            <div class="row">
                            	<div class="col-md-6">
                                	<div class="form-group mb-2">
                                        <label class="small font-weight-bold text-dark">Particulars</label>
                                        <input type="text" id="op_pay_invno" name="op_pay_invno" class="form-control form-control-sm" value="" required maxlength="100">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2" style="margin-bottom:0px !Important">
                                        <label class="small font-weight-bold text-dark">Amount*</label>
                                        <input type="text" id="op_pay_amount" name="op_pay_amount" class="form-control form-control-sm" value="0.00" required style="text-align:right;">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mt-3">
                                <button type="submit" id="op_formsubmit" class="btn btn-outline-primary btn-sm fa-pull-right" <?php // if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Add</button>
                                <!--input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none"-->
                            </div>
                            
                            <input type="hidden" name="hrefop" id="hrefop" value=""><!-- referring-detail-id-for-other-particulars -->
                            <!--input type="hidden" name="payment_complete" id="payment_complete" value=""-->
                            
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        <table class="table table-striped table-bordered table-sm small" id="optableDetails">
                            <thead>
                                <tr>
                                    <th>Particulars</th>
                                    <th class="text-right" style="width:200px;">Amount</th>
                                    <th class="text-center" style="width:100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        
                        <!--hr-->
                        
                        <div style="position: absolute; bottom: 0px; margin-top: 0px !important; margin-bottom: 0px; right: 15px;" class="form-group mt-3">
                        	<button type="button" class="btn btn-secondary btn-sm fa-pull-right" id="op_btn_complete"><i class="fas fa-save"></i>&nbsp;Save All</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal order print -->
<div class="modal fade" id="modalorderprint" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="viewdispatchprint"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger btn-sm fa-pull-right" id="btnorderprint"><i class="fas fa-print"></i>&nbsp;Print Order</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal order print -->


<!-- Modal Warning -->
<div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body bg-danger text-white text-center">
                <div id="warningdesc"></div>
            </div>
            <div class="modal-footer bg-danger rounded-0">
                <button type="button" class="btn btn-outline-light btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Warning -->


<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        $('body').tooltip({
            selector: '[data-toggle="tooltip"]'
        });
        $('[data-toggle="tooltip"]').tooltip({
            trigger : 'hover'
        });

        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        var headRecBranchCode='';
		var headRecAcNum='';
		var headRecNarration='';
		var headRecCreditor='';
		
		var main_table=$('#tableHeaders').DataTable( {
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/paymentlist.php",
                type: "POST", // you can use GET
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "payment_creditor"
                },
				{
					"data": "payment_credit_branch"
				},
				{
					"data": "payment_credit_account"
				},
				{
					"data": "payment_head_narration"
				},
                {
                    "data": "payment_head_amount",
                    "className": 'text-right'
                },
                {
                    "data": "header_id",
                    "className": 'text-center',
					"orderable":false,
                    "render": function(data, type, full) {
                        var button='';
                        
                        button+='<button class="btn btn-outline-primary btn-sm mr-1 btnedit" data-toggle="tooltip" data-placement="bottom" title="Edit" data-refid="'+data+'" ';button+='><i class="fas fa-edit"></i></button>'; 
                        /*
                        button+='<button class="btn btn-outline-danger btn-sm mr-1 btndelete ';button+='" data-toggle="tooltip" title="Delete" data-refid="'+data+'" disabled="disabled"><i class="far fa-trash-alt"></i></button>';
                        */
                        return button;
                    }
                }
            ]
        } );
		
		var book_table=$('#tableDetails').DataTable( {
				"info":false,
				"searching":false,
				"paging":false,
				"columns": [{data:'payment_debit_branch'},
							{data:'payment_debit_account'}, 
							{data:'payment_sub_narration'}, 
							{data:'invoice_no'},
							{data:'paid_amount', "className":"text-right"}, 
							{data:'detail_id', "className":"text-center"}], 
				"columnDefs": [{
						"targets":5,
						"orderable":false, 
						render: function( data, type, row ){
							return '<a class="act_del_item" '+
								'data-refid="'+data+'" href="#" >'+
									'<i class="fas fa-window-close"></i>'+
								'</a>';
						}
					}], 
				"createdRow": function( row, data, dataIndex ){
					$( row ).attr('id', 'pack-'+data.detail_id);
				}
			} );
		
		var op_table=$('#optableDetails').DataTable( {
				"info":false,
				"searching":false,
				"paging":false,
				"columns": [{data:'paid_particulars'},
							{data:'paid_amount', "className":"text-right"}, 
							{data:'detail_id', "className":"text-center"}], 
				"columnDefs": [{
						"targets":2,
						"orderable":false, 
						render: function( data, type, row ){
							return '<a class="act_del_part" '+
								'data-refid="'+data+'" href="#" >'+
									'<i class="fas fa-window-close"></i>'+
								'</a>';
						}
					}], 
				"createdRow": function( row, data, dataIndex ){
					$( row ).attr('id', 'part-'+data.detail_id);
				}
			} );
		
        $('.dpd1a').datepicker({
            uiLibrary: 'bootstrap4',
            autoclose: 'true',
            todayHighlight: true,
            startDate: 'today',
            format: 'yyyy-mm-dd'
        });
		
		$('.nest_head').change(function(){
			prep_nest($(this).data('findnest'), $(this).find(":selected").val(), 0);
		});
		
		function prep_nest(nestname, nestcode, selectedval){
			var childobj=$('select[data-nestname="'+nestname+'"]')
			
			var blockobj=$(childobj).find('option.nestopt');
			$(blockobj).prop('disabled', true);
			$(blockobj).addClass('d-none');
			
			var allowobj=$(childobj).find('option[data-nestcode="'+nestcode+'"]');
			$(allowobj).prop('disabled', false);
			$(allowobj).removeClass('d-none');
			
			selected_val=(selectedval==0)?$(childobj).children('option:first').val():selectedval;
			
			$(childobj).val(selected_val);
		}
		
		function storeRecHeader(){
			headRecBranchCode=$('#drp_credit_branch').find(":selected").val();
			headRecBranchName=$('#drp_credit_branch').find(":selected").text();
			headRecAcNum=$('#drp_credit_account').find(":selected").val();
			headRecAcDesc=$('#drp_credit_account').find(":selected").text();
			headRecNarration=$('#creditor_narration').val();
			headRecCreditor=$('#pay_creditor').val();
		}
		
		function verifyRecHeader(){
			var prompt_msg = '';
			
			if($('#hrefid').val()!=''){
				prompt_msg = (headRecBranchCode!=$('#drp_credit_branch').find(":selected").val())?'Credit branch : '+headRecBranchName+' as '+$('#drp_credit_branch').find(":selected").text()+'\r\n':'';
				prompt_msg += (headRecAcNum!=$('#drp_credit_account').find(":selected").val())?'Credit account : '+headRecAcDesc+' as '+$('#drp_credit_account').find(":selected").text()+'\r\n':'';
				prompt_msg += (headRecNarration!=$('#creditor_narration').val())?'Narration : '+headRecNarration+' as '+$('#creditor_narration').val()+'\r\n':'';
				prompt_msg += (headRecCreditor!=$('#pay_creditor').val())?'Paid To : '+headRecCreditor+' as '+$('#pay_creditor').val()+'\r\n':'';
				
			}
			
			return ((prompt_msg=='')?true:confirm('Proceed with modified Debit details ?\r\n'+prompt_msg));
		}
		
        // Create order part
        $('#btnordercreate').click(function(){
            $('#hrefid').val(''); // load-empty-record-number
			$('#hrefop').val('');
			$('#payment_complete').val(0);
			$('#modalcreateorder').modal('show');
			
			if($('.showdata').length>0){$('.showdata').remove();}
        });
		
		// Create other cheque payments part
        $('#btnOtherCreate').click(function(){
            $('#hrefid').val(''); // load-empty-record-number
			$('#hrefop').val('');
			$('#payment_complete').val(0);
			$('#modalCreateOther').modal('show');
			
			if($('.showdata').length>0){$('.showdata').remove();}
        });
		
		$('input[name="rad_pay_method"]').change(function(){
			//var cash_selected=$('#rad_cash').is(":checked");
			//$('#cheque_no').prop('disabled', cash_selected);
			//$('#cheque_date').prop('disabled', cash_selected);
			//$('#drp_bank').prop('disabled', cash_selected);
			
			var deposit_no=$("#hrefid").val();
			if(deposit_no==''){
				//getReceipts();
				//book_table.draw();
				/*
				console.log('b4-ca--'+$("#rad_cash").attr('data-togval'));
				console.log('b4-ch--'+$("#rad_cheque").attr('data-togval'));
				console.log('old-'+$('input[type="radio"][data-togval="1"]').attr('id'));
				*/
				$('input[type="radio"][data-togval="1"]').attr('data-togval', "0");
				
				/*
				console.log('new-'+$(this).attr('id'));
				*/
				$(this).attr('data-togval', "1");
				
				//$('#rec_amount').val('0.00'); // if(cheque_selected)
				
				/*
				console.log('ca--'+$("#rad_cash").attr('data-togval'));
				console.log('ch--'+$("#rad_cheque").attr('data-togval'));
				console.log('0->'+$('input[type="radio"][data-togval="0"]').attr('id'));
				console.log('1->'+$('input[type="radio"][data-togval="1"]').attr('id'));
				*/
			}else{
				//console.log('1--'+ !$('#rad_cash').is(":checked"));
				//console.log('2--'+ !$('#rad_cheque').is(":checked"));
				$('input[type="radio"][data-togval="1"]').prop("checked", true);
				alert('Changes to receipt type is not allowed');
				
			}
		});
		
		$(document).on("click", '.btnedit', function(){
			$('#hrefid').val($(this).data('refid')); // load-existing-record-number
			//
			
            var id = $('#hrefid').val();if($('.showdata').length>0){$('.showdata').remove();}if(id!=''){$('<a class="showdata" style="margin-right:15px; line-height:30px;" href="show_data.php?refno='+id+'&refch=P" target="_blank">show-data</a>').insertBefore('#op_btn_complete, #btn_complete');}
            $.ajax({
                type: "POST",
                data: {
                    refID: id
                },
				dataType:'JSON', 
                url: 'getprocess/get_creditor_payment.php',
                success: function(data) { 
					//alert(JSON.stringify(data));
                    if(data.particulars_data.length==0){
						$('#pay_creditor').val(data.pay_creditor);                   
						
						$('#creditor_narration').val(data.payment_head_narration);                   
						
						$('input[type="radio"][data-togval="1"]').attr('data-togval', "0");
						$('#rad_cash').prop('checked', (data.set_cash==1));
						$('#rad_cheque').prop('checked', (data.set_cheque==1));
						//console.log($('input[type="radio"]:checked').attr('id'));
						$('input[type="radio"]:checked').attr('data-togval', "1");
						
						$('#drp_credit_branch').val(data.credit_branch_id);
						prep_nest($('#drp_credit_branch').data('findnest'), data.credit_branch_id, data.credit_acc_id);
						//console.log('>>'+data.pay_complete);
						$('#payment_complete').val(data.pay_complete);
						
						book_table.clear();
						book_table.rows.add(data.table_data);
						book_table.draw();
						
						storeRecHeader();
						
						$('#modalcreateorder').modal('show');
					}else{
						$('#op_pay_creditor').val(data.pay_creditor);                   
						
						$('#op_creditor_narration').val(data.payment_head_narration);
						
						$('#op_credit_branch').val(data.credit_branch_id);
						prep_nest($('#op_credit_branch').data('findnest'), data.credit_branch_id, data.credit_acc_id);
						
						var debitdata = data.table_data[0];
						$("#hrefop").val(debitdata.detail_id);
						$('#op_debit_branch').val(debitdata.debit_branch_id);
						prep_nest($('#op_debit_branch').data('findnest'), debitdata.debit_branch_id, debitdata.debit_acc_id);
						$('#op_pay_narration').val(debitdata.payment_sub_narration);
						
						$('#payment_complete').val(data.pay_complete);
						
						op_table.clear();
						op_table.rows.add(data.particulars_data);
						op_table.draw();
						
						$('#modalCreateOther').modal('show');
					}
                }
            });
			
		});
		/*
		$('#modalcreateorder').on('shown.bs.modal', function () {});
		*/
        $('#modalcreateorder').on('hidden.bs.modal', function () {
            $('#pay_creditor').val('');
            
			$('#pay_invno').val('');
			
			$('#creditor_narration').val('');
            $('#pay_narration').val('');
            $('#pay_amount').val('0.00');
			$('#hrefid').val('');
			$('#hrefop').val('');
            //$('#tableDetails > tbody').html('');
			
			book_table.clear().draw();
        });
		
		$('#modalCreateOther').on('hidden.bs.modal', function () {
            $('#op_creditor_narration').val('');
			$('#op_pay_creditor').val('');
			$('#op_pay_narration').val('');
			$('#op_pay_invno').val('');
			$('#op_pay_amount').val('0.00');
			
			$('#hrefid').val('');
			$('#hrefop').val('');
            
			op_table.clear().draw();
        });
        
		$('#createorderform').on('submit', function(event){
            event.preventDefault();
			
			var refid = $("#hrefid").val();
            var paycreditor = $('#pay_creditor').val();
			var creditornarration = $('#creditor_narration').val();
			var creditbranch = $('#drp_credit_branch').find(":selected").val();
			var creditbranch_colcode = $('#drp_credit_branch').find(":selected").data('colcode');
			var creditacc = $('#drp_credit_account').find(":selected").val();
			var creditacc_colcode = $('#drp_credit_account').find(":selected").data('colcode');
			
			if(verifyRecHeader()){
				var debitbranch = $('#drp_debit_branch').find(":selected").val();
				var debitbranch_colcode = $('#drp_debit_branch').find(":selected").data('colcode');
				var debitacc = $('#drp_debit_account').find(":selected").val();
				var debitacc_colcode = $('#drp_debit_account').find(":selected").data('colcode');
				
				var invoiceno = $('#pay_invno').val();
				var paynarration = $('#pay_narration').val();
				var setcash = $('#rad_cash').is(':checked')?1:0;
				var payamount = $('#pay_amount').val();
	
				$.ajax({
					type: "POST",
					data: {
						ref_id: refid,
						pay_creditor: paycreditor,
						creditor_narration: creditornarration,
						debit_branch: debitbranch,
						debit_branch_colcode: debitbranch_colcode,
						debit_acc: debitacc,
						debit_acc_colcode: debitacc_colcode,
						credit_branch: creditbranch,
						credit_branch_colcode: creditbranch_colcode,
						credit_acc: creditacc,
						credit_acc_colcode: creditacc_colcode,
						invoice_no: invoiceno,
						pay_narration: paynarration,
						pay_amount:payamount, 
						set_cash:setcash
					},
					dataType: 'JSON',
					url: 'process/payment_reg_process.php',
					success: function(data) { //alert(JSON.stringify(data));
						if(data.msgdesc.type=="success"){
							if($("#hrefid").val()==''){
								$("#hrefid").val(data.head_k);
								main_table.row.add( {
									"header_id":data.head_k,
									"payment_credit_branch":'-', 
									"payment_credit_account":'-',
									"payment_head_narration":'-',
									"payment_creditor":paycreditor,
									"payment_head_amount":''
								}).draw( false ).node();
							}
							
							if(data.sub_k>0){
								var selected_tr=book_table.row('#pack-'+data.sub_k+'');
								
								if(selected_tr.length==0){
									var rowNode = book_table.row.add( {
										"payment_debit_branch":debitbranch_colcode,
										"payment_debit_account":debitacc_colcode,
										"detail_id":data.sub_k,
										"payment_sub_narration":paynarration,
										"paid_amount":payamount,
										"invoice_no":invoiceno
									}).draw( false ).node();
								}else{
									var d=selected_tr.data();
									d.payment_sub_narration=paynarration;
									d.paid_amount=payamount;
									d.invoice_no=invoiceno;
									book_table.row(selected_tr).data(d).draw();
								}
								
								storeRecHeader();
								
							}
						}
						
						action(data.msgdesc);
						
					}
				});
			}
        });
		
		$('#frmOtherPayment').on('submit', function(event){
            event.preventDefault();
			
			var refid = $("#hrefid").val();
			var refop = $("#hrefop").val();
            var paycreditor = $('#op_pay_creditor').val();
			var creditornarration = $('#op_creditor_narration').val();
			var creditbranch = $('#op_credit_branch').find(":selected").val();
			var creditbranch_colcode = $('#op_credit_branch').find(":selected").data('colcode');
			var creditacc = $('#op_credit_account').find(":selected").val();
			var creditacc_colcode = $('#op_credit_account').find(":selected").data('colcode');
			
			//if(verifyRecHeader()){
				var debitbranch = $('#op_debit_branch').find(":selected").val();
				var debitbranch_colcode = $('#op_debit_branch').find(":selected").data('colcode');
				var debitacc = $('#op_debit_account').find(":selected").val();
				var debitacc_colcode = $('#op_debit_account').find(":selected").data('colcode');
				
				var invoiceno = $('#op_pay_invno').val(); // particulars
				var paynarration = $('#op_pay_narration').val();
				var setcash = 0;//allow-cheques-only //$('#rad_cash').is(':checked')?1:0;
				var payamount = $('#op_pay_amount').val();
	
				$.ajax({
					type: "POST",
					data: {
						ref_id: refid,
						ref_op: refop,
						pay_creditor: paycreditor,
						creditor_narration: creditornarration,
						debit_branch: debitbranch,
						debit_branch_colcode: debitbranch_colcode,
						debit_acc: debitacc,
						debit_acc_colcode: debitacc_colcode,
						credit_branch: creditbranch,
						credit_branch_colcode: creditbranch_colcode,
						credit_acc: creditacc,
						credit_acc_colcode: creditacc_colcode,
						invoice_no: invoiceno,
						pay_narration: paynarration,
						pay_amount:payamount, 
						set_cash:setcash
					},
					dataType: 'JSON',
					url: 'process/payment_other_particulars_reg_process.php',
					success: function(data) { //alert(JSON.stringify(data));
						if(data.msgdesc.type=="success"){
							if($("#hrefid").val()==''){
								$("#hrefid").val(data.head_k);
								main_table.row.add( {
									"header_id":data.head_k,
									"payment_credit_branch":'-', 
									"payment_credit_account":'-',
									"payment_head_narration":'-',
									"payment_creditor":paycreditor,
									"payment_head_amount":''
								}).draw( false ).node();
							}
							
							if($("#hrefop").val()==''){
								$("#hrefop").val(data.sub_k);
							}
							
							if(data.part_k>0){
								var selected_tr=op_table.row('#part-'+data.part_k+'');
								
								if(selected_tr.length==0){
									var rowNode = op_table.row.add( {
										"detail_id":data.part_k,
										"paid_amount":payamount,
										"paid_particulars":invoiceno
									}).draw( false ).node();
								}else{
									var d=selected_tr.data();
									d.paid_amount=payamount;
									d.paid_particulars=invoiceno;
									op_table.row(selected_tr).data(d).draw();
								}
								
								//storeRecHeader();
								
							}
						}
						
						action(data.msgdesc);
						
					}
				});
			//}
        });
		
		$('#btn_complete, #op_btn_complete').click(function(){
			$.ajax({
				type: "POST",
				data: {
					ref_id: $("#hrefid").val()
				},
				dataType: "JSON",
				url: "process/payment_complete_process.php",
				success: function(data){
					var resmsg_data = {'icon':"fas fa-exclamation-triangle", 
							   'title':"", 
							   'message':"", 
							   'url':"", 
							   'target':"_blank", 
							   'type':"danger"};
					
					if(data.msgdesc.type=="success"){
						$('#payment_complete').val(data.rec_complete);
						resmsg_data = data.msgdesc;
					}else{
						if($('#hrefid').val()==''){
							resmsg_data.message="No receipt details available";
						}else if($('#payment_complete').val()==1){
							resmsg_data.message="Receipt details has already been processed";
						}else{
							resmsg_data.message=data.msgdesc.message;//"Unable to complete the process";
						}
					}
					
					action(resmsg_data);
					 
				}
			});
			
			
		});
		
        //main-table
		$(document).on( 'click', '.btndelete', function () {
		  	$(this).parent().parent().addClass("del_high");
			
			var selected_tr=main_table.$('tr.del_high');//$('tr.row_high');//#btn_delitem
			
			if(selected_tr.length>0){
				var item_desc=selected_tr.children("td:nth-child(2)").html();
				var del_res=confirm("Delete "+item_desc);
				if(del_res){
					//var itemref=($(".chkAct:checked").val());//#btn_delitem
					var itemref=$(this).data("packref");
					//console.log(">>"+itemref);
					
					$.ajax({
						method: "POST",
						url: 'alter',
						data: {item_ref:itemref},
						dataType: 'JSON', 
						beforeSend: function(){
							$("#lbl_warnmsg").html('<i class="fas fa-spinner fa-spin"></i>&nbsp;Please wait...').fadeIn();
						}
					}).done(function(data){
						var rres = data.resMsg;//.split(","); 
						if(data.msgErr){
							selected_tr.removeClass("del_high");
						}else{
							main_table.row(selected_tr).remove().draw();
							
						}
					});
				}else{
					selected_tr.removeClass("del_high");
				}
			}else{
				$("#lbl_warnmsg").html("Select the item you want to delete").fadeIn();
			}
		});
        
        //book-table
		$(document).on( 'click', '.act_del_item', function () {
		  	$(this).parent().parent().addClass("del_high");
			
			var selected_tr=book_table.$('tr.del_high');//$('tr.row_high');//#btn_delitem
			
			var resmsg_data = {'icon':"fas fa-exclamation-triangle", 
							   'title':"", 
							   'message':"", 
							   'url':"", 
							   'target':"_blank", 
							   'type':"danger"};
							   
			if(selected_tr.length>0){
				var item_desc=selected_tr.children("td:nth-child(2)").html();
				var del_res=confirm("Delete "+item_desc);
				if(del_res){
					var headref=$("#hrefid").val();
					var itemref=$(this).data("refid");
					//console.log(">>"+itemref);
					
					$.ajax({
						method: "POST",
						url: 'process/payment_del_process.php',
						data: {ref_id:headref, sub_id:itemref},
						dataType: 'JSON', 
						beforeSend: function(){
							//$("#lbl_warnmsg").html('<i class="fas fa-spinner fa-spin"></i>&nbsp;Please wait...').fadeIn();
						}
					}).done(function(data){
						if(data.msgdesc.type=="success"){
							book_table.row(selected_tr).remove().draw();
							resmsg_data = data.msgdesc;
						}else{
							selected_tr.removeClass("del_high");
							resmsg_data.message=data.msgdesc.message;
						}
						
						action(resmsg_data);
						
					});
				}else{
					selected_tr.removeClass("del_high");
				}
			}else{
				resmsg_data.message="Select the item you want to delete";//$("#lbl_warnmsg").html("").fadeIn();
			}
		});
		
		//op-table
		$(document).on( 'click', '.act_del_part', function () {
		  	$(this).parent().parent().addClass("del_high");
			
			var selected_tr=op_table.$('tr.del_high');//;//$('tr.row_high');//#btn_delitem
			
			var resmsg_data = {'icon':"fas fa-exclamation-triangle", 
							   'title':"", 
							   'message':"", 
							   'url':"", 
							   'target':"_blank", 
							   'type':"danger"};
							   
			if(selected_tr.length>0){
				var d=$(op_table.rows('tr.del_high').data())[0];
				var item_desc=d.paid_particulars;//selected_tr.children("td:nth-child(1)").html();
				var item_paid=d.paid_amount;//d[0].paid_amount;
				var del_res=confirm("Delete "+item_desc);//+" "+item_paid+' xxx '+JSON.stringify(d)
				
				if(del_res){
					var headref=$("#hrefid").val();
					var itemref=$("#hrefop").val();
					var partref=$(this).data("refid");
					//console.log(">>"+itemref);
					
					$.ajax({
						method: "POST",
						url: 'process/payment_other_particulars_del_process.php',
						data: {ref_id:headref, sub_id:itemref, part_id:partref, tot_paid:item_paid},
						dataType: 'JSON', 
						beforeSend: function(){
							//$("#lbl_warnmsg").html('<i class="fas fa-spinner fa-spin"></i>&nbsp;Please wait...').fadeIn();
						}
					}).done(function(data){
						if(data.msgdesc.type=="success"){
							op_table.row(selected_tr).remove().draw();
							resmsg_data = data.msgdesc;
							main_table.draw();
						}else{
							selected_tr.removeClass("del_high");
							resmsg_data.message=data.msgdesc.message;
						}
						
						action(resmsg_data);
						
					});
				}else{
					selected_tr.removeClass("del_high");
				}
			}else{
				resmsg_data.message="Select the item you want to delete";//$("#lbl_warnmsg").html("").fadeIn();
			}
		});
    });

    function action(data) { //alert(data);
        var obj = data;//JSON.parse(data);
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
            z_index: 5031,
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

    function print_receipt() {
        printJS({
            printable: 'viewdispatchprint',
            type: 'html',
            style: '@page { size: landscape; margin:0.25cm; }',
            targetStyles: ['*']
        })
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }

    
</script>
<?php include "include/footer.php"; ?>
