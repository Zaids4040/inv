<?php
/**
 * Product Image Management
 * 
 * This script handles product image operations including deletion and replacement.
 * It supports removing images from both database and filesystem, updating product
 * references, and uploading replacement images.
 * 
 * @version 2.0
 * @author Pro Developer
 * @date 2023-12-30
 */

// Include required functions
require_once('functions.php');

// Handle image deletion request
if (isset($_POST['updelbtn'])) {
    // Sanitize input parameters
    $imageId = filter_input(INPUT_POST, 'imgid', FILTER_SANITIZE_NUMBER_INT);
    $productId = filter_input(INPUT_POST, 'proid', FILTER_SANITIZE_NUMBER_INT);
    $oldImagePath = filter_input(INPUT_POST, 'oldimage', FILTER_SANITIZE_STRING);
    
    // Delete image record from database
    if (delete_table('pro_img', $imageId)) {
        // Remove the physical file from filesystem
        if (file_exists($oldImagePath) && unlink($oldImagePath)) {
            // Find another image to set as the product's main image
            $query = "SELECT url FROM pro_img WHERE productid = ? LIMIT 1";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, 'i', $productId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            // If another image exists, update the product's main image reference
            if ($data = mysqli_fetch_row($result)) {
                // Log the new image URL for debugging
                error_log("Setting new main image URL: " . $data[0]);
                
                // Update the product's main image URL
                $updateQuery = "UPDATE products SET image_url = ? WHERE id = ?";
                $updateStmt = mysqli_prepare($con, $updateQuery);
                mysqli_stmt_bind_param($updateStmt, 'si', $data[0], $productId);
                
                if (!mysqli_stmt_execute($updateStmt)) {
                    error_log("Failed to update product image reference: " . mysqli_error($con));
                }
                
                mysqli_stmt_close($updateStmt);
            } else {
                // No images left, could set a default placeholder
                error_log("No images remaining for product ID: " . $productId);
            }
            
            mysqli_stmt_close($stmt);
        } else {
            error_log("Failed to delete image file: " . $oldImagePath);
        }
    } else {
        error_log("Failed to delete image record from database");
    }
}

// Handle image replacement/upload
if (isset($_FILES['replacebtn']) && !empty($_FILES['replacebtn']['tmp_name'])) {
    $oldImagePath = filter_input(INPUT_POST, 'oldimage', FILTER_SANITIZE_STRING);
    
    // Validate file type (optional enhancement)
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($_FILES['replacebtn']['type'], $allowedTypes)) {
        // Move the uploaded file to replace the old one
        if (!move_uploaded_file($_FILES['replacebtn']['tmp_name'], $oldImagePath)) {
            error_log("Failed to move uploaded file to: " . $oldImagePath);
        }
    } else {
        error_log("Invalid file type uploaded: " . $_FILES['replacebtn']['type']);
    }
}

// Get product ID for redirect
$productId = filter_input(INPUT_POST, 'proid', FILTER_SANITIZE_NUMBER_INT);
?>
<script>
    // Redirect back to product edit page
    window.location = "/ecom/adminproduct_add.php?proup=<?php echo $productId; ?>";
</script>