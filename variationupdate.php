<?php
/**
 * Product Variation Update Handler
 * 
 * This script processes product variation updates submitted via POST and updates the database.
 * It handles multiple variations in a batch process and returns a success indicator.
 * 
 * @version 3.0
 * @author Pro Developer
 * @date 2024-07-01
 */

// Include required functions
require_once("functions.php");

// Process variation update request
if (isset($_POST['array_var_update']) && is_array($_POST['array_var_update'])) {
    // Retrieve the variation update array
    $variationUpdates = $_POST['array_var_update'];
    
    // Initialize success counter
    $successCount = 0;
    
    // Process each variation update
    foreach ($variationUpdates as $variation) {
        // Validate required fields exist
        if (count($variation) < 5) {
            continue; // Skip invalid entries
        }
        
        // Extract and sanitize variation data
        $id = filter_var($variation[0], FILTER_VALIDATE_INT);
        $barcode = filter_var($variation[1], FILTER_SANITIZE_STRING);
        $quantity = filter_var($variation[2], FILTER_VALIDATE_INT) ? $variation[2] : 0;
        $price = filter_var($variation[3], FILTER_VALIDATE_FLOAT) ? $variation[3] : 0.00;
        $wholePrice = filter_var($variation[4], FILTER_VALIDATE_FLOAT) ? $variation[4] : 0.00;
        
        // Skip if ID is invalid
        if (!$id) {
            continue;
        }
        
        // Prepare statement to prevent SQL injection
        $stmt = mysqli_prepare($con, 
            "UPDATE product_vari 
             SET qty = ?, barrcode = ?, price = ?, whole_price = ? 
             WHERE id = ?");
        
        // Bind parameters
        mysqli_stmt_bind_param($stmt, "isddi", 
            $quantity, $barcode, $price, $wholePrice, $id);
        
        // Execute the statement and track success
        if (mysqli_stmt_execute($stmt)) {
            $successCount++;
        } else {
            // Log error with prepared statement
            error_log("Error updating variation ID $id: " . mysqli_stmt_error($stmt));
        }
        
        // Close the statement
        mysqli_stmt_close($stmt);
    }
    
    // Return success indicator
    echo ($successCount > 0) ? 1 : 0;
} else {
    // Return error if no valid data received
    echo 0;
}
?>