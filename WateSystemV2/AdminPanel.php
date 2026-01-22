<?php
session_start();
include("functions.php");
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(isset($_SESSION['adminsucc']) && $_SESSION['adminsucc'] == 1)
{}else{echo "<script>window.location = 'adminlogin.php';</script>";}
if(isset($_SESSION['update_toast']) && $_SESSION['update_toast'] == 1 && isset($_GET['usrlist']))
{
	echo "<script>window.location = 'AdminPanel.php?usrlist=active'</script>";
	$_SESSION['update_toast'] = 0;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Panel</title>
<link rel="stylesheet" href="materialize-v1.0.0/materialize/css/materialize.css"/>
<link rel="stylesheet" href="materialize-v1.0.0/materialize/css/materialize.min.css"/>
  <link rel="stylesheet" type="text/css" href="assets/Roboto300.css" />
<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="assets/demo/demo.css" rel="stylesheet" />
</head>
<style>
.r1
{
	color:#F00;
}
.mi 
{
	font-weight:700;
}
.lid {
 display: block;
 transition-duration: 0.5s;
}

.lid:hover {
  cursor: pointer;
}

ul .lid .uld {
  visibility: hidden;
  opacity: 0;
  position: relative;
  transition: all 0.5s ease;
  margin-top: 1rem;
  left: 0;
  display: none;
}

ul .lid:hover > .uld,
ul .lid uld:hover {
  visibility: visible;
  opacity: 1;
  display: block;
}

ul .lid .uld .lid {
  clear: both;
  width: 100%;
}
</style>
<body>



 <div class="wrapper ">
    <div class="sidebar" data-color="azure" data-background-color="white" data-image="">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo"><a href="http://www.creative-tim.com" class="simple-text logo-normal">
          Admin Panel
        </a></div>
      <div class="sidebar-wrapper" data-color="green">
        <ul class="nav">
          <li class="nav-item <?php if($actual_link == "http://localhost/WateSystemV2/AdminPanel.php"){echo "active";}else{echo "";} ?> mi">
            <a class="nav-link" data-position="bottom" data-delay="50" data-tooltip="I am tooltip" href="AdminPanel.php">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="lid nav-item <?php if(isset($_GET['users']) || isset($_GET['usrlist'])){echo "active";} ?> ">
            <a class="nav-link mi tooltipped" data-tooltip="<i class='material-icons'>arrow_drop_down</i>" data-position="right" href="AdminPanel.php?users=active">
              <i class="material-icons">person</i>
              <p>Users</p>
               
            </a>
            <ul class="uld dropdown"> 
                <li class=" lid nav-item ">
                <a class="nav-link" href="AdminPanel.php?users=active">
                  <i class="material-icons">add</i>
                  <p>Add Users</p>
                </a>
                </li>
                <li class=" lid nav-item ">
                <a class="nav-link" href="AdminPanel.php?usrlist=active">
                  <i class="material-icons">list</i>
                  <p>Users List</p>
                </a>
                </li>

              </ul>
          </li>    
         	
        
         
           <li class="nav-item <?php if(isset($_GET['bottels'])){echo "active";} ?> ">
            <a class="nav-link mi" href="AdminPanel.php?bottels=active">
              <i class="material-icons">bubble_chart</i>
              <p>Bottels Details</p>
            </a>
          </li>
           <li class="nav-item ">
            <a class="nav-link mi" href="AdminPanel.php?liter=active">
              <i class="material-icons">local_drink</i>
              <p>Liter Details</p>
            </a>
          </li>
           <li class="nav-item lid <?php if(isset($_GET['products']) || isset($_GET['prolist'])){echo "active";} ?> ">
            <a class="nav-link mi tooltippedd" href="AdminPanel.php?products=active" data-tooltip="<i class='material-icons'>arrow_drop_down</i>" data-position="right">
              <i class="material-icons">library_books</i>
              <p>Add Products</p>
            </a>
            
            <ul class="uld dropdown"> 
                <li class=" lid nav-item ">
                <a class="nav-link" href="AdminPanel.php?products=active">
                  <i class="material-icons">add</i>
                  <p>Add Products</p>
                </a>
                </li>
                <li class=" lid nav-item ">
                <a class="nav-link" href="AdminPanel.php?prolist=active">
                  <i class="material-icons">list</i>
                  <p>Products List / Stock</p>
                </a>
                </li>

              </ul>
            
          </li>
            <li class="nav-item">
            <a class="nav-link mi" href="AdminPanel.php?sells=active">
              <i class="material-icons">content_paste</i>
              <p>Sells List</p>
            </a>
          </li>
           <li class="nav-item lid  <?php if(isset($_GET['shops']) || isset($_GET['shopslist'])){echo "active";} ?>">
            <a class="nav-link mi tooltippeddd" href="AdminPanel.php?shops=active" data-tooltip="<i class='material-icons'>arrow_drop_down</i>" data-position="right">
              <i class="material-icons">store_mall_directory</i>
              <p>Shops Details</p>
              </a>
               <ul class="uld dropdown"> 
                <li class=" lid nav-item ">
                <a class="nav-link"  href="AdminPanel.php?shops=active">
                  <i class="material-icons">add</i>
                  <p>Add Shops</p>
                </a>
                </li>
                <li class=" lid nav-item ">
                <a class="nav-link"  href="AdminPanel.php?shopslist=active">
                  <i class="material-icons">list</i>
                  <p>Shops List</p>
                </a>
                </li>

              </ul>
            
          </li>
        
          <li class="nav-item ">
            <a class="nav-link mi" href="#">
              <i class="material-icons">language</i>
              <p>RTL Support</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Dashboard</a>
          </div>
            <div class="justify-content-end">
				
            </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
            <li class="nav-item">
            	<form>
                <div class="input-field">
                   <input type="text" name="searchtxt" id="searchtxt" placeholder="Search From Data" />
                </div>
                </form>
            </li>
              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    Account
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <a class="dropdown-item" href="AdminPanel.php?logout=active">Log out</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- Navbar End -->
      <!-- Cards -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
          <!-- First Card -->
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">content_copy</i>
                  </div>
                  <p class="card-category">Total Earning</p>
                  <h3 class="card-title"><?php echo totalearning()."/-"; ?>
                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">warning</i>
                    <a href="javascript:;">Get More Space...</a>
                  </div>
                </div>
              </div>
            </div>
            <!-- First Card End -->
            
            <!-- Second Card -->
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">store</i>
                  </div>
                  <p class="card-category">Monthly Earning</p>
                  <h3 class="card-title"><?php echo monthlyearning()."/-"; ?></h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">date_range</i> Last 24 Hours
                  </div>
                </div>
              </div>
            </div>
            <!-- Second Card End -->
            
            <!-- Third Card -->
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">info_outline</i>
                  </div>
                  <p class="card-category">Monthly Profit</p>
                  <h3 class="card-title"><?php echo monthlyprofit(); ?></h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">local_offer</i> Tracked from Github
                  </div>
                </div>
              </div>
            </div>
            <!-- Third Card End -->
            
            <!-- Fourth Card -->
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-info card-header-icon">
                  <div class="card-icon">
                    <i class="fa fa-twitter"></i>
                  </div>
                  <p class="card-category">Borrow Amount</p>
                  <h3 class="card-title"><?PHP if(borrowamount() == ""){echo "0.00";}else{echo borrowamount();} ?></h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">update</i> Just Updated
                  </div>
                </div>
              </div>
            </div>
          </div>
		<!-- Fourth Card End -->
         <!-- Users Form -->
         <?php 
		 if(strtoupper($actual_link) == strtoupper('http://localhost/WateSystemV2/AdminPanel.php') || isset($_GET['clearid']))
		 {
			$lessproducts = sel_table("products","where stock_quantity < 10");
			$borrow_shop_data = sel_table("sells","where unique_id = unique_id and shop_borrow_statud = 1 GROUP by unique_id");
			$currdate = date("d");
			$usrsalday = sel_table("users_table","where status = 1");
			$today_sells = sel_table("sells","where shop_by_date = CURRENT_DATE");
			if(mysqli_num_rows($lessproducts) > 0)
			{
		 ?>
         <h3>Less Quantity Products</h3>
         <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-primary">
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Update</th>
                     
                      </thead>
                      <tbody>
                      	<?php
						while($lessquanrow = mysqli_fetch_row($lessproducts))
						{
						?>
                        <tr>
                        	<td><?php echo $lessquanrow[0]; ?></td>
                            <td><center><?php echo "<img src='$lessquanrow[11]' style='max-width:100%;height:100px'/>"; ?></center></td>
                            <td><?php echo $lessquanrow[1]; ?></td>
                            <td><?php echo $lessquanrow[3]; ?></td>
                             <td><a href="AdminPanel.php?products=active&proup=<?php echo $lessquanrow[0]; ?>&dash=active" class="btn btn-info">Update</a></td>
                          
                        </tr>
                      <?php
						}
					  ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
              
         
         <?php } 
		 if(mysqli_num_rows($borrow_shop_data)>0)
		 {
		 ?>
         <h3>Shop Borrow Status</h3>
         <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-primary">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Pending Payment</th>
                        <th>Shop ID</th>
                        <th>Borrow Date</th>
                        <th>date ago</th>
                     	<th>Clear</th>
                      </thead>
                      <tbody>
                      	<?php
						while($lessquanrow = mysqli_fetch_row($borrow_shop_data))
						{
						?>
                        <tr>
                        	<td><?php echo $lessquanrow[0]; ?></td>
                            <td><?php echo $lessquanrow[7]; ?></td>
                            <td><?php echo $lessquanrow[4]; ?></td>
                            <td><?php echo $lessquanrow[6]; ?></td>
                            <td><?php echo $lessquanrow[11];  ?></td>
                            <td><?php echo dateDiff($lessquanrow[11],date("y-m-d"))." Days Ago" ?></td>
                             <td><?php if(!isset($_GET['clearid'])){ ?><a href="AdminPanel.php?clearid=<?php echo $lessquanrow[0]; ?>" class="btn btn-info">Clear</a><?php }else{ 
							 if($_GET['clearid'] == $lessquanrow[0])
							 {
							 ?>
                             <a href="clearborrow.php?id=<?php echo $lessquanrow[13]; ?>" class="btn btn-danger">YES</a>
                             <a href="AdminPanel.php" class="btn btn-info">NO</a>
                             <?php
							 }
							 else
							 {
								 echo "<a href='AdminPanel.php?clearid=<?php echo $lessquanrow[0]; ?>' class='btn btn-info'>Clear</a>";
							 }
							 ?>
                             <?php } ?>
                             </td>
                          
                        </tr>
                      <?php
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
		 if(mysqli_num_rows($usrsalday)>0)
		 {
		 ?>
        
                      	<?php
						while($usrsalaryrow = mysqli_fetch_row($usrsalday))
						{
							$date_year = date("Y");
							$date_month = date("m");
							$salary_Sel = sel_table("usr_salary","where usr_id = $usrsalaryrow[0] and YEAR(currentdate) = '$date_year' and month(currentdate) = '$date_month'");
							if(mysqli_num_rows($salary_Sel) > 0)
							{}
							else
							{
						?>
                         <h3>Users Pending Salaries</h3>
         <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-primary">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                     	<th>Salary</th>
                        <th>Salary Date</th>
                        <th>Current Date</th>
                        <th>Clear</th>
                      </thead>
                      <tbody>
                        	<tr>
                            	<td><?php echo $usrsalaryrow[0]; ?></td>
                                <td><?php echo $usrsalaryrow[1]; ?></td>
                                <td><?php echo $usrsalaryrow[2]; ?></td>
                                <td><?php echo $usrsalaryrow[3]; ?></td>
                                <td><?php echo $usrsalaryrow[4]; ?></td>
                                <td><?php echo $usrsalaryrow[6]; ?></td>
                                <td><?php echo $usrsalaryrow[7]; ?></td>
                                <td><?php echo date("y-m-d"); ?></td>
                                <td><a href="userssalary.php?id=<?php echo $usrsalaryrow[0]; ?>" class="btn btn-info">Clear</a></td>
                            </tr>
                             </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
                        <?php
							}
						}
						?>
                     
         <?php
			 
		 }
		 if(mysqli_num_rows($today_sells)>0)
		 {
			 $todayincomquery =  "SELECT SUM(sub_total), sum(discount) FROM sells WHERE shop_by_date = CURRENT_DATE AND (shop_borrow_statud is null or shop_borrow_statud = 0)";
			 $totalincomexe = mysqli_query($con,$todayincomquery);
			 $today_income = mysqli_fetch_row($totalincomexe);
		 ?>
         <table><tr><td><b>Today's Total Incom</b></td><td><?php echo $today_income[0]; ?>/- PKR</td><td><b>Today's Total discount</b></td><td><?php echo $today_income[1]; ?>/- PKR</td></tr></table>
         <table>
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
                <th>User Id</th>
            </thead>
            <tbody>
            <?php
			$todaysellsquery = sel_table("sells","where shop_by_date = CURRENT_DATE order by id desc");
			while($todaysell = mysqli_fetch_row($todaysellsquery))
			{
			?>
	            <tr>
                	<TD><?php echo $todaysell[1]; ?></TD>
                    <TD><?php echo $todaysell[2]; ?></TD>
                    <TD><?php echo $todaysell[4]; ?></TD>
                    <TD><?php echo $todaysell[5]; ?></TD>
                    <TD><?php echo $todaysell[7]; ?></TD>
                    <TD><?php echo $todaysell[8]; ?></TD>
                    <TD><?php echo $todaysell[9]; ?></TD>
                    <TD><?php echo $todaysell[10]; ?></TD>
                    <TD><?php echo $todaysell[11]; ?></TD>
                    <TD><?php echo $todaysell[12]; ?></TD>
                    <TD><?php echo $todaysell[14]; ?></TD>
                   
                   
                </tr>	
            <?php 
			}
			?>    
            </tbody>
         </table>
         <?php
		 }
		 }
		 else if(isset($_GET['users']))
		 {
		 ?>
        <div class="container">
        	<div class="row">
              <div class="col-md-12">
                  <div class="card">
                    <div class="card-header card-header-success">
                      <h4 class="card-title">Add User</h4>
                      <p class="card-category">Complete your profile</p>
                    </div>
                    <div class="card-body">
                    <?php
					if(isset($_GET['usrup']))
					{
						$usrid = $_GET['usrup'];
						$usr_update_query = "SELECT * FROM users_table where id = $usrid";
						$usr_update_query_res = mysqli_query($con,$usr_update_query);
						
						$usr_update_rows = mysqli_fetch_row($usr_update_query_res);
					}
					?>
                      <form action="" method="post">
                        <div class="row">
                          <div class="input-field col s6">
                            <input id="usernametxt" name="usrtxt" <?php if(isset($_GET['usrup'])){ echo "value='$usr_update_rows[1]'"; } ?> type="text" class="validate">
         					<label for="usernametxt">UserName (صارف کا نام)</label>
                          </div>
                          <div class="input-field col s6">
                            <input id="emailtxt" type="email" <?php if(isset($_GET['usrup'])){ echo "value='$usr_update_rows[2]'"; } ?> name="emltxt" class="validate">
         					<label for="emailtxt">Email (ای میل)</label>
                          </div>
                         </div>
                         <div class="row">
                         	 <div class="input-field col s6">
                            <input id="phonetxt" type="number" <?php if(isset($_GET['usrup'])){ echo "value='$usr_update_rows[3]'"; } ?> name="phonetxt" class="validate">
         					<label for="phonetxt">Phone (فون نمبر) (Optional)</label>
                          </div>
                          <div class="input-field col s6">
                            <input id="addresstxt" type="text" <?php if(isset($_GET['usrup'])){ echo "value='$usr_update_rows[4]'"; } ?> name="addtxt" class="validate">
         					<label for="addresstxt">Address (صارف کا پتہ
)(Optional)</label>
                          </div>
                        </div>
                        <div class="row">
						  <div class="input-field col s12">
                            <input id="passtxt" type="password" <?php if(isset($_GET['usrup'])){ echo "value='$usr_update_rows[5]'"; } ?> name="pastxt" class="validate">
         					<label for="passtxt">Password (پاس ورڈ)</label>
                          </div>                        
                        </div>
                        <div class="row">
                        	<div class="input-field col s6">
                          	<input id="salarytxt" type="text"  <?php if(isset($_GET['usrup'])){ echo "value='$usr_update_rows[6]'"; } ?> name="salarytxt"/>
                            <label for="salarytxt">Salary (تنخواہ) (Optional)</label>
                            </div>
		                  	<div class="input-field col s6">
                          	<input id="salarydatetxt" type="number" <?php if(isset($_GET['usrup'])){ echo "value='$usr_update_rows[7]'"; } ?> name="salarydatetxt"/>
                            <label for="salarydatetxt">Salary Date (تنخواہ کی تاریخ
) (Optional)</label>
                          </div>
                        </div>
                       
						<input type="submit"  <?php if(isset($_GET['usrup'])){ echo "value='Update'"; }else{echo "value='Save'";} ?>  name="usrbtn" class="btn btn-success pull-right"/>
                       
                      </form>
                    </div>
                  </div>
                </div>
            </div>
        </div> 
         <?php
		 }
		 else if(isset($_GET['usrlist']))
		 {
		 ?>
          
         <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title ">Users Table</h4>
                  <p class="card-category">Your Available Users</p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-primary">
                        <th> ID</th>
                        <th>User Name </th>
                        <th> Email </th>
                        <th> Phone </th>
                        <th> Address </th>
                        <th> Password </th>
                        <th> Salary </th>
                        <th> Salary Date </th>
                        <th>Status</th>
                        <th> Update </th>
                        <th> Delete </th>
                      </thead>
                      <tbody id="searchtbody"></tbody>
                      <tbody>
						<?php
						$users_data = sel_table('users_table','');
						while($usrrows = mysqli_fetch_row($users_data))
						{
							?>
                           <tr>
                           	<td><?php echo $usrrows[0] ?></td>
                            <td><?php echo $usrrows[1] ?></td>
                            <td><?php echo $usrrows[2] ?></td>
                            <td><?php echo $usrrows[3] ?></td>
                            <td><?php echo $usrrows[4] ?></td>
                            <td><?php echo $usrrows[5] ?></td>
                            <td><?php echo $usrrows[6] ?></td>
                            <td><?php echo $usrrows[7] ?></td>
                            <td><a href="usersactive.php?id=<?php echo $usrrows[0]; ?>" class="btn btn-info"><?php if($usrrows[9] == 1){echo "Active";}else{echo "In-Active";} ?></a></td>
                            <td><a href="AdminPanel.php?users=active&usrup=<?php echo $usrrows[0] ?>" class="btn btn-info">Update</a></td>
                            <td><?php if(!isset($_GET['usrdel'])){ ?><a href="AdminPanel.php?usrlist=active&usrdel=<?php echo $usrrows[0] ?>" class="btn btn-danger">Delete</a><?php }else{  
							if($_GET['usrdel'] == $usrrows[0])
							{
							?>
                            
                            <div class="row"><div class="col s12"> Are You Sure? You Want To Delete This Record </div></div><div class="row"><a class="btn btn-danger" href="AdminPanel.php?usrlist=active&usrdelid=<?php echo $usrrows[0] ?>">Yes</a><a class="btn btn-Info" href="AdminPanel.php?usrlist=active">No</a></div><?php } ?></td>
                            <?php 
							}
							?>
                           </tr> 
                            <?php
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
		 else if(isset($_GET['products']))
		 {
		 ?>
         <!-- Products Form -->
         
         <div class="container">
        	<div class="row">
              <div class="col-md-12">
                  <div class="card">
                    <div class="card-header card-header-success">
                      <h4 class="card-title"><?php if(isset($_GET['proup'])){echo "Update Product";}else{echo "Add Product";} ?></h4>
                      <p class="card-category">Complete your product</p>
                    </div>
                    <div class="card-body">
                    <?php
					if(isset($_GET['proup']))
					{
						$proid = $_GET['proup'];
						$pro_update_query = "SELECT * FROM products where id = $proid";
						$pro_update_query_res = mysqli_query($con,$pro_update_query);
						
						$pro_update_rows = mysqli_fetch_row($pro_update_query_res);
					}
					?>
                      <form action="<?php if(isset($_GET['dash'])){ echo "procrude.php?proup=".$_GET['proup']."&date=".$pro_update_rows[7]."&dash=active";}else if(isset($_GET['proup'])){ echo "procrude.php?proup=".$_GET['proup']."&date=".$pro_update_rows[7]; }else{echo "procrude.php";}?>" method="post" enctype="multipart/form-data">
                        <div class="row">
                          <div class="input-field col s6">
                            <input id="pronametxt" name="protxt" <?php if(isset($_GET['proup'])){ echo "value='$pro_update_rows[1]'"; } ?> type="text" class="validate">
         					<label for="pronametxt">Product Name (پروڈکٹ کا نام)</label>
                          </div>
                          <div class="input-field col s6">
                            <input id="propricetxt" type="number" <?php if(isset($_GET['proup'])){ echo "value='$pro_update_rows[2]'"; } ?> name="propritxt" class="validate">
         					<label for="propricetxt">Single Product Price (فی مصنوعات کی قیمت)</label>
                          </div>
                         </div>
                         <div class="row">
                         	 <div class="input-field col s6">
                          
  		                           <input id="stocktxt" type="text" <?php if(isset($_GET['proup'])){if($pro_update_rows[9]==1){$stockupshow = $pro_update_rows[3];}else{$stockupshow = $pro_update_rows[3];} echo "value='$stockupshow'"; } ?> name="stocktxt" class="validate">
    		     				   <label for="stocktxt">Stock Quantity <?php if(isset($_GET['proup'])){echo "(per piece)";} ?> (اسٹاک کی مقدار)</label>
                              
                         </div>
                          <div class="input-field col s6">
                            <input id="litretxt" type="number" <?php if(isset($_GET['proup'])){ echo "value='$pro_update_rows[4]'"; } ?> name="litertxt" class="validate">
         					<label for="litretxt">Bottle Liter (ایک بوتل میں کتنا لیٹر ہے؟)</label>
                          </div>
                        </div>
                        <div class="row">
						  <div class="input-field col s6">
                            <input id="quan_c_txt" type="number" <?php if(isset($_GET['proup'])){ echo "value='$pro_update_rows[5]'"; } ?> name="quanctxt" class="validate">
         					<label for="quan_c_txt">Quantity in Carton (ایک کارٹن میں کتنی بوتلیں ہیں)</label>
                          </div>   
                          <div class="input-field col s6">
                          	<input id="c_dis_txt" type="number"  <?php if(isset($_GET['proup'])){ echo "value='$pro_update_rows[6]'"; } ?> name="carpricetxt"/>
                            <label for="c_dis_txt">Carton Discounted Price (کیا آپ کسی کارٹن پر کوئی چھوٹ دیں گے؟) (Optional)</label>
                            </div>                     
                        </div>
                        <div class="row">
                        	<div class="input-field col s6">
                            	<input type="text" id="salepricetxt" <?php if(isset($_GET['proup'])){ echo "value='$pro_update_rows[10]'"; } ?> name="wholesalepricetxt"/>
                                <label for="salepricetxt">Whole Sale Per Piece Price (آپ نے اس پروڈکٹ کے لئے کتنا رقم ادا کی)</label>
                            </div>
                            <div class="col s6">
                            	<div class="file-field input-field">
                                  <div class="btn btn-success">
                                    <i class="material-icons" style="margin-top:-40px;font-size:18px;">filter</i>
                                    <input type="file"  <?php if(isset($_GET['proup'])){ echo "value='c:/xampp/htdocs/WaterSystem/$pro_update_rows[11]'"; } ?> name="imageselector">
                                  </div>
                                  <div class="file-path-wrapper">
                                    <input class="file-path validate" disabled name="imgupdatetxt" <?php if(isset($_GET['proup'])){ echo "value='$pro_update_rows[11]'"; } ?> type="text">
                                  </div>
                                </div>
                            </div>
                        </div>
                       
                        <label>
                            <input type="checkbox" name="cartonquanch"   class="pull-right" />
                            <span>Carton Quantity (کیا یہ کارٹن کی مقدار ہے؟)</span>
	                    </label>
                       
                      
						<input type="submit"  <?php if(isset($_GET['proup'])){ echo "value='Update'"; }else{echo "value='Save'";} ?>  name="probtn" class="btn btn-success pull-right"/>
                       
                      </form>
                    </div>
                  </div>
                </div>
            </div>
        </div> 
         
         <!-- Products Form End -->
         
         <?php
		 }
		 else if(isset($_GET['prolist']))
		 {

		 ?>
         <!-- products list -->
         
         
         <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title ">Product List</h4>
                  <p class="card-category">Your avalible products</p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-primary">
                        <th> ID</th>
                        <th>Image</th>
                        <th>Product Name </th>
                        <th>Per Product Price </th>
                        <th>Stock / Quantity</th>
                        <th> Bottel Liter </th>
                        <th> Quantity In Carton </th>
                        <th> Carton Price </th>
                        <th> Whole Sale Per Piece Price </th>
                        <th> Update </th>
                        <th> Delete </th>
                      </thead>
                      <tbody id="searchtbody"></tbody>
                      <tbody>
						<?php
						if(isset($_GET['page']))
						{
							$current_page = $_GET['page'];
						}else{$current_page = 1;}
						$rows_num = 2;
						$startlim = ($current_page-1)*$rows_num;
						$products_data_final =  sel_table("products","ORDER BY id DESC LIMIT $startlim,$rows_num");
						$products_data = sel_table('products','ORDER BY id DESC');
						$num_of_records = mysqli_num_rows($products_data);
						$pages_num = ceil($num_of_records/$rows_num);
						while($prorows = mysqli_fetch_row($products_data_final))
						{
							?>
                           <tr id="<?php echo $prorows[0]; ?>">
                           	<td><?php echo $prorows[0] ?></td>
                            <td><center><img src="<?php echo $prorows[11]; ?>" id="proimg"  style="max-width:100%;height:100px;"/></center></td>
                            <td><?php echo $prorows[1] ?></td>
                            <td><?php echo $prorows[2] ?></td>
                            <td><?php echo $prorows[3] ?></td>
                            <td><?php echo $prorows[4] ?></td>
                            <td><?php echo $prorows[5] ?></td>
                            <td><?php if($prorows[6] == "" || $prorows[6] == 0){echo $prorows[2]* $prorows[5];}else{echo $prorows[6];} ?></td>
                            <td><?php echo $prorows[10] ?></td>
                            <td><a href="AdminPanel.php?products=active&proup=<?php echo $prorows[0] ?>" class="btn btn-info">Update</a></td>
                            <td>
							<?php if(!isset($_GET['prodel'])){ ?><a href="AdminPanel.php?prolist=active&prodel=<?php echo $prorows[0] ?>" class="btn btn-danger">Delete</a><?php }else{ ?>
                            <?php if($_GET['prodel'] == $prorows[0])
							{ 
							?>
                            <div class="row">
                            <div class="col s12"> Are You Sure? You Want To Delete This Record </div>
                            </div>
                            <div class="row">
                            <a class="btn btn-danger" href="AdminPanel.php?prolist=active&prodelid=<?php echo $prorows[0]; ?>&image=<?php echo $prorows[11] ?>">Yes</a>
                            <a class="btn btn-Info" href="AdminPanel.php?prolist=active">No</a></div><?php } 
							}?>
                            </td>
                           </tr> 
                            <?php
						}
						 ?>
                      </tbody>
                    </table>
                      <?php
					  for($i=1; $i <= $pages_num; $i++)
						{echo "<a href='AdminPanel.php?prolist=active&page=$i' class='btn btn-info'>$i</a>";}
					  ?>
                  </div>
                </div>
              </div>
            </div>
              
         
         
         <!-- products list end-->
         <?php
		 }
		 else if(isset($_GET['bottels']))
		 {
			 if(isset($_GET['bottelupid']))
			 {
				 $bottel_id_update = $_GET['bottelupid'];
				 $bottel_up_data_query  = sel_table("bottels","where id = $bottel_id_update");				 
				 while($bottel_up_row = mysqli_fetch_row($bottel_up_data_query))
				 {
					 
					 $bottel_up_name = $bottel_up_row[1];
					 $bottel_up_type = $bottel_up_row[3];
					 $bottel_up_price = $bottel_up_row[2];
					 $bottel_up_quantity = $bottel_up_row[4];
				 }
			 }
		 ?>
         
         	 <div class="container">
        	<div class="row">
              <div class="col-md-12">
                  <div class="card">
                    <div class="card-header card-header-success">
                      <h4 class="card-title">Add User</h4>
                      <p class="card-category">Complete your profile</p>
                    </div>
                    <div class="card-body">
                      <form <?php if(isset($_GET['bottelupid'])){echo "action='bottel_CRUDE.php?updateid=$bottel_id_update'";}else{echo "action='bottel_CRUDE.php'";} ?>  method="post" name="bottelform">
                        <div class="row">
                          <div class="input-field col s6">
                            <input id="bottelntxt" <?php if(isset($_GET['bottelupid'])){echo "value='".$bottel_up_name."'";} ?> name="botteltxt" type="text" class="validate">
         					<label for="bottelntxt">Bottel's Name</label>
                          </div>
                           <div class="input-field col s6">
                             <select name="bottelselect" onChange="bottels_get_select()">
								<?php if(isset($_GET['bottelupid'])){echo "<option>$bottel_up_type</option>";} ?>
                             	<option>Both</option>
                                <option>Only on N.I.C</option>
                                <option>Only on Payment</option>
                             </select>
                            </div>
                         </div>
                         <div class="row">
                         	<div class="input-field col s6" id="pricebotteltxt">
                            <input id="bottelpricedtxt" <?php if(isset($_GET['bottelupid'])){echo "value='$bottel_up_price'";} ?> type="number" name="bottelpricetxt" class="validate">
         					<label for="bottelpricedtxt">Deposit Price</label>
                          </div>
                          
                          <div class="input-field col s6">
                            <input id="bottelquantxt" <?php if(isset($_GET['bottelupid'])){echo "value='$bottel_up_quantity'";} ?> type="number" name="bottelquantitytxt" class="validate">
         					<label for="bottelquantxt">Quantity</label>
                          </div>
                          
                          
                          </div>
						<input type="submit" <?php if(isset($_GET['bottelupid'])){echo "value='Update'";}else{ echo "value='Save'";} ?> value="Save"   name="bottelbtn" class="btn btn-success pull-right"/>
                      </form>
                    </div>
                  </div>
                </div>
            </div>
        </div> 
         <hr>
          <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title ">Bottels List</h4>
                  <p class="card-category">Your avalible bottels</p>
                </div>
                <div class="card-body" id="tabel">
                  <div class="table-responsive">
                  <table>
                  	<thead>
                    	<th>ID</th>
                        <th>Bottel's Name</th>
                        <th>Type</th>
                        <th>Deposit Price</th>
                        <th>Quantity</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </thead>
                    <tbody>
                    <?php 
					$bottels_detail_query = sel_table("bottels","order by id desc");
					while($bottels_data_row = mysqli_fetch_row($bottels_detail_query))
					{
					?>
                    <tr>
                    	<td><?php echo $bottels_data_row[0]; ?></td>
                        <td><?php echo $bottels_data_row[1]; ?></td>
                        <td><?php echo $bottels_data_row[3]; ?></td>
                        <td><?php echo $bottels_data_row[2]; ?></td>
                        <td><?php echo $bottels_data_row[4]; ?></td>
                        <td><a href="AdminPanel.php?bottels=active&bottelupid=<?php echo $bottels_data_row[0]; ?>" class="btn btn-info" >Update</a></td>
                        <td>
                        <?php 
						if(!isset($_GET['botdelid']))
						{
						?>
                        <a href="AdminPanel.php?bottels=active&botdelid=<?php echo $bottels_data_row[0]; ?>#tabel" class="btn btn-danger" >Delete</a>
                        <?php
						}
						else
						{
						?>
                        <a href="bottel_CRUDE.php?delid=<?php echo $bottels_data_row[0]; ?>" class="btn btn-danger" >Yes</a>
                        <a href="AdminPanel.php?bottels=active#tabel" class="btn btn-info" >No</a>
                        
                        <?php	
						}
						?>
                        </td>
                    </tr>
                    <?php
					}
					?>
                    </tbody>
                  </table>
                  </div>
                </div>
         <?php
		 }
		 else if(isset($_GET['liter']))
		 {
			 $liter_row_count_query = sel_table("liter","");
			 $liter_row_count = mysqli_num_rows($liter_row_count_query);
			 if($liter_row_count > 0)
			 {
				 $liter_row = mysqli_fetch_row($liter_row_count_query);
			 }
		 ?>
         
         <div class="container">
        	<div class="row">
              <div class="col-md-12">
                  <div class="card">
                    <div class="card-header card-header-success">
                      <h4 class="card-title">Add Liter</h4>
                      <p class="card-category">Add Liter With Price</p>
                    </div>
                    <div class="card-body">
                      <form action="litercrude.php" method="post">
                      <input type="hidden" <?php if($liter_row_count > 0){echo "value='".$liter_row[0]."'";}else{} ?>  name="idhh"/>
                        <div class="row">
                          <div class="input-field col s6">
                            <input id="litertxt" <?php if($liter_row_count > 0){echo "value='".$liter_row[1]."'";}else{} ?>  name="litertxt" type="number" class="validate">
         					<label for="litertxt">Liters</label>
                          </div>
                          <div class="input-field col s6">
                            <input id="literamotxt" <?php if($liter_row_count > 0){echo "value='".$liter_row[2]."'";}else{} ?>  name="literamotxt" type="number" class="validate">
         					<label for="literamotxt">Amount</label>
                          </div>
                         </div>
						<input type="submit" <?php if($liter_row_count > 0){echo "value='Update'";}else{echo "value='Save'";} ?>   name="literbtn" class="btn btn-success pull-right"/>
                      </form>
                    </div>
                  </div>
                </div>
            </div>
        </div> 
         <hr>
          <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title ">Liter Details</h4>
                  <p class="card-category">Your avalible Liter Price</p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                  <table>
                  	<thead>
                    	<th>ID</th>
                        <th>Liter</th>
                        <th>Amount</th>
                        <th>Delete</th>
                    </thead>
                    <tbody>
					<?php 
					if($liter_row_count > 0)
					{
					?>
                    <tr>
                    	<td><?php echo $liter_row[0]; ?></td>
                        <td><?php echo $liter_row[1]; ?></td>
                        <td><?php echo $liter_row[2]; ?></td>
                        <td><a href="litercrude.php?delid=<?php echo $liter_row[0]; ?>" class="btn btn-danger">Delete</a></td>
                    </tr>
                    <?php
					}
					?>
                    </tbody>
                  </table>
                  </div>
                </div>
         <?php
		 }
		 else if(isset($_GET['shops']))
		 {
			 if(isset($_GET['upid']))
			 {
				 $shopid = $_GET['upid'];
				 $shopdata = sel_table("shops","where id = $shopid");
				 $shopuprow = mysqli_fetch_row($shopdata);
			 }
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
                      <form <?php if(isset($_GET['upid'])){echo "action='shopscrude.php?upid=$shopid'";}else{echo "action='shopscrude.php'";} ?> method="post">
                       <div class="row">
                            <div class="input-field col s6">
                            	<input type="text" name="shopnametxt" id="shopnamtxt" <?php if(isset($_GET['upid'])){echo "value='$shopuprow[1]'";}else{} ?> />
                                <label for="shopnametxt">Shop Name (دکان کا نام)</label>
                            </div>
                            <div class="input-field col s6">
                            	<input type="text" name="shopaddresstxt" id="shopaddresstxt" <?php if(isset($_GET['upid'])){echo "value='$shopuprow[2]'";}else{} ?>/>
                                <label for="shopaddresstxt">Shop Address (دکان کا پتہ)</label>
                            </div>
                       </div>
                       <div class="row">
                       <div class="input-field col s6">
                       	<input type="text" name="shopnic" id="shopnic" <?php if(isset($_GET['upid'])){echo "value='$shopuprow[3]'";}else{} ?>/>
                        <label for="shopnic">Shop owner's C.N.I.C number (Optional) (دکان کے  مالک کا   C.N.I.C نمبر)</label>
                       </div>
                       <div class="input-field col s6">
                       	<input type="number" name="shonum" id="shonum" <?php if(isset($_GET['upid'])){echo "value='$shopuprow[4]'";}else{} ?>/>
                        <label for="shonum">Shop owner's number (دکان کے  مالک کا نمبر)</label>
                       </div>
                       </div>
                       <div class="input-field">
                       	<select name="shopstatusdd">
                        	<?php if(isset($_GET['upid'])){
								if($shopuprow[5] == 1){echo "<option value='1'>Active</option>";}
								else if($shopuprow[5] == 0){echo "<option value='0'>De-Active</option>";}
								else{echo "<option value='-1'>Borrow Status</option>";}
							}else{} ?>
                        	<option value="-1">Borrow Status</option>
                            <option value="1">Active</option>
                            <option value="0">De-Active</option>
                        </select>
                       </div>
                       <input type="submit" <?php if(isset($_GET['upid'])){echo "value='Update'";}else{echo "value='Save'";} ?> name="shopsbtn" class="btn btn-success pull-right"/>
                      </form>
                    </div>
                  </div>
                </div>
            </div>
        </div> 
         <?php
		 }
		 else if(isset($_GET['shopslist']))
		 { 
		 	 $shops_query = sel_table("shops","");
		 ?>
          <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title ">Shop's Details</h4>
                  <p class="card-category">Your avalible Shops</p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                  <table>
                  	<thead>
                    	<th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>N.I.C</th>
                        <th>Phone</th>
                        <th>Borrow Status</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </thead>
                    <tbody>
					<?php
					while($shops_row = mysqli_fetch_row($shops_query))
					{
					?>
                    <tr>
                    	<td><?php echo $shops_row[0]; ?></td>
                        <td><?php echo $shops_row[1]; ?></td>
                        <td><?php echo $shops_row[2]; ?></td>
                        <td><?php echo $shops_row[3]; ?></td>
                        <td><?php echo $shops_row[4]; ?></td>
                        <td><?php if($shops_row[5] == 1){echo "Active";}else if($shops_row[5] == 0){echo "De-Active";}else{echo "Null";} ?></td>
                        <td><a href="Adminpanel.php?shops=active&upid=<?php echo $shops_row[0]; ?>" class="btn btn-info">Update</a></td>
                        <td><?php if(!isset($_GET['delid'])){ ?><a href="AdminPanel.php?shopslist=active&delid=<?php echo $shops_row[0]; ?>" class="btn btn-danger">Delete</a><?php } else{ ?>
						<a href="shopscrude.php?delid=<?php echo $shops_row[0]; ?>" class="btn btn-danger" >Yes</a>
                        <a href="AdminPanel.php?shopslist=active" class="btn btn-info" >No</a>
						<?php } ?></td>
                    </tr>
                   <?php
					}
				   ?>
                    </tbody>
                  </table>
                  </div>
                </div>
         <?php
		 }
		 else if(isset($_GET['sells']))
		 {
			 if(isset($_GET['page']))
			 {
				 $page_f = $_GET['page'];
			 }else{$page_f = 1;}
 			 $page_data_quantity = 10;
			 $starting_limit = ($page_f-1)*$page_data_quantity;
			 $sellect_sell_final = sel_table("sells","order by id desc LIMIT $starting_limit,$page_data_quantity");
			 $sells_data_query = sel_table("sells","");
			 $pages = mysqli_num_rows($sells_data_query)/$page_data_quantity;
			 $pages = ceil($pages);
		 ?>
          <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title ">Sells Details</h4>
                  <p class="card-category">Your Completed Sells</p>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                  <table>
                  	<thead>
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
                        <th>Date of Sell</th>
                        <th>Discount</th>
                        <th>User ID</th>
                        <th>Delete</th>
                    </thead>
                     <tbody id="searchtbody"></tbody>
                    <tbody>
						<?php
						while($sells_row = mysqli_fetch_row($sellect_sell_final))
						{
                        if(!isset($_GET['delid']))
                        {}
						else
						?>

                        	<tr>
                            	<td><?php echo $sells_row[0]; ?></td>
                                <td><?php echo $sells_row[1]; ?></td>
                                <td><?php echo $sells_row[2]; ?></td>
                                <td><?php echo $sells_row[3]; ?></td>
                              
                                <td><?php echo $sells_row[5]; ?></td>
                                <td><?php echo $sells_row[6]; ?></td>
                                <td><?php echo $sells_row[7]; ?></td>
                                <td><?php echo $sells_row[8]; ?></td>
                                <td><?php echo $sells_row[9]; ?></td>
                                <?PHP
								if($sells_row[10] == 1)
								{
								?>
								<td><?php if(!isset($_GET['clearsid'])){ ?><a href="AdminPanel.php?sells=active&clearsid=<?php echo $sells_row[0]; ?>" class="btn btn-info">Clear</a><?php }else{ 
							 if($_GET['clearsid'] == $sells_row[0])
							 {
							 ?>
                             <a href="clearborrow.php?id=<?php echo $sells_row[0]; ?>&sel=active" class="btn btn-danger">YES</a>
                             <a href="AdminPanel.php?sells=active" class="btn btn-info">NO</a>
                             <?php
							 }
							 else
							 {
								 echo "<a href='AdminPanel.php?sells=active&clearsid=<?php echo $sells_row[0]; ?>' class='btn btn-info'>Clear</a>";
							 }
							 ?>
                             <?php } ?>
                             </td>
                                <?php }
								else{
								 ?>
                                 <td>Done</td>
                                 <?php
								}
								 ?>
                                <td><?php echo $sells_row[11]; ?></td>
                                <td><?php echo $sells_row[12]; ?></td>
                                <td><?php echo $sells_row[14]; ?></td>
                                <td><?php if(!isset($_GET['delid']) || $_GET['delid'] !=  $sells_row[0]){ ?><a href="AdminPanel.php?sells=active&delid=<?php echo $sells_row[0]; ?>" class="btn btn-danger">Delete</a><?php } else if(isset($_GET['delid']) && $_GET['delid'] ==  $sells_row[0]){ ?>Are You Sure?<br>
						<a href="sells_delete.php?delid=<?php echo $sells_row[0]; ?>" class="btn btn-danger" >Yes</a>
                        <a href="AdminPanel.php?sells=active" class="btn btn-info" >No</a>
						<?php } ?></td>
                            </tr>
                        <?php
						}
						?>
                    </tbody>
                  </table>
                  <?php
                  for($page = 1 ;$page <= $pages ;$page++)
						{
							echo "<a href='AdminPanel.php?sells=active&page=$page' class='btn btn-info'>$page</a>";
						}
				  ?>
                  </div>
                </div>
         
         <?php
		 }
		 else if(isset($_GET['adminprofile']))
		 {
			 $profile_data = sel_table("adminlogin","");
			 $adminlogin = mysqli_fetch_row($profile_data);
         ?>
         <main>
    <center>
     
		<div class="section"></div>
        <h1>Admin Login</h1>
        <div class="section"></div>
      <h5 class="indigo-text">Login By Your Account Credientials</h5>
      <div class="section"></div>

      <div class="container">
        <div class="z-depth-1 grey lighten-4 col-md-6" style="display: block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">
			<form action="admincrediantials.php" method="post">
            <input type="hidden" <?php  if(mysqli_num_rows($profile_data)>0){ ?> value="<?php echo $adminlogin[0];?>"<?php } ?> name="adminidlog"/>
            <div class="row">
            	<div class="col s12">
                	<div class="input-field">
                    <i class="material-icons prefix">account_circle</i>
                    <input id="icon_prefix" type="email" <?php  if(mysqli_num_rows($profile_data)>0){ ?> value="<?php echo $adminlogin[1];?>"<?php } ?> name="adminusrtxt" class="validate">
                    <label for="icon_prefix">Email</label>
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col s12">
                	<div class="input-field">
                    <i class="material-icons prefix">enhanced_encryption</i>
                    <input type="password" id="password" name="adminpastxt" class="validate" <?php  if(mysqli_num_rows($profile_data)>0){ ?> value="<?php echo $adminlogin[2];?>"<?php } ?>>
                    <label for="password">Password</label>
                    <i class="material-icons prefix" style="cursor:pointer;margin-left:-35px;" id="togglePassword">remove_red_eye</i>
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col s12">
                	<div class="input-field">
                   <input type="submit"  <?php  if(mysqli_num_rows($profile_data)>0){ ?> value="<?php echo "Update";}else{echo "Save";}?>" name="adminlogbtn" class="btn btn-info w-100"/>
                    </div>
                </div>
            </div>
            </form>
        </div>
      </div>
    </center>
  </main>
         <?php
		 }else if(isset($_GET['logout'])){unset($_SESSION['adminsucc']);echo "<script>window.location = 'adminlogin.php'</script>";}
		 ?>
         <!-- Users Form End -->
        
         
         
         
    

     
  <!--my js link -->
  <script src="jquery-3.5.1.min.js"></script>
  <script src="materialize-v1.0.0/materialize/js/materialize.js"></script>
<script src="materialize-v1.0.0/materialize/js/materialize.min.js"></script>
<script>
const togglePassword = document.querySelector('#togglePassword');
		const password = document.querySelector('#password');
		
		togglePassword.addEventListener('click', function (e) {
		// toggle the type attribute
		const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
		password.setAttribute('type', type);
		// toggle the eye slash icon
		if(document.getElementById("togglePassword").innerText == "remove_red_eye")
		{
			document.getElementById("togglePassword").innerText = "panorama_fish_eye";
		}
		else{document.getElementById("togglePassword").innerText = "remove_red_eye";}
	
		});
</script>
<script>
 var searchtxt = document.getElementById('searchtxt');
 searchtxt.addEventListener("keyup",function(){
	 var txt = $(this).val();
<?php
	if(isset($_GET['usrlist']))
	{
?>
	 var datastring = "text="+txt+"&usrlis=active";
<?php
	}else if(isset($_GET['prolist'])){
?>
	var datastring = "text="+txt+"&prolis=active";
<?php }else if(isset($_GET['sells'])){ ?>
var datastring = "text="+txt+"&sells=active";
<?php
}
?>
	 	$.ajax({
			url:'searchpage.php',
			cache:false,
			data:datastring,
			success: function(res){
				$('#searchtbody').html(res);
				}
			
			})
	 });
</script>   
<script>
	function bottels_get_select()
	{
		var selecttxt = bottelform.bottelselect[bottelform.bottelselect.selectedIndex].text;
		if(selecttxt == "Both")
		{
			document.getElementById("pricebotteltxt").setAttribute("style","display:block");
		}
		else if(selecttxt == "Only on N.I.C")
		{
			document.getElementById("pricebotteltxt").setAttribute("style","display:none");
		}
		else if(selecttxt == "Only on Payment")
		{
			document.getElementById("pricebotteltxt").setAttribute("style","display:block");
		}
		
		document.getElementById("selebot").innerHTML = bottelform.bottelselect[bottelform.bottelselect.selectedIndex].text;
	}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
var elems = document.querySelectorAll('select');
var options = document.querySelectorAll('option');
var instances = M.FormSelect.init(elems, options);
});

const pop = document.querySelector(".tooltipped");
M.Tooltip.init(pop,{})

const pope = document.querySelector(".tooltippedd");
M.Tooltip.init(pope,{})

const popee = document.querySelector(".tooltippeddd");
M.Tooltip.init(popee,{})

$(document).ready(function() {
  $('.collapsible').collapsible({
    accordion: false
  });
});
</script>
 <!--my js link complete -->
<script>
var deletebtn = document.getElementById('delbtn');
function delwork(){
document.getElementById('yesbtn').setAttribute('style','display:block');
document.getElementById('delbtn').setAttribute('style','display:none')
});
</script>
  <!--   Core JS Files   -->
  <script src="assets/js/core/jquery.min.js"></script>
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!-- Plugin fr the momentJs  -->
  <script src="assets/js/plugins/moment.min.js"></script>
  <!--  Plugin for Sweet Alert -->
  <script src="assets/js/plugins/sweetalert2.js"></script>
  <!-- Forms Validations Plugin -->
  <script src="assets/js/plugins/jquery.validate.min.js"></script>
  <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
  <script src="assets/js/plugins/jquery.bootstrap-wizard.js"></script>
  <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
  <script src="assets/js/plugins/bootstrap-selectpicker.js"></script>
  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <script src="assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="assets/js/plugins/jquery.dataTables.min.js"></script>
  <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
  <script src="assets/js/plugins/bootstrap-tagsinput.js"></script>
  <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <script src="assets/js/plugins/jasny-bootstrap.min.js"></script>
  <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
  <script src="assets/js/plugins/fullcalendar.min.js"></script>
  <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
  <script src="assets/js/plugins/jquery-jvectormap.js"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="assets/js/plugins/nouislider.min.js"></script>
  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->

  <!-- Library for adding dinamically elements -->
  <script src="assets/js/plugins/arrive.min.js"></script>
  <!--  Google Maps Plugin    -->

  <!-- Chartist JS -->
  <script src="assets/js/plugins/chartist.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
  <!-- Material Dashboard DEMO methods, don't include it in your project! -->
  <script src="assets/demo/demo.js"></script>
 
  <script>
    $(document).ready(function() {
      $().ready(function() {
        $sidebar = $('.sidebar');

        $sidebar_img_container = $sidebar.find('.sidebar-background');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

        if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
          if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            $('.fixed-plugin .dropdown').addClass('open');
          }

        }

        $('.fixed-plugin a').click(function(event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .active-color span').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-color', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data-color', new_color);
          }
        });

        $('.fixed-plugin .background-color .badge').click(function() {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('background-color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-background-color', new_color);
          }
        });

        $('.fixed-plugin .img-holder').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).parent('li').siblings().removeClass('active');
          $(this).parent('li').addClass('active');


          var new_image = $(this).find("img").attr('src');

          if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            $sidebar_img_container.fadeOut('fast', function() {
              $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
              $sidebar_img_container.fadeIn('fast');
            });
          }

          if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $full_page_background.fadeOut('fast', function() {
              $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
              $full_page_background.fadeIn('fast');
            });
          }

          if ($('.switch-sidebar-image input:checked').length == 0) {
            var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
          }
        });

        $('.switch-sidebar-image input').change(function() {
          $full_page_background = $('.full-page-background');

          $input = $(this);

          if ($input.is(':checked')) {
            if ($sidebar_img_container.length != 0) {
              $sidebar_img_container.fadeIn('fast');
              $sidebar.attr('data-image', '#');
            }

            if ($full_page_background.length != 0) {
              $full_page_background.fadeIn('fast');
              $full_page.attr('data-image', '#');
            }

            background_image = true;
          } else {
            if ($sidebar_img_container.length != 0) {
              $sidebar.removeAttr('data-image');
              $sidebar_img_container.fadeOut('fast');
            }

            if ($full_page_background.length != 0) {
              $full_page.removeAttr('data-image', '#');
              $full_page_background.fadeOut('fast');
            }

            background_image = false;
          }
        });

        $('.switch-sidebar-mini input').change(function() {
          $body = $('body');

          $input = $(this);

          if (md.misc.sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

          } else {

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

            setTimeout(function() {
              $('body').addClass('sidebar-mini');

              md.misc.sidebar_mini_active = true;
            }, 300);
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);

        });
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();

    });
  </script>



