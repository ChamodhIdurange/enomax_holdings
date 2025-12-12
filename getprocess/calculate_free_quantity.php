<?php
/**
 * Calculate free quantity based on purchased quantity and active promotions
 * 
 * @param int $product_id Product ID
 * @param int $purchased_qty Quantity being purchased
 * @param string $order_date Order date (YYYY-MM-DD format)
 * @param object $connection Database connection
 * @return array ['free_qty' => int, 'promotion_details' => array]
 */
function calculateFreeQuantity($product_id, $purchased_qty, $order_date, $connection) {
    $response = array(
        'free_qty' => 0,
        'promotion_details' => null
    );
    
    // Find active promotion for this product on this date
    $sql = "SELECT * FROM enomaxtbl_product_free_issue 
            WHERE product_id = $product_id 
            AND status = 1
            AND '$order_date' BETWEEN start_date AND end_date
            ORDER BY buy_quantity ASC
            LIMIT 1";
    
    $result = mysqli_query($connection, $sql);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $promo = mysqli_fetch_assoc($result);
        
        // Calculate free items
        // Formula: (purchased_qty / buy_quantity) * free_quantity
        // Using floor to get whole number of free items
        $free_sets = floor($purchased_qty / $promo['buy_quantity']);
        $free_qty = $free_sets * $promo['free_quantity'];
        
        $response['free_qty'] = $free_qty;
        $response['promotion_details'] = array(
            'buy_quantity' => $promo['buy_quantity'],
            'free_quantity' => $promo['free_quantity'],
            'promotion_text' => "Buy " . $promo['buy_quantity'] . " Get " . $promo['free_quantity'] . " Free"
        );
    }
    
    return $response;
}

// Example usage when called via AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['calculate'])) {
    include "../include/dbconn.php";
    
    $product_id = (int)$_POST['product_id'];
    $purchased_qty = (int)$_POST['purchased_qty'];
    $order_date = isset($_POST['order_date']) ? $_POST['order_date'] : date('Y-m-d');
    
    $result = calculateFreeQuantity($product_id, $purchased_qty, $order_date, $connection);
    
    echo json_encode($result);
}
?>