<?php
/**
 * Expense List Management
 * 
 * This script handles displaying, searching, and deleting expense records
 * with pagination functionality.
 * 
 * @author Pro Developer
 * @version 2.0
 */

// Initialize session and include required functions
session_start();
require_once("functions.php");

// Handle expense deletion
if (isset($_GET['did'])) {
    $id = filter_input(INPUT_GET, 'did', FILTER_SANITIZE_NUMBER_INT);
    if (delete_table("expence", $id)) {
        echo "<script>M.toast({html: 'Data Deleted Successfully'})</script>";
    }
}

// Pagination setup
$page_f = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) ?? 1;
$page_data_quantity = 10;
$starting_limit = ($page_f - 1) * $page_data_quantity;

// Handle search functionality
$search_query = "";
if (isset($_POST['search']) || isset($_POST['searchdate'])) {
    $search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING) ?? "";
    $search_date = filter_input(INPUT_POST, 'searchdate', FILTER_SANITIZE_STRING) ?? "";
    // Build search query
    if(!isset($_POST['searchdate']) || $_POST['searchdate'] == '')
    {
        $search_query = "WHERE name LIKE '%$search%'";
    }
    else
    {
        $search_query = "WHERE name LIKE '%$search%' AND date_exp = '$search_date'";
    }
    
    
    // Get paginated results
    $sellect_exp_final = sel_table(
        "expence", 
        "$search_query ORDER BY id DESC LIMIT $starting_limit,$page_data_quantity"
    );
    
    // Get total count for pagination
    $sellect_exp = sel_table("expence", $search_query);
} else {
    // No search parameters - get all records with pagination
    $sellect_exp_final = sel_table(
        "expence", 
        "ORDER BY id DESC LIMIT $starting_limit,$page_data_quantity"
    );
    
    // Get total count for pagination
    $sellect_exp = sel_table("expence", "");
}
// Calculate total pages
$total_records = mysqli_num_rows($sellect_exp);
$pages = ceil($total_records / $page_data_quantity);
?>

<!-- Expense List Card -->
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title">Expense Details</h4>
        <p class="card-category">Your Completed Expenses</p>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Note</th>
                        <th>Date</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display expense records
                    while ($exp_row = mysqli_fetch_row($sellect_exp_final)) {
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($exp_row[0]) ?></td>
                            <td><?= htmlspecialchars($exp_row[1]) ?></td>
                            <td><?= htmlspecialchars($exp_row[2]) ?></td>
                            <td><?= htmlspecialchars($exp_row[3]) ?></td>
                            <td><?= htmlspecialchars($exp_row[4]) ?></td>
                            <td>
                                <a href="javascript:void(0)" 
                                   onClick="delexp(<?= $exp_row[0] ?>)" 
                                   class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            
            <!-- Pagination Controls -->
            <div class="pagination-container">
                <?php
                for ($page = 1; $page <= $pages; $page++) {
                    $active_class = ($page == $page_f) ? 'active' : '';
                    echo "<a id='vexbtn' onClick='vpageination($page)' class='btn btn-info $active_class'>$page</a>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Handle pagination functionality via AJAX
     * 
     * @param {number} page - The page number to load
     */
    function vpageination(page) {
        $.ajax({
            url: 'explist.php?page=' + page,
            cache: false,
            success: function() {
                $('#explistdv').load("explist.php?page=" + page);
            }
        });
    }
    
    /**
     * Handle expense deletion with confirmation
     * 
     * @param {number} id - The expense ID to delete
     */
    function delexp(id) {
        const confex = confirm("Are you sure you want to delete this expense?");
        if (confex) {
            $.ajax({
                url: 'explist.php?did=' + id,
                cache: false,
                success: function() {
                    $('#explistdv').load("explist.php?page=<?= $page_f ?>");
                }
            });
        }
    }
</script>
