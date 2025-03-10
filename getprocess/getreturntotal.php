<?php
require_once('../connection/db.php');

session_start();

$id = $_POST['id'];

// Check if ID is set and not empty
if (isset($id) && !empty($id)) {
    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT `tbl_return`.`total`, SUM(`tbl_creditenote`.`payAmount`) AS `payAmount` FROM `tbl_return` LEFT JOIN `tbl_creditenote` ON (`tbl_creditenote`.`returnid` = `tbl_return`.`idtbl_return`) WHERE `idtbl_return` = ?");
    $stmt->bind_param("s", $id);

    // Execute the statement
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Fetch the result as an associative array
        if ($row = $result->fetch_assoc()) {
            $obj = new stdClass();
            $obj->id = $id;
            $obj->total = $row['total'];
            if ($row['payAmount'] != null) {
                $obj->payAmount = $row['payAmount'];
            }else{
                $obj->payAmount = 0;
            }
            // Return the JSON encoded result
            echo json_encode($obj);
        } else {
            echo json_encode(['error' => 'No record found']);
        }
    } else {
        echo json_encode(['error' => 'Query execution failed']);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid ID']);
}

// Close the connection
$conn->close();
