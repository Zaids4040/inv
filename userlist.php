<?php
/**
 * User List Management
 * 
 * This file handles displaying and managing users in a tabular format
 * with pagination and search functionality.
 * 
 * @version 2.0
 * @author Pro Developer
 */

// Initialize session and include required functions
session_start();
require_once("functions.php");

// Handle user deletion
if (isset($_GET['did']) && !empty($_GET['did'])) {
    $id = filter_input(INPUT_GET, 'did', FILTER_SANITIZE_NUMBER_INT);
    if (delete_table("users_table", $id)) {
        echo "<script>M.toast({html: 'Data Deleted Successfully', classes: 'rounded'})</script>";
    }
}
?>
<div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title">Users Table</h4>
                  <p class="card-category">Your Available Users</p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class="text-primary">
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Password</th>
                        <th>Salary</th>
                        <th>Salary Date</th>
                        <th>Status</th>
                        <th>Update</th>
                        <th>Delete</th>
                      </thead>
                      <tbody id="searchtbody"></tbody>
                      <tbody>
						<?php
						// Pagination setup
						$page_f = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) ?? 1;
						$page_data_quantity = 10;
						$starting_limit = ($page_f - 1) * $page_data_quantity;
						
						// Handle different search scenarios
						if (isset($_GET['searchn'])) {
							$search = htmlspecialchars($_GET['searchn'], ENT_QUOTES, 'UTF-8');
							$users_data_n = sel_table("users_table", "WHERE username LIKE '%$search%'");
							$users_data = sel_table("users_table", "WHERE username LIKE '%$search%' ORDER BY id DESC LIMIT $starting_limit, $page_data_quantity");	
						} elseif (isset($_GET['searche'])) {
							$search = htmlspecialchars($_GET['searche'], ENT_QUOTES, 'UTF-8');
							$users_data_n = sel_table("users_table", "WHERE email LIKE '%$search%'");
							$users_data = sel_table("users_table", "WHERE email LIKE '%$search%' ORDER BY id DESC LIMIT $starting_limit, $page_data_quantity");	
						} elseif (isset($_GET['searcha'])) {
							$search = htmlspecialchars($_GET['searcha'], ENT_QUOTES, 'UTF-8');
							$users_data_n = sel_table("users_table", "WHERE address LIKE '%$search%'");
							$users_data = sel_table("users_table", "WHERE address LIKE '%$search%' ORDER BY id DESC LIMIT $starting_limit, $page_data_quantity");	
						} else {
							// Default: no search criteria
							$users_data_n = sel_table('users_table', '');
							$users_data = sel_table("users_table", "ORDER BY id DESC LIMIT $starting_limit, $page_data_quantity");	
						}
						
						// Calculate total pages for pagination
						$total_records = mysqli_num_rows($users_data_n);
						$pages = ceil($total_records / $page_data_quantity);
						
						// Display user data rows
						while ($user = mysqli_fetch_row($users_data)) {
							$id = (int)$user[0];
							$is_active = (int)$user[9] === 1;
							$status_text = $is_active ? "Active" : "In-Active";
							$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
							?>
                           <tr>
                           	<td><?php echo htmlspecialchars($user[0]) ?></td>
                            <td><?php echo htmlspecialchars($user[1]) ?></td>
                            <td><?php echo htmlspecialchars($user[2]) ?></td>
                            <td><?php echo htmlspecialchars($user[3]) ?></td>
                            <td><?php echo htmlspecialchars($user[4]) ?></td>
                            <td><?php echo htmlspecialchars($user[5]) ?></td>
                            <td><?php echo htmlspecialchars($user[6]) ?></td>
                            <td><?php echo htmlspecialchars($user[7]) ?></td>
                            <td><a onClick="activation(<?php echo $id ?>, <?php echo $current_page ?>)" class="btn btn-info"><?php echo $status_text ?></a></td>
                            <td><a href="/ecom/adminuser_add.php?usrup=<?php echo $id ?>" class="btn btn-info">Update</a></td>
                            <td><a onClick="delusr(<?php echo $id ?>)" class="btn btn-danger">Delete</a>  
                           </tr> 
                            <?php
						}
						?>
                      </tbody>
                    </table>
                    <?php
					// Generate pagination buttons
					for ($page = 1; $page <= $pages; $page++) {
						echo "<a id='vexbtn' onClick='vpageination($page)' class='btn btn-info'>$page</a>";
					}
					?>
                  </div>
                </div>
              </div>
            </div>
<script>
/**
 * Handle pagination navigation
 * @param {number} page - The page number to navigate to
 */
function vpageination(page) {
	$.ajax({
		url: 'userlist.php?page=' + page,
		cache: false,
		success: function() {
			$('#userlistdv').load("userlist.php?page=" + page);
		}
	});
}

/**
 * Toggle user activation status
 * @param {number} id - User ID to toggle activation
 * @param {number} page - Current page number for reload
 */
function activation(id, page) {
	$.ajax({
		url: 'usersactive.php?id=' + id,
		cache: false,
		success: function() {
			$('#userlistdv').load("userlist.php?page=" + page);
		}
	});
}

/**
 * Delete a user after confirmation
 * @param {number} id - User ID to delete
 */
function delusr(id) {
	const confirmDelete = confirm("Are you sure you want to delete this user?");
	if (confirmDelete) {
		$.ajax({
			url: 'userlist.php?did=' + id,
			cache: false,
			success: function() {
				$('#userlistdv').load("userlist.php?page=" + <?php echo $page_f; ?>);
			}
		});
	}
}
</script>         