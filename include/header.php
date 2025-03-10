<?php 
session_start();
$getUrl=$_SERVER['SCRIPT_NAME'];
$url=explode('/', $getUrl);
$lastElement=end($url);
if($lastElement!="index.php"){if(!isset($_SESSION['userid'])){header ("Location:index.php");}}
require_once('connection/db.php');

$menuprivilegearray=array();

if(isset($_SESSION['userid'])){
    $userSessionID=$_SESSION['userid'];
    
    $sqlmenucheck="SELECT `idtbl_menu_list`, `menu` FROM `tbl_menu_list` WHERE `status`=1";
    $resultmenucheck =$conn-> query($sqlmenucheck);
    
    while($rowmenucheck = $resultmenucheck-> fetch_assoc()){
        $menucheckID=$rowmenucheck['idtbl_menu_list'];
        $menuname=str_replace(" ","_",$rowmenucheck['menu']);
        
        $sqlprivilegecheck="SELECT `add`, `edit`, `statuschange`, `remove`, `access_status`, `tbl_menu_list_idtbl_menu_list` FROM `tbl_user_privilege` WHERE `tbl_user_idtbl_user`='$userSessionID' AND `tbl_menu_list_idtbl_menu_list`='$menucheckID' AND `status`=1";
        $resultprivilegecheck =$conn-> query($sqlprivilegecheck);
        $rowprivilegecheck = $resultprivilegecheck-> fetch_assoc();
        
        $objmenu=new stdClass();
        $objmenu->add=$rowprivilegecheck['add'];
        $objmenu->edit=$rowprivilegecheck['edit'];
        $objmenu->statuschange=$rowprivilegecheck['statuschange'];
        $objmenu->remove=$rowprivilegecheck['remove'];
        $objmenu->access_status=$rowprivilegecheck['access_status'];
        $objmenu->menuid=$rowprivilegecheck['tbl_menu_list_idtbl_menu_list'];
        array_push($menuprivilegearray, $objmenu);
    }
}

$actionJSON='';
if(isset($_GET['action'])){
    if($_GET['action']==4){
        $actionObj=new stdClass();
        $actionObj->icon='fas fa-save';
        $actionObj->title='';
        $actionObj->message='Record Added Successfully';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='success';

        $actionJSON=json_encode($actionObj);
    }
    else if($_GET['action']==1){
        $actionObj=new stdClass();
        $actionObj->icon='fas fa-check-circle';
        $actionObj->title='';
        $actionObj->message='Record Activate Successfully';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='success';

        $actionJSON=json_encode($actionObj);
    }
    else if($_GET['action']==2){
        $actionObj=new stdClass();
        $actionObj->icon='fas fa-times-circle';
        $actionObj->title='';
        $actionObj->message='Record Deativate Successfully';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='warning';

        $actionJSON=json_encode($actionObj);
    }
    else if($_GET['action']==3){
        $actionObj=new stdClass();
        $actionObj->icon='fas fa-trash-alt';
        $actionObj->title='';
        $actionObj->message='Record Delete Successfully';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='danger';

        $actionJSON=json_encode($actionObj);
    }
    else if($_GET['action']==5){
        $actionObj=new stdClass();
        $actionObj->icon='fas fa-exclamation-triangle';
        $actionObj->title='';
        $actionObj->message='Record Error';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='danger';

        $actionJSON=json_encode($actionObj);
    }
    else if($_GET['action']==6){
        $actionObj=new stdClass();
        $actionObj->icon='fas fa-save';
        $actionObj->title='';
        $actionObj->message='Record Update Successfully';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='primary';

        $actionJSON=json_encode($actionObj);
    }
}

function menucheck($arraymenu, $menuID){
    foreach($arraymenu as $array){
        if($array->menuid==$menuID && $array->access_status=='1'){
            return $array->access_status;
        }
    }
    return '0';
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>ENOMAX Holdings (PVT) LTD- By Erav Technology</title>
        <link href="assets/css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js"></script>
        <!-- Datepicker CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js"></script>
        <link href="assets/css/style.css" rel="stylesheet" />
        <link href="assets/css/animate.css" rel="stylesheet" />
        <link href="assets/css/select2.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
        <link rel="stylesheet" href="assets/slick/slick.css">
        <link rel="stylesheet" href="assets/icofont/icofont.min.css">
        <link rel="stylesheet" href="assets/flaticon/flaticon.css">

 
    </head>
    <body class="nav-fixed">