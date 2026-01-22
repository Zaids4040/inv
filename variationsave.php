<?php
/**
 * Product Variation Save Handler
 * 
 * This script processes product variation data submitted via POST and saves it to the database.
 * It handles multiple variations in a batch process and returns the count of successfully inserted records.
 * 
 * @version 2.0
 * @author Pro Developer
 * @date 2024-06-25
 */

// Include required functions
require_once("functions.php");

// Initialize counter for successful insertions
$successCount = 0;

// Validate that finalarr exists in the POST data
if (!isset($_POST['finalarr']) || !is_array($_POST['finalarr'])) {
    echo json_encode(['error' => 'Invalid data format']);
    exit;
}

// Get the array of product variations
$variationsArray = $_POST['finalarr'];

// Process each variation
foreach ($variationsArray as $variation) {
    // Extract variation data with validation
    $productId = filter_var($variation[0], FILTER_SANITIZE_STRING);
    $size = filter_var($variation[1], FILTER_SANITIZE_STRING);
    $color = filter_var($variation[2], FILTER_SANITIZE_STRING);
    $material = filter_var($variation[3], FILTER_SANITIZE_STRING);
    $style = filter_var($variation[4], FILTER_SANITIZE_STRING);
    $quantity = filter_var($variation[5], FILTER_VALIDATE_INT) ? $variation[5] : 0;
    $barcode = filter_var($variation[6], FILTER_SANITIZE_STRING);
    
    // Prepare statement to prevent SQL injection
    $stmt = mysqli_prepare($con, 
        "INSERT INTO product_vari(pro_id, size, style, color, material, barrcode, qty) 
         VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ssssssi", 
        $productId, $size, $style, $color, $material, $barcode, $quantity);
    
    // Execute the statement and track success
    if (mysqli_stmt_execute($stmt)) {
        $successCount++;
    } else {
        // Log error with prepared statement
        error_log("Error inserting variation: " . mysqli_stmt_error($stmt));
    }
    
    // Close the statement
    mysqli_stmt_close($stmt);
}

// Return the count of successfully inserted records
echo $successCount;
?>
