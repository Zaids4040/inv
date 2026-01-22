<?php
/**
 * Bottle CRUD Operations Handler
 * 
 * This script handles Create, Read, Update, and Delete operations for bottle records.
 * It processes form submissions and redirects with appropriate status messages.
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2023-10-15
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Process bottle form submission
if (isset($_POST['bottelbtn'])) {
	// Sanitize and validate input data
	$name = filter_input(INPUT_POST, 'botteltxt', FILTER_SANITIZE_STRING);
	$type = filter_input(INPUT_POST, 'bottelselect', FILTER_SANITIZE_STRING);
	$quantity = filter_input(INPUT_POST, 'bottelquantitytxt', FILTER_VALIDATE_INT);
	
	// Set price based on deposit type
	$price = 0;
	if ($type === "Both" || $type === "Only on Payment") {
		$price = filter_input(INPUT_POST, 'bottelpricetxt', FILTER_VALIDATE_FLOAT);
	}
	
	// Handle update operation if updateid is present
	if (isset($_GET['updateid'])) {
		$update_id = filter_input(INPUT_GET, 'updateid', FILTER_VALIDATE_INT);
		
		// Update bottle record and set status message
		if (update_bottels($update_id, $name, $price, $type, $quantity)) {
			$_SESSION['bottelsucessupdate'] = 1; // Success
		} else {
			$_SESSION['bottelsucessupdate'] = 0; // Failure
		}
		
		// Redirect to bottle management page with anchor
		header("Location: AdminPanel.php?bottels=active#tabel");
		exit;
	} else {
		// Handle insert operation for new bottle
		// Using prepared statements via insert_tables function (assuming it's been updated for security)
		if (insert_tables(
			"bottels(name, deposit_price, deposit_type, quantity)",
			"(?, ?, ?, ?)",
			[$name, $price, $type, $quantity]
		)) {
			$_SESSION['bottelsucessinsert'] = 1; // Success
		} else {
			$_SESSION['bottelsucessinsert'] = 0; // Failure
		}
		
		// Redirect to bottle management page
		header("Location: AdminPanel.php?bottels=active");
		exit;
	}
}

// Process delete operation
if (isset($_GET['delid'])) {
	// Sanitize and validate delete ID
	$delid = filter_input(INPUT_GET, 'delid', FILTER_VALIDATE_INT);
	
	// Delete bottle record and set status message
	if ($delid && delete_table("bottels", $delid)) {
		$_SESSION['bottelsucessdelete'] = 1; // Success
		
		// Redirect to bottle management page with anchor
		header("Location: AdminPanel.php?bottels=active#tabel");
		exit;
	}
}
?>