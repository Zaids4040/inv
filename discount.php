<?php
/**
 * Discount Calculator
 * 
 * This script calculates and displays the total discount amount
 * from all home sales in the system.
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2023-10-15
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Prepare query to get total discount amount
$query = "SELECT SUM(discount) FROM home_sells";

// Execute query using prepared statement
$stmt = mysqli_prepare($con, $query);

// Check if statement was prepared successfully
if ($stmt) {
    // Execute the statement
    mysqli_stmt_execute($stmt);
    
    // Bind result variable
    mysqli_stmt_bind_result($stmt, $total_discount);
    
    // Fetch the result
    mysqli_stmt_fetch($stmt);
    
    // Close the statement
    mysqli_stmt_close($stmt);
    
    // Output the total discount (with fallback to zero if null)
    echo $total_discount ?? 0;
} else {
    // Handle query preparation error
    echo "Error calculating discount: " . mysqli_error($con);
}
?>