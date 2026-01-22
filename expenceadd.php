<?php
/**
 * Expense Category Add Handler
 * 
 * This script processes form submissions for adding new expense categories.
 * It sanitizes input data and redirects with appropriate status messages.
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2023-10-15
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Process only if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['expenbtn'])) {
	// Sanitize expense name input
	$name = filter_input(INPUT_POST, 'expentxt', FILTER_SANITIZE_STRING);
	
	// Validate input
	if (empty($name)) {
		$_SESSION['expenadd'] = 0;
		header("Location: /ecom/adminexpence_view.php");
		exit;
	}
	
	// Insert expense category using prepared statement
	if (insert_tables(
		"expence_names(name)",
		"(?)",
		[$name]
	)) {
		// Set success status and redirect
		$_SESSION['expenadd'] = 1;
		header("Location: /ecom/adminexpence_view.php");
		exit;
	} else {
		// Set failure status and redirect
		$_SESSION['expenadd'] = 0;
		header("Location: /ecom/adminexpence_view.php");
		exit;
	}
}
?>