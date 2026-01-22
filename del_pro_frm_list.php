<?php
session_start();
include('functions.php');
if(isset($_GET['delid']))
{
	$id_del = $_GET['delid'];
	if(delete_table("home_sells","$id_del"))
	{echo "<script>window.location = 'home_page.php'</script>";}
	else{echo "error";}
}
?>