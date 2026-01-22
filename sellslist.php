<?php
/**
 * Sales List Display Script
 * 
 * This script displays a paginated list of sales records with filtering options.
 * It handles search functionality by product name, shop name, borrow status, and date.
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2024-05-20
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");
?>
<div class="card">
    <div class="card-header card-header-primary">
        <h4 class="card-title">Sales Details</h4>
        <p class="card-category">Your Completed Sales</p>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead class="text-primary">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Shop ID</th>
                    <th>Shop Name</th>
                    <th>Shop Address</th>
                    <th>Shop Phone</th>
                    <th>Shop Borrow Status</th>
                    <th>Date of Sale</th>
                    <th>Discount</th>
                    <th>User ID</th>
                </thead>
                <tbody id="searchtbody"></tbody>
                <tbody>
                    <?php
                    // Pagination configuration
                    $currentPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
                    $recordsPerPage = 10;
                    $offset = ($currentPage - 1) * $recordsPerPage;
                    
                    // Initialize query variables
                    $whereClause = "";
                    $orderClause = "ORDER BY id DESC";
                    $limitClause = "LIMIT $offset, $recordsPerPage";
                    
                    // Handle different search filters
                    if (isset($_GET['searchn'])) {
                        // Search by product name
                        $search = mysqli_real_escape_string($con, $_GET['searchn']);
                        $whereClause = "WHERE name LIKE '%$search%'";
                    } elseif (isset($_GET['searchsn'])) {
                        // Search by shop name
                        $search = mysqli_real_escape_string($con, $_GET['searchsn']);
                        $whereClause = "WHERE shop_name LIKE '%$search%'";
                    } elseif (isset($_GET['searchdd'])) {
                        // Search by borrow status
                        $searchValue = filter_input(INPUT_GET, 'searchdd', FILTER_VALIDATE_INT);
                        
                        if ($searchValue === 0) {
                            // Show only records with null borrow status
                            $whereClause = "WHERE shop_borrow_statud IS NULL";
                        } elseif ($searchValue === -1) {
                            // Show all records (no filter)
                            $whereClause = "";
                        } else {
                            // Filter by specific borrow status
                            $search = mysqli_real_escape_string($con, $_GET['searchdd']);
                            $whereClause = "WHERE shop_borrow_statud LIKE '%$search%'";
                        }
                    } elseif (isset($_GET['searchd'])) {
                        // Search by date
                        $search = mysqli_real_escape_string($con, $_GET['searchd']);
                        $whereClause = "WHERE shop_by_date LIKE '%$search%'";
                    }
                    
                    // Fetch paginated sales records
                    $salesQuery = "$whereClause $orderClause $limitClause";
                    $salesRecords = sel_table("sells", $salesQuery);
                    
                    // Count total records for pagination
                    $totalRecordsQuery = sel_table("sells", $whereClause);
                    $totalRecords = mysqli_num_rows($totalRecordsQuery);
                    $totalPages = ceil($totalRecords / $recordsPerPage);
                    
                    // Display sales records
                    while ($sale = mysqli_fetch_row($salesRecords)) {
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($sale[0]) ?></td>
                            <td><?= htmlspecialchars($sale[1]) ?></td>
                            <td><?= htmlspecialchars($sale[2]) ?></td>
                            <td><?= htmlspecialchars($sale[3]) ?></td>
                            <td><?= htmlspecialchars($sale[5]) ?></td>
                            <td><?= htmlspecialchars($sale[6]) ?></td>
                            <td><?= htmlspecialchars($sale[7]) ?></td>
                            <td><?= htmlspecialchars($sale[8]) ?></td>
                            <td><?= htmlspecialchars($sale[9]) ?></td>
                            <td>
                                <?php if ($sale[10] == 1): ?>
                                    <?php if (!isset($_GET['clearsid'])): ?>
                                        <a href="AdminPanel.php?sells=active&clearsid=<?= $sale[0] ?>" class="btn btn-info">Clear</a>
                                    <?php else: ?>
                                        <?php if ($_GET['clearsid'] == $sale[0]): ?>
                                            <a href="clearborrow.php?id=<?= $sale[0] ?>&sel=active" class="btn btn-danger">YES</a>
                                            <a href="AdminPanel.php?sells=active" class="btn btn-info">NO</a>
                                        <?php else: ?>
                                            <a href="AdminPanel.php?sells=active&clearsid=<?= $sale[0] ?>" class="btn btn-info">Clear</a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-success">Done</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($sale[11]) ?></td>
                            <td><?= htmlspecialchars($sale[12]) ?></td>
                            <td><?= htmlspecialchars($sale[14]) ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            
            <!-- Pagination controls -->
            <div class="pagination-container">
                <?php
                for ($page = 1; $page <= $totalPages; $page++) {
                    $activeClass = ($page == $currentPage) ? 'btn-primary' : 'btn-info';
                    echo "<a onclick='loadPage($page)' class='btn $activeClass m-1'>$page</a>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Load a specific page of sales records via AJAX
 * 
 * @param {number} page - The page number to load
 */
function loadPage(page) {
    $.ajax({
        url: 'sellslist.php?page=' + page,
        cache: false,
        success: function() {
            $('#sellslistdv').load("sellslist.php?page=" + page);
        }
    });
}

/**
 * Delete a sales record after confirmation
 * 
 * @param {number} id - The ID of the sales record to delete
 */
function delsells(id) {
    const confirmation = confirm("Are you sure you want to delete this sales record?");
    
    if (confirmation) {
        $.ajax({
            url: 'sells_delete.php?delid=' + id,
            cache: false,
            success: function() {
                const currentPage = <?= $currentPage ?>;
                $('#sellslistdv').load("sellslist.php?page=" + currentPage);
                M.toast({html: 'Sales record deleted successfully', classes: 'rounded'});
            }
        });
    }
}
</script>