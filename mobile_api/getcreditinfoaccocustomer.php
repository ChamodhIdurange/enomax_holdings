<?php
require_once('dbConnect.php');

function dateDiff($beginDate, $endDate) {
    $fromDate = date('Y-n-j',strtotime($beginDate));
    $toDate = date('Y-n-j',strtotime($endDate));
    $date_parts1=explode('-', $beginDate);
    $date_parts2=explode('-', $endDate);
    $start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
    $end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
    return $end_date - $start_date;
}

$record=$_POST['customerID'];
$today=date('Y-m-d');

$sql="SELECT `credittype`, `creditperiod`, `emergencydate` FROM `tbl_customer` WHERE `idtbl_customer`='$record' AND `status`=1";
$result=$con->query($sql);
$row=$result->fetch_assoc();

if($row['emergencydate']==$today){
    echo '0';
}
else{
    if($row['credittype']==1){
        $sqlcheck="SELECT COUNT(*) as `count` FROM `tbl_invoice` WHERE `tbl_customer_idtbl_customer`='$record' AND `status`=1 AND `paymentcomplete`=0";
        $resultcheck=$con->query($sqlcheck);
        $rowcheck=$resultcheck->fetch_assoc();

        if($rowcheck['count']>2){
            echo '1';
        }
        else{
            echo '0';
        }
    }
    else if($row['credittype']==2){
        $sqlcheck="SELECT `date` FROM `tbl_invoice` WHERE `tbl_customer_idtbl_customer`='$record' AND `status`=1 AND `paymentcomplete`=0 ORDER BY `date` DESC LIMIT 1";
        $resultcheck=$con->query($sqlcheck);
        $rowcheck=$resultcheck->fetch_assoc();

        if($resultcheck->num_rows>0){
            $numofdays=dateDiff($rowcheck['date'],$today);

            if($numofdays>$row['creditperiod']){
                echo '1';
            }
            else{
                echo '0';
            }
        }else{
            echo '0';
        }      
    }
    else if($row['credittype']==3){
        echo '0';
    }
}

?>