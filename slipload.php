<?php
/**
 * Sales Slip Loading Script
 * 
 * This script handles loading sales data from QR codes and updating cart quantities.
 * It provides two main functions:
 * 1. Loading complete sales data from a QR code into the shopping cart
 * 2. Updating the quantity of a single item in the cart
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2024-06-05
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Process QR code scan request
if (isset($_GET['qrcode'])) {
	// Sanitize the QR code input
	$qrcode = filter_input(INPUT_GET, 'qrcode', FILTER_SANITIZE_STRING);
	
	// Fetch sales data associated with the QR code
	$sells_data = sel_table("sells", "WHERE unique_id = '" . mysqli_real_escape_string($con, $qrcode) . "'");
	
	// Process each item in the sales data
	while ($row = mysqli_fetch_row($sells_data)) {
		// Calculate per-product price
		$perProductPrice = $row[2] / $row[5];
		
		// Insert each product into the shopping cart
		insert_tables(
			"home_sells(name, price, product_id, quantity, whole_sale, per_p_price, slip_status, replace_status, replace_quantity, vari)",
			"('" . mysqli_real_escape_string($con, $row[1]) . "', '" . 
			$row[2] . "', " . 
			$row[3] . ", " . 
			$row[5] . ", " . 
			$row[15] . ", " . 
			$perProductPrice . ", 1, 0, " . 
			$row[5] . ", " . 
			$row[17] . ")"
		);
	}
}

// Process single item quantity update
if (isset($_GET['singlebtn'])) {
	// Validate and sanitize input parameters
	$id = filter_input(INPUT_GET, 'idhidd', FILTER_VALIDATE_INT);
	$qty = filter_input(INPUT_GET, 'qty', FILTER_VALIDATE_INT);
	
	if ($id && $qty) {
		// Fetch current item data
		$home_data = sel_table("home_sells", "WHERE id = $id");
		
		if ($home_data && mysqli_num_rows($home_data) > 0) {
			$data = mysqli_fetch_row($home_data);
			
			// Calculate new quantity and price
			$quantity = $data[4] + $qty;
			$price = $quantity * $data[6];
			
			// Prepare and execute update query using prepared statement
			$stmt = mysqli_prepare($con, "UPDATE home_sells SET quantity = ?, price = ? WHERE id = ?");
			mysqli_stmt_bind_param($stmt, "idi", $quantity, $price, $id);
			
			// Return updated quantity on success, 0 on failure
			if (mysqli_stmt_execute($stmt)) {
				echo $quantity;
			} else {
				echo 0;
			}
			
			mysqli_stmt_close($stmt);
		} else {
			echo 0; // Item not found
		}
	} else {
		echo 0; // Invalid input parameters
	}
}
?>