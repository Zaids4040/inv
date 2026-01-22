<?php
/**
 * Clear Borrow Status Handler
 * 
 * This script updates the borrow status of a sell record to cleared (0).
 * It processes the request and redirects to the appropriate admin panel page.
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2023-10-15
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Sanitize and validate the unique ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Check if ID is valid
if (!$id) {
    $_SESSION['error_message'] = "Invalid ID provided";
    header("Location: AdminPanel.php");
    exit;
}

// Prepare the update query using prepared statements
$query = "UPDATE sells SET shop_borrow_statud = 0 WHERE unique_id = ?";

// Get database connection from functions.php
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $id);

// Execute the query and handle the result
if (mysqli_stmt_execute($stmt)) {
    // Set success message
    $_SESSION['borrow_cleared'] = "Borrow status cleared successfully";
    
    // Redirect based on the presence of 'sel' parameter
    if (isset($_GET['sel'])) {
        header("Location: AdminPanel.php?sells=active");
    } else {
        header("Location: AdminPanel.php");
    }
    exit;
} else {
    // Set error message
    $_SESSION['error_message'] = "Failed to clear borrow status: " . mysqli_error($con);
    header("Location: AdminPanel.php");
    exit;
}

// Close statement
mysqli_stmt_close($stmt);
?>