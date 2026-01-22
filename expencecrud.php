<?php
/**
 * Expense Management Handler
 * 
 * This script handles expense creation and validation operations.
 * It processes form submissions for adding expenses and checks for duplicate expense names.
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2023-10-15
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Process expense creation
if (isset($_POST['expbtn'])) {
	// Sanitize input data
	$expense = filter_input(INPUT_POST, 'exptxt', FILTER_SANITIZE_STRING);
	$amount = filter_input(INPUT_POST, 'expamotxt', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$note = filter_input(INPUT_POST, 'notetxt', FILTER_SANITIZE_STRING);
	
	// Insert expense record using prepared statement
	if (insert_tables(
		"expence(name,amount,note,date_exp)","('$expense', '$amount', '$note', CURRENT_DATE)"
	)) {
		// Set success status
		$_SESSION['expcru'] = 1;
		// Redirect to home page
		header("Location: home_page.php");
		exit;
	} else {
		// Set failure status
		$_SESSION['expcru'] = 0;
		// Redirect to home page
		header("Location: home_page.php");
		exit;
	}
}

// Check for duplicate expense name (AJAX endpoint)
if (isset($_GET['checkexp'])) {
	// Sanitize input
	$name = filter_input(INPUT_GET, 'checkexp', FILTER_SANITIZE_STRING);
	
	// Query database for matching expense name using prepared statement
	$data = sel_table(
		"expence_names",
		"WHERE name = ?",
		[$name]
	);
	
	// Return result (1 if exists, 0 if not)
	echo (mysqli_num_rows($data) > 0) ? '1' : '0';
	exit;
}
?>