<?php 
require_once('../connection/db.php');

$tabledata=json_decode($_POST['tablejson']);
$arraylist=array();
$discounttotal=0;

foreach($tabledata as $rowtabledata){
    $invoiceno=$rowtabledata->col_1;
    $invoicetotal=$rowtabledata->col_5;
    $collecttype=$rowtabledata->col_11;
    $collectamount=$rowtabledata->col_10;

    if($collecttype>0 && $collectamount>0){
        if($collecttype==1){
            $discountamount=calculatediscount($collecttype, $collectamount);
        }
        else{
            $discountamount=calculatediscount($collecttype, $collectamount);
        }

        $objsub=new stdClass();
        $objsub->invoiceno=$invoiceno;
        $objsub->invtotal=$invoicetotal;
        $objsub->payamount=$collectamount;
        $objsub->discount=$discountamount;

        array_push($arraylist, $objsub);

        $discounttotal=$discounttotal+$discountamount;
    }
}

function calculatediscount($collecttype, $collectamount){
    if($collecttype==1){
        if($collectamount>100000 && $collectamount<200000){
            $discountamount=($collectamount*12)/100;
        }
        else if($collectamount>=200000 && $collectamount<300000){
            $discountamount=($collectamount*13)/100;
        }
        else if($collectamount>=300000 && $collectamount<400000){
            $discountamount=($collectamount*15)/100;
        }
        else if($collectamount>=400000 && $collectamount<500000){
            $discountamount=($collectamount*16)/100;
        }
        else if($collectamount>=500000 && $collectamount<600000){
            $discountamount=($collectamount*18)/100;
        }
        else if($collectamount>=600000 && $collectamount<700000){
            $discountamount=($collectamount*19)/100;
        }
        else if($collectamount>=700000 && $collectamount<800000){
            $discountamount=($collectamount*20)/100;
        }
        else if($collectamount>=800000 && $collectamount<900000){
            $discountamount=($collectamount*21)/100;
        }
        else if($collectamount>=900000 && $collectamount<1000000){
            $discountamount=($collectamount*22)/100;
        }
        else if($collectamount>=1000000){
            $discountamount=($collectamount*24)/100;
        }
        else if($collectamount<100000){
            $discountamount=($collectamount*11)/100;
        }

        return $discountamount;
    }
    else if($collecttype==2){
        if($collectamount>100000 && $collectamount<200000){
            $discountamount=($collectamount*8)/100;
        }
        else if($collectamount>=200000 && $collectamount<300000){
            $discountamount=($collectamount*9)/100;
        }
        else if($collectamount>=300000 && $collectamount<400000){
            $discountamount=($collectamount*11)/100;
        }
        else if($collectamount>=400000 && $collectamount<500000){
            $discountamount=($collectamount*12)/100;
        }
        else if($collectamount>=500000 && $collectamount<600000){
            $discountamount=($collectamount*14)/100;
        }
        else if($collectamount>=600000 && $collectamount<700000){
            $discountamount=($collectamount*15)/100;
        }
        else if($collectamount>=700000 && $collectamount<800000){
            $discountamount=($collectamount*16)/100;
        }
        else if($collectamount>=800000 && $collectamount<900000){
            $discountamount=($collectamount*17)/100;
        }
        else if($collectamount>=900000 && $collectamount<1000000){
            $discountamount=($collectamount*18)/100;
        }
        else if($collectamount>=1000000){
            $discountamount=($collectamount*20)/100;
        }
        else if($collectamount<100000){
            $discountamount=($collectamount*7)/100;
        }

        return $discountamount;
    }
}

$obj=new stdClass();
$obj->paymentlist=json_encode($arraylist);
$obj->totaldiscount=$discounttotal;

echo json_encode($obj);