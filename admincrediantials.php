<?php
session_start();

// Include functions with require_once for better error handling
require_once("functions.php");

// CSRF protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if form is submitted with proper CSRF token
if (isset($_POST['adminlogbtn'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }
    
    // Sanitize and validate inputs
    $id = filter_input(INPUT_POST, 'adminidlog', FILTER_VALIDATE_INT) ?? 0;
    $name = htmlspecialchars(trim($_POST['adminusrtxt'] ?? ''), ENT_QUOTES, 'UTF-8');
    $raw_password = $_POST['adminpastxt'] ?? '';
    
    // Validate inputs
    if (empty($name) || empty($raw_password) || strlen($raw_password) < 8) {
        $_SESSION['error_message'] = "Invalid username or password. Password must be at least 8 characters.";
        header("Location: AdminPanel.php?adminprofile=active&error=1");
        exit;
    }
    
    // Hash password for secure storage
    $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);
    
    // Get database connection
    $con = get_db_connection(); // Assuming this function exists in functions.php
    if (!$con) {
        die("Database connection failed");
    }
    
    $check_data = sel_table("adminlogin", "");
    
    if (mysqli_num_rows($check_data) > 0) {
        // Update existing admin credentials using prepared statement
        $stmt = mysqli_prepare($con, "UPDATE adminlogin SET usrname = ?, passwordd = ? WHERE id = ?");
        
        if (!$stmt) {
            die("Prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "ssi", $name, $hashed_password, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['adminlogup'] = 1;
            $_SESSION['success_message'] = "Admin credentials updated successfully";
            mysqli_stmt_close($stmt);
            header("Location: AdminPanel.php?adminprofile=active&success=1");
            exit;
        } else {
            $_SESSION['error_message'] = "Failed to update admin credentials";
            mysqli_stmt_close($stmt);
            header("Location: AdminPanel.php?adminprofile=active&error=2");
            exit;
        }
    } else {
        // Insert new admin credentials using prepared statement
        $stmt = mysqli_prepare($con, "INSERT INTO adminlogin (usrname, passwordd) VALUES (?, ?)");
        
        if (!$stmt) {
            die("Prepare failed: " . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, "ss", $name, $hashed_password);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['adminlogup'] = 1;
            $_SESSION['success_message'] = "Admin credentials created successfully";
            mysqli_stmt_close($stmt);
            header("Location: AdminPanel.php?adminprofile=active&success=2");
            exit;
        } else {
            $_SESSION['error_message'] = "Failed to create admin credentials";
            mysqli_stmt_close($stmt);
            header("Location: AdminPanel.php?adminprofile=active&error=3");
            exit;
        }
    }
}
?>