</body>
</html>
<?php
if(isset($_POST['usrbtn']))
{
	$usrtxt = $_POST["usrtxt"];
	$emailtxt = $_POST["emltxt"];
	$phonetxt = $_POST["phonetxt"];
	$pastxt = $_POST["pastxt"];
	$addtxt = $_POST["addtxt"];
	$salarytxt = $_POST["salarytxt"];
	$salarydatetxt = $_POST["salarydatetxt"];
	if(!isset($_GET['usrup']))
	{
	$usrtable = insert_tables("users_table(username,email,phone,address,pass,salary,salary_date,registerd_date)","('$usrtxt','$emailtxt','$phonetxt','$addtxt','$pastxt',$salarytxt,'$salarydatetxt',CURRENT_DATE)");
	if($usrtable)
	{
		echo " <script>
               M.toast({html: 'User Inserted Sucessfully', classes: 'rounded'}); 
        </script>";
	}
	else
	{
		if(sel_table('users_table',"where email = '$emailtxt'"))
		{echo "<script> M.toast({html: 'This Email is Already Registerd',classes:'r1'})</script>";}
		else{echo "<script> M.toast({html: 'Invalid',classes:'r1'})</script>";}
	}
	}
	else if(isset($_GET['usrup']))
	{
		$id = $_GET['usrup'];
		if(update_user($id,$usrtxt,$emailtxt,$phonetxt,$addtxt,$pastxt,$salarytxt,$salarydatetxt))
		{
			echo " <script>
			   window.location='AdminPanel.php?usrlist=activeup';
        </script>";
		}
		else
		{
			echo "<script> M.toast({html: 'Invalid',classes:'r1'})</script>";
		}
	}
}
if(isset($_GET['usrlist']) && $_GET['usrlist'] == "activeup")
{echo "<script>M.toast({html: 'User Updated Sucessfully', classes: 'rounded'}); </script>";$_SESSION['update_toast']=1;}

