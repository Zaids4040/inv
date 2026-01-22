<?php
session_start();
include("functions.php");

if(isset($_POST['literbtn']))
{
	$liter = $_POST['liter_borrowtxt'];
	$amopunt = $_POST['liter_amounttxtt'];
	$quantity = $_POST['qtyliter'];
	if(insert_tables("liter_sell(liter, price, quantity, inserted_date)","($liter,$amopunt,$quantity,CURRENT_DATE)"))
	{
		$_SESSION['home_liter'] = 1;
		echo "<script>window.location='home_page.php'</script>";
	}
	
}
?>