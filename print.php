<?php
/**
 * Auto-Print Receipt System
 * 
 * This script generates a printable receipt for customer purchases and automatically prints.
 * It displays transaction details including product information, pricing,
 * discounts, and totals. The receipt includes a barcode for transaction tracking.
 * 
 * @version 5.0
 * @author Pro Developer - Enhanced by OceanX.Solutions
 * @date 2025-01-01
 */

// Initialize session and include required functions
session_start();
require_once('functions.php');

// Fetch transaction data
$transactionData = sel_table("home_sells", "");

// Calculate totals using prepared statements for better security
$replacedItemsQuery = "SELECT SUM(price) FROM home_sells WHERE replace_status = 1";
$regularItemsQuery = "SELECT SUM(price) FROM home_sells WHERE replace_status = 0 OR replace_status IS NULL";
$discountQuery = "SELECT SUM(discount) FROM home_sells";

// Get replaced items total (returns/exchanges)
$replacedItemsResult = mysqli_query($con, $replacedItemsQuery);
$replacedItemsTotal = mysqli_fetch_row($replacedItemsResult)[0] ?? 0;

// Get regular items total
$regularItemsResult = mysqli_query($con, $regularItemsQuery);
$regularItemsTotal = mysqli_fetch_row($regularItemsResult)[0] ?? 0;

// Calculate gross total
$grossTotal = $regularItemsTotal - $replacedItemsTotal;

// Get discount amount from URL parameter with validation
$discount = filter_input(INPUT_GET, 'discount', FILTER_VALIDATE_FLOAT) ?? 0;

// Calculate final bill amount
$finalBill = $grossTotal - $discount;

