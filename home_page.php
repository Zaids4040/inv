<?php
session_start();
include("functions.php");
if(!isset($_SESSION['usrid']))
{
	echo "<script>window.location = 'login.php'</script>";

}
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if(isset($_SESSION['update_toast']) && $_SESSION['update_toast'] == 1 && isset($_GET['usrlist']))
{
	echo "<script>window.location = 'AdminPanel.php?usrlist=active'</script>";
	$_SESSION['update_toast'] = 0;
}

if(isset($_GET['logout']))
{
	session_unset($_SESSION['usrid']);
	session_unset($_SESSION['usrname']);
	echo "<script>window.location='login.php'</script>";
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>User Panel</title>
<link rel="stylesheet" href="materialize-v1.0.0/materialize/css/materialize.css"/>
<link rel="stylesheet" href="materialize-v1.0.0/materialize/css/materialize.min.css"/>
  <link rel="stylesheet" type="text/css" href="assets/Roboto300.css" />
<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  
  <link rel="stylesheet" href="assets/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="assets/demo/demo.css" rel="stylesheet" />
</head>
<style>
.qty .count_liter {
    color: #000;
    display: inline-block;
    vertical-align: top;
    font-size: 25px;
    font-weight: 700;
    line-height: 30px;
    padding: 0 2px
    ;min-width: 35px;
    text-align: center;
}
.qty .plus_liter{
    cursor: pointer;
    display: inline-block;
    vertical-align: top;
    color: white;
    width: 30px;
    height: 30px;
    font: 30px/1 Arial,sans-serif;
    text-align: center;
    border-radius: 50%;
    }
	.qty .minus_liter {
    cursor: pointer;
    display: inline-block;
    vertical-align: top;
    color: white;
    width: 30px;
    height: 30px;
    font: 30px/1 Arial,sans-serif;
    text-align: center;
    border-radius: 50%;
    background-clip: padding-box;
}
.qty .countbottels {
    color: #000;
    display: inline-block;
    vertical-align: top;
    font-size: 25px;
    font-weight: 700;
    line-height: 30px;
    padding: 0 2px
    ;min-width: 35px;
    text-align: center;
}
.qty .plusbottels {
    cursor: pointer;
    display: inline-block;
    vertical-align: top;
    color: white;
    width: 30px;
    height: 30px;
    font: 30px/1 Arial,sans-serif;
    text-align: center;
    border-radius: 50%;
    }
	.qty .minusbottels {
    cursor: pointer;
    display: inline-block;
    vertical-align: top;
    color: white;
    width: 30px;
    height: 30px;
    font: 30px/1 Arial,sans-serif;
    text-align: center;
    border-radius: 50%;
    background-clip: padding-box;
}
<?php 
$products_data_style = sel_table("products","");
while($products_style = mysqli_fetch_row($products_data_style))
{
?>
	.qty .count<?php echo $products_style[0]; ?> {
    color: #000;
    display: inline-block;
    vertical-align: top;
    font-size: 25px;
    font-weight: 700;
    line-height: 30px;
    padding: 0 2px
    ;min-width: 35px;
    text-align: center;
}
.qty .plus<?php echo $products_style[0]; ?> {
    cursor: pointer;
    display: inline-block;
    vertical-align: top;
    color: white;
    width: 30px;
    height: 30px;
    font: 30px/1 Arial,sans-serif;
    text-align: center;
    border-radius: 50%;
    }
.qty .minus<?php echo $products_style[0]; ?> {
    cursor: pointer;
    display: inline-block;
    vertical-align: top;
    color: white;
    width: 30px;
    height: 30px;
    font: 30px/1 Arial,sans-serif;
    text-align: center;
    border-radius: 50%;
    background-clip: padding-box;
}

<?php
}
?>
.mt-5 {
  margin-top: ($spacer * 3) !important;
}
.qty {
    text-align: center;
}
.minus 
{
	background-color: 	#00a900 !important;
}
.plus
{
	background-color: 	#00a900 !important;
}
.minus:hover{
    background-color: 	#009c00 !important;
}
.plus:hover{
    background-color:	#009c00 !important;
}
/*Prevent text selection*/
.minspan{
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
}
.countinput{  
    border: 0;
    width: 2%;
}
nput::-webkit-outer-spin-button,
.countinput::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.countinput:disabled{
    background-color:white;
}
</style>
<body>



 <div class="wrapper ">
    <div class="sidebar" data-color="green" data-background-color="white" data-image="">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo"><a href="#" class="simple-text logo-normal">
          Accounts
        </a>
        <div class="container"><input type="text" id="slipbarcodetxt" placeholder="Slip Bar Code"/></div>
        </div>
      <div class="sidebar-wrapper">
       <?php $sells_list = sel_table("home_sells","");
	   ?>
         	<div id="productlisted"></div>
           
			<div class="col-md-12">
            <form action="sells.php" method="post">
            <div id="shoplistdiv" <?php if(mysqli_num_rows($sells_list)>0){}else{ ?> style="display:none;"<?php } ?>>
            <?php
				$shop_query = sel_table("shops","");
			?>
                <div class="input-field">
                    <select name="shopdd" id="shopdd">
                        <option value="-1">Shops Collection</option>
                        <?php
						while($shoprow = mysqli_fetch_row($shop_query))
						{
						?>
                        <option value="<?php echo $shoprow[0]; ?>"><?php echo $shoprow[1]; ?></option>
                        <?php
						}
						?>
                    </select>
                </div>
                <div id="shopchh" style="display:none;">
                	<label>
                	<input type="checkbox" name="shopch" />
                    <span>Borrow</span>
                    </label>
                </div>
                <center>Max Discount : <div id="discount_show"></div></center>
                <div class="input-field">
                	<input type="number" onKeyUp="discount()" name="discounttxt" id="discounttxt" />
                    <label for="discounttxt">Discount (Optional)</label>
                </div>
                </div>
                
            <center><h3>Total</h3></center>
			<div id="amounttakendiv"></div>
            <div id="subtotallist"></div>
            
            </form>
            
            <hr>
            <!-- deposit bottels ko display none kia hua h agr show krna h to display none hata dena -->
            <div class="" style="display:none">
				<div class="brand-logo center">Deposit Bottels</div>
                <form action="bottelhomesys.php" method="post" name="bottelform">
                	<div class="input-field">
                    	<select name="bottelselect" id="bottelselect">
                        	<option value="-1">Select Bottel...</option>
                            <?php 
							$bott_option_query = sel_table("bottels","");
							while($botoptions = mysqli_fetch_row($bott_option_query)){
							?>
                            <option value="<?php echo $botoptions[0]; ?>"><?php echo $botoptions[1]; ?></option>
                            <?php
							}
							?>
                        </select>
                        <div class="qty">
                            <table>
                            <tr>
                            	<td><span class="minusbottels minus bg-dark minspan">-</span></td>
                                <td><input type="number" class="count countbottels countinput" name="qtybottel" value="1"></td>
                                
                                <td><span class="plusbottels plus bg-dark minspan">+</span></td>
                            </tr>
                            </table>
                        </div>
                       		<div class="mt-2" style="display:none" id="dnic">
                            	<label>
                            	<input type="checkbox" name="deponicch"/>
                                <span>Deposit N.I.C</span>
                                </label>
                            </div>
                             <div class="mt-2" id="dpay" style="display:none">
                            	<label>
                            	<input type="checkbox" name="depopaymentch"/>
                                <span>Deposit Payment</span>
                                </label>
                            </div>
                            <div class="row">
                            	<div class="col-md-6"><input type="submit" value="Deposit"  class="btn btn-danger w-100" name="deopbtn"/></div>
                                <div class="col-md-6"><input type="submit" value="Recieve"  class="btn btn-info w-100" name="recvebtn"/></div>
                            </div>
                    </div>
                </form>
            
            </div>
   		</div>
        
        <div class="container">
        <?php
		$expnames = sel_table("expence_names","");	
		?>
        <div class="brand-logo center">Expence</div><hr>
        	<form action="expencecrud.php" method="post">
            	<input list="magicHouses" id="exptxt" name="exptxt" placeholder="Expence Name" />
                <datalist id="magicHouses">
                <?php
				while($expdata = mysqli_fetch_row($expnames))
				{
					echo "<option value='$expdata[1]'>";
				}
				 ?>
                </datalist>
                
                <input type="number" placeholder="Amount" name="expamotxt"/>
              	<textarea name="notetxt" placeholder="Note (Optional)"></textarea>
                
                <input type="submit" value="Save" name="expbtn" id="expbtn" class="btn btn-info w-100"/>
            </form>
        </div>
        
        
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Welcome <?php echo $_SESSION['usrname']; ?></a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
           <ul>
          	<input type="text" name="srtxt" id="srtxt" placeholder="Search" />
          </ul>
          <ul>
          	<input type="text" name="qrtxt" id="qrtxt" placeholder="Bar Code" autofocus />
          </ul>
            <ul class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    Account
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <a class="dropdown-item" href="home_page.php?logout=active">Log out</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- Navbar End -->
      
    <div id="mainpro" class="content"></div>

	
        
         <div id="chec"></div>
     
  <!--my js link -->
  <script src="jquery-3.5.1.min.js"></script>
  <script src="materialize-v1.0.0/materialize/js/materialize.js"></script>
<script src="materialize-v1.0.0/materialize/js/materialize.min.js"></script>

<script>
	var expencheck = document.getElementById("exptxt"); 
	expencheck.addEventListener("keyup",function(){
		var expdata = $(this).val();
		if(expdata.length > 1)
		{
		$.ajax({
			url:'expencecrud.php?checkexp='+expdata,	
			cache:false,
			data:expdata,
			success: function(res){
					if(res == 0)
					{
						document.getElementById("exptxt").style.color = 'red',
						document.getElementById("expbtn").disabled = true
					}
					else
					{
						document.getElementById("exptxt").style.color = '',
						document.getElementById("expbtn").disabled = false
					}
				}
		});
		}
		});

</script>

 <script>
 $("#mainpro").load("homeprolist.php");
 $(document).ready(function(e) {
  $("#productlisted").load("homeproductlist.php");
  $('#subtotallist').load("subtotalwork.php");
  $('#amounttakendiv').load("subtotalwork.php?amot=active");
  $('#discount_show').load("discount.php");
});




var srtxt = document.getElementById("srtxt");
srtxt.addEventListener("keyup",function(){
	var srdata = $(this).val();
	if(srdata != "")
	{
		$.ajax({
			url:'homeprolist.php?data='+srdata,
			cache:false,
			data:srdata,
			success: function(){
				$("#mainpro").load("homeprolist.php?data="+srdata);
				}
			
			})
	}
	});

var qrtxt = document.getElementById("qrtxt");
qrtxt.addEventListener("keypress",function(){
	var vqr = $(this).val();
	if(vqr != "" && qrtxt.value.length <= 100)
	{
	$.ajax({
			url:'homesells.php?qrcode='+vqr,
			cache:false,
			data:vqr,
			success: function(res){
				$("#productlisted").html(res);
				qrtxt.value = "";
				$('#subtotallist').load("subtotalwork.php");
			 $('#amounttakendiv').load("subtotalwork.php?amot=active");
			 $('#discount_show').load("discount.php");
				}
			
			})
	}
	
	});

</script>


<script>
var qrsliptxt = document.getElementById("slipbarcodetxt");
qrsliptxt.addEventListener("keyup",function(){
	var vqsr = $(this).val();
	if(vqsr != "" && qrsliptxt.value.length <= 100)
	{
	$.ajax({
			url:'slipload.php?qrcode='+vqsr,
			cache:false,
			data:vqsr,
			success: function(){
				$("#productlisted").load("homeproductlist.php");
				qrsliptxt.value = "";
				$('#subtotallist').load("subtotalwork.php");
			 $('#amounttakendiv').load("subtotalwork.php?amot=active");
			 $('#discount_show').load("discount.php");
				}
			
			})
	}
	
	});
</script>


<script>
function productadd(id,name,price,btn)
{
	if(btn == 'singlebtn')
	{
		var qty = document.getElementById("qty"+id).value;
		productdata = "qty="+qty+"&idhidd="+id+"&namehdd="+name+"&pricehdd="+price+"&singlebtn=active";
		$.ajax({
		url:'homesells.php',
		type:'GET',
		data: productdata,
		cache:false,
		success:function(r){

			$("#productlisted").html(r);
			document.getElementById("shoplistdiv").setAttribute("style","display:block");
			document.getElementById("qty"+id).value = 1;
			 $('#subtotallist').load("subtotalwork.php");
			 $('#amounttakendiv').load("subtotalwork.php?amot=active");
			 $('#discount_show').load("discount.php");
			}
		});
	}
	else
	{
		var qty = document.getElementById("qty"+id).value;
		productdata = "qty="+qty+"&idhidd="+id+"&namehdd="+name+"&pricehdd="+price+"&cartonbtn=active";
		$.ajax({
		url:'homesells.php',
		type:'GET',
		data: productdata,
		cache:false,
		success:function(r){
			$("#productlisted").html(r);
			document.getElementById("shoplistdiv").setAttribute("style","display:block");
			document.getElementById("qty"+id).value = 1;
			 $('#subtotallist').load("subtotalwork.php");
			 $('#amounttakendiv').load("subtotalwork.php?amot=active");
			 $('#discount_show').load("discount.php");
			}
		});

	}
}


</script>

<script>
 $(document).ready(function(e) {
    $("#bottelselect").change(function(){
		var id = $(this).find(":selected").val();
		var datastring = "id="+id;
		$.ajax({
			url:'ajaxbottel.php',
			data: datastring,
			cache:false,
			success: function(obj){
				if(obj == "Only on Payment")
				{
					document.getElementById("dnic").setAttribute("style","display:none");
					document.getElementById("dpay").setAttribute("style","display:block");
				}
				else if(obj == "Both")
				{
					document.getElementById("dnic").setAttribute("style","display:block");
					document.getElementById("dpay").setAttribute("style","display:block");
				}
				else if(obj == "Only on N.I.C")
				{
					document.getElementById("dnic").setAttribute("style","display:block");
					document.getElementById("dpay").setAttribute("style","display:none");
				}
				else
				{
					document.getElementById("dnic").setAttribute("style","display:none");
					document.getElementById("dpay").setAttribute("style","display:none");
				}
				
				}
			});
		})
		
		
		
		 $("#shopdd").change(function(){
   var shopid = $(this).find(":selected").val();
   var datastringfroshop = "id="+shopid;
   $.ajax({
	   url:'shopddselect.php',
	   data:datastringfroshop,
	   cache:false,
	   success: function(s)
	   {
		   if(s == 1)
		   {
			   document.getElementById("shopchh").setAttribute("style","display:block");
		   }
		   else{document.getElementById("shopchh").setAttribute("style","display:none");}  
		   
	   }
	   })
   });
});

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
</script>
 <!--my js link complete -->
 <?php
 $products_data_script = sel_table("products","");
 while($products_script = mysqli_fetch_row($products_data_script))
 {
  ?>
  <script>
  			$(document).ready(function(){
   			$(document).on('click','.plus<?php echo $products_script[0]; ?>',function(){
				$('.count<?php echo $products_script[0]; ?>').val(parseInt($('.count<?php echo $products_script[0]; ?>').val()) + 1 );
    		});
        	$(document).on('click','.minus<?php echo $products_script[0]; ?>',function(){
    			$('.count<?php echo $products_script[0]; ?>').val(parseInt($('.count<?php echo $products_script[0]; ?>').val()) - 1 );
    				
    	    	});
 		});
  </script>
  <?php
 }
  ?>
  
<script>
  			$(document).ready(function(){
   			$(document).on('click','.plusbottels',function(){
				$('.countbottels').val(parseInt($('.countbottels').val()) + 1 );
    		});
        	$(document).on('click','.minusbottels',function(){
    			$('.countbottels').val(parseInt($('.countbottels').val()) - 1 );
    				
    	    	});
				
				$(document).on('click','.plus_liter',function(){
				$('.count_liter').val(parseInt($('.count_liter').val()) + 1 );
				document.getElementById("totalit").innerHTML = "Total : "+lit_amo.value * $('.count_liter').val();
    		});
        	$(document).on('click','.minus_liter',function(){
    			$('.count_liter').val(parseInt($('.count_liter').val()) - 1 );
    				document.getElementById("totalit").innerHTML = "Total : "+lit_amo.value * $('.count_liter').val();
    	    	});
 		});
		
		lit_change_txt.addEventListener("keyup",function(){
		var finallitamountchange = liter.value * <?php echo $row[2]/$row[1]; ?> * $('.count_liter').val();
		var changeamo = lit_change_txt.value-finallitamountchange;
		document.getElementById("totalitchange").innerText = "Return Amount : " + changeamo;					

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
if(isset($_SESSION['botupcom']) && $_SESSION['botupcom']= 1)
{
	echo "<script> M.toast({html: 'Bottel Deposited'})</script>";
	unset($_SESSION['botupcom']);	
}
else if(isset($_SESSION['botaddcom']) && $_SESSION['botaddcom']= 1)
{
	echo "<script> M.toast({html: 'Bottel Recieved'})</script>";
	unset($_SESSION['botaddcom']);	
}
else if(isset($_SESSION['botnocom']) && $_SESSION['botnocom']= 1)
{
	echo "<script> M.toast({html: 'No Deposite Bottels Available'})</script>";
	unset($_SESSION['botnocom']);	
}
else if(isset($_SESSION['botnotavailable']) && $_SESSION['botnotavailable']= 1)
{
	echo "<script> M.toast({html: 'This Amount Of Bottels are not Available'})</script>";
	unset($_SESSION['botnotavailable']);	
}
else if(isset($_SESSION['invalid']) && $_SESSION['invalid'] == 1)
{
	echo "<script> M.toast({html: 'Invalid... Seems Like Some Thing Not Filled'})</script>";
	unset($_SESSION['invalid']);	
}
else if(isset($_SESSION['botnocomv2']) && $_SESSION['botnocomv2'] == 1)
{
	echo "<script> M.toast({html: 'This Amount Of Bottels Are Not Deposited'})</script>";
	unset($_SESSION['botnocomv2']);	
}
else if(isset($_SESSION['home_liter']) && $_SESSION['home_liter'] == 1)
{
	echo "<script> M.toast({html: 'Sucessfully Selled'})</script>";
	unset($_SESSION['home_liter']);	
}
else if(isset($_SESSION['invalidquan']) && $_SESSION['invalidquan'] == 1)
{
	$msg = $_SESSION["availablequan"];
	echo "<script> M.toast({html: 'Out Of Stock There is only $msg Available'})</script>";
	unset($_SESSION['invalidquan']);	
}
else if(isset($_SESSION['expcru']) && $_SESSION['expcru'] == 1)
{
	echo "<script> M.toast({html: 'Expence Added Successfully'})</script>";
	unset($_SESSION['expcru']);	
}
else if(isset($_SESSION['expcru']) && $_SESSION['expcru'] == 0)
{
	echo "<script> M.toast({html: 'Invalid'})</script>";
	unset($_SESSION['expcru']);	
}
?>