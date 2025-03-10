<?php

require_once('../connection/db.php');

$id = $_POST['catalogcategoryid'];

$sql_product_details = "
SELECT 
    `tbl_catalog_details`.`idtbl_catalog_details`, 
    `tbl_catalog_details`.`status`, 
    `tbl_product`.`product_name`, 
    `tbl_product`.`idtbl_product`, 
    `tbl_product`.`saleprice`,  
    `tbl_product`.`product_code`, 
    SUM(`tbl_stock`.`qty`) AS `qty`, 
    `tbl_product`.`tbl_sizes_idtbl_sizes`, 
    `tbl_product`.`tbl_size_categories_idtbl_size_categories`, 
    `tbl_sizes`.`name` AS `sizename`, 
    `tbl_size_categories`.`name` AS `catname`,
    `tbl_catalog_category`.`sequence`
FROM 
    `tbl_catalog_details` 
    LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_catalog_details`.`product_name` 
    LEFT JOIN `tbl_catalog` ON `tbl_catalog`.`idtbl_catalog` = `tbl_catalog_details`.`tbl_catalog_idtbl_catalog` 
    LEFT JOIN `tbl_catalog_category` ON `tbl_catalog_category`.`idtbl_catalog_category` = `tbl_catalog`.`tbl_catalog_category_idtbl_catalog_category` 
    LEFT JOIN `tbl_stock` ON `tbl_stock`.`tbl_product_idtbl_product` = `tbl_product`.`idtbl_product` 
    LEFT JOIN `tbl_sizes` ON `tbl_sizes`.`idtbl_sizes` = `tbl_product`.`tbl_sizes_idtbl_sizes` 
    LEFT JOIN `tbl_size_categories` ON `tbl_size_categories`.`idtbl_size_categories` = `tbl_product`.`tbl_size_categories_idtbl_size_categories` 
WHERE 
    `tbl_catalog_details`.`tbl_catalog_idtbl_catalog`='$id' 
    AND `tbl_catalog_details`.`status`='1'
    AND `qty` > 0
GROUP BY 
    `tbl_product`.`idtbl_product`
ORDER BY
    `tbl_catalog_category`.`sequence` ASC";

$res_product_details = $conn->query($sql_product_details);
$product_details = array();

while ($row = mysqli_fetch_array($res_product_details)) {
    $productID = $row['idtbl_product'];
    $fullQty = $row['qty'];

    $sqlholdstock="SELECT COALESCE(SUM(`qty`), 0) as `qty` FROM `tbl_customer_order_hold_stock` WHERE `tbl_product_idtbl_product`='$productID' AND `status` = '1' AND `invoiceissue` = '0' GROUP BY `tbl_product_idtbl_product`";
    $resultholdstock = $conn->query($sqlholdstock);

    if ($rowholdstock = $resultholdstock->fetch_assoc()) {
        $holdqty = $rowholdstock['qty'];
    } else {
        $holdqty = 0;
    }

    if($fullQty-$holdqty > 0){
        array_push($product_details, array(
            "id" => $row['idtbl_catalog_details'],
            "name" => $row['product_name'],
            "productID" => $productID,
            "price" => $row['saleprice'],
            "fullQty" => $fullQty,
            "holdqty" => $holdqty,
            "availableQty" => $fullQty-$holdqty,
            "sequence" => $row['sequence']
        ));
    }
    
}


$sql_product_images = "
SELECT 
    `imagepath`, 
    `idtbl_product_image` 
FROM 
    `tbl_product_image`  
WHERE 
    `tbl_catalog_idtbl_catalog`='$id'";

$res_product_images = $conn->query($sql_product_images);

$product_images = array();

while ($row = mysqli_fetch_array($res_product_images)) {
    array_push($product_images, array(
        "imagepath" => $row['imagepath'],
        "id" => $row['idtbl_product_image']
    ));
}


$response = array(
    "product_details" => $product_details,
    "product_images" => $product_images
);

header('Content-Type: application/json');
print(json_encode($response));
