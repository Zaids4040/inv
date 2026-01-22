<?php
/**
 * Shop Data Retrieval Script
 * 
 * This script fetches and returns shop information based on the provided ID.
 * It's designed to be called via AJAX to populate shop details in forms.
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2024-05-25
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Process shop data request
if (isset($_GET['id'])) {
    // Validate and sanitize the shop ID
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
    if ($id) {
        // Query the database for shop information
        $data = sel_table("shops", "WHERE id = " . $id);
        
        // Check if shop exists and return the relevant data
        if ($data && mysqli_num_rows($data) > 0) {
            $row = mysqli_fetch_row($data);
            echo htmlspecialchars($row[5], ENT_QUOTES, 'UTF-8');
        } else {
            // Return empty response if shop not found
            echo "";
        }
    } else {
        // Return error message for invalid ID
        echo "Invalid shop ID";
    }
}
?>