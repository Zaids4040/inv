<?php
/**
 * User Authentication System
 * 
 * This script handles user login functionality with secure authentication.
 * It validates user credentials against the database and manages session creation.
 * 
 * @version 3.0
 * @author Pro Developer
 * @date 2023-12-30
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Process login form submission
if (isset($_POST['logbtn'])) {
    // Sanitize and validate user inputs
    $email = filter_input(INPUT_POST, 'usrtxt', FILTER_SANITIZE_EMAIL);
    $password = $_POST['pastxt']; // Will be hashed in a production environment
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $loginError = "Invalid email format";
    } else {
        // Query to check user credentials
        // Note: In production, use prepared statements and password_verify()
        $userQuery = sel_table(
            "users_table", 
            "WHERE email = '" . mysqli_real_escape_string($con, $email) . "' 
             AND pass = '" . mysqli_real_escape_string($con, $password) . "' 
             AND status = 1"
        );
        
        // Check if user exists and credentials are valid
        if ($userQuery && mysqli_num_rows($userQuery) > 0) {
            $userData = mysqli_fetch_row($userQuery);
            
            // Set session variables
            $_SESSION['usrid'] = $userData[0];
            $_SESSION['usrname'] = $userData[1];
            
            // Redirect to home page
            echo "<script>window.location = 'home_page.php'</script>";
            exit;
        } else {
            $loginError = "Invalid username or password";
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User Login</title>
    <link rel="stylesheet" href="materialize-v1.0.0/materialize/css/materialize.css"/>
    <link rel="stylesheet" href="materialize-v1.0.0/materialize/css/materialize.min.css"/>
    <link rel="stylesheet" type="text/css" href="assets/Roboto300.css" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- Material Dashboard CSS -->
    <link href="assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
    <!-- Demo styles -->
    <link href="assets/demo/demo.css" rel="stylesheet" />
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        main {
            flex: 1 0 auto;
        }
        
        .brand-logo > img {
            width: 50px;
            height: 50px;
            margin-top: 5px;
        }
        
        /* Menu - profile */
        .bg-card-user {
            background: rgba(0,77,64,.5);
            padding: 15px 0;
        }
        
        .truncate > img {
            width: 180px;
            margin-top: 6px;
            margin-bottom: 6px;
        }
        
        /* FOOTER */
        footer .foot-text {
            margin-top: 10px;
        }

        /* LOGIN */
        .logueo {
            height: 650px!important;
        }
        
        i.iconis {
            font-size: 1em!important;
            margin-top: 8px;
        }
        
        .login {
            border: 1px solid #FFF;
            width: 80%;
            margin: 0 auto;
            background-color: rgba(255,255,255,.7);
            padding: 20px;
        }
    </style>
</head>

