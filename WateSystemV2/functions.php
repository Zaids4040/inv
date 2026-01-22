<?php
$con = mysqli_connect("Localhost","root","","watermanage_version_two");
function insert_tables($table_w_para,$valu)
{
	global $con;
	$query = "INSERT INTO $table_w_para VALUES $valu";
	return mysqli_query($con,$query);
}
function sel_table($table,$option)
{
	global $con;
	$query = "SELECT * FROM $table $option";
	return  mysqli_query($con,$query);
}
function update_user($id,$username,$eamil,$phone,$adddress,$pass,$salary,$salary_date)
{
	global $con;
    $query = "UPDATE users_table
SET
username = '$username',
email = '$eamil',
phone = '$phone',
address = '$adddress',
pass = '$pass',
salary = '$salary',
salary_date = '$salary_date'
WHERE
id = $id";
return mysqli_query($con,$query);
}
function delete_table($table,$id)
{
	global $con;
	$query = "delete from $table where id = $id";
	return mysqli_query($con,$query);
}

function update_product($id,$product,$price,$stock,$liter,$carton_quan,$carton_price,$invent_date,$carton_stock,$wholepeice,$image)
{
	global $con;
	$query = "UPDATE products
SET 
product_name='$product',
per_product_price='$price',
stock_quantity='$stock',
bottle_liters='$liter',
quantiity_in_cartan='$carton_quan',
cartan_price='$carton_price',
invent_date='$invent_date',
updated_date=CURRENT_DATE,
carton_stock='$carton_stock',
whole_sale_price = '$wholepeice',
image_url = '$image'
WHERE 
id = $id";
return mysqli_query($con,$query);
}

function update_product_w_o_img($id,$product,$price,$stock,$liter,$carton_quan,$carton_price,$invent_date,$carton_stock,$wholepeice)
{
	global $con;
	$query = "UPDATE products
SET 
product_name='$product',
per_product_price='$price',
stock_quantity='$stock',
bottle_liters='$liter',
quantiity_in_cartan='$carton_quan',
cartan_price='$carton_price',
invent_date='$invent_date',
updated_date=CURRENT_DATE,
carton_stock='$carton_stock',
whole_sale_price = '$wholepeice'
WHERE 
id = $id";
return mysqli_query($con,$query);
}
function update_bottels($id,$name,$price,$type,$quantity)
{
	global $con;
	$Query = "UPDATE bottels
SET
name = '$name',
deposit_price = '$price',
deposit_type = '$type',
quantity = $quantity
WHERE id = $id";
	return mysqli_query($con,$Query);
}
function custom_delete($table,$option)
{
	global $con;
	$query = "delete from $table where $option";
	return mysqli_query($con,$query);
}
function update_bottel_custom($id,$quan)
{
	global $con;
	$query = "
	UPDATE bottels
	SET
	quantity = quantity $quan
	WHERE id = $id
	";
	return mysqli_query($con,$query);
}
function custom_select($sum,$table,$option)
{
	global $con;
	$query = "SELECT $sum from $table $option";
	$res = mysqli_query($con,$query);
	$row = mysqli_fetch_row($res);
	return $row[0];
}
function update_liter($liter,$amount,$id)
{
	global $con;
	$query = "
	UPDATE liter
	SET
	liter = $liter,
	amount_liter =$amount 
	WHERE
	id = $id
	";
	return mysqli_query($con,$query);
	
}
function update_shops($name,$address,$nic,$number,	$status,$id)
{
	global $con;
	$query ="UPDATE shops
SET
name = '$name',
address = '$address',
nic_number = '$nic',
number = '$number',
borrow_statud = $status
WHERE
id = $id";
return mysqli_query($con,$query);
}

function dateDiff($date1, $date2) 
{
  $date1_ts = strtotime($date1);
  $date2_ts = strtotime($date2);
  $diff = $date2_ts - $date1_ts;
  return round($diff / 86400);
}
function totalearning()
{
	global $con;
	$query = "SELECT SUM(price) FROM sells";
	$querytwo = "SELECT SUM(quantity*price) FROM liter_sell";
	$sumone = mysqli_query($con,$query);
	$sumtwo = mysqli_query($con,$querytwo);
	$resone = mysqli_fetch_row($sumone);
	$restwo = mysqli_fetch_row($sumtwo);
	return $final = $resone[0]+$restwo[0];
}
function monthlyearning()
{
	global $con;
	$date_year = date("Y");
    $date_month = date("m");
	$query = "SELECT SUM(price) FROM `sells` WHERE year(shop_by_date) = '$date_year' and month(shop_by_date) = '$date_month
	'";
	$execute = mysqli_query($con,$query);
	$res = mysqli_fetch_row($execute);
	
	$query_two = "SELECT SUM(price*quantity) FROM `liter_sell` WHERE year(inserted_date) = '$date_year' and month(inserted_date) = '$date_month'";
	$execute_two = mysqli_query($con,$query_two);
	$res_two = mysqli_fetch_row($execute_two);
	
	
	return $res[0]+$res_two[0];
}
function remainigproducts()
{
	global $con;
	$query = "SELECT SUM(whole_sale_price*stock_quantity) FROM products";
	$execute = mysqli_query($con,$query);
	$res = mysqli_fetch_row($execute);
	return $res[0]; 
}
function salarycalculate()
{
	global $con;
	$date_year = date("Y");
    $date_month = date("m");
	$query = "SELECT SUM(salary) FROM `usr_salary` WHERE year(currentdate) = '$date_year' and month(currentdate) = '$date_month'";
	$execute = mysqli_query($con,$query);
	$res = mysqli_fetch_row($execute);
	return $res[0]; 
}
function borrowamount()
{
	global $con;
	$query = "SELECT SUM(price) FROM `sells` WHERE shop_borrow_statud = 1";
	$execute = mysqli_query($con,$query);
	$res = mysqli_fetch_row($execute);
	echo $res[0];
}
function monthlyprofit()
{
	global $con;
	$date_year = date("Y");
    $date_month = date("m");
	$query = "SELECT SUM(price)-SUM(whole_sale_price) FROM sells WHERE year(shop_by_date) = '$date_year' and month(shop_by_date) = '$date_month' ";
	$exe = mysqli_query($con,$query);
	$res = mysqli_fetch_row($exe);
	echo $res[0]-salarycalculate();
	
}

?>