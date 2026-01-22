<?php
/**
 * Admin Sales List Page
 * 
 * This file displays the sales management interface in the admin panel.
 * It includes search functionality and dynamic loading of sales data.
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
        <div class="col s12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Sales Management</h4>
                    <p class="card-category">View and search sales records</p>
                </div>
                <div class="card-body">
                    <!-- Search filters with improved styling -->
                    <div class="row">
                        <div class="col s3">
                            <div class="input-field">
                                <i class="material-icons prefix">person</i>
                                <input type="text" id="searchselntxt" class="form-control" placeholder="Search By Name" />
                                <label for="searchselntxt">Customer Name</label>
                            </div>
                        </div>
                        <div class="col s3">
                            <div class="input-field">
                                <i class="material-icons prefix">store</i>
                                <input type="text" id="searchselsntxt" class="form-control" placeholder="Search By Shop Name" />
                                <label for="searchselsntxt">Shop Name</label>
                            </div>
                        </div>
                        <div class="col s3">
                            <div class="input-field">
                                <i class="material-icons prefix">assignment_return</i>
                                <select id="searchbdd" class="form-control">
                                    <option value="-1">Select Borrow Status</option>
                                    <option value="0">Done</option>
                                    <option value="1">Un-Done</option>
                                </select>
                                <label for="searchbdd">Borrow Status</label>
                            </div>
                        </div>
                        <div class="col s3">
                            <div class="input-field">
                                <i class="material-icons prefix">date_range</i>
                                <input type="text" id="searchseldtxt" class="form-control datepicker" placeholder="Search By Date" />
                                <label for="searchseldtxt">Date</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sales list container - dynamically populated via AJAX -->
<div id="sellslistdv" class="container-fluid"></div>

<?php
include('adminfooter.php');

// Display toast notification for user updates
if (isset($_GET['usrlist']) && $_GET['usrlist'] == "activeup") {
    echo "<script>M.toast({html: 'User Updated Successfully', classes: 'rounded'}); </script>";
    $_SESSION['update_toast'] = 1;
}
?>

<script>
/**
 * Sales List Management Script
 * 
 * Handles the dynamic loading and searching of sales records
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Materialize components
    M.AutoInit();
    
    // Initial load of sales list
    $('#sellslistdv').load("sellslist.php");
    
    // Search by customer name with debounce
    let customerSearchTimeout = null;
    const searchCustomerInput = document.getElementById("searchselntxt");
    
    searchCustomerInput.addEventListener("keyup", function() {
        // Clear previous timeout
        if (customerSearchTimeout) {
            clearTimeout(customerSearchTimeout);
        }
        
        const searchValue = this.value.trim();
        
        // Set a timeout to reduce number of requests while typing
        customerSearchTimeout = setTimeout(function() {
            // Perform AJAX request with proper error handling
            $.ajax({
                url: 'sellslist.php',
                method: 'GET',
                data: { searchn: searchValue },
                cache: false,
                success: function(response) {
                    $('#sellslistdv').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Error loading sales data:", error);
                    M.toast({html: 'Error loading sales data. Please try again.'});
                }
            });
        }, 300); // 300ms debounce
    });

    // Search by shop name with debounce
    let shopSearchTimeout = null;
    const searchShopInput = document.getElementById("searchselsntxt");
    
    searchShopInput.addEventListener("keyup", function() {
        // Clear previous timeout
        if (shopSearchTimeout) {
            clearTimeout(shopSearchTimeout);
        }
        
        const searchValue = this.value.trim();
        
        // Set a timeout to reduce number of requests while typing
        shopSearchTimeout = setTimeout(function() {
            $.ajax({
                url: 'sellslist.php',
                method: 'GET',
                data: { searchsn: searchValue },
                cache: false,
                success: function(response) {
                    $('#sellslistdv').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Error loading shop data:", error);
                    M.toast({html: 'Error loading shop data. Please try again.'});
                }
            });
        }, 300); // 300ms debounce
    });

    // Filter by borrow status
    const borrowStatusSelect = document.getElementById("searchbdd");
    
    borrowStatusSelect.addEventListener("change", function() {
        const selectedValue = this.value;
        
        $.ajax({
            url: 'sellslist.php',
            method: 'GET',
            data: { searchdd: selectedValue },
            cache: false,
            success: function(response) {
                $('#sellslistdv').html(response);
            },
            error: function(xhr, status, error) {
                console.error("Error filtering by status:", error);
                M.toast({html: 'Error filtering data. Please try again.'});
            }
        });
    });

    // Search by date with debounce
    let dateSearchTimeout = null;
    const searchDateInput = document.getElementById("searchseldtxt");
    
    searchDateInput.addEventListener("keyup", function() {
        // Clear previous timeout
        if (dateSearchTimeout) {
            clearTimeout(dateSearchTimeout);
        }
        
        const searchValue = this.value.trim();
        
        // Set a timeout to reduce number of requests while typing
        dateSearchTimeout = setTimeout(function() {
            $.ajax({
                url: 'sellslist.php',
                method: 'GET',
                data: { searchd: searchValue },
                cache: false,
                success: function(response) {
                    $('#sellslistdv').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Error searching by date:", error);
                    M.toast({html: 'Error searching by date. Please try again.'});
                }
            });
        }, 300); // 300ms debounce
    });
});
</script>