<?php
/**
 * User Status Toggle Handler
 * 
 * This script handles toggling the active status of users in the system.
 * It processes requests to activate or deactivate user accounts based on their current status.
 * 
 * @version 2.0
 * @author Pro Developer
 * @date 2024-06-15
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Validate and sanitize user ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Proceed only if ID is valid
if ($id) {
    // Fetch user data using prepared statement
    $stmt = mysqli_prepare($con, "SELECT * FROM users_table WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $userData = mysqli_fetch_row($result);
    mysqli_stmt_close($stmt);
    
    // Determine new status based on current status
    $newStatus = ($userData[9] == 1) ? 0 : 1;
    
    // Prepare and execute status update
    $updateStmt = mysqli_prepare($con, "UPDATE users_table SET status = ? WHERE id = ?");
    mysqli_stmt_bind_param($updateStmt, "ii", $newStatus, $id);
    
    if (mysqli_stmt_execute($updateStmt)) {
        // Redirect to appropriate page based on the operation performed
        // Note: Fixed typo in the 'else' condition redirect URL
        $redirectUrl = ($newStatus == 0) ? 
            "AdminPanel.php?usrlist=active" : 
            "AdminPanel.php?usrlist=active";
            
        // Use header for redirection instead of JavaScript
        header("Location: $redirectUrl");
        exit;
    }
    
    mysqli_stmt_close($updateStmt);
} else {
    // Handle invalid ID
    header("Location: AdminPanel.php?error=invalid_user_id");
    exit;
}
?>