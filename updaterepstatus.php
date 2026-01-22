<?php
/**
 * Replace Status Update Handler
 * 
 * This script handles updating the replace_status field for items in the home_sells table.
 * It processes AJAX requests to toggle items between replaced and non-replaced states.
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2024-06-10
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Process replace status update request
if (isset($_GET['data'])) {
    // Validate and sanitize input parameters
    $status = filter_input(INPUT_GET, 'data', FILTER_VALIDATE_INT);
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
    // Only proceed if both parameters are valid integers
    if ($id !== false && $status !== false) {
        // Prepare statement to prevent SQL injection
        $stmt = mysqli_prepare($con, "UPDATE home_sells SET replace_status = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ii", $status, $id);
        
        // Execute the update and handle result
        if (mysqli_stmt_execute($stmt)) {
            // Return success status (could be expanded to return JSON response)
            echo "success";
        } else {
            // Log error or return error status
            echo "error";
        }
        
        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        // Handle invalid input parameters
        echo "invalid parameters";
    }
}
?>