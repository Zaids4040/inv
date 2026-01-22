<?php
/**
 * Product List Management
 * 
 * This script displays a paginated list of products with options to update or delete.
 * It handles product deletion including associated images and provides search functionality.
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2024-01-20
 */

// Initialize session
session_start();

// Include required functions
require_once('functions.php');

// Handle product deletion
if (isset($_GET['did'])) {
    $id = filter_input(INPUT_GET, 'did', FILTER_VALIDATE_INT);
    
    if ($id) {
        // Delete associated product images first
        $imgData = sel_table('pro_img', 'WHERE productid = ' . $id);
        
        if ($imgData) {
            while ($delImg = mysqli_fetch_row($imgData)) {
                // Remove physical image file and then database record
                if (file_exists($delImg[1]) && unlink($delImg[1])) {
                    delete_table('pro_img', $delImg[0]);
                }
            }
        }
        
        // Delete the product record
        if (delete_table("products", $id)) {
            echo "<script>M.toast({html: 'Product deleted successfully', classes: 'rounded'})</script>";
        }
    }
}
?>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Product List</h4>
                        <p class="card-category">Your available products</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-primary">
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Per Product Price</th>
                                    <th>Stock / Quantity</th>
                                    
                                    <th>Quantity In Carton</th>
                                    <th>Carton Price</th>
                                    <th>Wholesale Price</th>
                                    <th>Variation</th>
                                    <th>Update</th>
                                    <th>Delete</th>
                                </thead>
                                <tbody id="searchtbody"></tbody>
                                <tbody>
                                    <?php
                                    // Pagination configuration
                                    $currentPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
                                    $rowsPerPage = 100;
                                    $startLimit = ($currentPage - 1) * $rowsPerPage;
                                    
                                    // Handle search functionality
                                    if (isset($_GET['searcha'])) {
                                        $search = filter_input(INPUT_GET, 'searcha', FILTER_SANITIZE_STRING);
                                        $searchTerm = mysqli_real_escape_string($con, $search);
                                        
                                        // Query for paginated results with search
                                        $productsDataFinal = sel_table(
                                            "products", 
                                            "WHERE product_name LIKE '%$searchTerm%' ORDER BY id DESC LIMIT $startLimit, $rowsPerPage"
                                        );
                                        
                                        // Query for total count with search
                                        $productsData = sel_table(
                                            'products', 
                                            "WHERE product_name LIKE '%$searchTerm%' ORDER BY id DESC"
                                        );
                                    } else {
                                        // Query for paginated results without search
                                        $productsDataFinal = sel_table(
                                            "products", 
                                            "ORDER BY id DESC LIMIT $startLimit, $rowsPerPage"
                                        );
                                        
                                        // Query for total count without search
                                        $productsData = sel_table('products', 'ORDER BY id DESC');
                                    }
                                    
                                    // Calculate pagination
                                    $numOfRecords = mysqli_num_rows($productsData);
                                    $pagesNum = ceil($numOfRecords / $rowsPerPage);
                                    
                                    // Display product rows
                                    while ($product = mysqli_fetch_row($productsDataFinal)) {
                                        // Calculate carton price if not set
                                        $cartonPrice = ($product[6] == "" || $product[6] == 0) 
                                            ? $product[2] * $product[5] 
                                            : $product[6];
                                    ?>
                                    <tr id="<?= htmlspecialchars($product[0]) ?>">
                                        <td><?= htmlspecialchars($product[0]) ?></td>
                                        <td>
                                            <center>
                                                <img src="<?= htmlspecialchars($product[11]) ?>" id="proimg" 
                                                     style="max-width:100%;height:100px;" alt="Product Image"/>
                                            </center>
                                        </td>
                                        <td><?= htmlspecialchars($product[1]) ?></td>
                                        <td><?= htmlspecialchars($product[2]) ?></td>
                                        <td><?= htmlspecialchars($product[3]) ?></td>
                                       
                                        <td><?= htmlspecialchars($product[5]) ?></td>
                                        <td><?= htmlspecialchars($cartonPrice) ?></td>
                                        <td><?= htmlspecialchars($product[10]) ?></td>
                                        <td><button class="btn btn-primary">ADD</button></td>
                                        <td>
                                            <a href="adminproduct_add.php?proup=<?= htmlspecialchars($product[0]) ?>" 
                                               class="btn btn-info">Update</a>
                                        </td>
                                        <td>
                                            <a onclick="deleteProduct(<?= htmlspecialchars($product[0]) ?>)" 
                                               class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                            
                            <!-- Pagination controls -->
                            <div class="pagination-container">
                                <?php
                                for ($i = 1; $i <= $pagesNum; $i++) {
                                    echo "<a onclick='loadPage($i)' class='btn btn-info" . 
                                         ($i == $currentPage ? " active" : "") . "'>$i</a>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Load a specific page of products
 * 
 * @param {number} page - The page number to load
 */
function loadPage(page) {
    $.ajax({
        url: 'productlist.php?page=' + page,
        cache: false,
        success: function() {
            $('#productlistdv').load("productlist.php?page=" + page);
        }
    });
}

/**
 * Delete a product after confirmation
 * 
 * @param {number} id - The product ID to delete
 */
function deleteProduct(id) {
    const confirmation = confirm("Are you sure you want to delete this product?");
    
    if (confirmation) {
        $.ajax({
            url: 'productlist.php?did=' + id,
            cache: false,
            success: function() {
                // Reload current page after deletion
                $('#productlistdv').load("productlist.php?page=" + 
                    <?= isset($_GET['page']) ? filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) : 1 ?>);
            }
        });
    }
}
</script>