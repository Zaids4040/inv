<?php
/**
 * User Salary Processing Script
 * 
 * This script handles the processing of pending user salaries.
 * It marks pending salaries as paid and records the transaction.
 * 
 * @version 3.0
 * @author Pro Developer
 * @date 2024-06-20
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Validate and sanitize input parameters
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$statusId = filter_input(INPUT_GET, 'statusid', FILTER_VALIDATE_INT);

// Redirect if invalid parameters
if (!$id || !$statusId) {
    header("Location: AdminPanel.php?error=invalid_parameters");
    exit;
}

// Fetch pending salary data using prepared statement
$stmt = mysqli_prepare($con, "SELECT * FROM usr_pending_salary WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $statusId);
mysqli_stmt_execute($stmt);
$pendingSalaryResult = mysqli_stmt_get_result($stmt);
$pendingSalaryData = mysqli_fetch_row($pendingSalaryResult);
mysqli_stmt_close($stmt);

// Fetch user data using prepared statement
$userStmt = mysqli_prepare($con, "SELECT * FROM users_table WHERE id = ?");
mysqli_stmt_bind_param($userStmt, "i", $id);
mysqli_stmt_execute($userStmt);
$userData = mysqli_stmt_get_result($userStmt);
mysqli_stmt_close($userStmt);

// Process salary payment if user exists
if (mysqli_num_rows($userData) > 0) {
    $userRow = mysqli_fetch_row($userData);
    $salary = $userRow[6];
    
    // Insert salary payment record using prepared statement
    $insertStmt = mysqli_prepare($con, 
        "INSERT INTO usr_salary(usr_id, salary, currentdate, salarymonth) VALUES (?, ?, CURRENT_DATE, ?)");
    mysqli_stmt_bind_param($insertStmt, "ids", $id, $salary, $pendingSalaryData[2]);
    $insertSuccess = mysqli_stmt_execute($insertStmt);
    mysqli_stmt_close($insertStmt);
    
    if ($insertSuccess) {
        // Update pending salary status to paid (1)
        $updateStmt = mysqli_prepare($con, "UPDATE usr_pending_salary SET status = 1 WHERE id = ?");
        mysqli_stmt_bind_param($updateStmt, "i", $statusId);
        $updateSuccess = mysqli_stmt_execute($updateStmt);
        mysqli_stmt_close($updateStmt);
        
        if ($updateSuccess) {
            // Set success flag and redirect
            $_SESSION['salaryclear'] = 1;
            header("Location: AdminPanel.php");
            exit;
        }
    }
}

// Redirect if any operation fails
header("Location: AdminPanel.php");
exit;
?>