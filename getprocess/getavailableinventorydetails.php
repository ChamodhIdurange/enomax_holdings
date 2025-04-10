<?php 
session_start();
require_once('../connection/db.php');

$categorylist = $_POST['categorylist'];
$categorylist = implode(", ", $categorylist);
$fulltotal = 0;

$today = date("Y-m-d");

$sqloutstanding = "SELECT 
                    `p`.`idtbl_product`,
                    `p`.`product_code`,
                    `p`.`product_name`,
                    `p`.`saleprice`,
                    `p`.`retail`,
                    `p`.`unitprice`,
                    `g`.`idtbl_group_category`,
                    `g`.`category` AS 'groupcategory', 
                     (p.saleprice - p.unitprice) * 100 / p.unitprice AS percentageVal,
                     COALESCE((SELECT SUM(`s`.`qty`) 
                            FROM `tbl_stock` `s` 
                            WHERE `s`.`tbl_product_idtbl_product` = `p`.`idtbl_product`), 0) AS availableqty
                FROM `tbl_product` AS p
                LEFT JOIN `tbl_group_category` AS `g` ON `g`.`idtbl_group_category` = `p`.`tbl_group_category_idtbl_group_category`
                WHERE p.status = '1'  
                AND `p`.`tbl_group_category_idtbl_group_category` IN ($categorylist)
                ORDER BY `g`.`category` ASC";
$resultstock = $conn->query($sqloutstanding);

if ($resultstock->num_rows > 0) {
    $c = 1;
    $categoryId = -99;
    $oldCategoryId = -99;
    $totalSaleValue = 0;
    $totalUnitPrice = 0;
    $totalCostTotal = 0;
    $allTotalVal = 0;
    $allCostTotalVal = 0;

    echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
            <div style="text-align: left;">
                <h4 style="margin: 0; font-size: 16px;">EVEREST HARDWARE CO. (PVT) LTD</h4>
                <p style="margin: 3px 0; font-size: 12px;">
                    #363/10/01, Malwatte, Kal-Eliya (Mirigama) <br>
                    033 4 950 951 | <a href="mailto:info@everesthardware.lk">info@everesthardware.lk</a>
                </p>
            </div>
            <div>
                <h2 style="margin: 0; font-size: 18px;">Inventory Stock Details</h2>
            </div>
        </div>';
    
    while ($row = $resultstock->fetch_assoc()) {
        $categoryId = $row['idtbl_group_category'];
    
        if ($c !=1 && $oldCategoryId != $categoryId) {
            echo ' 
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f1f1f1; font-weight: bold;">
                            <td colspan="5" class="text-center">Total</td>
                            <td class="text-right">' . number_format($totalSaleValue, 2) . '</td>
                            <td colspan="1"></td>
                            <td class="text-right">' . number_format($totalUnitPrice, 2) . '</td>
                            <td class="text-right">' . number_format($totalCostTotal, 2) . '</td>
                        </tr>
                    </tfoot>
                </table>
            </div>';
            $allTotalVal += $totalSaleValue;
            $allCostTotalVal += $totalCostTotal;
            $totalSaleValue = 0;
            $totalUnitPrice = 0;
            $totalCostTotal = 0;
        }
    
        $totalSaleValue += $row['saleprice'] * $row['availableqty'];
        $totalCostTotal += $row['unitprice'] * $row['availableqty'];
        $totalUnitPrice += $row['unitprice'];
        
        if ($oldCategoryId != $categoryId) {
            $oldCategoryId = $categoryId;
    
            echo '
               <div style="background: #fff; border-radius: 10px; box-shadow: 0px 3px 8px rgba(0,0,0,0.1); padding-left: 5px; margin-bottom: 5px; border-left: 4px solid #004085;">
                <h2 style="margin: 0 0 5px; font-size: 16px; font-weight: bold; color: #004085;">' . $row['groupcategory'] . '</h2>
    
                <table class="table table-bordered table-striped table-sm nowrap" id="dataTable" 
                    style="background: #fff; border-radius: 5px; overflow: hidden; font-size: 12px;">
                    <thead style="background: #004085; color: #fff; font-size: 12px;">
                        <tr>
                            <th class="text-center" style="padding: 5px;">No</th>
                            <th class="text-center" style="padding: 5px;">Item Code</th>
                            <th class="text-center" style="padding: 5px;">Item Name</th>
                            <th class="text-center" style="padding: 5px;">Qty</th>
                            <th class="text-center" style="padding: 5px;">Retail Price</th>
                            <th class="text-center" style="padding: 5px;">Sale Value</th>
                            <th class="text-center" style="padding: 5px;">Pr Per</th>
                            <th class="text-center" style="padding: 5px;">Cost Value</th>
                            <th class="text-center" style="padding: 5px;">Cost Total</th>
                        </tr>
                    </thead>
            <tbody>';
        }
        echo ' 
            <tr>
                <td class="text-center">' . $c . '</td>
                <td class="text-center">' . $row['product_code'] . '</td>
                <td class="text-center">' . $row['product_name'] . '</td>
                <td class="text-center">' . $row['availableqty'] . '</td>
                <td class="text-right">' . $row['saleprice'] . '</td>
                <td class="text-right">' . number_format($row['saleprice'] * $row['availableqty'], 2, '.', ',')   . '</td>
                <td class="text-right">' . number_format($row['percentageVal'], 2) . '%</td>
                <td class="text-right">' . $row['unitprice'] . '</td>
                <td class="text-right">' . number_format($row['unitprice'] * $row['availableqty'], 2, '.', ',')   . '</td>

            </tr>';
        $c++;
    }
    $allTotalVal += $totalSaleValue;
    $allCostTotalVal += $totalCostTotal;
    echo ' 
        </tbody>
        <tfoot>
            <tr style="background-color: #f1f1f1; font-weight: bold;">
                <td  colspan="5" class="text-center">Total</td>
                <td class="text-right">' . number_format($totalSaleValue, 2) . '</td>
                <td colspan="1"></td>
                <td class="text-right">' . number_format($totalUnitPrice, 2) . '</td>
                <td class="text-right">' . number_format($totalCostTotal, 2) . '</td>
            </tr>
        </tfoot>
    </table>
    <table class="table table-bordered nowrap mt-4" id="dataTable" 
        <tr>
            <th class="text-right"> Gross Sale Total: Rs.' . number_format($allTotalVal, 2) . '</th>
        </tr>
        <tr>
            <th class="text-right"> Gross Cost Total: Rs.' . number_format($allCostTotalVal, 2) . '</th>
        </tr>
    </table>
</div>';
} else {
    echo '<div class="alert alert-info" role="alert">No records found.</div>';
}
?>
