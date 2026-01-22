<?php
/**
 * Shop List Display Component
 * 
 * This script generates the shop selection dropdown and related form elements
 * for the checkout process. It displays shops from the database and provides
 * options for borrow status and discount application.
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2024-05-30
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Fetch all items in the current shopping cart
$sells_list = sel_table("home_sells", "");

// Only display shop selection if cart has items
if (mysqli_num_rows($sells_list) > 0) {
    // Fetch all available shops from database
    $shop_query = sel_table("shops", "");
    
    // Start building the shop selection form
    echo '<div class="input-field">
        <select name="shopdd" id="shopdd" class="form-select">
            <option value="-1">Shops Collection</option>';
            
    // Populate dropdown with shop options
    while ($shoprow = mysqli_fetch_row($shop_query)) {
        // Note: Using double quotes to properly interpolate variables
        echo "<option value=\"{$shoprow[0]}\">{$shoprow[1]}</option>";
    }
            
    echo '</select>
    </div>
    
    <!-- Borrow status option (hidden by default, shown via JavaScript) -->
    <div id="shopchh" style="display:none;">
        <label class="form-check-label">
            <input type="checkbox" name="shopch" class="form-check-input" />
            <span>Borrow</span>
        </label>
    </div>
    
    <!-- Optional discount field -->
    <div class="input-field">
        <input type="text" name="discounttxt" id="discounttxt" class="form-control" />
        <label for="discounttxt">Discount (Optional)</label>
    </div>';
}
?>