<?php
/**
 * Liter Management Script
 * 
 * This script handles CRUD operations for liter data:
 * - Create: Insert new liter records
 * - Read: Retrieve liter information
 * - Update: Modify existing liter records
 * - Delete: Remove liter records
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Check if delete operation is requested
if (!isset($_GET['delid'])) {
    // Handle create/update operations
    if (isset($_POST['literbtn'])) {
        // Sanitize and validate input data
        $liter = filter_input(INPUT_POST, 'litertxt', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $price = filter_input(INPUT_POST, 'literamotxt', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $cost = filter_input(INPUT_POST, 'litercosttxt', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        
        // Check if ID exists for update operation
        $id = null;
        if (isset($_POST['idhh'])) {
            $id = filter_input(INPUT_POST, 'idhh', FILTER_SANITIZE_NUMBER_INT);
        }
        
        // Query to check if liter records exist
        $liter_query = sel_table("liter", "");
        
        // Determine whether to update or insert
        if (mysqli_num_rows($liter_query) > 0) {
            // Update existing record
            if (update_liter($liter, $price, $id)) {
                // Set success message in session
                $_SESSION["literupdate"] = 1;
                
                // Redirect to admin panel
                header("Location: AdminPanel.php?liter=active");
                exit;
            }
        } else {
            // Insert new record
            if (insert_tables(
                "liter(liter, amount_liter, created_date, cost)",
                "($liter, $price, CURRENT_DATE, '$cost')"
            )) {
                // Set success message in session
                $_SESSION["literinsert"] = 1;
                
                // Redirect to admin panel
                header("Location: AdminPanel.php?liter=active");
                exit;
            }
        }
    }
} else {
    // Handle delete operation
    $id = filter_input(INPUT_GET, 'delid', FILTER_SANITIZE_NUMBER_INT);
    
    if (delete_table("liter", $id)) {
        // Set success message in session
        $_SESSION["literdelete"] = 1;
        
        // Redirect to admin panel
        header("Location: AdminPanel.php?liter=active");
        exit;
    }
}
?>