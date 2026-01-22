<?php
/**
 * Admin User List Page
 * 
 * This file displays the user management interface in the admin panel.
 * It includes search functionality and dynamic loading of user data.
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
                    <h4 class="card-title">User Management</h4>
                    <p class="card-category">View and search user records</p>
                </div>
                <div class="card-body">
                    <!-- Search filters with improved styling -->
                    <div class="row">
                        <div class="col s4">
                            <div class="input-field">
                                <i class="material-icons prefix">person</i>
                                <input type="text" id="usrnametxt" class="form-control" placeholder="Search By Name" />
                                <label for="usrnametxt">User Name</label>
                            </div>
                        </div>
                        <div class="col s4">
                            <div class="input-field">
                                <i class="material-icons prefix">email</i>
                                <input type="text" id="emailusrtxt" class="form-control" placeholder="Search By Email" />
                                <label for="emailusrtxt">Email</label>
                            </div>
                        </div>
                        <div class="col s4">
                            <div class="input-field">
                                <i class="material-icons prefix">location_on</i>
                                <input type="text" id="addressusrtxt" class="form-control" placeholder="Search By Address" />
                                <label for="addressusrtxt">Address</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User list container - dynamically populated via AJAX -->
<div id="userlistdv" class="container-fluid"></div>

<?php
include('adminfooter.php');
?>

<script>
/**
 * User List Management Script
 * 
 * Handles the dynamic loading and searching of users
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Materialize components
    M.AutoInit();
    
    // Initial load of user list
    $('#userlistdv').load("userlist.php");
    
    // Search by name with debounce
    let nameSearchTimeout = null;
    const searchNameInput = document.getElementById("usrnametxt");
    
    searchNameInput.addEventListener("keyup", function() {
        // Clear previous timeout
        if (nameSearchTimeout) {
            clearTimeout(nameSearchTimeout);
        }
        
        const searchValue = this.value.trim();
        
        // Set a timeout to reduce number of requests while typing
        nameSearchTimeout = setTimeout(function() {
            // Perform AJAX request with proper error handling
            $.ajax({
                url: 'userlist.php',
                method: 'GET',
                data: { searchn: searchValue },
                cache: false,
                success: function(response) {
                    $('#userlistdv').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Error loading users:", error);
                    M.toast({html: 'Error loading users. Please try again.'});
                }
            });
        }, 300); // 300ms debounce
    });
    
    // Search by email with debounce
    let emailSearchTimeout = null;
    const searchEmailInput = document.getElementById("emailusrtxt");
    
    searchEmailInput.addEventListener("keyup", function() {
        // Clear previous timeout
        if (emailSearchTimeout) {
            clearTimeout(emailSearchTimeout);
        }
        
        const searchValue = this.value.trim();
        
        // Set a timeout to reduce number of requests while typing
        emailSearchTimeout = setTimeout(function() {
            // Perform AJAX request with proper error handling
            $.ajax({
                url: 'userlist.php',
                method: 'GET',
                data: { searche: searchValue },
                cache: false,
                success: function(response) {
                    $('#userlistdv').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Error loading users:", error);
                    M.toast({html: 'Error loading users. Please try again.'});
                }
            });
        }, 300); // 300ms debounce
    });
    
    // Search by address with debounce
    let addressSearchTimeout = null;
    const searchAddressInput = document.getElementById("addressusrtxt");
    
    searchAddressInput.addEventListener("keyup", function() {
        // Clear previous timeout
        if (addressSearchTimeout) {
            clearTimeout(addressSearchTimeout);
        }
        
        const searchValue = this.value.trim();
        
        // Set a timeout to reduce number of requests while typing
        addressSearchTimeout = setTimeout(function() {
            // Perform AJAX request with proper error handling
            $.ajax({
                url: 'userlist.php',
                method: 'GET',
                data: { searcha: searchValue },
                cache: false,
                success: function(response) {
                    $('#userlistdv').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("Error loading users:", error);
                    M.toast({html: 'Error loading users. Please try again.'});
                }
            });
        }, 300); // 300ms debounce
    });
});
</script>