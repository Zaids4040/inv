<?php
session_start();
include("functions.php");
$usrid = $_SESSION['usrid'];

if(isset($_POST['checkoutbtn']))
{
	$subtotal = $_POST['subtotalhd'];
	$shopid = $_POST['shopdd'];
	$t = time();
	if(isset($_POST['discounttxt']) && $_POST['discounttxt'] != "")
	{$discount = $_POST['discounttxt'];
		
	$subtotal = $subtotal-$discount;
	}
	else{$discount = 0;$subtotal = $_POST['subtotalhd'];}
	if(isset($_POST['shopch'])){$borrow_status = 1;}else{$borrow_status = 0;}
	$cart_products = sel_table("home_sells","");
	$procount = mysqli_num_rows($cart_products);
	if($discount != 0){$perprodis = $discount / $procount;}else{$perprodis =0;}
	while($cart_procuct_row = mysqli_fetch_row($cart_products))
	{
		$products_min_quan = sel_table("products","where id = $cart_procuct_row[3]");
		$pro_up_query = "UPDATE products
	SET
	stock_quantity = stock_quantity - ($cart_procuct_row[4]) 
	where 
	id = $cart_procuct_row[3]";	
		if(mysqli_query($con,$pro_up_query))
		{
			if($shopid == -1)
			{
				if(insert_tables("sells(name, price, product_id, sub_total,quantity,shop_by_date,discount,unique_id,usr_id,whole_sale_price)","('$cart_procuct_row[1]',$cart_procuct_row[2] - $perprodis,$cart_procuct_row[3],$subtotal,$cart_procuct_row[4],CURRENT_DATE,$perprodis,'$t',$usrid,$cart_procuct_row[5])"))
				{$_SESSION['home_liter'] = 1;}else{$_SESSION['home_liter'] = 0;}
			}
			else
			{
				
				$shop_data = sel_table("shops","where id = $shopid");
				$shop_row = mysqli_fetch_row($shop_data); 
				if(insert_tables("sells(name, price, product_id, sub_total,quantity, shop_id, shop_name, shop_address, shop_phone, shop_borrow_statud, shop_by_date,discount,unique_id,usr_id,whole_sale_price)","('$cart_procuct_row[1]',$cart_procuct_row[2] - $perprodis,$cart_procuct_row[3],$subtotal,$cart_procuct_row[4],$shopid,'$shop_row[1]','$shop_row[2]','$shop_row[4]',$borrow_status,CURRENT_DATE,$discount,'$t',$usrid,$cart_procuct_row[5])"))
				{$_SESSION['home_liter'] = 1;}else{$_SESSION['home_liter'] = 0;}
			}
		}
	}
	echo "<script>window.location='print.php'</script>";
}
else
{
	 /*?>$subtotal = $_GET['subtotal'];
	$cart_products = sel_table("home_sells","");
	while($cart_procuct_row = mysqli_fetch_row($cart_products))
	{
		$products_min_quan = sel_table("products","where id = $cart_procuct_row[3]");
		$pro_up_query = "UPDATE products
	SET
	stock_quantity = stock_quantity - ($cart_procuct_row[4]) 
	where 
	id = $cart_procuct_row[3]";
		if(mysqli_query($con,$pro_up_query))
		{
			insert_tables("sells(name, price, product_id, sub_total,quantity)","('$cart_procuct_row[1]',$cart_procuct_row[2],$cart_procuct_row[3],$subtotal,$cart_procuct_row[4])");
			delete_table("home_sells","$cart_procuct_row[0]");
		}
	}
	echo "<script>window.location = 'home_page.php'</script>";<?php */
}
?>