// Get unique transaction ID from database (not from URL)
$transactionDataForId = sel_table("home_sells", "");
$transactionIdRow = mysqli_fetch_row($transactionDataForId);
$transactionId = $transactionIdRow[10] ?? date('YmdHis') . rand(100, 999);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Receipt - Auto Print</title>
    <style>
        /* Slip size printer optimization */
        @page {
            size: 80mm auto; /* Thermal printer size */
            margin: 0;
        }
        
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 5mm;
            width: 70mm;
            font-size: 12px;
            line-height: 1.2;
            color: #000;
        }
        
        .receipt-container {
            width: 100%;
            text-align: center;
        }
        
        .header {
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .receipt-info {
            font-size: 10px;
            margin: 5px 0;
        }
        
        .barcode-section {
            margin: 10px 0;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            text-align: left;
        }
        
        .items-table th {
            border-bottom: 1px solid #000;
            padding: 2px 1px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .items-table td {
            padding: 1px;
            font-size: 9px;
            border-bottom: 1px dotted #ccc;
        }
        
        .item-name {
            max-width: 45mm;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .item-price, .item-qty {
            text-align: right;
            width: 12mm;
        }
        
        .totals-section {
            border-top: 2px solid #000;
            padding-top: 5px;
            margin-top: 10px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
            font-size: 11px;
        }
        
        .final-total {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 3px 0;
            margin: 5px 0;
        }
        
        .footer {
            border-top: 2px dashed #000;
            padding-top: 10px;
            margin-top: 15px;
            font-size: 10px;
        }
        
        .thank-you {
            font-weight: bold;
            margin: 5px 0;
        }
        
        .software-credit {
            font-size: 9px;
            font-style: italic;
            margin-top: 10px;
            color: #666;
        }
        
        /* Print specific styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .no-print {
                display: none !important;
            }
        }
        
        /* Manual print button (hidden by default after auto-print) */
        #printbtn {
            display: none;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="receipt-container" id="receipt">
        <!-- Header Section -->
        <div class="header">
            <div class="company-name">SALES RECEIPT</div>
            <div class="receipt-info">Date: <?php echo date('d/m/Y H:i:s'); ?></div>
            <div class="receipt-info">Transaction ID: <?php echo htmlspecialchars($transactionId); ?></div>
        </div>
        
        <!-- Barcode Section -->
        <div class="barcode-section">
            <?php if(!empty($transactionId)): ?>
                <img alt="Transaction Barcode" 
                     src="barcode.php?codetype=Code128&size=30&text=<?php echo urlencode($transactionId); ?>&print=true"
                     style="max-width: 60mm; height: auto;"/>
            <?php endif; ?>
        </div>
        
        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="text-align: left;">Item</th>
                    <th>Price</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reset pointer to beginning of result set
                mysqli_data_seek($transactionData, 0);
                
                // Display each item in the transaction
                while ($row = mysqli_fetch_row($transactionData)) {
                    // Format price display based on whether item is a return/exchange
                    $priceDisplay = ($row[8] == 1) ? "-" . number_format($row[2], 2) : number_format($row[2], 2);
                    
                    echo "<tr>
                        <td class='item-name'>" . htmlspecialchars($row[1]) . "</td>
                        <td class='item-price'>{$priceDisplay}</td>
                        <td class='item-qty'>{$row[4]}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
        
        <!-- Totals Section -->
        <div class="totals-section">
            <div class="total-row">
                <span>Gross Total:</span>
                <span><?php echo number_format($grossTotal, 2); ?></span>
            </div>
            
            <?php if($discount > 0): ?>
            <div class="total-row">
                <span>Discount:</span>
                <span>-<?php echo number_format($discount, 2); ?></span>
            </div>
            <?php endif; ?>
            
            <div class="total-row final-total">
                <span>TOTAL:</span>
                <span><?php echo number_format($finalBill, 2); ?></span>
            </div>
        </div>
        
        <!-- Footer Section -->
        <div class="footer">
            <div class="thank-you">Thank you for your purchase!</div>
            <div>Visit us again soon!</div>
            <div class="software-credit">Software By OceanX.Solutions</div>
        </div>
    </div>
    
    <!-- Manual print button (backup) -->
    <button id="printbtn" class="no-print" onclick="manualPrint()">Print Receipt</button>
    
    <script src="jquery-3.5.1.min.js"></script>
    <script>
        /**
         * Auto-print functionality
         * Automatically triggers print when page loads
         */
        
        let printExecuted = false;
        
        // Auto-print when page loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                if (!printExecuted) {
                    autoPrint();
                }
            }, 500); // Small delay to ensure page is fully loaded
        });
        
        function autoPrint() {
            printExecuted = true;
            
            // Print the receipt
            window.print();
            
            // After print dialog, clear cart and redirect
            setTimeout(function() {
                clearCartAndRedirect();
            }, 1000); // Give time for print dialog
        }
        
        function manualPrint() {
            window.print();
            setTimeout(function() {
                clearCartAndRedirect();
            }, 1000);
        }
        
        function clearCartAndRedirect() {
            // Clear cart data and redirect to home page
            $.ajax({
                url: 'homeselldelete.php',
                cache: false,
                success: function(response) {
                    window.location = 'home_page.php';
                },
                error: function(xhr, status, error) {
                    console.error("Error clearing cart data:", error);
                    // Still redirect even if clear fails
                    window.location = 'home_page.php';
                }
            });
        }
        
        // Handle print dialog cancellation
        window.addEventListener('afterprint', function() {
            // User completed or cancelled print
            setTimeout(function() {
                clearCartAndRedirect();
            }, 500);
        });
        
        // Fallback: if user closes tab/window without printing
        window.addEventListener('beforeunload', function() {
            if (!printExecuted) {
                // Quick AJAX call to clear cart
                navigator.sendBeacon('homeselldelete.php');
            }
        });
        
        // Show manual print button if auto-print fails
        setTimeout(function() {
            if (window.matchMedia && !window.matchMedia('print').matches) {
                document.getElementById('printbtn').style.display = 'block';
            }
        }, 3000);
    </script>
</body>
</html>