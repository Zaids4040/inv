<?php
/**
 * Admin User Management Page
 * 
 * This file handles adding and updating users in the admin panel.
 * It includes functionality for managing user profiles, credentials, and salary information.
 * 
 * @version 3.0
 * @author Pro Developer
 */

// Include header and initialize session
include("adminheader.php");

// Handle redirect after update toast
if(isset($_SESSION['update_toast']) && $_SESSION['update_toast'] == 1 && isset($_GET['usrlist'])) {
    echo "<script>window.location = 'AdminPanel.php?usrlist=active'</script>";
    $_SESSION['update_toast'] = 0;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-success">
                    <h4 class="card-title"><?= isset($_GET['usrup']) ? "Update User" : "Add User" ?></h4>
                    <p class="card-category">Complete user profile</p>
                </div>
                <div class="card-body">
                    <?php
                    // Fetch user data if in update mode
                    if(isset($_GET['usrup'])) {
                        $usrid = intval($_GET['usrup']);
                        
                        // Use prepared statement for security
                        $stmt = mysqli_prepare($con, "SELECT * FROM users_table WHERE id = ?");
                        mysqli_stmt_bind_param($stmt, "i", $usrid);
                        mysqli_stmt_execute($stmt);
                        $usr_update_result = mysqli_stmt_get_result($stmt);
                        $usr_update_rows = mysqli_fetch_row($usr_update_result);
                    }
                    ?>
                    
                    <!-- User Form -->
                    <form action="" method="post">
                        <!-- Username and Email -->
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="usernametxt" name="usrtxt" 
                                    value="<?= isset($_GET['usrup']) ? htmlspecialchars($usr_update_rows[1]) : '' ?>" 
                                    type="text" class="validate" required>
                                <label for="usernametxt">UserName (صارف کا نام)</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="emailtxt" type="email" 
                                    value="<?= isset($_GET['usrup']) ? htmlspecialchars($usr_update_rows[2]) : '' ?>" 
                                    name="emltxt" class="validate" required>
                                <label for="emailtxt">Email (ای میل)</label>
                            </div>
                        </div>
                        
                        <!-- Phone and Address -->
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="phonetxt" type="tel" 
                                    value="<?= isset($_GET['usrup']) ? htmlspecialchars($usr_update_rows[3]) : '' ?>" 
                                    name="phonetxt" class="validate">
                                <label for="phonetxt">Phone (فون نمبر) (Optional)</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="addresstxt" type="text" 
                                    value="<?= isset($_GET['usrup']) ? htmlspecialchars($usr_update_rows[4]) : '' ?>" 
                                    name="addtxt" class="validate">
                                <label for="addresstxt">Address (صارف کا پتہ) (Optional)</label>
                            </div>
                        </div>
                        
                        <!-- Password -->
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="passtxt" type="password" 
                                    value="<?= isset($_GET['usrup']) ? htmlspecialchars($usr_update_rows[5]) : '' ?>" 
                                    name="pastxt" class="validate" required>
                                <label for="passtxt">Password (پاس ورڈ)</label>
                            </div>
                        </div>
                        
                        <!-- Salary Information -->
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="salarytxt" type="text" 
                                    value="<?= isset($_GET['usrup']) ? htmlspecialchars($usr_update_rows[6]) : '' ?>" 
                                    name="salarytxt"/>
                                <label for="salarytxt">Salary (تنخواہ) (Optional)</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="salarydatetxt" type="number" 
                                    value="<?= isset($_GET['usrup']) ? htmlspecialchars($usr_update_rows[7]) : '' ?>" 
                                    name="salarydatetxt"/>
                                <label for="salarydatetxt">Salary Date (تنخواہ کی تاریخ) (Optional)</label>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" name="usrbtn" class="btn btn-success pull-right">
                            <?= isset($_GET['usrup']) ? 'Update' : 'Save' ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('adminfooter.php');

/**
 * Process form submission for user creation/update
 */
if(isset($_POST['usrbtn'])) {
    // Sanitize input data
    $usrtxt = htmlspecialchars($_POST["usrtxt"]);
    $emailtxt = filter_var($_POST["emltxt"], FILTER_SANITIZE_EMAIL);
    $phonetxt = htmlspecialchars($_POST["phonetxt"]);
    $pastxt = $_POST["pastxt"]; // Password will be stored as-is (consider hashing)
    $addtxt = htmlspecialchars($_POST["addtxt"]);
    $salarytxt = htmlspecialchars($_POST["salarytxt"]);
    $salarydatetxt = htmlspecialchars($_POST["salarydatetxt"]);
    
    // Create new user
    if(!isset($_GET['usrup'])) {
        // Prepare query based on whether salary is provided
        if(empty($salarytxt)) {
            $query = "INSERT INTO users_table(username, email, phone, address, pass, salary, salary_date, registerd_date) 
                     VALUES (?, ?, ?, ?, ?, '', '', CURRENT_DATE)";
            $params = [$usrtxt, $emailtxt, $phonetxt, $addtxt, $pastxt];
            $types = "sssss";
        } else {
            $query = "INSERT INTO users_table(username, email, phone, address, pass, salary, salary_date, registerd_date) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_DATE)";
            $params = [$usrtxt, $emailtxt, $phonetxt, $addtxt, $pastxt, $salarytxt, $salarydatetxt];
            $types = "sssssss";
        }
        
        // Execute prepared statement
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        $result = mysqli_stmt_execute($stmt);
        
        if($result) {
            echo "<script>
                M.toast({html: 'User Inserted Successfully', classes: 'rounded'});
            </script>";
        } else {
            // Check if email already exists
            $stmt = mysqli_prepare($con, "SELECT * FROM users_table WHERE email = ?");
            mysqli_stmt_bind_param($stmt, "s", $emailtxt);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if(mysqli_num_rows($result) > 0) {
                echo "<script>M.toast({html: 'This Email is Already Registered', classes: 'r1'})</script>";
            } else {
                echo "<script>M.toast({html: 'Invalid Input', classes: 'r1'})</script>";
            }
        }
    } 
    // Update existing user
    else if(isset($_GET['usrup'])) {
        $id = intval($_GET['usrup']);
        
        // Use the update_user function with proper parameters
        if(update_user($id, $usrtxt, $emailtxt, $phonetxt, $addtxt, $pastxt, $salarytxt, $salarydatetxt)) {
            echo "<script>
                window.location='/ecom/adminuser_list.php?usrlist=activeup';
            </script>";
        } else {
            echo "<script>M.toast({html: 'Update Failed', classes: 'r1'})</script>";
        }
    }
}

/**
 * Handle user deletion
 */
if(isset($_GET['usrdelid'])) {
    $usrdelid = intval($_GET['usrdelid']);
    
    // Use prepared statement for security
    $stmt = mysqli_prepare($con, "SELECT * FROM users_table WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $usrdelid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while($delusrrow = mysqli_fetch_row($result)) {
        // Archive the user before deletion
        $archive_query = "INSERT INTO users_delete_recorde(username, email, phone, address, pass, salary, salary_date, registerd_date, deleted_date) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURRENT_DATE)";
        
        $stmt = mysqli_prepare($con, $archive_query);
        mysqli_stmt_bind_param($stmt, "ssssssss", 
            $delusrrow[1], $delusrrow[2], $delusrrow[3], $delusrrow[4], 
            $delusrrow[5], $delusrrow[6], $delusrrow[7], $delusrrow[8]
        );
        
        if(mysqli_stmt_execute($stmt)) {
            // Delete the user after archiving
            if(delete_table('users_table', $usrdelid)) {
                echo "<script>window.location='adminuser_list.php'</script>";
            } else {
                echo "<script>M.toast({html: 'Deletion Failed', classes: 'r1'})</script>";
            }
        }
    }
}
?>