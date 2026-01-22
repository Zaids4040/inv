<?php
/**
 * AJAX Bottle Data Retrieval
 * 
 * This script handles AJAX requests to fetch bottle data by ID.
 * It returns the bottle's specific data (index 3) from the database.
 * 
 * @version 3.0
 * @author Pro Developer
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Process the request only if ID is provided
if (isset($_REQUEST['id'])) {
    // Sanitize the input to prevent SQL injection
    $id = filter_var($_REQUEST['id'], FILTER_VALIDATE_INT);
    
    if ($id !== false) {
        // Use prepared statement via the sel_table function
        // Assuming sel_table has been updated to use prepared statements
        $data_query = sel_table("bottels", "where id = $id");
        
        if ($data_query && mysqli_num_rows($data_query) > 0) {
            $data = mysqli_fetch_row($data_query);
            // Output the specific bottle data (index 3)
            echo htmlspecialchars($data[3] ?? '', ENT_QUOTES, 'UTF-8');
        } else {
            // No data found for the given ID
            echo "No data found";
        }
    } else {
        // Invalid ID format
        echo "Invalid ID format";
    }
} else {
    // No ID provided in the request
    echo "Missing bottle ID parameter";
}
?>