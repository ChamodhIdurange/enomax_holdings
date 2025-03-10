<?php
require_once('../connection/db.php');

$orderID=$_POST['orderID'];

$sqlorder="SELECT `nettotal`, `remark` FROM `tbl_porder` WHERE `idtbl_porder`='$orderID'";
$resultorder=$conn->query($sqlorder);
$roworder=$resultorder->fetch_assoc();

$detailarray=array();
    $sqlorderdetail="SELECT  `tbl_product`.`idtbl_product`, `tbl_porder_detail`.`idtbl_porder_detail`, `tbl_porder_detail`.`total`, `tbl_product`.`product_name`, `tbl_product`.`idtbl_product`, `tbl_porder_detail`.`unitprice`, `tbl_porder_detail`.`saleprice`, `tbl_product`.`retail`,`tbl_porder_detail`.`qty` FROM `tbl_porder_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_porder_detail`.`tbl_product_idtbl_product` WHERE `tbl_porder_detail`.`status`=1 AND `tbl_porder_detail`.`tbl_porder_idtbl_porder`='$orderID'";
    $resultorderdetail=$conn->query($sqlorderdetail);

    while($roworderdetail=$resultorderdetail->fetch_assoc()){

    
        $objdetail=new stdClass();
        $objdetail->product_name=$roworderdetail['product_name'];
        $objdetail->productid=$roworderdetail['idtbl_product'];
        $objdetail->unitprice=$roworderdetail['unitprice'];
        $objdetail->saleprice=$roworderdetail['saleprice'];
        $objdetail->retail=$roworderdetail['retail'];
        $objdetail->qty=$roworderdetail['qty'];
        $objdetail->total=$roworderdetail['total'];;
        $objdetail->podetailId=$roworderdetail['idtbl_porder_detail'];;
        $objdetail->idtbl_product=$roworderdetail['idtbl_product'];;
    
        array_push($detailarray, $objdetail);
    }

$obj=new stdClass();
$obj->remark=$roworder['remark'];
$obj->nettotalshow=number_format($roworder['nettotal'], 2);
$obj->nettotal=$roworder['nettotal'];
$obj->tablelist=$detailarray;

echo json_encode($obj);

?>