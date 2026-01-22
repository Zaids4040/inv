<?php
// Include header file
require_once("adminheader.php");

// Generate current URL with proper protocol
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

// Check admin authentication
if (!isset($_SESSION['adminsucc']) || $_SESSION['adminsucc'] !== 1) {
    echo "<script>window.location = 'adminlogin.php';</script>";
    exit;
}

// Handle update toast notification
if (isset($_SESSION['update_toast']) && $_SESSION['update_toast'] == 1 && isset($_GET['usrlist'])) {
    echo "<script>window.location = 'AdminPanel.php?usrlist=active';</script>";
    $_SESSION['update_toast'] = 0;
}
?>

<!-- Dashboard Cards -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Total Earning Card -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-warning card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">content_copy</i>
                        </div>
                        <p class="card-category">Total Earning</p>
                        <h3 class="card-title"><?= totalearning() ?>/-</h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons text-danger">warning</i>
                            <a href="javascript:;">Get More Space...</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Monthly Earning Card -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">store</i>
                        </div>
                        <p class="card-category">Monthly Earning</p>
                        <h3 class="card-title"><?= monthlyearning() ?>/-</h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons">date_range</i> Last 24 Hours
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Monthly Profit Card -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-danger card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">info_outline</i>
                        </div>
                        <p class="card-category">Monthly Profit</p>
                        <h3 class="card-title"><?= monthlyprofit() ?></h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons">local_offer</i> Tracked from Github
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Borrow Amount Card -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="fa fa-twitter"></i>
                        </div>
                        <p class="card-category">Borrow Amount</p>
                        <h3 class="card-title"><?= borrowamount() ?: "" ?></h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons">update</i> Just Updated
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End First Row Cards -->
        
        <!-- Second Row Cards -->
        <div class="row">
            <!-- Today's Earning Card -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-warning card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">content_copy</i>
                        </div>
                        <p class="card-category">Today's Earning</p>
                        <h3 class="card-title"><?= todayearning() ?>/-</h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons text-danger">warning</i>
                            <a href="javascript:;">Get More Space...</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Today's Profit Card -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">store</i>
                        </div>
                        <p class="card-category">Today's Profit</p>
                        <h3 class="card-title"><?= todayprofit() ?>/-</h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons">date_range</i> Last 24 Hours
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Remaining Product Amount Card -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-danger card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">info_outline</i>
                        </div>
                        <p class="card-category">Remaining Product Amount</p>
                        <h3 class="card-title"><?= total_pr_amo() ?>/-</h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons">local_offer</i> Tracked from Github
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Remaining Profit Amount Card -->
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-header card-header-info card-header-icon">
                        <div class="card-icon">
                            <i class="fa fa-twitter"></i>
                        </div>
                        <p class="card-category">Remaining Profit Amount</p>
                        <h3 class="card-title"><?= rem_prof_amo() ?>/-</h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <i class="material-icons">update</i> Just Updated
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Second Row Cards -->
        
        <?php 
        if (true) {
            // Fetch products with low stock
            $lessProducts = sel_table("products", "WHERE stock_quantity < 10");
            
            // Fetch shop borrow data
            $borrowShopData = sel_table("sells", "WHERE unique_id = unique_id AND shop_borrow_statud = 1 GROUP BY unique_id");
            
            // Get current date
            $currDate = date("d");
            
            // Fetch active users
            $usrSalDay = sel_table("users_table", "WHERE status = 1");
            
            // Fetch today's sales
            $todaySells = sel_table("sells", "WHERE shop_by_date = CURRENT_DATE");
            
            // Display products with low stock
            if (mysqli_num_rows($lessProducts) > 0) {
        ?>
        <!-- Low Stock Products Section -->
        <h3>Less Quantity Products</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Update</th>
                            </thead>
                            <tbody>
                                <?php while ($lessQuanRow = mysqli_fetch_row($lessProducts)): ?>
                                <tr>
                                    <td><?= $lessQuanRow[0] ?></td>
                                    <td class="text-center">
                                        <img src="<?= htmlspecialchars($lessQuanRow[11]) ?>" style="max-width:100%;height:100px" alt="Product Image">
                                    </td>
                                    <td><?= htmlspecialchars($lessQuanRow[1]) ?></td>
                                    <td><?= $lessQuanRow[3] ?></td>
                                    <td>
                                        <a href="adminproduct_add.php?products=active&proup=<?= $lessQuanRow[0] ?>&dash=active" class="btn btn-info">Update</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            } 
            
            // Display shop borrow status
            if (mysqli_num_rows($borrowShopData) > 0) {
        ?>
        <!-- Shop Borrow Status Section -->
        <h3>Shop Borrow Status</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <th>ID</th>
                                <th>Name</th>
                                <th>Pending Payment</th>
                                <th>Shop ID</th>
                                <th>Borrow Date</th>
                                <th>Days Ago</th>
                                <th>Clear</th>
                            </thead>
                            <tbody>
                                <?php while ($borrowRow = mysqli_fetch_row($borrowShopData)): ?>
                                <tr>
                                    <td><?= $borrowRow[0] ?></td>
                                    <td><?= htmlspecialchars($borrowRow[7]) ?></td>
                                    <td><?= $borrowRow[4] ?></td>
                                    <td><?= $borrowRow[6] ?></td>
                                    <td><?= $borrowRow[11] ?></td>
                                    <td><?= dateDiff($borrowRow[11], date("y-m-d")) ?> Days Ago</td>
                                    <td>
                                        <?php if (!isset($_GET['clearid'])): ?>
                                            <a href="AdminPanel.php?clearid=<?= $borrowRow[0] ?>" class="btn btn-info">Clear</a>
                                        <?php else: ?>
                                            <?php if ($_GET['clearid'] == $borrowRow[0]): ?>
                                                <a href="clearborrow.php?id=<?= $borrowRow[13] ?>" class="btn btn-danger">YES</a>
                                                <a href="AdminPanel.php" class="btn btn-info">NO</a>
                                            <?php else: ?>
                                                <a href="AdminPanel.php?clearid=<?= $borrowRow[0] ?>" class="btn btn-info">Clear</a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
            }
            
            // Display users pending salaries
            if (mysqli_num_rows($usrSalDay) > 0) {
        ?>
        <!-- Users Pending Salaries Section -->
        <h3>Users Pending Salaries</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Salary</th>
                                <th>Salary Date</th>
                                <th>Salary Month</th>
                                <th>Current Date</th>
                                <th>Clear</th>
                            </thead>
                            <tbody>
                                <?php
                                // Process salary dates
                                $usrSalaryDate = sel_table("users_table", "WHERE salary_date <= date_format(CURRENT_DATE,'%d') AND salary_date != 0");
                                while ($dataUsrSal = mysqli_fetch_row($usrSalaryDate)) {
                                    $checkUsrSal = sel_table("usr_pending_salary", "WHERE month(usr_salary_month) = month(CURRENT_DATE) AND year(usr_salary_month) = year(CURRENT_DATE) AND usr_id = {$dataUsrSal[0]}");
                                    if (mysqli_num_rows($checkUsrSal) <= 0) {
                                        $usrPId = $dataUsrSal[0];
                                        $queryP = "INSERT INTO usr_pending_salary(usr_id, usr_salary_month, status) VALUES($usrPId, CURRENT_DATE, 0)";
                                        mysqli_query($con, $queryP);
                                    }
                                }
                                
                                // Display pending salaries
                                $usrPSWStatus = sel_table("usr_pending_salary", "WHERE status = 0");
                                while ($usrSz = mysqli_fetch_row($usrPSWStatus)) {
                                    $usrIdP = $usrSz[1];
                                    $usrPData = sel_table("users_table", "WHERE id = $usrIdP");
                                    while ($finalUsrSal = mysqli_fetch_row($usrPData)) {
                                ?>
                                <tr>
                                    <td><?= $finalUsrSal[0] ?></td>
                                    <td><?= htmlspecialchars($finalUsrSal[1]) ?></td>
                                    <td><?= htmlspecialchars($finalUsrSal[2]) ?></td>
                                    <td><?= htmlspecialchars($finalUsrSal[3]) ?></td>
                                    <td><?= htmlspecialchars($finalUsrSal[4]) ?></td>
                                    <td><?= $finalUsrSal[6] ?></td>
                                    <td><?= $finalUsrSal[7] ?></td>
                                    <td><?= date("Y-m", strtotime($usrSz[2])) ?></td>
                                    <td><?= date("Y-m-d") ?></td>
                                    <td>
                                        <a href="userssalary.php?id=<?= $finalUsrSal[0] ?>&statusid=<?= $usrSz[0] ?>" class="btn btn-info">Clear</a>
                                    </td>
                                </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
            }
            
            // Display today's sales
            if (mysqli_num_rows($todaySells) > 0) {
                $todayIncomQuery = "SELECT SUM(sub_total), SUM(discount) FROM sells WHERE shop_by_date = CURRENT_DATE AND (shop_borrow_statud IS NULL OR shop_borrow_statud = 0)";
                $totalIncomExe = mysqli_query($con, $todayIncomQuery);
                $todayIncome = mysqli_fetch_row($totalIncomExe);
        ?>
        <!-- Today's Sales Section -->
        <div class="row">
            <div class="col-md-12">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-3">
                            <tr>
                                <td><strong>Today's Total Income</strong></td>
                                <td><?= today_income() ?>/- PKR</td>
                                <td><strong>Today's Total Discount</strong></td>
                                <td><?= $todayIncome[1] ?>/- PKR</td>
                            </tr>
                        </table>
                        
                        <table class="table">
                            <thead>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Sub Total</th>
                                <th>Quantity</th>
                                <th>Shop Name</th>
                                <th>Shop Address</th>
                                <th>Shop Phone</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Discount</th>
                                <th>User ID</th>
                            </thead>
                            <tbody>
                                <?php
                                $todaySellsQuery = sel_table("sells", "WHERE shop_by_date = CURRENT_DATE ORDER BY id DESC");
                                while ($todaySell = mysqli_fetch_row($todaySellsQuery)):
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($todaySell[1]) ?></td>
                                    <td><?= $todaySell[2] ?></td>
                                    <td><?= $todaySell[4] ?></td>
                                    <td><?= $todaySell[5] ?></td>
                                    <td><?= htmlspecialchars($todaySell[7]) ?></td>
                                    <td><?= htmlspecialchars($todaySell[8]) ?></td>
                                    <td><?= htmlspecialchars($todaySell[9]) ?></td>
                                    <td><?= $todaySell[10] ?></td>
                                    <td><?= $todaySell[11] ?></td>
                                    <td><?= $todaySell[12] ?></td>
                                    <td><?= $todaySell[14] ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
            }
        } elseif (isset($_GET['expence'])) {
            // Expense form
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-header-success">
                            <h4 class="card-title">Add Shops</h4>
                            <p class="card-category">Add Shops That You Want</p>
                        </div>
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="row">
                                    <div class="col s6">
                                        <!-- Form fields go here -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
        } elseif (isset($_GET['logout'])) {
            // Handle logout
            unset($_SESSION['adminsucc']);
            echo "<script>window.location = 'adminlogin.php';</script>";
        }
        ?>
    </div>
</div>

<!-- JavaScript Section -->
<script>
$(document).ready(function() {
    // Document ready handler
});

// Search expense functionality
var searchtxt = document.getElementById("expserchtxt");
if (searchtxt) {
    searchtxt.addEventListener("keyup", function() {
        var valuetxt = $(this).val();
        $.ajax({
            url: 'viewexp.php?serch=' + valuetxt,
            cache: false,
            success: function() {
                $('#vexpence').load("viewexp.php?serch=" + valuetxt);
            }
        });
    });
}

// Search expense list functionality
var searchelisttxt = document.getElementById("explistserchtxt");
if (searchelisttxt) {
    searchelisttxt.addEventListener("keyup", function() {
        var valuelisttxt = $(this).val();
        $.ajax({
            url: 'explist.php?serch=' + valuelisttxt,
            cache: false,
            success: function() {
                $('#explistdv').load("explist.php?serch=" + valuelisttxt);
            }
        });
    });
}

// Search expense list by date functionality
var searchelistddtxt = document.getElementById("explistserchddtxt");
if (searchelistddtxt) {
    searchelistddtxt.addEventListener("keyup", function() {
        var valuelistddtxt = $(this).val();
        $.ajax({
            url: 'explist.php?serchdd=' + valuelistddtxt,
            cache: false,
            success: function() {
                $('#explistdv').load("explist.php?serchdd=" + valuelistddtxt);
            }
        });
    });
}

// Password toggle functionality
const togglePassword = document.querySelector('#togglePassword');
if (togglePassword) {
    const password = document.querySelector('#password');
    
    togglePassword.addEventListener('click', function() {
        // Toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Toggle the eye icon
        this.innerText = this.innerText === "remove_red_eye" ? "panorama_fish_eye" : "remove_red_eye";
    });
}

// Search functionality
var searchtxt = document.getElementById('searchtxt');
if (searchtxt) {
    searchtxt.addEventListener("keyup", function() {
        var txt = $(this).val();
        var datastring = "";
        
        <?php if (isset($_GET['usrlist'])): ?>
            datastring = "text=" + txt + "&usrlis=active";
        <?php elseif (isset($_GET['prolist'])): ?>
            datastring = "text=" + txt + "&prolis=active";
        <?php elseif (isset($_GET['sells'])): ?>
            datastring = "text=" + txt + "&sells=active";
        <?php endif; ?>
        
        $.ajax({
            url: 'searchpage.php',
            cache: false,
            data: datastring,
            success: function(res) {
                $('#searchtbody').html(res);
            }
        });
    });
}
</script>

</body>
</html>

<?php
// Handle product deletion
if (isset($_GET['prodelid'])) {
    $delimg = isset($_GET["image"]) ? $_GET["image"] : null;
    
    if (delete_table('products', $_GET['prodelid'])) {
        if ($delimg) {
            unlink($delimg);
        }
        echo "<script>window.location='AdminPanel.php?prolist=active';</script>";
    } else {
        echo "<script>M.toast({html: 'Invalid', classes:'r1'});</script>";
    }
}

// Display toast notifications
$toastMessages = [
    'proinsert' => [
        1 => "Product Added Successfully",
        0 => "Invalid",
        -1 => "Invalid Image"
    ],
    'proupdate' => [
        1 => "Product Updated Successfully",
        0 => "Invalid",
        -1 => "Invalid Image"
    ],
    'bottelsucessinsert' => [
        1 => "Bottel Added Successfully",
        0 => "Invalid"
    ],
    'bottelsucessupdate' => [
        1 => "Bottel Updated Successfully",
        0 => "Invalid"
    ],
    'bottelsucessdelete' => [
        1 => "Bottel Deleted Successfully",
        0 => "Invalid"
    ]
];

// Process toast notifications
foreach ($toastMessages as $key => $messages) {
    if (isset($_SESSION[$key])) {
        $status = $_SESSION[$key];
        if (isset($messages[$status])) {
            echo "<script>M.toast({html: '{$messages[$status]}', classes: 'rounded'});</script>";
        }
        unset($_SESSION[$key]);
    }
}

// Handle other specific toast notifications
if (isset($_SESSION['literinsert'])) {
    echo "<script>M.toast({html: 'Liter Details are Set'});</script>";
    unset($_SESSION['literinsert']);
} elseif (isset($_SESSION['literupdate'])) {
    echo "<script>M.toast({html: 'Liter Details Updated'});</script>";
    unset($_SESSION['literupdate']);
} elseif (isset($_SESSION['literdelete'])) {
    echo "<script>M.toast({html: 'Liter Deleted'});</script>";
    unset($_SESSION['literdelete']);
} elseif (isset($_SESSION['shopinsert'])) {
    echo "<script>M.toast({html: 'Shop Successfully Added'});</script>";
    unset($_SESSION['shopinsert']);
} elseif (isset($_SESSION['shopupdate'])) {
    echo "<script>M.toast({html: 'Shop Successfully Updated'});</script>";
    unset($_SESSION['shopupdate']);
} elseif (isset($_SESSION['shopdel'])) {
    echo "<script>M.toast({html: 'Shop Successfully Deleted'});</script>";
    unset($_SESSION['shopdel']);
} elseif (isset($_SESSION['sellsdelete'])) {
    echo "<script>M.toast({html: 'Sells Successfully Deleted'});</script>";
    unset($_SESSION['sellsdelete']);
} elseif (isset($_SESSION['salaryclear'])) {
    echo "<script>M.toast({html: 'Salary Successfully Cleared'});</script>";
    unset($_SESSION['salaryclear']);
} elseif (isset($_SESSION['adminlogup'])) {
    echo "<script>M.toast({html: 'Login Details Successfully Updated'});</script>";
    unset($_SESSION['adminlogup']);
} elseif (isset($_SESSION['expenadd'])) {
    $message = $_SESSION['expenadd'] == 1 ? "Expence Successfully Added" : "Invalid";
    echo "<script>M.toast({html: '{$message}'});</script>";
    unset($_SESSION['expenadd']);
}
?>