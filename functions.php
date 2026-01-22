<?php
$con = mysqli_connect("Localhost","root","","imsdb");
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

function update_product($id,$product,$price,$stock,$liter,$carton_quan,$carton_price,$invent_date,$carton_stock,$wholepeice,$image,$qrcode,$pro_disc)
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
image_url = '$image',
qr_code = '$qrcode',
product_discount = '$pro_disc'
WHERE 
id = $id";
return mysqli_query($con,$query);
}

function update_product_w_o_img($id,$product,$price,$stock,$liter,$carton_quan,$carton_price,$invent_date,$carton_stock,$wholepeice,$qrcode,$pro_disc)
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
qr_code = '$qrcode',
product_discount = '$pro_disc'
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
	$query = "SELECT SUM(price) FROM sells where shop_borrow_statud is null || shop_borrow_statud = 0";
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
	' and shop_borrow_statud is null || shop_borrow_statud = 0";
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
	$query = "SELECT SUM(salary) FROM `usr_salary` WHERE year(salarymonth) = '$date_year' and month(salarymonth) = '$date_month'";
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
	$query = "SELECT SUM(price)-SUM(quantity*whole_sale_price) FROM sells WHERE year(shop_by_date) = '$date_year' and month(shop_by_date) = '$date_month' ";
	$queryt = "SELECT * FROM  liter_sell WHERE month(inserted_date) = month(CURRENT_DATE) and year(inserted_date) = year(CURRENT_DATE);";
	$datat = mysqli_query($con, $queryt);
	$queryth = "SELECT * FROM liter";
	$datath = mysqli_query($con,$queryth);
	$exe = mysqli_query($con,$query);
	$res = mysqli_fetch_row($exe);
	$queryexp = "select sum(amount) from  expence where month(date_exp) = month(CURRENT_DATE) and year(date_exp) = year(CURRENT_DATE)";
	$expencedata = mysqli_query($con,$queryexp);
	$expencefinal = mysqli_fetch_row($expencedata);
	if(mysqli_num_rows($datat)>0)
	{
	while($rowt = mysqli_fetch_row($datat))
	{
		
		
			$litprofit = ((($rowt[2]*$rowt[3])-($rowt[5]*$rowt[1])*$rowt[3]));
			echo $res[0]-salarycalculate() + $litprofit -$expencefinal[0];
			
	
	}
		}
		else
		{
			echo $res[0] -salarycalculate() -$expencefinal[0];
		}
	
}
function todayearning()
{
	global $con;
	$query = "SELECT SUM(price) FROM sells WHERE shop_by_date = CURRENT_DATE and shop_borrow_statud is null || 0;";
	$data = mysqli_query($con,$query);
	$queryt = "SELECT SUM(price*quantity) FROM  liter_sell WHERE inserted_date = CURRENT_DATE;";
	$datat = mysqli_query($con, $queryt);
	while($row = mysqli_fetch_row($data))
	{
		$rowt = mysqli_fetch_row($datat);
		echo ceil($row[0]+$rowt[0]);
	}
}
function todayprofit()
{
	global $con;
	$query = "SELECT SUM(price-(whole_sale_price*quantity)) FROM sells WHERE shop_by_date = CURRENT_DATE
";
$queryt = "SELECT * FROM  liter_sell WHERE inserted_date = CURRENT_DATE";
	$datat = mysqli_query($con, $queryt);
	$queryth = "SELECT * FROM liter";
	$datath = mysqli_query($con,$queryth);
	$res = mysqli_query($con,$query);
	$queryexp = "select sum(amount) from  expence where date_exp = CURRENT_DATE";
	$expencedata = mysqli_query($con,$queryexp);
	$expencefinal = mysqli_fetch_row($expencedata);
	while($row = mysqli_fetch_row($res))
	{
		if(mysqli_num_rows($datath)>0)
		{
		while($rowth = mysqli_fetch_row($datath))
		{
			
			if(mysqli_num_rows($datat) > 0)
			{
			while($rowt = mysqli_fetch_row($datat))
			{
				$litprofit = ((($rowt[2]*$rowt[3])-($rowt[5]*$rowt[1])*$rowt[3]));
				echo $litprofit +$row[0] - $expencefinal[0];
			}
			}
			else
			{
				echo $row[0] - $expencefinal[0];
			}
		}
		}else
		{echo $row[0] - $expencefinal[0];}
	}
}
function total_pr_amo()
{
	global $con;
	$query = "SELECT SUM(whole_sale_price*stock_quantity) FROM products";
	$res = mysqli_query($con,$query);
	while($row = mysqli_fetch_row($res))
	{
		echo ceil($row[0]);
	}
}
function rem_prof_amo()
{
	global $con;
	$query = "SELECT SUM(per_product_price*stock_quantity) - SUM(whole_sale_price*stock_quantity) FROM  products";
	$res = mysqli_query($con,$query);
	while($row = mysqli_fetch_row($res))
	{
		echo ceil($row[0]);
	}
}
function addcurrdateonsalarytab($id)
{
	global $con;
	$query = "UPDATE users_table
SET
salarymonthy = CURRENT_DATE
WHERE 
id = $id";
	if(mysqli_query($con,$query))
	{
		true;
	}
	else
	{
		false;
	}
}

function today_income()
{
	global $con;
	$query = "SELECT SUM(price) FROM sells WHERE shop_by_date = CURRENT_DATE and (shop_borrow_statud is null || shop_borrow_statud = 0);";
	$data = mysqli_query($con,$query);
	$queryt = "SELECT SUM(price*quantity) FROM  liter_sell WHERE inserted_date = CURRENT_DATE;";
	$datat = mysqli_query($con, $queryt);
	
	$expencequery = "SELECT SUM(amount) FROM expence WHERE date_exp = CURRENT_DATE";
    $totalexp = mysqli_query($con,$expencequery);
	$total_exp_Amo = mysqli_fetch_row($totalexp);
	while($row = mysqli_fetch_row($data))
	{
		$rowt = mysqli_fetch_row($datat);
		echo ($row[0]+$rowt[0]) - $total_exp_Amo[0] ;
	}
}
?>