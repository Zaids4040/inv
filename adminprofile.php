<?php
/**
 * Admin Profile Management
 * 
 * This file handles the admin profile viewing and updating functionality.
 * It allows administrators to update their login credentials securely.
 * 
 * @version 3.0
 * @author Pro Developer
 */

// Include header and initialize session
include('adminheader.php');

// Fetch admin profile data using prepared statement for security
$stmt = mysqli_prepare($con, "SELECT * FROM adminlogin");
mysqli_stmt_execute($stmt);
$profile_data = mysqli_stmt_get_result($stmt);
$adminlogin = mysqli_fetch_row($profile_data);

// Determine if we're updating an existing profile or creating a new one
$mode = mysqli_num_rows($profile_data) > 0 ? 'update' : 'create';
?>

<div class="content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Admin Profile</h4>
                        <p class="card-category">Manage your admin credentials</p>
                    </div>
                    
                    <div class="card-body">
                        <!-- Admin credentials form -->
                        <form action="admincrediantials.php" method="post" id="adminProfileForm">
                            <!-- Hidden ID field for updates -->
                            <input type="hidden" name="adminidlog" value="<?= $mode === 'update' ? htmlspecialchars($adminlogin[0]) : '' ?>"/>
                            
                            <!-- Email field -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-field">
                                        <i class="material-icons prefix">account_circle</i>
                                        <input id="admin_email" type="email" name="adminusrtxt" class="validate"
                                               value="<?= $mode === 'update' ? htmlspecialchars($adminlogin[1]) : '' ?>" required>
                                        <label for="admin_email">Email</label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Password field with toggle visibility -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-field">
                                        <i class="material-icons prefix">enhanced_encryption</i>
                                        <input type="password" id="admin_password" name="adminpastxt" class="validate"
                                               value="<?= $mode === 'update' ? htmlspecialchars($adminlogin[2]) : '' ?>" required>
                                        <label for="admin_password">Password</label>
                                        <i class="material-icons prefix" style="cursor:pointer;margin-left:-35px;" id="togglePassword">visibility_off</i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit button -->
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" name="adminlogbtn" class="btn btn-primary">
                                        <?= $mode === 'update' ? 'Update' : 'Save' ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include('adminfooter.php');
?>

<script>
/**
 * Password visibility toggle functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('admin_password');
    
    togglePassword.addEventListener('click', function() {
        // Toggle password visibility
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        
        // Toggle eye icon
        this.textContent = type === 'password' ? 'visibility_off' : 'visibility';
    });
    
    // Form validation
    const form = document.getElementById('adminProfileForm');
    form.addEventListener('submit', function(event) {
        const email = document.getElementById('admin_email').value;
        const password = passwordField.value;
        
        if (!email || !password) {
            event.preventDefault();
            M.toast({html: 'Please fill in all required fields'});
        }
    });
});
</script>