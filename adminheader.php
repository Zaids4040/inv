<?php
/**
 * Admin Panel Header
 * 
 * This file handles authentication, includes necessary styles and scripts,
 * and renders the admin panel navigation structure.
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

// Authentication check
if (!isset($_SESSION['adminsucc']) || $_SESSION['adminsucc'] !== 1) {
    // Redirect to login page if not authenticated
    header("Location: adminlogin.php");
    exit;
}

// Get current URL for active menu highlighting
$current_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin Panel</title>
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    
    <!-- Fonts and Icons -->
    <link rel="stylesheet" type="text/css" href="assets/Roboto300.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="materialize-v1.0.0/materialize/css/materialize.min.css">
    <link href="assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet">
    <link href="assets/demo/demo.css" rel="stylesheet">
    
    <style>
    /* Custom Styles */
    .r1 {
        color: #F00;
    }
    
    .mi {
        font-weight: 700;
    }
    
    /* Dropdown Menu Styles */
    .lid {
        display: block;
        transition-duration: 0.5s;
    }
    
    .lid:hover {
        cursor: pointer;
    }
    
    ul .lid .uld {
        visibility: hidden;
        opacity: 0;
        position: relative;
        transition: all 0.5s ease;
        margin-top: 1rem;
        left: 0;
        display: none;
    }
    
    ul .lid:hover > .uld,
    ul .lid .uld:hover {
        visibility: visible;
        opacity: 1;
        display: block;
    }
    
    ul .lid .uld .lid {
        clear: both;
        width: 100%;
    }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-color="azure" data-background-color="white">
            <div class="logo">
                <a href="AdminPanel.php" class="simple-text logo-normal">
                    Admin Panel
                </a>
            </div>
            
            <div class="sidebar-wrapper" data-color="green">
                <ul class="nav">
                    <!-- Dashboard -->
                    <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) === 'AdminPanel.php' && !isset($_GET['users']) && !isset($_GET['products'])) ? 'active' : ''; ?> mi">
                        <a class="nav-link" href="AdminPanel.php">
                            <i class="material-icons">dashboard</i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    
                    <!-- Users Section -->
                    <li class="lid nav-item <?php echo (isset($_GET['users']) || isset($_GET['usrlist'])) ? 'active' : ''; ?>">
                        <a class="nav-link mi tooltipped" data-tooltip="<i class='material-icons'>arrow_drop_down</i>" data-position="right" href="AdminPanel.php?users=active">
                            <i class="material-icons">person</i>
                            <p>Users</p>
                        </a>
                        <ul class="uld dropdown"> 
                            <li class="lid nav-item">
                                <a class="nav-link" href="adminuser_add.php">
                                    <i class="material-icons">add</i>
                                    <p>Add Users</p>
                                </a>
                            </li>
                            <li class="lid nav-item">
                                <a class="nav-link" href="adminuser_list.php">
                                    <i class="material-icons">list</i>
                                    <p>Users List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Products Section -->
                    <li class="nav-item lid <?php echo (isset($_GET['products']) || isset($_GET['prolist'])) ? 'active' : ''; ?>">
                        <a class="nav-link mi tooltippedd" href="AdminPanel.php?products=active" data-tooltip="<i class='material-icons'>arrow_drop_down</i>" data-position="right">
                            <i class="material-icons">library_books</i>
                            <p>Products</p>
                        </a>
                        <ul class="uld dropdown"> 
                            <li class="lid nav-item">
                                <a class="nav-link" href="adminproduct_add.php">
                                    <i class="material-icons">add</i>
                                    <p>Add Products</p>
                                </a>
                            </li>
                            <li class="lid nav-item">
                                <a class="nav-link" href="adminproduct_list.php">
                                    <i class="material-icons">list</i>
                                    <p>Products List / Stock</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Sales List -->
                    <li class="nav-item">
                        <a class="nav-link mi" href="adminsells_list.php">
                            <i class="material-icons">content_paste</i>
                            <p>Sales List</p>
                        </a>
                    </li>
                    
                    <!-- Expense List -->
                    <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) === 'adminexpence_list.php') ? 'active' : ''; ?>">
                        <a class="nav-link mi" href="adminexpence_list.php">
                            <i class="material-icons">attach_money</i>
                            <p>Expense List</p>
                        </a>
                    </li>
                    
                    <!-- Company Info -->
                    <li class="nav-item">
                       <a class="nav-link mi" href="https://oceanx.solutions" target="_blank">
                            <i class="material-icons">business</i>
                            <p>OceanX Solutions</p>
                        </a>

                    </li>
                </ul>
                
                <!-- Quick Action Forms -->
                <div class="container">
                    <!-- Expense Quick Add Form -->
                    <form action="expenceadd.php" method="post" class="mb-4">
                        <div class="form-group">
                            <input type="text" name="expentxt" class="form-control" placeholder="Expense Name" required/>
                        </div>
                        <div class="row">
                            <div class="col s6">
                                <a class="btn btn-warning w-100" href="adminexpence_view.php">View</a>
                            </div>
                            <div class="col s6">
                                <button type="submit" name="expenbtn" class="btn btn-info w-100">ADD</button>
                            </div>
                        </div>
                    </form>

                    <!-- Category Quick Add Form -->
                    <form action="categoryadd.php" method="post">
                        <div class="form-group">
                            <input type="text" name="cattxt" class="form-control" placeholder="Category Name" required/>
                        </div>
                        <div class="row">
                            <div class="col s6">
                                <a class="btn btn-warning w-100" href="admincategory_view.php">View</a>
                            </div>
                            <div class="col s6">
                                <button type="submit" name="catbtn" class="btn btn-info w-100">ADD</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Main Panel -->
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <a class="navbar-brand" href="javascript:;">Dashboard</a>
                    </div>
                    
                    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                    </button>
                    
                    <div class="collapse navbar-collapse justify-content-end">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="btn btn-info" href="barcode_page.php">Generate New Bar Code</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">person</i>
                                    <p class="d-lg-none d-md-block">Account</p>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                                    <a class="dropdown-item" href="AdminPanel.php?logout=active">Log out</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
            
            <!-- Content Spacer -->
            <div class="content-spacer" style="margin-top: 6rem;"></div>