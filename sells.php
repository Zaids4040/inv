<?php
/**
 * Checkout Processing Script
 * 
 * This script handles the checkout process for the shopping cart.
 * It processes product purchases, updates inventory, handles returns/exchanges,
 * applies discounts, and records transactions in the sales database.
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2024-05-15
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Get current user ID from session
$userId = $_SESSION['usrid'] ?? 0;

// Process checkout when form is submitted
if (isset($_POST['checkoutbtn'])) {
    // Get transaction data from form
    $subtotal = filter_input(INPUT_POST, 'subtotalhd', FILTER_VALIDATE_FLOAT);
    $shopId = filter_input(INPUT_POST, 'shopdd', FILTER_VALIDATE_INT);
    $transactionId = time(); // Generate unique transaction ID
    
    // Process discount if provided
    $discount = 0;
    if (!empty($_POST['discounttxt'])) {
        $discount = filter_input(INPUT_POST, 'discounttxt', FILTER_VALIDATE_FLOAT);
        $subtotal = $subtotal - $discount;
    }
    
    // Check if this is a borrow transaction
    $borrowStatus = isset($_POST['shopch']) ? 1 : 0;
    
    // Fetch all products in cart
    $cartProducts = sel_table("home_sells", "");
    $productCount = mysqli_num_rows($cartProducts);
    
    // Calculate per-product discount if applicable
    $perProductDiscount = ($discount != 0 && $productCount > 0) ? $discount / $productCount : 0;
    
    // Process each product in cart
    while ($cartProduct = mysqli_fetch_row($cartProducts)) {
        // Get product details
        $productId = $cartProduct[3];
        $quantity = $cartProduct[4];
        $isReplacement = $cartProduct[8] == 1;
        $variationId = $cartProduct[12];
        
        // Get current product data
        $productData = sel_table("products", "WHERE id = $productId");
        
        // Prepare database update query based on transaction type
        if (!$isReplacement) {
            // Regular sale - decrease inventory
            if ($variationId == 0) {
                // Update main product inventory
                $updateQuery = "UPDATE products 
                                SET stock_quantity = stock_quantity - $quantity 
                                WHERE id = $productId";
            } else {
                // Update product variation inventory
                $updateQuery = "UPDATE product_vari 
                                SET qty = qty - $quantity 
                                WHERE id = $variationId";
            }
        } else {
            // Product return/exchange - increase inventory and recalculate wholesale price
            $product = mysqli_fetch_row($productData);
            $replacementAmount = $quantity * $cartProduct[5]; // Quantity * Wholesale price
            $currentAmount = $product[3] * $product[10]; // Current stock * Current wholesale price
            $totalQuantity = $quantity + $product[3];
            $totalAmount = $replacementAmount + $currentAmount;
            $newWholesalePrice = $totalAmount / $totalQuantity;
            
            if ($variationId == 0) {
                // Update main product
                $updateQuery = "UPDATE products 
                                SET stock_quantity = stock_quantity + $quantity,
                                    whole_sale_price = $newWholesalePrice 
                                WHERE id = $productId";
            } else {
                // Update product variation
                $updateQuery = "UPDATE product_vari 
                                SET qty = qty + $quantity,
                                    whole_price = $newWholesalePrice 
                                WHERE id = $variationId";
            }
        }
        
        // Execute inventory update
        if (mysqli_query($con, $updateQuery)) {
            // Get variation details if applicable
            $variation = "0";
            if ($variationId != 0) {
                $variQuery = "SELECT * FROM product_vari WHERE id = $variationId";
                $variResult = mysqli_query($con, $variQuery);
                if ($variData = mysqli_fetch_row($variResult)) {
                    $variation = "$variData[4]-$variData[5]-$variData[6]-$variData[7]";
                }
            }
            
            // Record transaction in sales table
            if ($shopId == -1) {
                // Direct sale (no shop)
                if (!$isReplacement) {
                    // Regular sale
                    $price = $cartProduct[2] - $perProductDiscount;
                    $insertQuery = "INSERT INTO sells(
                        name, price, product_id, sub_total, quantity, 
                        shop_by_date, discount, unique_id, usr_id, 
                        whole_sale_price, variation, variation_id
                    ) VALUES (
                        '" . mysqli_real_escape_string($con, $cartProduct[1]) . "',
                        $price, $productId, $subtotal, $quantity,
                        CURRENT_DATE, $perProductDiscount, '$transactionId', $userId,
                        $cartProduct[5], '$variation', $variationId
                    )";
                } else {
                    // Return/exchange
                    $price = $cartProduct[2] - $perProductDiscount;
                    $insertQuery = "INSERT INTO sells(
                        name, price, product_id, sub_total, quantity, 
                        shop_by_date, discount, unique_id, usr_id, 
                        whole_sale_price, variation, variation_id
                    ) VALUES (
                        '" . mysqli_real_escape_string($con, $cartProduct[1]) . "',
                        -$price, -$productId, $subtotal, $quantity,
                        CURRENT_DATE, $perProductDiscount, '$transactionId', $userId,
                        -$cartProduct[5], '$variation', $variationId
                    )";
                }
            } else {
                // Shop sale
                $shopData = sel_table("shops", "WHERE id = $shopId");
                $shop = mysqli_fetch_row($shopData);
                
                if (!$isReplacement) {
                    // Regular sale to shop
                    $price = $cartProduct[2] - $perProductDiscount;
                    $insertQuery = "INSERT INTO sells(
                        name, price, product_id, sub_total, quantity, 
                        shop_id, shop_name, shop_address, shop_phone, 
                        shop_borrow_statud, shop_by_date, discount, 
                        unique_id, usr_id, whole_sale_price, variation, variation_id
                    ) VALUES (
                        '" . mysqli_real_escape_string($con, $cartProduct[1]) . "',
                        $price, $productId, $subtotal, $quantity,
                        $shopId, '" . mysqli_real_escape_string($con, $shop[1]) . "', 
                        '" . mysqli_real_escape_string($con, $shop[2]) . "', 
                        '" . mysqli_real_escape_string($con, $shop[4]) . "',
                        $borrowStatus, CURRENT_DATE, $discount, 
                        '$transactionId', $userId, $cartProduct[5], '$variation', $variationId
                    )";
                } else {
                    // Return/exchange from shop
                    $price = $cartProduct[2] - $perProductDiscount;
                    $insertQuery = "INSERT INTO sells(
                        name, price, product_id, sub_total, quantity, 
                        shop_id, shop_name, shop_address, shop_phone, 
                        shop_borrow_statud, shop_by_date, discount, 
                        unique_id, usr_id, whole_sale_price, variation, variation_id
                    ) VALUES (
                        '" . mysqli_real_escape_string($con, $cartProduct[1]) . "',
                        -$price, -$productId, $subtotal, $quantity,
                        $shopId, '" . mysqli_real_escape_string($con, $shop[1]) . "', 
                        '" . mysqli_real_escape_string($con, $shop[2]) . "', 
                        '" . mysqli_real_escape_string($con, $shop[4]) . "',
                        $borrowStatus, CURRENT_DATE, $discount, 
                        '$transactionId', $userId, -$cartProduct[5], '$variation', $variationId
                    )";
                }
            }
            
            // Execute transaction insert
            if (mysqli_query($con, $insertQuery)) {
                // Update transaction ID in home_sells table
                $updateUnIdQuery = "UPDATE home_sells SET un_id = $transactionId";
                mysqli_query($con, $updateUnIdQuery);
                $_SESSION['home_liter'] = 1;
            } else {
                $_SESSION['home_liter'] = 0;
            }
        }
    }
    
    // Redirect to print receipt page
    echo "<script>window.location='print.php?discount=$discount'</script>";
} else {
    // This section is commented out in the original code
    // It appears to be legacy code for direct checkout without the form
    /*
    $subtotal = $_GET['subtotal'];
    $cart_products = sel_table("home_sells","");
    while($cart_procuct_row = mysqli_fetch_row($cart_products))
    {
        $products_min_quan = sel_table("products","where id = $cart_procuct_row[3]");
        $pro_up_query = "UPDATE products
    SET
    stock_quantity = stock_quantity - ($cart_procuct_row[4]) 
    where 
    id = $cart_procuct_row[3]";
        if(mysqli_query($con,$pro_up_query))
        {
            insert_tables("sells(name, price, product_id, sub_total,quantity)","('$cart_procuct_row[1]',$cart_procuct_row[2],$cart_procuct_row[3],$subtotal,$cart_procuct_row[4])");
            delete_table("home_sells","$cart_procuct_row[0]");
        }
    }
    echo "<script>window.location = 'home_page.php'</script>";
    */
}
?>