if(isset($_GET['usrdelid']))
{
	$usrdelid = $_GET['usrdelid'];

	$usrdelquery = "SELECT * FROM users_table where id = $usrdelid";
	$delusr = mysqli_query($con,$usrdelquery);
	while($delusrrow = mysqli_fetch_row($delusr))
	{
		if(insert_tables('users_delete_recorde(username,email,phone,address,pass,salary,salary_date,registerd_date,deleted_date)',"('$delusrrow[1]','$delusrrow[2]','$delusrrow[3]','$delusrrow[4]','$delusrrow[5]','$delusrrow[6]','$delusrrow[7]','$delusrrow[8]',CURRENT_DATE)"))
		{
			if(delete_table('users_table',$usrdelid))
			{
				echo "<script>window.location='AdminPanel.php?usrlist=active'</script>";
			}
			else
			{
				echo "<script> M.toast({html: 'Invalid',classes:'r1'})</script>";
			}
			
		}
		
	}
}
if(isset($_GET['prodelid']))
{
	if(isset($_GET["image"])){$delimg = $_GET["image"];}
	if(delete_table('products',$_GET['prodelid']))
	{
	if(isset($_GET["image"])){unlink($delimg);}
		echo "<script>window.location='AdminPanel.php?prolist=active'</script>";
	}
	else
	{
		echo "<script> M.toast({html: 'Invalid',classes:'r1'})</script>";
	}
}

