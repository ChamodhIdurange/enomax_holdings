<?php 
require_once('../connection/db.php');

$noncurrenttot=0;
$currenttot=0;
$equitytot=0;
$currentliabilitiestot=0;
$noncurrentbanktot=0;

$totassets=0;
$addclosestock=0;
$totpandlless=0;
$tot_transfer=0;
$stockclosebal=0;

$param_date_fr=$_POST['param_date_fr'];
$param_date_to=$_POST['param_date_to'];
$branch=1;

// P & L profit calculation

$pre_sql = "SELECT idtbl_master FROM tbl_master WHERE tbl_company_branch_idtbl_company_branch=1 AND status=1";
$stmtReg = $conn->prepare($pre_sql);
//$stmtReg->bind_param('', '')
$stmtReg->execute();
$stmtReg->store_result();
$stmtReg->bind_result($idtbl_master);
$row_rs = $stmtReg->fetch();

function add_sect($sect_code, $fig_value_col, $fig_grp_sum=false, $cnt_rev=false){
	global $conn;
	global $idtbl_master;
	
	$sql = "SELECT tbl_gl_report_sub_sections.id AS fig_sect_ref, tbl_gl_report_sub_sections.sub_section_name AS sect_name, CONCAT(tbl_gl_report_sub_section_particulars.subaccount, ' ', tbl_subaccount.subaccountname) AS fig_name, ((IFNULL(drv_open.ac_open_balance, 0)*tbl_gl_report_sub_section_particulars.value_ac_open_bal)+(IFNULL(drv_crdr.accamount, 0)*tbl_gl_report_sub_section_particulars.value_ac_cr_dr)) AS fig_value FROM tbl_gl_report_sub_sections INNER JOIN tbl_gl_report_sub_section_particulars ON tbl_gl_report_sub_sections.id=tbl_gl_report_sub_section_particulars.tbl_gl_report_sub_section_id INNER JOIN tbl_subaccount ON tbl_gl_report_sub_section_particulars.subaccount=tbl_subaccount.subaccount ";
	$sql .= "LEFT OUTER JOIN (SELECT subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details WHERE tbl_master_idtbl_master=? GROUP BY subaccount) AS drv_open ON tbl_gl_report_sub_section_particulars.subaccount=drv_open.subaccount ";
	$sql .= "LEFT OUTER JOIN (SELECT acccode, SUM((accamount*(crdr='D'))+((accamount*(crdr='C'))*-1)) AS accamount FROM tbl_account_transaction WHERE reversstatus=0 AND tbl_master_idtbl_master=? GROUP BY acccode) AS drv_crdr ON tbl_gl_report_sub_section_particulars.subaccount=drv_crdr.acccode ";
	$sql .= "WHERE tbl_gl_report_sub_sections.tbl_gl_report_head_section_id=? AND tbl_gl_report_sub_sections.sect_cancel=0 AND tbl_gl_report_sub_section_particulars.report_part_cancel=0 ORDER BY tbl_gl_report_sub_section_particulars.fig_seq_no, tbl_gl_report_sub_section_particulars.tbl_gl_report_sub_section_id";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('sss', $idtbl_master, $idtbl_master, $sect_code);
	$stmt->execute();
	$stmt->store_result();
	$total_rsFigs=$stmt->num_rows;
	$stmt->bind_result($fig_sect_ref, $sect_name, $fig_name, $fig_value);
	$row_rs=$stmt->fetch();
	
	$row_pos=0;
	$sub_sect_ref='-1';
	
	//$fig_value=0;//10;
	$fig_total=0;
	$sect_total=0;
	
	$col_values = array('l'=>array('', ''), 'm'=>array('', ''), 'r'=>array('', ''));
	$col_grpsum = array('l'=>'m', 'm'=>'r');
	
	
	//$doc_sect_ref='-1';
	$tot_sect_ref=false; // keep-track-of-group-total-allocation-to-be-cleared
	
	if($total_rsFigs>0){
		while($row_pos<$total_rsFigs){
			$col_values[$fig_value_col][0]=number_format((float)$fig_value, 2, '.', '');
			$col_values[$fig_value_col][1]=' class="text-right"';
	
			
			if($tot_sect_ref){
				$col_values[$col_grpsum[$fig_value_col]][0]='';
				$col_values[$col_grpsum[$fig_value_col]][1]='';
				$tot_sect_ref=false;
			}
			
			$fig_grp_name=''; // keep-section-name
			
			$fig_disp_name=$fig_name; // keep-particulars-name-to-be-presented-even-after-fetching-next-record
			
			$fig_bottom_border = '';
			
			$sect_total+=$fig_value;
			
			if($sub_sect_ref!=$fig_sect_ref){
				$fig_grp_name=''.$sect_name.'&nbsp;';//echo '<tr><td colspan="5">'.$sect_name.'</td></tr>';
				$sub_sect_ref=$fig_sect_ref;
				$fig_total=$fig_value;
			}else{
				$fig_total+=$fig_value;
			}
			
			$row_pos++;
			$stmt->fetch();
			
			$col_lm=(($fig_value_col=='l') || (($fig_value_col=='m') && $fig_grp_sum));
			$col_xm=(($fig_value_col=='l') || ($fig_value_col=='m'));
			
			$grp_summary_format='text-right';
			$acc_summary_format='text-right';
			
			if($col_lm || $col_xm){
				if($col_xm){
					if($row_pos==$total_rsFigs){
						$grp_summary_format='text-right sect_col';
						$acc_summary_format='text-right sect_col';
					}
					
				}
				if($col_lm){
					if(($sub_sect_ref!=$fig_sect_ref)||($row_pos==$total_rsFigs)){
						$tot_sect_ref=true;
						
						$grp_summary_format='text-right sect_col';
						
						
						if(($fig_value_col=='l') && ($row_pos==$total_rsFigs)){
							$col_values['r'][0]=number_format((float)$sect_total, 2, '.', '');
							$col_values['r'][1]=' class="text-right sect_col"';
							
						}
						
						
						$col_values[$col_grpsum[$fig_value_col]][0]=number_format((float)$fig_total, 2, '.', '');
						$col_values[$col_grpsum[$fig_value_col]][1]=' class="'.$acc_summary_format.'"';
						
						if($row_pos<$total_rsFigs){
							// $fig_bottom_border='<tr><td colspan=5>&nbsp;</td></tr>';
						}
					}
					
					if($fig_grp_name!=''){
						// echo '<tr><td colspan="5">'.$fig_grp_name.'</td></tr>';
						$fig_grp_name='';
					}
				}
				
				$col_values[$fig_value_col][1]=' class="'.$grp_summary_format.'"';//' class="text-right sect_col"';
				
			}
			
			// echo '<tr><td colspan="2">'.$fig_grp_name.$fig_disp_name.'</td><td'.$col_values['l'][1].'>'.$col_values['l'][0].'</td><td'.$col_values['m'][1].'>'.$col_values['m'][0].'</td><td'.$col_values['r'][1].'>'.$col_values['r'][0].'</td></tr>';
			
			// echo $fig_bottom_border;
			
		}
	}else{
		if($cnt_rev){
			$sect_total = 0;
		}
	}
	
	return $sect_total;
}

