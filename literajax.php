<?php
/**
 * Liter Rate Calculator
 * 
 * This script calculates and returns the liter rate from the database.
 * It fetches the liter pricing data and returns the rate as a decimal value.
 * 
 * @version 2.0
 * @author Pro Developer
 * @date 2023-12-30
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Fetch liter pricing data from database
$literQuery = sel_table("liter", "");

// Check if data exists
if ($literQuery && mysqli_num_rows($literQuery) > 0) {
    $literData = mysqli_fetch_row($literQuery);
    
    // Calculate liter rate (amount/quantity)
    $literRate = $literData[2] / $literData[1];
    
    // Output the calculated rate
    echo $literRate;
} else {
    // Handle case when no liter data is found
    error_log("No liter pricing data found in database");
    echo "0"; // Return zero as default value
}
?>