if(isset($_SESSION['proinsert']) && $_SESSION['proinsert'] == 1)
{
	echo "<script>M.toast({html: 'Product Added Sucessfully', classes: 'rounded'}); </script>";
	unset($_SESSION['proinsert']);
}
else if(isset($_SESSION['proinsert']) && $_SESSION['proinsert'] == 0)
{
	echo "<script> M.toast({html: 'Invalid',classes:'r1'})</script>";
	unset($_SESSION['proinsert']);
}
else if(isset($_SESSION['proinsert']) && $_SESSION['proinsert'] == -1)
{
	echo "<script> M.toast({html: 'Invalid Image',classes:'r1'})</script>";
	unset($_SESSION['proinsert']);
}


if(isset($_SESSION['proupdate']) && $_SESSION['proupdate'] == 1)
{
	echo "<script>M.toast({html: 'Product Updated Sucessfully', classes: 'rounded'}); </script>";
	unset($_SESSION['proupdate']);
}
else if(isset($_SESSION['proupdate']) && $_SESSION['proupdate'] == 0)
{
	echo "<script> M.toast({html: 'Invalid',classes:'r1'})</script>";
	unset($_SESSION['proupdate']);
}
else if(isset($_SESSION['proupdate']) && $_SESSION['proupdate'] == -1)
{
	echo "<script> M.toast({html: 'Invalid Image',classes:'r1'})</script>";
	unset($_SESSION['proupdate']);
}

