<?php

require_once('dbConnect.php');

$id = $_POST['catalogcategoryid'];
//$id = 28;


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
    `tbl_size_categories`.`name` AS `catname` 
FROM 
    `tbl_catalog_details` 
    LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product` = `tbl_catalog_details`.`product_name` 
    LEFT JOIN `tbl_stock` ON `tbl_stock`.`tbl_product_idtbl_product` = `tbl_product`.`idtbl_product` 
    LEFT JOIN `tbl_sizes` ON `tbl_sizes`.`idtbl_sizes` = `tbl_product`.`tbl_sizes_idtbl_sizes` 
    LEFT JOIN `tbl_size_categories` ON `tbl_size_categories`.`idtbl_size_categories` = `tbl_product`.`tbl_size_categories_idtbl_size_categories` 
WHERE 
    `tbl_catalog_details`.`tbl_catalog_idtbl_catalog`='$id' 
    AND `tbl_catalog_details`.`status`='1' 
GROUP BY 
    `tbl_product`.`idtbl_product`
HAVING 
    qty > 0";

$res_product_details = mysqli_query($con, $sql_product_details);

if (!$res_product_details) {
    die("Error in product details query: " . mysqli_error($con));
}

$product_details = array();

while ($row = mysqli_fetch_array($res_product_details)) {
    array_push($product_details, array(
        "id" => $row['idtbl_catalog_details'],
        "name" => $row['product_name'],
        "price" => $row['saleprice'],
        "qty" => $row['qty']
    ));
}


$sql_product_images = "
SELECT 
    `imagepath`, 
    `idtbl_product_image` 
FROM 
    `tbl_product_image`  
WHERE 
    `tbl_catalog_idtbl_catalog`='$id'";

$res_product_images = mysqli_query($con, $sql_product_images);

if (!$res_product_images) {
    die("Error in product images query: " . mysqli_error($con));
}

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
mysqli_close($con);
