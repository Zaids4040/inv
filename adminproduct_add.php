<?php
/**
 * Admin Product Management Page
 * 
 * This file handles adding and updating products in the admin panel.
 * It includes functionality for managing product variations, images, and inventory.
 * 
 * @version 2.0
 * @author System Developer
 */

// Include header and initialize session
include("adminheader.php");

// Handle redirect after update toast
if(isset($_SESSION['update_toast']) && $_SESSION['update_toast'] == 1 && isset($_GET['usrlist'])) {
    echo "<script>window.location = 'AdminPanel.php?usrlist=active'</script>";
    $_SESSION['update_toast'] = 0;
}

// Fetch all categories from database
$categories = sel_table('category', '');
?>

<style>
  th {
    font-size: large;
  }
  th, td, td input {
    text-align: center;
  }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-success">
                    <h4 class="card-title"><?= isset($_GET['proup']) ? "Update Product" : "Add Product" ?></h4>
                    <p class="card-category">Complete your product</p>
                </div>
                <div class="card-body">
                    <?php
                    // Fetch product data if in update mode
                    if(isset($_GET['proup'])) {
                        $proid = intval($_GET['proup']);
                        $pro_update_query = "SELECT * FROM products WHERE id = ?";
                        
                        // Use prepared statement for security
                        $stmt = mysqli_prepare($con, $pro_update_query);
                        mysqli_stmt_bind_param($stmt, "i", $proid);
                        mysqli_stmt_execute($stmt);
                        $pro_update_result = mysqli_stmt_get_result($stmt);
                        $pro_update_rows = mysqli_fetch_row($pro_update_result);
                    }
                    ?>
                    
                    <!-- Product Form -->
                    <form action="<?= isset($_GET['dash']) ? 
                        "procrude.php?proup={$_GET['proup']}&date={$pro_update_rows[7]}&dash=active" : 
                        (isset($_GET['proup']) ? 
                            "procrude.php?proup={$_GET['proup']}&date={$pro_update_rows[7]}" : 
                            "procrude.php") ?>" 
                        method="post" enctype="multipart/form-data">
                        
                        <!-- Product Name and Price -->
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="pronametxt" name="protxt" 
                                    value="<?= isset($_GET['proup']) ? htmlspecialchars($pro_update_rows[1]) : '' ?>" 
                                    type="text" class="validate" required>
                                <label for="pronametxt">Product Name (پروڈکٹ کا نام)</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="propricetxt" type="number" step="0.01"
                                    value="<?= isset($_GET['proup']) ? htmlspecialchars($pro_update_rows[2]) : '' ?>" 
                                    name="propritxt" class="validate" required>
                                <label for="propricetxt">Single Product Price (فی مصنوعات کی قیمت)</label>
                            </div>
                        </div>
                        
                        <!-- Stock and Bottle Size -->
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="stocktxt" type="text" 
                                    value="<?php 
                                        if(isset($_GET['proup'])) {
                                            $stockupshow = ($pro_update_rows[9] == 1) ? $pro_update_rows[3] : $pro_update_rows[3];
                                            echo htmlspecialchars($stockupshow);
                                        }
                                    ?>" 
                                    name="stocktxt" class="validate" required>
                                <label for="stocktxt">Stock Quantity <?= isset($_GET['proup']) ? "(per piece)" : "" ?> (اسٹاک کی مقدار)</label>
                            </div>
                            <div class="input-field col s6" style='display:none'>
                                <input id="litretxt" value='1' type="number" step="0.01"
                                    value="<?= isset($_GET['proup']) ? htmlspecialchars($pro_update_rows[4]) : '' ?>" 
                                    name="litertxt" class="validate">
                                <label for="litretxt">Bottle Liter (ایک بوتل میں کتنا لیٹر ہے؟)</label>
                            </div>
                        </div>
                        
                        <!-- Carton Quantity and Discounts -->
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="quan_c_txt" type="number" 
                                    value="<?= isset($_GET['proup']) ? htmlspecialchars($pro_update_rows[5]) : '' ?>" 
                                    name="quanctxt" class="validate">
                                <label for="quan_c_txt">Quantity in Carton (ایک کارٹن میں کتنی بوتلیں ہیں)</label>
                            </div>   
                            <div class="input-field col s6">
                                <div class="col s6">
                                    <input id="c_dis_txt" type="text" 
                                        value="<?= isset($_GET['proup']) ? htmlspecialchars($pro_update_rows[6]) : '' ?>" 
                                        name="carpricetxt"/>
                                    <label for="c_dis_txt">Carton Discount (Optional)</label>
                                </div>
                                <div class="col s6">
                                    <input id="pro_dis_txt" type="text" 
                                        value="<?= isset($_GET['proup']) ? htmlspecialchars($pro_update_rows[14]) : '' ?>" 
                                        name="pro_dis_txt"/>
                                    <label for="pro_dis_txt">Piece Discount (Optional)</label>
                                </div>
                            </div>                     
                        </div>
                        
                        <!-- Wholesale Price and Image Upload -->
                        <div class="row">
                            <div class="input-field col s6">
                                <input type="text" id="salepricetxt" 
                                    value="<?= isset($_GET['proup']) ? htmlspecialchars($pro_update_rows[10]) : '' ?>" 
                                    name="wholesalepricetxt"/>
                                <label for="salepricetxt">Whole Sale Per Piece Price (آپ نے اس پروڈکٹ کے لئے کتنا رقم ادا کی)</label>
                            </div>
                            <div class="col s6">
                                <div class="file-field input-field">
                                    <div class="btn btn-success">
                                        <i class="material-icons" style="margin-top:-40px;font-size:18px;">filter</i>
                                        <input type="file" name="imageselector[]" multiple>
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" disabled name="imgupdatetxt" 
                                            value="<?= isset($_GET['proup']) ? htmlspecialchars($pro_update_rows[11]) : '' ?>" 
                                            type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- QR Code and Category -->
                        <div class="row">  
                            <div class="input-field col s6">
                                <input id="qrcode" type="number" 
                                    value="<?= isset($_GET['proup']) ? htmlspecialchars($pro_update_rows[12]) : '' ?>" 
                                    name="qrcode"/>
                                <label for="qrcode">QR Code</label>
                            </div>  
                            <div class="input-field col s6">
                                <select id="catDD" name="catDD">
                                    <option value="-1" disabled selected>Select Category</option>
                                    <?php 
                                    // Display all categories
                                    while($cat = mysqli_fetch_row($categories)) {
                                        echo "<option value=\"" . htmlspecialchars($cat[0]) . "\">" . htmlspecialchars($cat[1]) . "</option>";
                                    } 
                                    ?>
                                </select>
                                <label>Category Selection</label>
                            </div>
                        </div>
                        
                        <!-- Options -->
                        <div style="display:flex">
                            <label>
                                <input type="checkbox" name="cartonquanch" class="pull-right" />
                                <span>Carton Quantity (کیا یہ کارٹن کی مقدار ہے؟)</span>
                            </label>
                            <p style="margin-left:10px">
                                <label>
                                    <input name="toggle" type="radio" value="plainEdit" checked />
                                    <span>Plain Edit</span>
                                </label>
                            </p>
                            <p style="margin-left:10px">
                                <label>
                                    <input name="toggle" type="radio" value="inventoryCalculate" />
                                    <span>Inventory Calculate</span>
                                </label>
                            </p>
                        </div>
                       
                        <!-- Submit Button -->
                        <input type="submit" 
                            value="<?= isset($_GET['proup']) ? 'Update' : 'Save' ?>" 
                            name="probtn" class="btn btn-success pull-right"/>
                    </form>
                    
                    <?php
                    // Display additional options for product update
                    if(isset($_GET['proup'])) {
                    ?>
                    <br>
                    <!-- Product Images Table -->
                    <div class="container">
                        <table class="striped">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Replace</th>
                                    <th>Delete</th>
                                    <th>Save</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch and display product images
                                $img_usr = sel_table('pro_img', 'WHERE productid = ' . intval($_GET['proup']));
                                while($img = mysqli_fetch_row($img_usr)) {
                                ?>
                                <form action="imageedit.php" method="post" enctype="multipart/form-data">
                                    <tr>
                                        <td>
                                            <img src="<?= htmlspecialchars($img[1]) ?>" style="max-width:100%;height:100px;" alt="Product Image" />
                                        </td>
                                        <td>
                                            <input type="file" value="Replace" id="replacebtn" name="replacebtn" class="btn btn-primary">
                                        </td>
                                        <td>
                                            <input type="hidden" value="<?= htmlspecialchars($img[1]) ?>" name="oldimage"/>
                                            <input type="hidden" value="<?= htmlspecialchars($img[2]) ?>" name="proid"/>
                                            <input type="hidden" value="<?= htmlspecialchars($img[0]) ?>" name="imgid"/>
                                            <input type="submit" value="Delete" class="btn btn-danger" id="updelbtn" name="updelbtn">
                                        </td>
                                        <td>
                                            <input type="submit" value="Save" class="btn btn-primary" id="savebtn" name="savebtn">
                                        </td>
                                    </tr>
                                </form>
                                <?php
                                }
                                ?> 
                            </tbody>
                        </table>
                    </div>

                    <?php
                    // Product Variations Section
                    $query_variations = "SELECT * FROM product_vari WHERE pro_id = ?";
                    $stmt = mysqli_prepare($con, $query_variations);
                    mysqli_stmt_bind_param($stmt, "i", $_GET['proup']);
                    mysqli_stmt_execute($stmt);
                    $sql_exe_vaition = mysqli_stmt_get_result($stmt);
                    $num_rows = mysqli_num_rows($sql_exe_vaition);
                    
                    if($num_rows > 0) {
                        // Display existing variations
                        $fetch_variations = mysqli_fetch_row($sql_exe_vaition);
                    ?>
                    <input type="hidden" id="varcount" value="<?= $num_rows ?>">

                    <div class="container">
                        <table class="striped">
                            <thead>
                                <tr>
                                    <th>Variations</th>
                                    <th>Barcode</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Wholesale Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                mysqli_data_seek($sql_exe_vaition, 0);
                                if (mysqli_num_rows($sql_exe_vaition) > 0) {
                                    // Loop through variations and display them
                                    while ($varrow = mysqli_fetch_row($sql_exe_vaition)) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($varrow[4]." ".$varrow[5]." ".$varrow[6]." ".$varrow[7]) . "</td>";
                                        echo '<td><input type="text" id="varbarcode[' . $varrow[0] . ']" value="' . htmlspecialchars($varrow[3]) . '"></td>';
                                        echo '<td><input type="number" id="varquantity[' . $varrow[0] . ']" value="' . htmlspecialchars($varrow[2]) . '"></td>';
                                        echo '<td><input type="number" id="pricevar[' . $varrow[0] . ']" value="' . htmlspecialchars($varrow[8]) . '"></td>';
                                        echo '<td><input type="number" id="wholevar[' . $varrow[0] . ']" value="' . htmlspecialchars($varrow[9]) . '"></td>';
                                        
                                        echo '<input type="hidden" id="varid[' . $varrow[0] . ']" value="' . $varrow[0] . '">';
                                        echo '<input type="hidden" id="fidvar" value="' . $varrow[0] . '">';
                                        echo '<input type="hidden" id="wholehdd[' . $varrow[0] . ']" value="' . htmlspecialchars($varrow[9]) . '">';
                                        echo '<input type="hidden" id="preqty[' . $varrow[0] . ']" value="' . htmlspecialchars($varrow[2]) . '">';
                                        
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>No variations found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        <button type="button" class="btn" onclick="updatevariation()" style="float:inline-end" id="savechangBtn">Save Changes</button>
                        <div class="switch">
                            <p>
                                <label>
                                    <input name="group1" type="radio" value="0" checked />
                                    <span>Plain Edit</span>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input name="group1" value="1" type="radio" />
                                    <span>Inventory Calculate</span>
                                </label>
                            </p>
                        </div>
                    </div>
                    <?php
                    } else {
                        // Show option to add variations
                    ?>
                        <label>
                            <input type="checkbox" name="variationCH" id="variationCH" onchange="variation()" class="pull-right">
                            <span>This Product has Variations</span>
                        </label>
                    <?php
                    }
                    ?>
                     
                    <br>
                    <hr>
                    <!-- Variation Management Section -->
                    <div id="variationDIV" class="mt-5" style="display:none">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col s4">
                                    <select id="varDD" name="varDD">
                                        <option value="-1" disabled selected>Select Variation Type</option>
                                        <option value="1">Size</option>
                                        <option value="2">Color</option>
                                        <option value="3">Material</option>
                                        <option value="4">Style</option>
                                    </select>
                                    <label>Variation Type</label>
                                </div>
                                <div class="col s4">
                                  <input type="text" class="form-control" placeholder="Enter Value" id="valtxt" />
                                </div>
                                <div class="col s2">
                                    <input type="button" class="btn bt-primary w-100" id="varBtn" value="ADD" onclick="arr_var()"/>
                                </div>
                                <div class="col s2">
                                  <input type="button" class="btn btn-success w-100" value="Save" onclick="variation_com()"/>
                                </div>
                              </div>

                              <div class="container">
                                <div class="row" id="variationdisplay"></div>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div id="outputDiv"></div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('adminfooter.php');
?>

<script>
/**
 * Product Variation Management Script
 * 
 * Handles the creation, display, and management of product variations
 */

// Arrays to store variation types
var color = [];
var size = [];
var style = [];
var material = [];
var variants = [];

// Set the selected category if updating a product
const sselect = document.getElementById('catDD');
<?php if(isset($pro_update_rows[15])): ?>
sselect.value = <?= $pro_update_rows[15] ?>;
<?php endif; ?>

/**
 * Toggle variation div visibility
 */
function variation() {
    const variationDiv = document.getElementById('variationDIV');
    variationDiv.style.display = document.getElementById("variationCH").checked ? "block" : "none";
}
 
/**
 * Add variation to appropriate array based on type
 */
function arr_var() {
    const valtxt = document.getElementById('valtxt');
    const varBtn = document.getElementById('varBtn');
    const variationDD = document.getElementById('varDD');
    const variationdisplay = document.getElementById('variationdisplay');
    
    if(valtxt.value === "") {
        alert('Please Fill The Value Of Variation');
        return;
    }
    
    varBtn.value = "...";
    
    if(variationDD.value == -1) {
        alert('Please Select Any Variation');
        varBtn.value = "ADD";
        return;
    }
    
    // Add to appropriate array based on variation type
    let displayArray;
    let prefix;
    
    switch(parseInt(variationDD.value)) {
        case 1: // Size
            prefix = "si_";
            size.push(prefix + valtxt.value);
            displayArray = size;
            break;
        case 2: // Color
            prefix = "co_";
            color.push(prefix + valtxt.value);
            displayArray = color;
            break;
        case 3: // Material
            prefix = "ma_";
            material.push(prefix + valtxt.value);
            displayArray = material;
            break;
        case 4: // Style
            prefix = "st_";
            style.push(prefix + valtxt.value);
            displayArray = style;
            break;
    }
    
    valtxt.value = "";
    varBtn.value = "ADD";
    variationdisplay.innerHTML = displayArray.join(', ');
    
    // Focus back on input for quick entry
    valtxt.focus();
}

/**
 * Generate combinations of variations
 */
function variation_com() {
    // Create an array containing the four variation arrays
    var arrays = [color, size, style, material];
    
    // Sort arrays by length (descending)
    arrays.sort(function(a, b) {
        return b.length - a.length;
    });
    
    // Initialize array to store non-empty arrays
    var first = [];
    
    // Add non-empty arrays to first
    for (var i = 0; i < arrays.length; i++) {
        first.push(arrays[i]);
    }
    
    // Filter out empty arrays
    const filteredNames = first.filter(subArray => subArray.length > 0);
    
    // Generate Cartesian product of arrays
    const cartesian = (...a) => a.reduce((a, b) => a.flatMap(d => b.map(e => [d, e].flat())));
    
    // Get all combinations
    let output = cartesian(...filteredNames);
    console.log(output);
    
    // Display the variations
    var outputDiv = document.getElementById("outputDiv");
    outputDiv.innerHTML = "<b><h3>Variations</h3></b><br>";
    printSubarraysWithQty(output);
}

/**
 * Display variations with input fields for quantity and barcode
 * @param {Array} output - Array of variation combinations
 */
function printSubarraysWithQty(output) {
    var outputDiv = document.getElementById("outputDiv");
    document.getElementById('variationdisplay').innerHTML = "";

    output.forEach(function(item, index) {
        var subarrayOutput = '';

        // Format variation name
        if (Array.isArray(item)) {
            item.forEach(function(name, i) {
                // Remove prefixes from display
                var modifiedName = name.replace(/^(si_|co_|ma_|st_)/i, "");
                subarrayOutput += modifiedName;
                if (i !== item.length - 1) {
                    subarrayOutput += ' / ';
                }
            });
        } else {
            // Handle single item
            subarrayOutput += item.replace(/^(si_|co_|ma_|st_)/i, "");
        }

        // Create quantity input field
        var qtyTextbox = document.createElement("input");
        qtyTextbox.setAttribute("type", "text");
        qtyTextbox.setAttribute("placeholder", "QTY");
        qtyTextbox.setAttribute("value", document.getElementById('stocktxt').value);
        qtyTextbox.setAttribute("id", "qtytxt_" + index);

        // Create barcode input field
        var barTextbox = document.createElement("input");
        barTextbox.setAttribute("type", "text");
        barTextbox.setAttribute("placeholder", "Bar Code");
        barTextbox.setAttribute("id", "bartxt_" + index);

        // Append variation name
        outputDiv.innerHTML += '<b>' + subarrayOutput + '</b>';
        
        // Create container for inputs
        var containerDiv = document.createElement('div');
        containerDiv.classList.add('container');

        // Create row for inputs
        var rowDiv = document.createElement('div');
        rowDiv.classList.add('row');

        // Create columns for inputs
        var qtyColDiv = document.createElement('div');
        qtyColDiv.classList.add('col', 's6');
        qtyColDiv.appendChild(qtyTextbox);

        var barColDiv = document.createElement('div');
        barColDiv.classList.add('col', 's6');
        barColDiv.appendChild(barTextbox);

        // Assemble the layout
        rowDiv.appendChild(qtyColDiv);
        rowDiv.appendChild(barColDiv);
        containerDiv.appendChild(rowDiv);
        outputDiv.appendChild(containerDiv);
        outputDiv.appendChild(document.createElement("br"));
    });

    // Add save button
    var buttonContainer = document.createElement('div');
    buttonContainer.classList.add('btn-container');

    var savebtn = document.createElement('input');
    savebtn.classList.add('btn', 'btn-success');
    savebtn.setAttribute('type', 'button');
    savebtn.setAttribute('value', 'Save');
    savebtn.setAttribute('onclick', 'savvar(' + JSON.stringify(output) + ')');

    buttonContainer.appendChild(savebtn);
    buttonContainer.style.textAlign = 'right';
    outputDiv.appendChild(buttonContainer);
}

/**
 * Save variations to database
 * @param {Array} output - Array of variation combinations
 */
function savvar(output) {
    var finalarr = [];
    var proup = <?= isset($_GET['proup']) ? intval($_GET['proup']) : 0 ?>;
    
    // Process each variation
    for (var i = 0; i < output.length; i++) {
        var qty = document.getElementById('qtytxt_' + i).value;
        var barcode = document.getElementById('bartxt_' + i).value;
        var size = "";
        var color = "";
        var material = "";
        var style = "";
        
        // Extract variation values
        if (Array.isArray(output[i])) {
            for (var j = 0; j < output[i].length; j++) {
                if (output[i][j].startsWith("si_")) {
                    size = output[i][j].substring(3);
                } else if (output[i][j].startsWith("co_")) {
                    color = output[i][j].substring(3);
                } else if (output[i][j].startsWith("ma_")) {
                    material = output[i][j].substring(3);
                } else if (output[i][j].startsWith("st_")) {
                    style = output[i][j].substring(3);
                }
            }
        } else {
            if (output[i].startsWith("si_")) {
                size = output[i].substring(3);
            } else if (output[i].startsWith("co_")) {
                color = output[i].substring(3);
            } else if (output[i].startsWith("ma_")) {
                material = output[i].substring(3);
            } else if (output[i].startsWith("st_")) {
                style = output[i].substring(3);
            }
        }

        var finaldata = [proup, size, color, material, style, qty, barcode];
        finalarr.push(finaldata);
    }
    
    // Send data to server
    $.ajax({
        type: "POST",
        url: "variationsave.php",
        data: {
            finalarr: finalarr
        },
        success: function(response) {
            if(finalarr.length == response) {
                alert('Successfully Saved');
            }
        },
        error: function(response) {
            console.log(response);
        }
    });
}

/**
 * Update existing variations
 */
function updatevariation() {
    var checkedValue = document.querySelector('input[name="group1"]:checked').value;
    var array_var_update = [];
    var fidvar = document.getElementById('fidvar').value;
    var varcount = parseInt(fidvar) + parseInt(document.getElementById('varcount').value);

    // Process each variation
    for(var i = fidvar; i < varcount; i++) {
        var pricevar = document.getElementById('pricevar[' + i + ']').value;
        var wholevar = document.getElementById('wholevar[' + i + ']').value;
        var wholehdd = document.getElementById('wholehdd[' + i + ']').value;
        var preqty = document.getElementById('preqty[' + i + ']').value;

        var rid = document.getElementById('varid[' + i + ']').value;
        var barcode = document.getElementById('varbarcode[' + i + ']').value;
        var qty = document.getElementById('varquantity[' + i + ']').value;

        var total_qty, final_whole_price;
        
        // Calculate based on edit mode
        if(checkedValue == 0) {
            // Plain edit - use values as entered
            total_qty = parseInt(qty);
            final_whole_price = parseFloat(wholevar);
        } else {
            // Inventory calculate - weighted average calculation
            var mul_curr_qty = parseFloat(wholevar * qty);
            var mul_pre_qty = parseFloat(wholehdd * preqty);
            var add_muls = parseFloat(mul_curr_qty + mul_pre_qty);
            total_qty = parseInt(qty) + parseInt(preqty);
            final_whole_price = parseFloat(add_muls / total_qty);
        }

        array_var_update.push([rid, barcode, total_qty, pricevar, final_whole_price]);
    }
    
    // Update button to show loading state
    var button = document.getElementById('savechangBtn');
    var originalText = button.innerHTML;
    button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
    
    // Send update to server
    $.ajax({
        url: 'variationupdate.php',
        type: 'POST',
        data: { array_var_update: array_var_update },
        success: function(response) {
            if(response == 1) {
                button.innerHTML = originalText;
                alert('Successfully Updated');
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            button.innerHTML = originalText;
        }
    });
}
</script>