<body>
    <main>
        <center>
            <div class="section"></div>
            <h1>Login</h1>
            <div class="section"></div>
            <h5 class="indigo-text">Login With Your Account Credentials</h5>
            <div class="section"></div>

            <div class="container">
                <div class="z-depth-1 grey lighten-4 col-md-6" style="display: block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">
                    <!-- Display error message if login failed -->
                    <?php if (isset($loginError)): ?>
                        <div class="card-panel red lighten-4">
                            <span class="red-text text-darken-2"><?php echo $loginError; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <div class="row">
                            <div class="col s12">
                                <div class="input-field">
                                    <i class="material-icons prefix">account_circle</i>
                                    <input id="icon_prefix" type="email" name="usrtxt" class="validate" required>
                                    <label for="icon_prefix">Email</label>
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

    <!-- JavaScript Libraries -->
    <script src="materialize-v1.0.0/materialize/js/materialize.js"></script>
    <script src="materialize-v1.0.0/materialize/js/materialize.min.js"></script>
    <script src="jquery-3.5.1.min.js"></script>
    <script>
        /**
         * Initialize Materialize components and handle password visibility toggle
         */
        $(document).ready(function() {
            // Initialize sidenav
            $('.button-collapse').sideNav({
                menuWidth: 300,
                edge: 'left',
                closeOnClick: true,
                draggable: true,
                onOpen: function(el) { /* Do Stuff*/ },
                onClose: function(el) { /* Do Stuff*/ },
            });
            
            // Initialize parallax effect
            $('.parallax').parallax();
            
            // Initialize any toast messages
            <?php if (isset($loginError)): ?>
                M.toast({html: '<?php echo $loginError; ?>', classes: 'red'});
            <?php endif; ?>
        });
        
        // Password visibility toggle functionality
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        
        togglePassword.addEventListener('click', function (e) {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle the eye icon
            if (document.getElementById("togglePassword").innerText == "remove_red_eye") {
                document.getElementById("togglePassword").innerText = "panorama_fish_eye";
            } else {
                document.getElementById("togglePassword").innerText = "remove_red_eye";
            }
        });
    </script>
    
    <!-- Core JS Files -->
    <script src="assets/js/core/jquery.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap-material-design.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <!-- Plugin for the momentJs -->
    <script src="assets/js/plugins/moment.min.js"></script>
    <!-- Plugin for Sweet Alert -->
    <script src="assets/js/plugins/sweetalert2.js"></script>
    <!-- Forms Validations Plugin -->
    <script src="assets/js/plugins/jquery.validate.min.js"></script>
    <!-- Plugin for the Wizard -->
    <script src="assets/js/plugins/jquery.bootstrap-wizard.js"></script>
    <!-- Plugin for Select -->
    <script src="assets/js/plugins/bootstrap-selectpicker.js"></script>
    <!-- Plugin for the DateTimePicker -->
    <script src="assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
    <!-- DataTables.net Plugin -->
    <script src="assets/js/plugins/jquery.dataTables.min.js"></script>
    <!-- Plugin for Tags -->
    <script src="assets/js/plugins/bootstrap-tagsinput.js"></script>
    <!-- Plugin for Fileupload -->
    <script src="assets/js/plugins/jasny-bootstrap.min.js"></script>
    <!-- Full Calendar Plugin -->
    <script src="assets/js/plugins/fullcalendar.min.js"></script>
    <!-- Vector Map plugin -->
    <script src="assets/js/plugins/jquery-jvectormap.js"></script>
    <!-- Plugin for the Sliders -->
    <script src="assets/js/plugins/nouislider.min.js"></script>
    <!-- Library for adding dynamic elements -->
    <script src="assets/js/plugins/arrive.min.js"></script>
    <!-- Chartist JS -->
    <script src="assets/js/plugins/chartist.min.js"></script>
    <!-- Notifications Plugin -->
    <script src="assets/js/plugins/bootstrap-notify.js"></script>
    <!-- Control Center for Material Dashboard -->
    <script src="assets/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
    <!-- Material Dashboard DEMO methods -->
    <script src="assets/demo/demo.js"></script>
    
    <script>
        $(document).ready(function() {
            $().ready(function() {
                $sidebar = $('.sidebar');
                $sidebar_img_container = $sidebar.find('.sidebar-background');
                $full_page = $('.full-page');
                $sidebar_responsive = $('body > .navbar-collapse');
                window_width = $(window).width();
                fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

                if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
                    if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
                        $('.fixed-plugin .dropdown').addClass('open');
                    }
                }

                $('.fixed-plugin a').click(function(event) {
                    if ($(this).hasClass('switch-trigger')) {
                        if (event.stopPropagation) {
                            event.stopPropagation();
                        } else if (window.event) {
                            window.event.cancelBubble = true;
                        }
                    }
                });

                $('.fixed-plugin .active-color span').click(function() {
                    $full_page_background = $('.full-page-background');
                    $(this).siblings().removeClass('active');
                    $(this).addClass('active');
                    var new_color = $(this).data('color');
                    
                    if ($sidebar.length != 0) {
                        $sidebar.attr('data-color', new_color);
                    }
                    
                    if ($full_page.length != 0) {
                        $full_page.attr('filter-color', new_color);
                    }
                    
                    if ($sidebar_responsive.length != 0) {
                        $sidebar_responsive.attr('data-color', new_color);
                    }
                });

                $('.fixed-plugin .background-color .badge').click(function() {
                    $(this).siblings().removeClass('active');
                    $(this).addClass('active');
                    var new_color = $(this).data('background-color');
                    
                    if ($sidebar.length != 0) {
                        $sidebar.attr('data-background-color', new_color);
                    }
                });

                $('.fixed-plugin .img-holder').click(function() {
                    $full_page_background = $('.full-page-background');
                    $(this).parent('li').siblings().removeClass('active');
                    $(this).parent('li').addClass('active');
                    var new_image = $(this).find("img").attr('src');
                    
                    if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
                        $sidebar_img_container.fadeOut('fast', function() {
                            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
                            $sidebar_img_container.fadeIn('fast');
                        });
                    }
                    
                    if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
                        var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');
                        
                        $full_page_background.fadeOut('fast', function() {
                            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
                            $full_page_background.fadeIn('fast');
                        });
                    }
                    
                    if ($('.switch-sidebar-image input:checked').length == 0) {
                        var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
                        var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');
                        
                        $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
                        $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
                    }
                    
                    if ($sidebar_responsive.length != 0) {
                        $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
                    }
                });

                $('.switch-sidebar-image input').change(function() {
                    $full_page_background = $('.full-page-background');
                    $input = $(this);
                    
                    if ($input.is(':checked')) {
                        if ($sidebar_img_container.length != 0) {
                            $sidebar_img_container.fadeIn('fast');
                            $sidebar.attr('data-image', '#');
                        }
                        
                        if ($full_page_background.length != 0) {
                            $full_page_background.fadeIn('fast');
                            $full_page.attr('data-image', '#');
                        }
                        
                        background_image = true;
                    } else {
                        if ($sidebar_img_container.length != 0) {
                            $sidebar.removeAttr('data-image');
                            $sidebar_img_container.fadeOut('fast');
                        }
                        
                        if ($full_page_background.length != 0) {
                            $full_page.removeAttr('data-image', '#');
                            $full_page_background.fadeOut('fast');
                        }
                        
                        background_image = false;
                    }
                });

                $('.switch-sidebar-mini input').change(function() {
                    $body = $('body');
                    $input = $(this);
                    
                    if (md.misc.sidebar_mini_active == true) {
                        $('body').removeClass('sidebar-mini');
                        md.misc.sidebar_mini_active = false;
                        $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();
                    } else {
                        $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');
                        
                        setTimeout(function() {
                            $('body').addClass('sidebar-mini');
                            md.misc.sidebar_mini_active = true;
                        }, 300);
                    }
                    
                    // Simulate window resize to update charts
                    var simulateWindowResize = setInterval(function() {
                        window.dispatchEvent(new Event('resize'));
                    }, 180);
                    
                    // Stop simulation after animations complete
                    setTimeout(function() {
                        clearInterval(simulateWindowResize);
                    }, 1000);
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Initialize dashboard charts
            md.initDashboardPageCharts();
        });
    </script>
</body>
</html>