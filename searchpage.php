<?php
/**
 * Search Page - Handles search functionality for users, products, and sales
 * 
 * This script processes search queries and displays matching results from the database
 * based on the search parameters provided in the URL.
 */

// Initialize session
session_start();

// Include helper functions
require_once("functions.php");

// Only process search if query is at least 3 characters
if (strlen($_GET['text'] ?? '') > 2) {
    // User search functionality
    if (isset($_GET['usrlis'])) {
        if (isset($_GET['text'])) {
            $name = htmlspecialchars($_GET['text'], ENT_QUOTES, 'UTF-8');
            
            // Prepare query based on search term
            if (strtolower($name) === 'active') {
                // Special case for 'active' search
                $query = "SELECT * FROM users_table WHERE 
                    username LIKE ? OR 
                    email LIKE ? OR 
                    phone LIKE ? OR 
                    salary LIKE ? OR 
                    salary_date LIKE ? OR 
                    status = 1 OR 
                    address LIKE ?";
                $searchParam = "%$name%";
                
                // Use prepared statement
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt, "ssssss", 
                    $searchParam, $searchParam, $searchParam, 
                    $searchParam, $searchParam, $searchParam);
            } else {
                // Regular search
                $query = "SELECT * FROM users_table WHERE 
                    username LIKE ? OR 
                    email LIKE ? OR 
                    phone LIKE ? OR 
                    salary LIKE ? OR 
                    salary_date LIKE ? OR 
                    status LIKE ? OR 
                    address LIKE ?";
                $searchParam = "%$name%";
                
                // Use prepared statement
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt, "sssssss", 
                    $searchParam, $searchParam, $searchParam, 
                    $searchParam, $searchParam, $searchParam, $searchParam);
            }
            
            // Execute query
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            // Display results if any found
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_row($result)) {
                    // Determine user status
                    $status = ($row[9] == 1) ? 'Active' : 'In-Active';
                    
                    // Output user data row
                    echo "<tr>
                        <td>{$row[0]}</td>
                        <td>{$row[1]}</td>
                        <td>{$row[2]}</td>
                        <td>{$row[3]}</td>
                        <td>{$row[4]}</td>
                        <td>{$row[5]}</td>
                        <td>{$row[6]}</td>
                        <td>{$row[7]}</td>
                        <td><a href='usersactive.php?id={$row[0]}' class='btn btn-info'>{$status}</a></td>
                        <td><a href='AdminPanel.php?users=active&usrup={$row[0]}' class='btn btn-info'>Update</a></td>
                        <td>";
                    ?> 
                      <a class="btn btn-danger" href="AdminPanel.php?usrlist=active&usrdelid=<?php echo $row[0]; ?>">Delete Permanent</a>
                    <?php
                    echo "</td></tr>";
                }
            }
            
            // Close statement
            mysqli_stmt_close($stmt);
        }
    } 
    // Product search functionality
    elseif (isset($_GET['prolis'])) {
        if (isset($_GET['text'])) {
            $text = htmlspecialchars($_GET['text'], ENT_QUOTES, 'UTF-8');
            
            // Use the helper function to query products
            $query = sel_table("products", "WHERE 
                product_name LIKE '%$text%' OR 
                per_product_price LIKE '%$text%' OR 
                stock_quantity LIKE '%$text%' OR 
                quantiity_in_cartan LIKE '%$text%' OR 
                cartan_price LIKE '%$text%'");
            
            // Display results if any found
            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_row($query)) {
                    // Calculate price
                    $price = (empty($row[6]) || $row[6] == 0) ? $row[2] * $row[5] : $row[6];
                    
                    // Output product data row
                    echo "
                    <tr>
                        <td>{$row[0]}</td>
                        <td><center><img src='{$row[11]}' id='proimg' style='max-width:100%;height:100px;'/></center></td>
                        <td>{$row[1]}</td>
                        <td>{$row[2]}</td>
                        <td>{$row[3]}</td>
                        <td>{$row[4]}</td>
                        <td>{$row[5]}</td>
                        <td>{$price}</td>
                        <td>{$row[10]}</td>
                        <td><a href='AdminPanel.php?products=active&proup={$row[0]}' class='btn btn-info'>Update</a></td>
                        <td>";
                    
                    echo "
                     <a class='btn btn-danger' href='AdminPanel.php?prolist=active&prodelid={$row[0]}&image={$row[11]}'>Delete Permanent</a>
                    ";
                    echo "</td></tr>";
                }
            }
        }
    } 
    // Sales search functionality
    elseif (isset($_GET['sells'])) {
        if (isset($_GET['text'])) {
            $text = htmlspecialchars($_GET['text'], ENT_QUOTES, 'UTF-8');
            
            // Use the helper function to query sales
            $query = sel_table("sells", "WHERE 
                name LIKE '%$text%' OR 
                price LIKE '%$text%' OR 
                sub_total LIKE '%$text%' OR 
                quantity LIKE '%$text%' OR 
                product_id LIKE '%$text%' OR 
                shop_id LIKE '%$text%' OR 
                shop_name LIKE '%$text%' OR 
                shop_address LIKE '%$text%' OR 
                shop_phone LIKE '%$text%' OR 
                shop_borrow_statud LIKE '%$text%' OR 
                shop_by_date LIKE '%$text%' OR 
                discount LIKE '%$text%' OR 
                unique_id LIKE '%$text%' OR 
                usr_id LIKE '%$text%' OR 
                whole_sale_price LIKE '%$text%' OR 
                id LIKE '%$text%'");
            
            // Display results if any found
            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_row($query)) {
                    // Output sales data row
                    echo "    
                    <tr>
                        <td>{$row[0]}</td>
                        <td>{$row[1]}</td>
                        <td>{$row[2]}</td>
                        <td>{$row[3]}</td>
                        <td>{$row[5]}</td>
                        <td>{$row[6]}</td>
                        <td>{$row[7]}</td>
                        <td>{$row[8]}</td>
                        <td>{$row[9]}</td>
                        <td>{$row[10]}</td>
                        <td>{$row[11]}</td>
                        <td>{$row[12]}</td>
                        <td>{$row[14]}</td>
                        <td>
                            <a class='btn btn-danger' href='sells_delete.php?delid={$row[0]}'>Delete Permanent</a>
                        </td>
                    </tr>
                    ";
                }
            }
        }
    }
}
?>

<script>
    /**
     * Shows confirmation buttons for delete operation
     */
    function deletemove() {
        document.getElementById('yesnobtn').style.display = 'block';
        document.getElementById('deletebtn').style.display = 'none';
    }
</script>