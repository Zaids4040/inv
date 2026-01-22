<?php
/**
 * Product Management Script
 * 
 * This script handles CRUD operations for product data:
 * - Create: Insert new product records with images
 * - Read: Retrieve product information
 * - Update: Modify existing product records
 * - Delete: Not implemented in this file
 * 
 * @version 3.0
 * @author Pro Developer
 * @date 2024-01-15
 */

// Initialize session
session_start();

// Include required functions
require_once('functions.php');

// Process form submission for product operations
if (isset($_POST['probtn'])) {
    // Sanitize and validate input data
    $name = filter_input(INPUT_POST, 'protxt', FILTER_SANITIZE_STRING);
    $per_price = filter_input(INPUT_POST, 'propritxt', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $stock_quantity = filter_input(INPUT_POST, 'stocktxt', FILTER_SANITIZE_NUMBER_INT);
    $liters = filter_input(INPUT_POST, 'litertxt', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $quantity_in_carton = filter_input(INPUT_POST, 'quanctxt', FILTER_SANITIZE_NUMBER_INT);
    $whole_sale_price = filter_input(INPUT_POST, 'wholesalepricetxt', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $qrcode = filter_input(INPUT_POST, 'qrcode', FILTER_SANITIZE_STRING);
    $categoryid = filter_input(INPUT_POST, 'catDD', FILTER_SANITIZE_NUMBER_INT);
    
    // Calculate carton price and product discount based on input combinations
    $carton_price = "";
    $pro_discount = "";
    
    $carton_price_input = filter_input(INPUT_POST, 'carpricetxt', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $pro_discount_input = filter_input(INPUT_POST, 'pro_dis_txt', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    
    // Logic for calculating carton price and product discount
    if (empty($pro_discount_input) && !empty($carton_price_input)) {
        // Calculate discount per item from carton price
        $pro_discount = $carton_price_input / $quantity_in_carton;
        $carton_price = $carton_price_input;
    } elseif (!empty($pro_discount_input) && empty($carton_price_input)) {
        // Calculate carton price from per-item discount
        $carton_price = $pro_discount_input * $quantity_in_carton;
        $pro_discount = $pro_discount_input;
    } elseif (empty($pro_discount_input) && empty($carton_price_input)) {
        // Both empty, leave as empty strings
        $carton_price = "";
        $pro_discount = "";
    } elseif (!empty($pro_discount_input) && !empty($carton_price_input)) {
        // Both provided, prioritize carton price
        $pro_discount = $carton_price_input / $quantity_in_carton;
        $carton_price = $carton_price_input;
    }
    
    // Handle carton stock checkbox
    $carton_stock = 0;
    if (isset($_POST['cartonquanch']) && $_POST['cartonquanch'] == 'on') {
        $carton_stock = 1;
        $stock_quantity = $stock_quantity * $quantity_in_carton;
    }
    
    // Check if this is a new product or an update
    if (!isset($_GET['proup'])) {
        // INSERT NEW PRODUCT
        
        // Insert product data into database
        $proinsert = insert_tables(
            "products(product_name, per_product_price, stock_quantity, bottle_liters, quantiity_in_cartan, cartan_price, invent_date, updated_date, carton_stock, whole_sale_price, qr_code, product_discount, categoryid)",
            "('$name', '$per_price', $stock_quantity, $liters, $quantity_in_carton, '$carton_price', CURRENT_DATE, CURRENT_DATE, $carton_stock, $whole_sale_price, '$qrcode', '$pro_discount', $categoryid)"
        );
        
        // Get the ID of the newly inserted product
        $sel_query = sel_table('products', 'ORDER BY id DESC LIMIT 1');
        $lastid = mysqli_fetch_row($sel_query);
        
        // Handle image uploads if any
        if (isset($_FILES['imageselector']) && !empty($_FILES['imageselector']['name'][0])) {
            $filename = [];
            
            // Process each uploaded image
            for ($i = 0; $i < count($_FILES['imageselector']['name']); $i++) {
                $filename[] = basename($_FILES['imageselector']['name'][$i]);
                $uploadfile = $_FILES['imageselector']['tmp_name'][$i];
                $targetpath = "images/" . time() . $filename[$i];
                
                // Move uploaded file to target directory
                if (move_uploaded_file($uploadfile, $targetpath)) {
                    // Set the first image as the product's main image
                    if ($i == 0) {
                        $updatequery = "UPDATE products SET image_url = ? WHERE id = ?";
                        $stmt = mysqli_prepare($con, $updatequery);
                        mysqli_stmt_bind_param($stmt, 'si', $targetpath, $lastid[0]);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                    }
                    
                    // Insert image record into product images table
                    $queryinsert = "INSERT INTO pro_img(url, productid) VALUES(?, ?)";
                    $stmt = mysqli_prepare($con, $queryinsert);
                    mysqli_stmt_bind_param($stmt, 'si', $targetpath, $lastid[0]);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                }
            }
        }
        
        // Redirect based on operation result
        if ($proinsert) {
            $_SESSION['proinsert'] = 1;
            header("Location: adminproduct_add.php");
            exit;
        } else {
            $_SESSION['proinsert'] = 0;
            header("Location: adminproduct_add.php");
            exit;
        }
    } else {
        // UPDATE EXISTING PRODUCT
        $pro_update_id = filter_input(INPUT_GET, 'proup', FILTER_SANITIZE_NUMBER_INT);
        $invent_date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);
        
        // Get current product data
        $prost = sel_table("products", "where id = $pro_update_id");
        $prostdata = mysqli_fetch_row($prost);
        
        // Calculate inventory values
        $preqamo = $prostdata[3] * $prostdata[10]; // Current quantity * price
        $newqt = $stock_quantity;
        $newamo = $newqt * $whole_sale_price;
        $finalproamo = $whole_sale_price;
        
        // Handle inventory calculation options
        if (isset($_POST['toggle'])) {
            $selectedOption = $_POST['toggle'];
            if ($selectedOption == 'plainEdit') {
                // Simple edit - use new values directly
                $finalproamo = $whole_sale_price;
            } elseif ($selectedOption == 'inventoryCalculate') {
                // Calculate weighted average price
                $totamo = $preqamo + $newamo;
                $finalproamo = $totamo / ($stock_quantity + $prostdata[3]);
                $stock_quantity = ($stock_quantity + $prostdata[3]);
            }
        }
        
        // Update product without changing image
        $update_pro_wo_img = update_product_w_o_img(
            $pro_update_id, 
            $name, 
            $per_price, 
            $stock_quantity, 
            $liters, 
            $quantity_in_carton, 
            $carton_price, 
            $invent_date, 
            $carton_stock, 
            $finalproamo, 
            $qrcode, 
            $pro_discount
        );
        
        // Handle image update if a new image was uploaded
        if (isset($_FILES['imageselector']) && !empty($_FILES['imageselector']['name'][0])) {
            // Get current image path
            $img_delete = sel_table("products", "p where p.id=$pro_update_id");
            $img_del_row = mysqli_fetch_row($img_delete);
            
            // Delete old image file if it exists
            if (!empty($img_del_row[11]) && file_exists($img_del_row[11])) {
                unlink($img_del_row[11]);
            }
            
            // Process new image upload
            $filename = basename($_FILES['imageselector']['name'][0]);
            $uploadfile = $_FILES['imageselector']['tmp_name'][0];
            $targetpath = "images/" . time() . $filename;
            
            if (move_uploaded_file($uploadfile, $targetpath)) {
                // Update product with new image
                $update_pro_w_img = update_product(
                    $pro_update_id, 
                    $name, 
                    $per_price, 
                    $stock_quantity, 
                    $liters, 
                    $quantity_in_carton, 
                    $carton_price, 
                    $invent_date, 
                    $carton_stock, 
                    $finalproamo, 
                    $targetpath, 
                    $qrcode, 
                    $pro_discount
                );
            }
        }
        
        // Set session message and redirect
        $_SESSION['proupdate'] = $update_pro_wo_img ? 1 : 0;
        
        // Redirect based on where the request came from
        if (isset($_GET['dash'])) {
            header("Location: AdminPanel.php");
        } else {
            header("Location: AdminPanel.php?prolist=active");
        }
        exit;
    }
}
?>