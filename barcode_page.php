<?php
/**
 * Barcode Generator Page
 * 
 * This script generates a unique barcode for products using the current timestamp.
 * It checks if the generated code already exists in the database to avoid duplicates.
 * 
 * @version 3.0
 * @author Pro Developer
 */

// Initialize session
session_start();

// Include required functions
require_once('functions.php');

// Generate a unique barcode using current timestamp
$barcode_num = time();

// Prepare and execute a query to check if the barcode already exists
$stmt = mysqli_prepare($con, "SELECT * FROM products WHERE qr_code = ?");
mysqli_stmt_bind_param($stmt, "i", $barcode_num);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Only display the barcode if it doesn't already exist in the database
if(mysqli_num_rows($result) == 0) {
    // Output the barcode image with proper HTML escaping
    echo '<img src="barcode.php?codetype=Code39&size=40&text='.htmlspecialchars($barcode_num, ENT_QUOTES, 'UTF-8').'&print=true" alt="Product Barcode" />';
}

// Close the statement
mysqli_stmt_close($stmt);
?>