if(isset($_SESSION['bottelsucessinsert']) && $_SESSION['bottelsucessinsert'] == 1)
{
	echo "<script> M.toast({html: 'Bottel Added Sucessfully'})</script>";
	unset($_SESSION['bottelsucessinsert']);
}
else if(isset($_SESSION['bottelsucessinsert']) && $_SESSION['bottelsucessinsert'] == 0)
{
	echo "<script> M.toast({html: 'Invalid'})</script>";
	unset($_SESSION['bottelsucessinsert']);
}
else if(isset($_SESSION['bottelsucessupdate']) && $_SESSION['bottelsucessupdate']= 1)
{
	echo "<script> M.toast({html: 'Bottel Updated Sucessfully'})</script>";
	unset($_SESSION['bottelsucessupdate']);	
}
else if(isset($_SESSION['bottelsucessupdate']) && $_SESSION['bottelsucessupdate']= 0)
{
	echo "<script> M.toast({html: 'Invalid'})</script>";
	unset($_SESSION['bottelsucessupdate']);	
}
else if(isset($_SESSION['bottelsucessdelete']) && $_SESSION['bottelsucessdelete']= 1)
{
	echo "<script> M.toast({html: 'Bottel Deleted Sucessfully'})</script>";
	unset($_SESSION['bottelsucessdelete']);	
}
else if(isset($_SESSION['bottelsucessdelete']) && $_SESSION['bottelsucessdelete']= 0)
{
	echo "<script> M.toast({html: 'Invalid'})</script>";
	unset($_SESSION['bottelsucessdelete']);	
}
else if(isset($_SESSION['literinsert']))
{
	echo "<script> M.toast({html: 'Liter Details are Set'})</script>";
	unset($_SESSION['literinsert']);	
}
else if(isset($_SESSION['literupdate']) && $_SESSION['literupdate']= 1)
{
	echo "<script> M.toast({html: 'Liter Details Updated'})</script>";
	unset($_SESSION['literupdate']);	
}
else if(isset($_SESSION['literdelete']) && $_SESSION['literdelete']= 1)
{
	echo "<script> M.toast({html: 'Liter Deleted'})</script>";
	unset($_SESSION['literdelete']);	
}
else if(isset($_SESSION['shopinsert']) && $_SESSION['shopinsert']= 1)
{
	echo "<script> M.toast({html: 'Shop Successfully Added'})</script>";
	unset($_SESSION['shopinsert']);	
}
else if(isset($_SESSION['shopupdate']) && $_SESSION['shopupdate']= 1)
{
	echo "<script> M.toast({html: 'Shop Successfully Updated'})</script>";
	unset($_SESSION['shopupdate']);	
}
else if(isset($_SESSION['shopdel']) && $_SESSION['shopdel']= 1)
{
	echo "<script> M.toast({html: 'Shop Successfully Deleted'})</script>";
	unset($_SESSION['shopdel']);	
}
else if(isset($_SESSION['sellsdelete']) && $_SESSION['sellsdelete']= 1)
{
	echo "<script> M.toast({html: 'Sells Successfully Deleted'})</script>";
	unset($_SESSION['sellsdelete']);	
}
else if(isset($_SESSION['salaryclear']) && $_SESSION['salaryclear']= 1)
{
	echo "<script> M.toast({html: 'Salary Successfully Cleared'})</script>";
	unset($_SESSION['salaryclear']);	
}
else if(isset($_SESSION['adminlogup']) && $_SESSION['adminlogup']= 1)
{
	echo "<script> M.toast({html: 'Login Details Successfully Updated'})</script>";
	unset($_SESSION['adminlogup']);	
}
?>