function calc_stock($opening_stock=false){
	global $conn;
	$sql = "";
	
	if($opening_stock){
        $lastenddate=date("Y-m-d", strtotime("last day of previous month"));
		$sql = "SELECT closingstock AS stock_close FROM tbl_stock_closing WHERE `date`='$lastenddate'";
	}else{
		$sql = "SELECT SUM(tbl_stock.fullqty*tbl_product.unitprice) AS stock_close_value FROM tbl_stock INNER JOIN tbl_product ON tbl_stock.tbl_product_idtbl_product=tbl_product.idtbl_product WHERE tbl_stock.status=1 AND tbl_stock.fullqty>0";
	}
	
	$stmt = $conn->prepare($sql);
	//$stmt->bind_param('', '')
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($stock_close_value);
	$row_rs = $stmt->fetch();
	
	return $stock_close_value;
}

$sqlmaster="SELECT tbl_finacial_year.`desc` AS financial_year, tbl_master.idtbl_master FROM tbl_master INNER JOIN tbl_finacial_year ON tbl_master.tbl_finacial_year_idtbl_finacial_year=tbl_finacial_year.idtbl_finacial_year WHERE tbl_master.tbl_company_branch_idtbl_company_branch='$branch' AND tbl_master.status=1";
$resultmaster=$conn->query($sqlmaster);
$rowmaster=$resultmaster->fetch_assoc();

$idtbl_master=$rowmaster['idtbl_master'];

$sqlheadsection="SELECT * FROM `tbl_gl_report_head_sections` WHERE `report_id`='BAL'";
$resultheadsection=$conn->query($sqlheadsection);

