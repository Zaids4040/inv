<?php
/**
 * Admin Login Page
 * 
 * This script handles admin authentication and login functionality.
 * It validates credentials against the database and manages session state.
 * 
 * @version 2.0
 * @author Pro Developer
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include core functions
require_once("functions.php");

// CSRF protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Initialize error variable
$invalid = null;

// Process login form submission
if (isset($_POST['logbtn'])) {
    // Verify CSRF token if it exists
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $invalid = 2; // CSRF token validation failed
    } else {
        // Sanitize and validate inputs
        $username = htmlspecialchars(trim($_POST['usrtxt'] ?? ''), ENT_QUOTES, 'UTF-8');
        $password = $_POST['pastxt'] ?? '';
        
        // Validate input presence
        if (empty($username) || empty($password)) {
            $invalid = 1;
        } else {
            // Get database connection
            
            if (!$con) {
                $invalid = 3; // Database connection error
            } else {
                // Use prepared statement to prevent SQL injection
                $stmt = mysqli_prepare($con, "SELECT id, passwordd FROM adminlogin WHERE usrname = ?");
                
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "s", $username);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    
                    // Check if user exists
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        mysqli_stmt_bind_result($stmt, $id, $hashed_password);
                        mysqli_stmt_fetch($stmt);
                        
                        // Verify password (assuming passwords are now hashed)
                        if (password_verify($password, $hashed_password) || $password === $hashed_password) {
                            // Set session variables
                            $_SESSION['adminsucc'] = 1;
                            $_SESSION['admin_id'] = $id;
                            $_SESSION['last_activity'] = time();
                            
                            // Regenerate session ID for security
                            session_regenerate_id(true);
                            
                            // Redirect to admin panel
                            header("Location: AdminPanel.php");
                            exit;
                        } else {
                            $invalid = 1; // Invalid password
                        }
                    } else {
                        $invalid = 1; // User not found
                    }
                    
                    mysqli_stmt_close($stmt);
                } else {
                    $invalid = 3; // SQL statement preparation failed
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Login</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="materialize-v1.0.0/materialize/css/materialize.min.css"/>
    <link rel="stylesheet" type="text/css" href="assets/Roboto300.css" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link href="assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
    <link href="assets/demo/demo.css" rel="stylesheet" />
    
    <style>
        .r1 { color: #F00; }
        .login-container {
            max-width: 500px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <main>
        <center>
            <div class="section"></div>
            <h1>Admin Login</h1>
            <div class="section"></div>
            <h5 class="indigo-text">Login With Your Account Credentials</h5>
            <div class="section"></div>

            <div class="container login-container">
                <div class="z-depth-1 grey lighten-4" style="display: block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">
                    <form action="" method="post">
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <div class="row">
                            <div class="col s12">
                                <div class="input-field">
                                    <i class="material-icons prefix">account_circle</i>
                                    <input id="icon_prefix" type="text" name="usrtxt" class="validate" required>
                                    <label for="icon_prefix">Username</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <div class="input-field">
                                    <i class="material-icons prefix">enhanced_encryption</i>
                                    <input type="password" id="password" name="pastxt" class="validate" required>
                                    <label for="password">Password</label>
                                    <i class="material-icons prefix" style="cursor:pointer;margin-left:-35px;" id="togglePassword">remove_red_eye</i>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <div class="input-field">
                                    <button type="submit" name="logbtn" class="btn btn-info w-100">Login</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </center>
    </main>

    <!-- Core JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="materialize-v1.0.0/materialize/js/materialize.min.js"></script>
    
    <!-- Password Toggle Script -->
    <script>
    /**
     * Toggle password visibility
     */
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        
        togglePassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle the eye icon
            this.innerText = this.innerText === 'remove_red_eye' ? 'panorama_fish_eye' : 'remove_red_eye';
        });
        
        // Initialize Materialize components
        M.AutoInit();
    });
    </script>
    
    <!-- Material Dashboard Core JS -->
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap-material-design.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    
    <!-- Plugin JS Files -->
    <script src="assets/js/plugins/moment.min.js"></script>
    <script src="assets/js/plugins/sweetalert2.js"></script>
    <script src="assets/js/plugins/jquery.validate.min.js"></script>
    <script src="assets/js/plugins/jquery.bootstrap-wizard.js"></script>
    <script src="assets/js/plugins/bootstrap-selectpicker.js"></script>
    <script src="assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
    <script src="assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="assets/js/plugins/bootstrap-tagsinput.js"></script>
    <script src="assets/js/plugins/jasny-bootstrap.min.js"></script>
    <script src="assets/js/plugins/fullcalendar.min.js"></script>
    <script src="assets/js/plugins/jquery-jvectormap.js"></script>
    <script src="assets/js/plugins/nouislider.min.js"></script>
    <script src="assets/js/plugins/arrive.min.js"></script>
    <script src="assets/js/plugins/chartist.min.js"></script>
    <script src="assets/js/plugins/bootstrap-notify.js"></script>
    <script src="assets/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
    <script src="assets/demo/demo.js"></script>
</body>
</html>
<?php
// Display error messages
if ($invalid === 1) {
    echo "<script>M.toast({html: 'Invalid username or password', classes: 'r1'})</script>";
} elseif ($invalid === 2) {
    echo "<script>M.toast({html: 'Security validation failed', classes: 'r1'})</script>";
} elseif ($invalid === 3) {
    echo "<script>M.toast({html: 'Database connection error', classes: 'r1'})</script>";
}
?>