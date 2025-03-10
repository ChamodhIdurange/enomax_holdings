<?php 
require_once('../connection/db.php');

$validfrom=$_POST['validfrom'];
$validto=$_POST['validto'];

$period = new DatePeriod(
     new DateTime($validfrom),
     new DateInterval('P1D'),
     new DateTime($validto)
);

$productarray=array();
$sqlproductlist="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `tbl_product_category_idtbl_product_category`=1 AND `status`=1";
$resultproductlist =$conn-> query($sqlproductlist);
while($rowproductlist = $resultproductlist-> fetch_assoc()){ 
    $obj=new stdClass();
    $obj->productID=$rowproductlist['idtbl_product'];
    $obj->product=$rowproductlist['product_name'];

    array_push($productarray, $obj);
}

$dayslist=array();
$productone=array();
$producttwo=array();
$productthree=array();
$productfour=array();

foreach ($period as $key => $value) {
    $days=$value->format('Y-m-d');  
    $dayslist[]=$days;
    
    foreach($productarray as $rowproductarray){ 
        $newsalecount=0;
        $refillsalecount=0;
        $trustsalecount=0;
        
        $productID=$rowproductarray->productID;

        $sqlsalecount="SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty`, SUM(`trustqty`) AS `trustqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$productID' AND `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `status`=1 AND `date`='$days')";
        $resultsalecount =$conn-> query($sqlsalecount);
        $rowsalecount = $resultsalecount-> fetch_assoc();

        if(!empty($rowsalecount['newqty'])){
            $newsalecount=$rowsalecount['newqty'];
        }
        if(!empty($rowsalecount['refillqty'])){
            $refillsalecount=$rowsalecount['refillqty'];
        }
        if(!empty($rowsalecount['trustqty'])){
            $trustsalecount=$rowsalecount['trustqty'];
        }

        $totsalecount=$newsalecount+$refillsalecount+$trustsalecount;

        if($productID==1){
            $productone[]=$totsalecount;
        }
        else if($productID==2){
            $producttwo[]=$totsalecount;
        }
        else if($productID==4){
            $productthree[]=$totsalecount;
        }
        else if($productID==6){
            $productfour[]=$totsalecount;
        }
    }
}
$html='';
$html.='<table class="table table-striped table-bordered table-sm small" id="tableproductsale">
    <thead>
        <tr>
            <th>Product</th>
            <th class="text-center">New Qty</th>
            <th class="text-center">Refill Qty</th>
            <th class="text-center">Trust Qty</th>
        </tr>
    </thead>
    <tbody>';
        foreach($productarray as $rowproductarray){ 
            $productID=$rowproductarray->productID;

            $sqlsalecount="SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty`, SUM(`trustqty`) AS `trustqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$productID' AND `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `status`=1 AND `date` BETWEEN '$validfrom' AND '$validto')";
            $resultsalecount =$conn-> query($sqlsalecount);
            $rowsalecount = $resultsalecount-> fetch_assoc();
        $html.='<tr>
            <td>'.$rowproductarray->product.'</td>
            <td class="text-center">'.$rowsalecount['newqty'].'</td>
            <td class="text-center">'.$rowsalecount['refillqty'].'</td>
            <td class="text-center">'.$rowsalecount['trustqty'].'</td>
        </tr>';
        }
    $html.='</tbody>
</table>';

$objdata=new stdClass();
$objdata->html=$html;
$objdata->days=$dayslist;
$objdata->productone=$productone;
$objdata->producttwo=$producttwo;
$objdata->productthree=$productthree;
$objdata->productfour=$productfour;

echo json_encode($objdata);
