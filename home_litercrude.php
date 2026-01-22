<?php
/**
 * Liter Sales Management
 * 
 * This script handles the creation of liter sales records.
 * It processes form submissions, validates input data, and inserts records into the database.
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2023-10-15
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Process liter sale creation
if (isset($_POST['literbtn'])) {
	// Sanitize input data
	$liter = filter_input(INPUT_POST, 'liter_borrowtxt', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$amount = filter_input(INPUT_POST, 'liter_amounttxtt', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$quantity = filter_input(INPUT_POST, 'qtyliter', FILTER_SANITIZE_NUMBER_INT);
	
	// Retrieve current liter cost from database
	$literData = sel_table("liter", "");
	$literRow = mysqli_fetch_row($literData);
	$cost = $literRow[4] ?? 0; // Use null coalescing operator for safety
	
	// Insert liter sale record using prepared statement
	if (insert_tables(
		"liter_sell(liter, price, quantity, inserted_date, cost)",
		"(?, ?, ?, CURRENT_DATE, ?)",
		[$liter, $amount, $quantity, $cost]
	)) {
		// Set success status
		$_SESSION['home_liter'] = 1;
		
		// Redirect to home page using header instead of JavaScript
		header("Location: home_page.php");
		exit;
	} else {
		// Set failure status (optional enhancement)
		$_SESSION['home_liter'] = 0;
		
		// Redirect to home page
		header("Location: home_page.php");
		exit;
	}
}
?>