$i=1;
while($rowheadsection=$resultheadsection->fetch_assoc()){
    $headid=$rowheadsection['id'];

    $sqlaccount="SELECT `tbl_gl_report_sub_sections`.`sub_section_name`, `tbl_gl_report_sub_section_particulars`.`idtbl_subaccount`, `tbl_gl_report_sub_section_particulars`.`subaccount`, `tbl_gl_report_sub_sections`.`id` FROM `tbl_gl_report_sub_sections` LEFT JOIN `tbl_gl_report_sub_section_particulars` ON `tbl_gl_report_sub_section_particulars`.`tbl_gl_report_sub_section_id`=`tbl_gl_report_sub_sections`.`id` WHERE `tbl_gl_report_sub_sections`.`tbl_gl_report_head_section_id`='$headid' AND `tbl_gl_report_sub_sections`.`sect_cancel`=0";
    $resultaccount=$conn->query($sqlaccount);

    if($i>1){
?>
    <tr>
        <th class="font-weight-bold"></th>
        <td></td>
        <td></td>
        <th class="text-right"><?php echo number_format($totassets, 2); $totassets=0; ?></th>
    </tr>
<?php 
    } 
?>
    <tr>
        <th class="font-weight-bold"><?php echo $rowheadsection['head_section_name'] ?></th>
        <td></td>
        <td></td>
        <td></td>
    </tr>
<?php 
    $subhead='';
    while($rowaccount=$resultaccount->fetch_assoc()){ 
        $accountnumber=$rowaccount['subaccount'];
        $accountid=$rowaccount['idtbl_subaccount'];
        $subheadid=$rowaccount['id'];

        $sqltotrecord="SELECT COUNT(*) AS `count` FROM `tbl_gl_report_sub_section_particulars` WHERE `tbl_gl_report_head_section_id`='$headid' AND `tbl_gl_report_sub_section_id`='$subheadid'";
        $resulttotrecord=$conn->query($sqltotrecord);
        $rowtotrecord=$resulttotrecord->fetch_assoc();

        $sqlbal="SELECT accname, accamount, crdr, accnum FROM (SELECT CONCAT(drv_acc.subaccountno, ' ', tbl_subaccount.subaccountname) AS accname, (SELECT drv_acc.subaccountno) AS accnum, (IFNULL(drv_open.ac_open_balance, 0)+ABS(IFNULL(drv_reg.accamount, 0))) AS accamount, tbl_mainclass.transactiontype AS crdr FROM (SELECT DISTINCT subaccountno FROM tbl_account_allocation WHERE tbl_company_branch_idtbl_company_branch='$branch') AS drv_acc INNER JOIN tbl_subaccount ON drv_acc.subaccountno=tbl_subaccount.subaccount INNER JOIN tbl_mainclass ON tbl_subaccount.mainclasscode=tbl_mainclass.code LEFT OUTER JOIN (SELECT subaccount, SUM(ac_open_balance) AS ac_open_balance FROM tbl_gl_account_balance_details WHERE tbl_master_idtbl_master='$idtbl_master' GROUP BY subaccount) AS drv_open ON drv_acc.subaccountno=drv_open.subaccount LEFT OUTER JOIN (SELECT acccode, SUM((accamount*(crdr='D'))+(accamount*(crdr='C')*-1)) AS accamount, crdr FROM `tbl_account_transaction` WHERE `reversstatus`=0 AND `tbl_master_idtbl_master`='$idtbl_master' AND `tradate`<=DATE(NOW()) GROUP BY acccode) AS drv_reg ON drv_acc.subaccountno=drv_reg.acccode WHERE tbl_subaccount.status=1 ORDER BY crdr DESC, drv_acc.subaccountno) AS drv_rpt WHERE accnum='$accountnumber'";
        $resultbal=$conn->query($sqlbal);
        $rowbal=$resultbal->fetch_assoc();

        if($subhead!==$rowaccount['sub_section_name']){
			$noncurrenttot=$rowbal['accamount'];
			if($subheadid==20){
				$j=0;
			}
			else{
				$j=1;
			}
			// $j=1;
        }
        else{
            $noncurrenttot=$noncurrenttot+$rowbal['accamount'];
            $j++;
        }
    
    if($subheadid==22){
?>
    <tr>
        <td class="pl-4"><?php echo $subhead=$rowaccount['sub_section_name']; ?></td>
        <td><?php echo $rowbal['accname']; ?></td>
        <td class="text-right">
            <?php 
                $tot_sale = add_sect('1', 'r'); 
                $open_stock = calc_stock(true);
                $tot_sect = $open_stock+add_sect('2', 'm');
                $tot_stock = calc_stock();
                $cost_of_sale = $tot_sect-$tot_stock;
                $gross_profit = $tot_sale-$cost_of_sale;
                $tot_other_income = add_sect('4', 'm', true, true);
                $tot_income = $gross_profit+$tot_other_income;
                $tot_expenses = add_sect('3', 'l');
                $tot_transfer = $tot_income-$tot_expenses;
                echo number_format($tot_transfer, 2);
            ?>
        </td>
        <th class="text-right">&nbsp;</th>
    </tr>
<?php 
    }
    else if($subheadid==23){
?>
    <tr>
        <td class="pl-4"><?php echo $subhead=$rowaccount['sub_section_name']; ?></td>
        <td><?php echo $rowbal['accname']; ?></td>
        <td class="text-right sect_col">(<?php echo number_format($rowbal['accamount'], 2); ?>)</td>
        <th class="text-right"><?php $totpandlless=($tot_transfer-$rowbal['accamount']); echo number_format($totpandlless, 2); $totassets=$totassets+$totpandlless; ?></th>
    </tr>
<?php
    }
    else{
		if($subheadid==20 && $j==0){
			$sqlclosestock="SELECT `closingstock` FROM `tbl_stock_closing` WHERE `status`=1 AND `date`='$param_date_fr'";
			$resultclosestock=$conn->query($sqlclosestock);
			$rowclosestock=$resultclosestock->fetch_assoc();

			$stockclosebal=$stockclosebal+$rowclosestock['closingstock'];
?>
	<tr>
        <td class="pl-4"><?php echo $subhead=$rowaccount['sub_section_name']; ?></td>
        <td>Closing Stock <?php echo $param_date_fr; ?></td>
        <td class="text-right"><?php echo number_format($rowclosestock['closingstock'], 2);  ?></td>
        <th class="text-right">&nbsp;</th>
    </tr>
	<tr>
        <td class="pl-4"><?php if($subhead!==$rowaccount['sub_section_name']){echo $subhead=$rowaccount['sub_section_name'];} ?></td>
        <td><?php echo $rowbal['accname']; ?></td>
        <td class="text-right <?php if($rowtotrecord['count']==$j && $subheadid!=20){echo 'sect_col';}else if($rowtotrecord['count']==$j && $subheadid==20){echo 'sect_col';} ?>"><?php echo number_format($rowbal['accamount'], 2); ?></td>
        <th class="text-right <?php if($rowtotrecord['count']==$j && $subheadid==25){echo 'sect_col';}else if($rowtotrecord['count']==$j && $subheadid==20){echo 'sect_col';} ?>"><?php if($rowtotrecord['count']==$j && $subheadid!=20){echo number_format($noncurrenttot,2); $totassets=$totassets+$noncurrenttot;}else if($rowtotrecord['count']==$j && $subheadid==20){echo number_format($noncurrenttot,2); $totassets=$totassets+$noncurrenttot;} ?></th>
    </tr>
<?php 
		$j++;}
		else{ 
?>
    <tr>
        <td class="pl-4"><?php if($subhead!==$rowaccount['sub_section_name']){echo $subhead=$rowaccount['sub_section_name'];} ?></td>
        <td><?php echo $rowbal['accname']; ?></td>
        <td class="text-right <?php if($rowtotrecord['count']==$j && $subheadid!=20){echo 'sect_col';}else if($rowtotrecord['count']==$j && $subheadid==20){echo 'sect_col';} ?>"><?php echo number_format($rowbal['accamount'], 2); ?></td>
        <th class="text-right <?php if($rowtotrecord['count']==$j && $subheadid==25){echo 'sect_col';}else if($rowtotrecord['count']==$j && $subheadid==20){echo 'sect_col';} ?>"><?php if($rowtotrecord['count']==$j && $subheadid!=20){echo number_format($noncurrenttot,2); $totassets=$totassets+$noncurrenttot;}else if($rowtotrecord['count']==$j && $subheadid==20){echo number_format(($noncurrenttot+$stockclosebal),2); $totassets=$totassets+($noncurrenttot+$stockclosebal);} ?></th>
    </tr>    
<?php }}} if($i==$resultheadsection->num_rows){ ?>
    <tr>
        <th class="font-weight-bold"></th>
        <td></td>
        <td></td>
        <th class="text-right sect_col"><?php echo number_format($totassets, 2); ?></th>
    </tr>
<?php }$i++;} ?>