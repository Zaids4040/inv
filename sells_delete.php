<?php
/**
 * Sales Record Deletion Handler
 * 
 * This script handles the archiving and deletion of sales records.
 * It first copies the record to a deletion archive table before removing it
 * from the active sales table, maintaining data integrity and audit trail.
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2024-05-01
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Validate and sanitize the sales record ID
$id = filter_input(INPUT_GET, 'delid', FILTER_VALIDATE_INT);

// Exit if invalid ID provided
if (!$id) {
    $_SESSION['error_message'] = "Invalid sales record ID";
    header("Location: AdminPanel.php?sells=active");
    exit;
}

// Retrieve the sales record to be deleted
$query = sel_table("sells", "WHERE id = " . $id);

// Check if record exists
if (!$query || mysqli_num_rows($query) === 0) {
    $_SESSION['error_message'] = "Sales record not found";
    header("Location: AdminPanel.php?sells=active");
    exit;
}

// Fetch the record data
$select = mysqli_fetch_row($query);

// Prepare data for archiving with proper escaping to prevent SQL injection
$archiveData = [
    'name' => mysqli_real_escape_string($con, $select[1]),
    'price' => mysqli_real_escape_string($con, $select[2]),
    'product_id' => mysqli_real_escape_string($con, $select[3]),
    'quantity' => mysqli_real_escape_string($con, $select[5]),
    'shop_id' => mysqli_real_escape_string($con, $select[6]),
    'shop_name' => mysqli_real_escape_string($con, $select[7]),
    'shop_address' => mysqli_real_escape_string($con, $select[8]),
    'shop_phone' => mysqli_real_escape_string($con, $select[9]),
    'shop_borrow_statud' => mysqli_real_escape_string($con, $select[10]),
    'shop_by_date' => mysqli_real_escape_string($con, $select[11]),
    'discount' => mysqli_real_escape_string($con, $select[12]),
    'unique_id' => mysqli_real_escape_string($con, $select[13]),
    'USR_ID' => mysqli_real_escape_string($con, $select[14])
];

// Archive the sales record before deletion
$archiveQuery = "INSERT INTO sells_delete(
    name, price, product_id, quantity, deleted_date, 
    shop_id, shop_name, shop_address, shop_phone, 
    shop_borrow_statud, shop_by_date, discount, 
    unique_id, USR_ID
) VALUES (
    '{$archiveData['name']}', '{$archiveData['price']}', 
    '{$archiveData['product_id']}', '{$archiveData['quantity']}', 
    CURRENT_DATE, '{$archiveData['shop_id']}', 
    '{$archiveData['shop_name']}', '{$archiveData['shop_address']}', 
    '{$archiveData['shop_phone']}', '{$archiveData['shop_borrow_statud']}', 
    '{$archiveData['shop_by_date']}', '{$archiveData['discount']}', 
    '{$archiveData['unique_id']}', '{$archiveData['USR_ID']}'
)";

// Execute archive operation
if (mysqli_query($con, $archiveQuery)) {
    // Delete the original record after successful archiving
    if (delete_table("sells", $id)) {
        // Set success message and redirect
        $_SESSION['sellsdelete'] = 1;
        header("Location: AdminPanel.php?sells=active");
        exit;
    } else {
        // Handle deletion failure
        $_SESSION['error_message'] = "Failed to delete sales record";
        header("Location: AdminPanel.php?sells=active");
        exit;
    }
} else {
    // Handle archiving failure
    $_SESSION['error_message'] = "Failed to archive sales record";
    header("Location: AdminPanel.php?sells=active");
    exit;
}
?>