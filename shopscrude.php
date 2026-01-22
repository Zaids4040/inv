<?php
/**
 * Shop Management Controller
 * 
 * This script handles CRUD operations for shop entities including:
 * - Creating new shops
 * - Updating existing shop information
 * - Deleting shops from the system
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2024-06-01
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Process shop creation or update
if (isset($_POST['shopsbtn'])) {
	// Sanitize and validate input data
	$name = filter_input(INPUT_POST, 'shopnametxt', FILTER_SANITIZE_STRING);
	$address = filter_input(INPUT_POST, 'shopaddresstxt', FILTER_SANITIZE_STRING);
	$status = filter_input(INPUT_POST, 'shopstatusdd', FILTER_VALIDATE_INT);
	
	// Handle optional fields with null coalescing operator
	$nic = $_POST['shopnic'] ?? "";
	$number = $_POST['shonum'] ?? "";
	
	// Determine if this is a new shop or an update
	if (!isset($_GET['upid'])) {
		// Create new shop record
		if (insert_tables(
			"shops(name, address, nic_number, number, borrow_statud)",
			"('" . mysqli_real_escape_string($con, $name) . "','" . 
			mysqli_real_escape_string($con, $address) . "','" . 
			mysqli_real_escape_string($con, $nic) . "','" . 
			mysqli_real_escape_string($con, $number) . "'," . 
			$status . ")"
		)) {
			// Set success message and redirect
			$_SESSION['shopinsert'] = 1;
			header("Location: AdminPanel.php?shops=active");
			exit;
		}
	} else {
		// Update existing shop record
		$id = filter_input(INPUT_GET, 'upid', FILTER_VALIDATE_INT);
		
		if ($id && update_shops($name, $address, $nic, $number, $status, $id)) {
			// Set success message and redirect
			$_SESSION['shopupdate'] = 1;
			header("Location: AdminPanel.php?shopslist=active");
			exit;
		}
	}
} 
// Process shop deletion
else if (isset($_GET['delid'])) {
	// Validate and sanitize the shop ID
	$delid = filter_input(INPUT_GET, 'delid', FILTER_VALIDATE_INT);
	
	if ($delid && delete_table("shops", $delid)) {
		// Set success message and redirect
		$_SESSION['shopdel'] = 1;
		header("Location: AdminPanel.php?shopslist=active");
		exit;
	}
}
?>