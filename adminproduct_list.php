<?php
/**
 * Admin Product List Page
 * 
 * This file displays the product management interface in the admin panel.
 * It includes search functionality and dynamic loading of product data.
 * 
 * @version 3.0
 * @author Pro Developer
 */

// Include header and initialize session
include("adminheader.php");

// Handle redirect after update toast
if (isset($_SESSION['update_toast']) && $_SESSION['update_toast'] == 1 && isset($_GET['usrlist'])) {
    echo "<script>window.location = 'AdminPanel.php?usrlist=active'</script>";
    $_SESSION['update_toast'] = 0;
}
?>

<div class="container">
    <div class="row mb-3">
        <div class="col s12 w-100 d-flex justify-content-between align-items-center">
            <!-- Search input with improved styling -->
            <div class="input-field col s6">
                <input type="text" class="form-control" name="searchprotxt" id="searchprotxt" placeholder="Search By Name" />
                <i class="material-icons prefix">search</i>
            </div>
            
            <!-- Add Product button with proper styling -->
            <a class="btn btn-success waves-effect waves-light" href="adminproduct_add.php">
                <i class="material-icons left">add</i>Add Product
            </a>
        </div>
    </div>
</div>

<!-- Product list container - dynamically populated via AJAX -->
<div id="productlistdv" class="container-fluid"></div>

<?php
include('adminfooter.php');
?>

<script>
/**
 * Product List Management Script
 * 
 * Handles the dynamic loading and searching of products
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initial load of product list
    $('#productlistdv').load("productlist.php");
    
    // Search functionality with debounce
    let searchTimeout = null;
    const searchInput = document.getElementById("searchprotxt");
    
    searchInput.addEventListener("keyup", function() {
        // Clear previous timeout
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
        
        const searchValue = this.value.trim();
        
        // Set a timeout to reduce number of requests while typing
        searchTimeout = setTimeout(function() {
            // Perform AJAX request with proper error handling
            $.ajax({
                url: 'productlist.php',
                method: 'GET',
                data: { searcha: searchValue },
                cache: false,
                success: function(response) {
                    $('#productlistdv').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Error loading products:", error);
                    M.toast({html: 'Error loading products. Please try again.'});
                }
            });
        }, 300); // 300ms debounce
    });
});
</script>