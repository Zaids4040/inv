<?php 
/**
 * Subtotal Work Page
 * 
 * This page calculates subtotals, discounts, and return amounts for sales.
 * It displays checkout information and handles amount calculations.
 * 
 * @version 1.0
 */

// Start session and include required functions
session_start();
require_once("functions.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout Page</title>
</head>

<body>
<?php
// Fetch all home sells records
$sells_list = sel_table("home_sells", "");

// Query to calculate subtotal for non-replaced items
$subtotal_query = "SELECT SUM(price) FROM home_sells WHERE replace_status = 0 OR replace_status IS NULL";
$subtotal_result = mysqli_query($con, $subtotal_query);

// Query to calculate total for replaced items
$slip_query = "SELECT SUM(price) FROM home_sells WHERE replace_status = 1";
$slip_result = mysqli_query($con, $slip_query);
$slip_total_data = mysqli_fetch_row($slip_result);

// If amount parameter is not set, display checkout information
if (!isset($_GET['amot'])) {
    // Calculate final subtotal
    if ($subtotal_row = mysqli_fetch_row($subtotal_result)) {
        $sub_total_final = $subtotal_row[0] - ($slip_total_data[0] ?? 0);
        ?>
        <hr>
        <table>
            <tr>
                <td><b>Sub Total: </b></td>
                <td><?= number_format($sub_total_final, 2) ?></td>
            </tr>
            
            <tr>
                <td id="disounth"></td>
                <td id="discountm"></td>
            </tr>
            
            <tr>
                <td id="grossh"></td>
                <td id="grossm"></td>
            </tr>
        </table>
        <input type="hidden" value="<?= $sub_total_final ?>" name="subtotalhd"/>
        <input type="submit" value="Check Out" class="btn btn-info w-100" name="checkoutbtn"/>
        
        <script>
            /**
             * Initialize discount and total calculations on page load
             */
            document.addEventListener('DOMContentLoaded', function() {
                updateCalculations();
            });
            
            /**
             * Update discount and total calculations
             */
            function updateCalculations() {
                const amount = document.getElementById("amountchangetxt");
                const discount = document.getElementById("discounttxt");
                const subtotal = <?= $sub_total_final ?>;
                
                // Update discount display
                document.getElementById("disounth").innerHTML = "Discount: ";
                document.getElementById("discountm").innerHTML = "- " + (discount.value ? discount.value : 0);
                
                // Update gross total
                document.getElementById("grossh").innerHTML = "Gross Total: ";
                const grossTotal = subtotal - (discount.value ? parseFloat(discount.value) : 0);
                document.getElementById("grossm").innerHTML = grossTotal.toFixed(2);
                
                // Calculate return amount if amount is provided
                if (amount && amount.value) {
                    const returnAmount = parseFloat(amount.value) - grossTotal;
                    document.getElementById("returnamotxt").innerText = "Return Amount: " + returnAmount.toFixed(2);
                }
            }
            
            /**
             * Handle discount changes
             */
            function discount() {
                updateCalculations();
            }
            
            /**
             * Handle return amount calculation
             */
            function returnamo() {
                updateCalculations();
            }
        </script>
        <?php
    }
} else if (isset($_GET['amot'])) {
    // Display amount input field if sells list has records
    if (mysqli_num_rows($sells_list) > 0) {
        ?>
        <div class="input-field">
            <input type="number" onkeyup="returnamo()" id="amountchangetxt" />
            <label for="amountchangetxt">Amount Taken</label>
        </div>
        <b><div id="returnamotxt"></div></b>
        <?php
    }
}
?>
</body>
</html>