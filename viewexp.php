<?php
/**
 * Expense Management View
 * 
 * This script displays and manages expense categories with pagination and search functionality.
 * It allows users to view and delete expense categories.
 */

// Initialize session
session_start();

// Include helper functions
require_once("functions.php");

// Handle expense deletion
if (isset($_GET['did']) && !empty($_GET['did'])) {
    $id = filter_input(INPUT_GET, 'did', FILTER_SANITIZE_NUMBER_INT);
    if (delete_table("expence_names", $id)) {
        echo "<script>M.toast({html: 'Data Deleted Successfully'})</script>";
    }
}

// Pagination setup
$page_f = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) ?? 1;
$page_data_quantity = 5; // Items per page
$starting_limit = ($page_f - 1) * $page_data_quantity;

// Handle search functionality
if (isset($_GET['serch']) && !empty($_GET['serch'])) {
    $search = filter_input(INPUT_GET, 'serch', FILTER_SANITIZE_SPECIAL_CHARS);
    // Get paginated results for current page
    $sellect_exp_final = sel_table(
        "expence_names", 
        "WHERE name LIKE '%$search%' ORDER BY id DESC LIMIT $starting_limit, $page_data_quantity"
    );
    // Get all results for pagination calculation
    $sellect_exp = sel_table("expence_names", "WHERE name LIKE '%$search%'");
} else {
    // No search term - get all records with pagination
    $sellect_exp_final = sel_table(
        "expence_names", 
        "ORDER BY id DESC LIMIT $starting_limit, $page_data_quantity"
    );
    $sellect_exp = sel_table("expence_names", "");
}

// Calculate total pages
$total_records = mysqli_num_rows($sellect_exp);
$pages = ceil($total_records / $page_data_quantity);
?>

<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title">Expense Categories</h4>
        <p class="card-category">Manage your expense categories</p>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total_records > 0): ?>
                        <?php while ($exp_row = mysqli_fetch_row($sellect_exp_final)): ?>
                            <tr>
                                <td><?= htmlspecialchars($exp_row[0]) ?></td>
                                <td><?= htmlspecialchars($exp_row[1]) ?></td>
                                <td>
                                    <a onclick="delexp(<?= $exp_row[0] ?>)" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No expense categories found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <!-- Pagination controls -->
            <div class="pagination-container">
                <?php for ($page = 1; $page <= $pages; $page++): ?>
                    <a id="vexbtn" 
                       onclick="vpageination(<?= $page ?>)" 
                       class="btn btn-info <?= ($page == $page_f) ? 'active' : '' ?>">
                        <?= $page ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>
    </div>

<script>
    /**
     * Handle pagination for expense categories
     * @param {number} page - The page number to load
     */
    function vpageination(page) {
        $.ajax({
            url: 'viewexp.php?page=' + page,
            cache: false,
            success: function() {
                $('#vexpence').load("viewexp.php?page=" + page);
            },
            error: function(xhr, status, error) {
                console.error("Pagination error: " + error);
            }
        });
    }
    
    /**
     * Handle expense category deletion with confirmation
     * @param {number} id - The ID of the expense category to delete
     */
    function delexp(id) {
        const confirmDelete = confirm("Are you sure you want to delete this expense category?");
        if (confirmDelete) {
            $.ajax({
                url: 'viewexp.php?did=' + id,
                cache: false,
                success: function() {
                    $('#vexpence').load("viewexp.php?page=" + <?= $page_f ?>);
                },
                error: function(xhr, status, error) {
                    console.error("Deletion error: " + error);
                }
            });
        }
    }
</script>