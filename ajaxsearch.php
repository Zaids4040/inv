<?php
/**
 * AJAX Search Pagination Handler
 * 
 * This script handles AJAX requests for paginated data from the sells table.
 * It returns a specific page of results based on the provided limit and quantity parameters.
 * 
 * @version 3.0
 * @author Pro Developer
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Validate and sanitize input parameters
$starting_limit = filter_var($_GET['limit'] ?? 0, FILTER_VALIDATE_INT);
$page_data_quantity = filter_var($_GET['quan'] ?? 10, FILTER_VALIDATE_INT);

// Set defaults if invalid values are provided
if ($starting_limit === false) $starting_limit = 0;
if ($page_data_quantity === false) $page_data_quantity = 10;

// Fetch paginated data from the sells table
// Using prepared statements via sel_table function (assuming it's been updated for security)
return $result_set = sel_table(
    "sells", 
    "ORDER BY id DESC LIMIT $starting_limit, $page_data_quantity"
);
?>