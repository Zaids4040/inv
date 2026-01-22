<?php
/**
 * Category List Manager
 * 
 * This script displays a paginated list of product categories with search functionality.
 * It also handles category deletion operations through AJAX requests.
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2023-10-15
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Process category deletion
if (isset($_GET['did'])) {
    $id = filter_input(INPUT_GET, 'did', FILTER_VALIDATE_INT);
    if ($id && delete_table("category", $id)) {
        echo "<script>M.toast({html: 'Category Deleted Successfully', classes: 'rounded'})</script>";
    }
}

// Pagination setup
$page_number = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$items_per_page = 10;
$offset = ($page_number - 1) * $items_per_page;

// Handle search functionality
$search_query = '';
if (isset($_GET['serch']) || isset($_GET['serchdd'])) {
    $search_term = filter_input(INPUT_GET, 'serch', FILTER_SANITIZE_STRING) ?: '';
    $search_dropdown = filter_input(INPUT_GET, 'serchdd', FILTER_SANITIZE_STRING) ?: '';
    
    // Build search condition
    $search_query = "WHERE name LIKE '%{$search_term}%'";
    
    // Get paginated results
    $categories_paginated = sel_table(
        "category", 
        "{$search_query} ORDER BY id DESC LIMIT {$offset},{$items_per_page}"
    );
    
    // Get total count for pagination
    $all_categories = sel_table("category", $search_query);
} else {
    // No search applied
    $categories_paginated = sel_table(
        "category", 
        "ORDER BY id DESC LIMIT {$offset},{$items_per_page}"
    );
    
    // Get total count for pagination
    $all_categories = sel_table("category", "");
}

// Calculate total pages
$total_records = mysqli_num_rows($all_categories);
$total_pages = ceil($total_records / $items_per_page);
?>

<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title">Category Details</h4>
        <p class="card-category">Your Completed Categories</p>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead class="text-primary">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display category data
                    if ($categories_paginated && mysqli_num_rows($categories_paginated) > 0) {
                        while ($category = mysqli_fetch_row($categories_paginated)) {
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($category[0], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($category[1], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm" onclick="delexp(<?= $category[0] ?>)">
                                        <i class="material-icons">delete</i> Delete
                                    </button>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="3" class="text-center">No categories found</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            
            <!-- Pagination controls -->
            <div class="pagination-container">
                <?php
                for ($page = 1; $page <= $total_pages; $page++) {
                    $active_class = ($page == $page_number) ? 'btn-primary' : 'btn-info';
                    echo "<a id='vexbtn' onclick='vpageination($page)' class='btn $active_class'>$page</a>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Handles pagination via AJAX
     * 
     * @param {number} page - The page number to load
     */
    function vpageination(page) {
        $.ajax({
            url: 'categorylist.php?page=' + page,
            cache: false,
            success: function() {
                $('#categorylistdiv').load("categorylist.php?page=" + page);
            }
        });
    }
    
    /**
     * Handles category deletion with confirmation
     * 
     * @param {number} id - The category ID to delete
     */
    function delexp(id) {
        const confirmDelete = confirm("Are you sure you want to delete this category?");
        if (confirmDelete) {
            $.ajax({
                url: 'categorylist.php?did=' + id,
                cache: false,
                success: function() {
                    $('#categorylistdiv').load("categorylist.php?page=" + <?= $page_number ?>);
                }
            });
        }
    }
</script>
