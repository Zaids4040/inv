<?php
/**
 * Category Add Handler
 * 
 * This script processes form submissions for adding new product categories.
 * It sanitizes input data and redirects with appropriate status messages.
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2023-10-15
 */

// Include required functions
require_once('functions.php');

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Process only if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize category name input
    $name = filter_input(INPUT_POST, 'cattxt', FILTER_SANITIZE_STRING);
    
    // Validate input
    if (empty($name)) {
        $_SESSION['category_error'] = 'Category name cannot be empty';
        header("Location: AdminPanel.php");
        exit;
    }
    
    // Insert category using prepared statement
    if (insert_tables("category(name)","('".$name."')")) {
        // Set success message and redirect
        $_SESSION['category_success'] = 'Category added successfully';
        header("Location: AdminPanel.php");
        exit;
    } else {
        // Set error message and redirect
        $_SESSION['category_error'] = 'Failed to add category';
        header("Location: AdminPanel.php");
        exit;
    }
} else {
    // Redirect if accessed directly without form submission
    header("Location: AdminPanel.php");
    exit;
}
?>