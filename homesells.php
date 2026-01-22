<?php
session_start();
include("functions.php");
if(!isset($_GET['qrcode']))
{
	$id = $_GET['idhidd'];
	if($_GET['qty'] < 0)
	{
		$query = 'delete from home_sells where product_id = '.$id;
		if(mysqli_query($con,$query))
		{echo "<script>$('#productlisted').load('homeproductlist.php');</script>";}
		else{echo "error";}
		exit;
	}
	$check_quan = sel_table("products","where id = $id");
	$data_fetch = mysqli_fetch_row($check_quan);
	$products_data_check = sel_table("products","where id = $id");
	$products_check = mysqli_fetch_row($products_data_check);
	if(isset($_GET['cartonbtn'])){$checkingquan = $_GET['qty']*$products_check[5];}else{$checkingquan = $_GET['qty'];}
	
	if($data_fetch[3] >= $checkingquan)
	{
		if(isset($_GET['cartonbtn']))
		{
			$quantity = $_GET['qty'];
			$id = $_GET['idhidd'];
			$name = $_GET['namehdd'];
			$price = $_GET['pricehdd'];
			$products_data = sel_table("products","where id = $id");
			while($products = mysqli_fetch_row($products_data))
			{
				$quantity_final = $products[5]*$quantity;
				$priice_final = $price*$quantity_final;
				$whole_sale = $products[10];
				$perppprice = $products[2];
				$piece_discount = $products[14];
			}
			
			$sell_check = sel_table("home_sells","where product_id = $id");
			
			if(mysqli_num_rows($sell_check)>0)
			{
				$sell_check_fetch = mysqli_fetch_row($sell_check);
				$checkquantity = $sell_check_fetch[4] + $quantity_final;
				if($checkquantity <= $data_fetch[3])
				{
				$query = "UPDATE home_sells
					SET
					quantity = quantity + $quantity_final,
					price = price + $priice_final,
					discount = quantity*$piece_discount
					WHERE 
					product_id = $id";
				if(mysqli_query($con,$query))
				{
					echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
				}
				}
				else
				{
					$_SESSION['invalidquan'] = 1;
					$_SESSION["availablequan"] = " ".$data_fetch[3].""." ".$data_fetch[1];
					echo "<script>window.location = 'home_page.php'</script>";
				}
			}
			else
			{
			if(insert_tables("home_sells(name, price, product_id,quantity,whole_sale,per_p_price,slip_status,discount)","('$name','$priice_final',$id,$quantity_final,$whole_sale,$perppprice,0,($piece_discount*$quantity_final))"))
			{
				echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
			}
			else
			{
				echo "error";
			}
			}
		}
		else if(isset($_GET['singlebtn']))
		{
			$quantity = $_GET['qty'];
			$id = $_GET['idhidd'];
			$name = $_GET['namehdd'];
			$price = $_GET['pricehdd'];
			if(isset($_GET['vari']))
			{
				$vari_get = $_GET['vari'];
			}
			$price_fianl = $price * $quantity;
			$products_data = sel_table("products","where id = $id");
			while($products = mysqli_fetch_row($products_data))
			{
				$whole_sale = $products[10];
				$perpprice = $products[2];
				$piece_discount = $products[14];
			}
			if(isset($_GET['vari']))
			{
				$sell_check_one = sel_table("home_sells","where product_id = $id and vari = $vari_get and slip_status = 0 or slip_status is null");
			}
			else
			{
				$sell_check_one = sel_table("home_sells","where product_id = $id and slip_status = 0 or slip_status is null");
			}
			if(mysqli_num_rows($sell_check_one)>0)
			{
				while($sell_make_price = mysqli_fetch_row($sell_check_one))
				{
					$quantity_update_final = $sell_make_price[4]+$quantity;
					$price_update_fianl = $price * $quantity_update_final;
					$vari_id = $sell_make_price[12];
					
				}
				if($vari_id == 0)
				{
					if($data_fetch[3] >= $quantity_update_final)
					{
						$query = "UPDATE home_sells
						SET
						quantity = $quantity_update_final,
						price = $price_update_fianl,
						discount = quantity*$piece_discount
						WHERE 
						product_id = $id";
						if(mysqli_query($con,$query))
						{
							echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
						}
						else
						{
							echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
							echo "error";
						}
					}
					else
					{
						$_SESSION['invalidquan'] = 1;
						$_SESSION["availablequan"] = " ".$data_fetch[3].""." ".$data_fetch[1];
						echo "<script>window.location = 'home_page.php'</script>";
					}
				}
				else
				{
					$pro_vari_query = "select * from product_vari where id = $vari_id";
					$runquery = mysqli_query($con,$pro_vari_query);
					$fetch_quan = mysqli_fetch_row($runquery);
					echo $fetch_quan[2];
					if($fetch_quan[2] >= $quantity_update_final)
					{
						$query = "UPDATE home_sells
						SET
						quantity = $quantity_update_final,
						price = $price_update_fianl,
						discount = quantity*$piece_discount
						WHERE 
						product_id = $id
						and 
						vari =  $vari_id";
						if(mysqli_query($con,$query))
						{
							echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
						}
						else
						{
							echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
							echo "error";
						}
					}
					else
					{
						$_SESSION['invalidquan'] = 1;
						$_SESSION["availablequan"] = " ".$data_fetch[3].""." ".$data_fetch[1];
						echo "<script>window.location = 'home_page.php'</script>";
					}
				}
				
			}
			else
			{
				if(insert_tables("home_sells(name, price, product_id,quantity,whole_sale,per_p_price,slip_status,discount)","('$name','$price_fianl',$id,$quantity,$whole_sale,$perpprice,0,($piece_discount*$quantity))"))
				{
					echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
				}
				else
				{
					echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
					echo "error";
				}
			}
			
		}
	}
	else
	{
		$_SESSION['invalidquan'] = 1;
		$_SESSION["availablequan"] = " ".$data_fetch[3].""." ".$data_fetch[1];
		echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
		echo "<script>window.location = 'home_page.php'</script>";
	}
}
else
{
	
	echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
	$qr_pro_data  = sel_table("products","where qr_code = ".$_GET['qrcode']);
	if(mysqli_num_rows($qr_pro_data)==0)
	{
		
		$qr_pro_data  = sel_table("product_vari","where barrcode = ".$_GET['qrcode']);
		while($qr_data = mysqli_fetch_row($qr_pro_data))
		{
			$query_pro = "SELECT * FROM products where id = ".$qr_data[1];
			$pro_data = mysqli_query($con, $query_pro); 

			while($pro_datas = mysqli_fetch_row($pro_data))
			{
				$qr_proname = $pro_datas[1];	
				$qr_id = $pro_datas[0];	
				$qr_price = $qr_data[8];	
				$qr_w_price = $qr_data[9];	
				$qr_discount = $pro_datas[14];
			}
			$qr_sell_check_one = sel_table("home_sells","where product_id = $qr_id and vari = $qr_data[0]");
			if(mysqli_num_rows($qr_sell_check_one)>0)
			{
				$qty_check = mysqli_fetch_row($qr_sell_check_one);
				if($qr_data[2] >= ($qty_check[4]+1))
				{
						$query = "UPDATE home_sells
					SET
					quantity = quantity+1,
					price = $qr_price*quantity,
					discount = $qr_discount*quantity
					WHERE 
					product_id = $qr_id 
					and
					vari = $qr_data[0]";
					if(mysqli_query($con,$query))
					{
						echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
					}
				}
				else
				{
					echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
				}
				
			}
			else
			{

				if(insert_tables("home_sells(name, price, product_id,quantity,whole_sale,per_p_price,slip_status,discount,vari)","('$qr_proname','$qr_price',$qr_id,1,'$qr_w_price',$qr_price,0,$qr_discount,$qr_data[0])"))
				{
					echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
				}
				else
				{
					echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
					echo "error";
				}	
			}
			
		}
	}
	else
	{
		while($qr_data = mysqli_fetch_row($qr_pro_data))
		{
			$qr_proname = $qr_data[1];	
			$qr_id = $qr_data[0];	
			$qr_price = $qr_data[2];	
			$qr_w_price = $qr_data[10];	
			$qr_discount = $qr_data[14];
			$qr_sell_check_one = sel_table("home_sells","where product_id = $qr_id");
			if(mysqli_num_rows($qr_sell_check_one)>0)
			{
				$query = "UPDATE home_sells
				SET
				quantity = quantity+1,
				price = $qr_price*quantity,
				discount = $qr_discount*quantity
				WHERE 
				product_id = $qr_id";
				if(mysqli_query($con,$query))
				{
					echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
				}
			}
			else
			{

				if(insert_tables("home_sells(name, price, product_id,quantity,whole_sale,per_p_price,slip_status,discount)","('$qr_proname','$qr_price',$qr_id,1,'$qr_w_price',$qr_price,0,$qr_discount)"))
				{
					
					echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
				}
				else
				{
					echo "<script>$('#productlisted').load('homeproductlist.php');</script>";
					echo "error";
				}	
			}
			
		}
	}
}
?>