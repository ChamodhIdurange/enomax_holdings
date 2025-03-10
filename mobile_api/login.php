<?php

require_once('dbConnect.php');
$username = $_POST["username"];
$password1 = $_POST["password"];
$logintype = $_POST["logintype"];

$md5password = md5($password1);
$id = "";
$type = "";
$refName = "";
$phone = "";
$address = "";
$code = "500";
$emp_id = "";
$LastMobileInnoNo = "";

$sql = "SELECT * FROM `tbl_user` WHERE `status`='1' AND  `username`='$username' AND `password`='$md5password'";
$result = mysqli_query($con, $sql);
// $response = array();
if (mysqli_num_rows($result) > 0) {

    $row = mysqli_fetch_row($result);
    $id = $row[0];
    if($logintype == 0){
        $sqlRefData = "SELECT `idtbl_employee`, `name`, `epfno`, `nic`, `phone`, `address`, `status`, `updatedatetime`,`tbl_user_type_idtbl_user_type`, `useraccountid` FROM `tbl_employee` WHERE `useraccountid`='$id'";
        $resultRefData = mysqli_query($con, $sqlRefData);

        $rowRefDta = mysqli_fetch_array($resultRefData);
        $refName = "";
        $phone = "";
        $emp_id = "";
        $userType = "";
        if ($rowRefDta) {
            $refName = $rowRefDta['name'];
            $phone = $rowRefDta['phone'];
            $emp_id = $rowRefDta['idtbl_employee'];
            $userType = $rowRefDta['tbl_user_type_idtbl_user_type'];
            $useraccountid = $rowRefDta['useraccountid'];

            $code = "200";

            $response = array("code" => $code, "refid" => $id, "empid" => $emp_id, "name" => $refName, "mobile" =>  $phone, "userType" => $userType, "userAccountId" => $useraccountid);
            print_r(json_encode($response));
        }else{
            $code = "500";
            $response = array("code" => $code, "refid" => $id, "empid" => $emp_id, "name" => $refName, "mobile" => $phone, "userType" => $userType);
            print_r(json_encode($response));
        }
    }else{
        $sqlAreaManagerData = "SELECT `idtbl_sales_manager`, `salesmanagername`, `contactone`, `tbl_user_idtbl_user` FROM `tbl_sales_manager` WHERE `tbl_user_idtbl_user`='$id'";
        $resultAreaManagerData = mysqli_query($con, $sqlAreaManagerData);

        $rowRefDta = mysqli_fetch_array($resultAreaManagerData);
        $refName = "";
        $phone = "";
        $emp_id = "";
        $userType = "13";
        if ($rowRefDta) {
            $refName = $rowRefDta['salesmanagername'];
            $phone = $rowRefDta['contactone'];
            $emp_id = $rowRefDta['idtbl_sales_manager'];
            $userType = 13;
            $useraccountid = $rowRefDta['tbl_user_idtbl_user'];

            $code = "200";

            $response = array("code" => $code, "refid" => $id, "empid" => $emp_id, "name" => $refName, "mobile" =>  $phone, "userType" => $userType, "userAccountId" => $useraccountid);
            print_r(json_encode($response));
        }else{
            $code = "500";
            $response = array("code" => $code, "refid" => $id, "empid" => $emp_id, "name" => $refName, "mobile" => $phone, "userType" => $userType);
            print_r(json_encode($response));
        }
    }
    
  
    
} else {

    $code = "500";
    $response = array("code" => $code, "refid" => $id, "empid" => $emp_id, "name" => $refName, "mobile" => $phone, "userType" => $userType);
    print_r(json_encode($response));
}
mysqli_close($con);
