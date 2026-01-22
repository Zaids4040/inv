<?php 
session_start();
include("functions.php");

if(isset($_POST['deopbtn']))
{
	if(isset($_POST['deponicch']) || isset($_POST['depopaymentch']))
	{
	$id = $_POST['bottelselect'];
	if($id == "-1")
	{
		$_SESSION['invalid'] = 1;
		echo "<script>window.location = 'home_page.php'</script>";
	}
	else
	{
		$bottet_query = sel_table("bottels","where id = $id");
		if(mysqli_num_rows($bottet_query)>0)
		{
			$data = mysqli_fetch_row($bottet_query);
			$name = $data[1];
			$tabel_quantity = $data[4];
		} 
		$quan = $_POST['qtybottel'];
		$checkquan = mysqli_fetch_row($bottet_query);
		if($tabel_quantity  < $quan)
		{
			$_SESSION['botnotavailable'] = 1;
			echo "<script>window.location = 'home_page.php'</script>";
		}
		else
		{
		if(isset($_POST['deponicch'])){$deponic = 1;}else{$deponic = 0;}
		if(isset($_POST['depopaymentch'])){$depopay = 1;}else{$depopay = 0;}
			for($i = 1 ; $i <= $quan ; $i++)
			{
				if(insert_tables("deposit_bottels(name, quantity, depo_nic, depo_payment, bottel_id)","('$name',1,$deponic,$depopay,$id)"))
				{
					if(update_bottel_custom($id,"-1"))
					{
						$_SESSION['botupcom'] = 1;
						echo "<script>window.location = 'home_page.php'</script>";
					}
				}
			}
		}
	}
	}
	else
	{
			$_SESSION['invalid'] = 1;
			echo "<script>window.location = 'home_page.php'</script>";
	}
	
	
}
else if(isset($_POST['recvebtn']))
{
	if(isset($_POST['deponicch']) || isset($_POST['depopaymentch']))
	{
	$check = sel_table("deposit_bottels","");
	if(mysqli_num_rows($check) < 1)
	{
		$_SESSION['botnocom'] = 1;
		echo "<script>window.location = 'home_page.php'</script>";
	}
	else
	{
		$id = $_POST['bottelselect'];
		$bottet_query = sel_table("bottels","where id = $id");
		if(mysqli_num_rows($bottet_query)>0)
		{
			$data = mysqli_fetch_row($bottet_query);
			$name = $data[1];
		} 
		$quan = $_POST['qtybottel'];
		$checkquan = mysqli_fetch_row($bottet_query);
		if(isset($_POST['deponicch']) && !isset($_POST['depopaymentch']))
		{
			if(custom_select("COUNT(*)","deposit_bottels"," WHERE depo_nic = 1 and bottel_id = $id") >= $quan)
			{
			custom_delete("deposit_bottels","depo_nic =1 and bottel_id = $id LIMIT $quan");
			update_bottel_custom($id,"+$quan");
			$_SESSION['botaddcom'] = 1;
			echo "<script>window.location = 'home_page.php'</script>";
			}
			else
			{
				$_SESSION['botnocomv2'] = 1;
				echo "<script>window.location = 'home_page.php'</script>";
			}
		}
		else if(isset($_POST['depopaymentch']) && !isset($_POST['deponicch']))
		{
			if(custom_select("COUNT(*)","deposit_bottels"," WHERE depo_payment = 1 and bottel_id = $id") >= $quan)
			{
			custom_delete("deposit_bottels","depo_payment =1 and bottel_id = $id LIMIT $quan");
			update_bottel_custom($id,"+$quan");
			$_SESSION['botaddcom'] = 1;
			echo "<script>window.location = 'home_page.php'</script>";
			}
			else
			{
				$_SESSION['botnocomv2'] = 1;
				echo "<script>window.location = 'home_page.php'</script>";
			}
		}
		else if(isset($_POST['depopaymentch']) && isset($_POST['deponicch']))
		{
			if(custom_select("COUNT(*)","deposit_bottels"," WHERE depo_payment = 1 and depo_nic =1 and bottel_id = $id") >= $quan)
			{
			custom_delete("deposit_bottels","depo_payment = 1 and depo_nic =1 and bottel_id = $id LIMIT $quan");
			update_bottel_custom($id,"+$quan");
			$_SESSION['botaddcom'] = 1;
			echo "<script>window.location = 'home_page.php'</script>";
			}
			else
			{
				$_SESSION['botnocomv2'] = 1;
				echo "<script>window.location = 'home_page.php'</script>";
			}
		}
		else
		{
			$_SESSION['invalid'] = 1;
			echo "<script>window.location = 'home_page.php'</script>";
		}
	}
	}
	else
	{
			$_SESSION['invalid'] = 1;
			echo "<script>window.location = 'home_page.php'</script>";
	}
	
}
?>