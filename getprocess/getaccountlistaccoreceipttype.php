<?php 
require_once('../connection/db.php');

$typeID=$_POST['typeID'];
$company=$_POST['company'];
$companybranch=$_POST['companybranch'];

if($typeID==1){
    $sql="SELECT `subaccount` FROM `tbl_subaccount` WHERE `subaccount` IN (SELECT `subaccountno` FROM `tbl_account_allocation` WHERE `tbl_company_idtbl_company`='$company' AND `tbl_company_branch_idtbl_company_branch`='$companybranch' AND `status`=1) AND `status`=1 AND `tbl_account_category_idtbl_account_category`>1";
    $result=$conn->query($sql);
}
else if($typeID==2){
    $sql="SELECT `subaccount` FROM `tbl_subaccount` WHERE `subaccount` IN (SELECT `subaccountno` FROM `tbl_account_allocation` WHERE `tbl_company_idtbl_company`='$company' AND `tbl_company_branch_idtbl_company_branch`='$companybranch' AND `status`=1) AND `status`=1 AND `tbl_account_category_idtbl_account_category`=1";
    $result=$conn->query($sql);
}
else if($typeID==3){
    $sql="SELECT `subaccount` FROM `tbl_subaccount` WHERE `subaccount` IN (SELECT `subaccountno` FROM `tbl_account_allocation` WHERE `tbl_company_idtbl_company`='$company' AND `tbl_company_branch_idtbl_company_branch`='$companybranch' AND `status`=1) AND `status`=1";
    $result=$conn->query($sql);
}

$arrayaccountlist=array();
while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->subaccount=$row['subaccount'];
    
    array_push($arrayaccountlist, $obj);
}

echo json_encode($arrayaccountlist);