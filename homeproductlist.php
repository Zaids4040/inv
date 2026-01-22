<?php
include("functions.php");
session_start();
$cquery ="SELECT count(id) FROM home_sells";
$countquery = mysqli_query($con,$cquery);
$count_home_sells = mysqli_fetch_row($countquery);
$sells_list = sel_table("home_sells","");


if(isset($_GET['unchdel']))
{
	$query = "DELETE FROM home_sells WHERE slip_status = 1 AND replace_status = 0";
	if(mysqli_query($con,$query))
	{}
	else
	{echo "<script>Invalid</script>";}
}
else if(isset($_GET['chall']))
{
	$query = "UPDATE home_sells SET replace_status = 1 WHERE slip_status = 1";
	if(mysqli_query($con,$query))
	{}
	else
	{echo "<script>Invalid</script>";}
}
$sells_list_for_del_btn = sel_table("home_sells","where slip_status =1");

if(mysqli_num_rows($sells_list_for_del_btn) >0)
{
	echo ' 
	<input type="button" onclick="ddel_unch()" value="Delete un-checked recordes" class="btn btn-danger w-100">
	<input type="button" onclick="chall()" value="Check All" class="btn btn-info w-100">
	';

}
while($cart = mysqli_fetch_row($sells_list))
{
	$pp = $cart[4]*$cart[6];
echo '
<div class="col-md-12">
		<table class="center-align">
			<thead>
			<tr>';
			if($cart[7] == 1)
			{
				echo '<th>Replace</th>';
			}
			echo '
			<th>Name</th>
			<th>Quantity</th>
			<th>Price</th>
			</tr>
			</thead>
			<tbody>
			<tr>';
			if($cart[7] == 1)
			{
				echo '<td> <label>
        <input type="checkbox" id="checkss'.$cart[0].'" onclick="checkboxstatus('.$cart[0].')" '; if($cart[8] == 1){echo "checked";} echo '/><span></span>
      </label></td>';
			}
			echo '
			<td  class="center-align">'.$cart[1].'</td>
			<td class="center-align">'.$cart[4].'</td>
			<td class="center-align">'.$pp.'</td>
			</tr>
			</tbody>
		</table>';
		
		if($cart[12] != 0){
			$variation = sel_table("product_vari","where id =".$cart[12]);
			while($vari = mysqli_fetch_row($variation))
			{

				echo '(' . 
    ($vari[4] != "" ? $vari[4] : "") . 
    ($vari[5] != "" ? '-' . $vari[5] : "") . 
    ($vari[6] != "" ? '-' . $vari[6] : "") . 
    ($vari[7] != "" ? '-' . $vari[7] : "") . 
    ')';

			}
		
		}
		
		?>
        <div class="row">
        <?php if($cart[7] != 1){ ?>
        
				<input type="button" onclick="subpro(<?php echo $cart[3]; ?>,'<?php echo $cart[1]; ?>',<?php echo $pp; ?>,<?php echo $cart[4]; ?>,<?php echo $cart[6]; ?>,<?php echo $cart[7]; ?>,<?php echo $cart[12]; ?>)" class="btn  right col s1" style="background-color:transparent;color:#000;box-shadow:none;font-size:24px;font-weight:100" value="-"/>
                
                <?php 
				 }
				 else{ ?>
                 
                <input type="button" onclick="slip_subpro(<?php echo $cart[0]; ?>,'<?php echo $cart[1]; ?>',<?php echo $pp; ?>,<?php echo $cart[4]; ?>,<?php echo $cart[6]; ?>)" class="btn  right col s1" style="background-color:transparent;color:#000;box-shadow:none;font-size:24px;font-weight:100" value="-"/>
                
                <?php } ?>
				<input type="button" onclick="deleteprofl(<?php echo $cart[0]; ?>)" class="btn btn-danger col s10" value="Delete From List">
                 <?php if($cart[7] != 1){ ?>
                 
				<input type="button" onclick="addpro(<?php echo $cart[3]; ?>,'<?php echo $cart[1]; ?>',<?php echo $pp; ?>,<?php echo $cart[4]; ?>,<?php echo $cart[6]; ?>,<?php echo $cart[12]; ?>)" class="btn left col s1"  style="background-color:transparent;color:#000;box-shadow:none;font-size:24px;font-weight:100" value="+" <?php if($cart[7] == 1){ echo "disabled"; } ?> >
                <?php }else{ ?>
                
    				<input type="button" onclick="slip_addpro(<?php echo $cart[0]; ?>,'<?php echo $cart[1]; ?>',<?php echo $pp; ?>,<?php echo $cart[4]; ?>,<?php echo $cart[6]; ?>)" class="btn left col s1"  style="background-color:transparent;color:#000;box-shadow:none;font-size:24px;font-weight:100" value="+" <?php if($cart[4] >= $cart[9]){ echo "disabled"; } ?> >
                    <?php } ?>            
			</div>
        <?php
		echo '	
</div>';
}
?>
<script>
function addpro(id,name,price,quant,pppri,vari)
{
	
	pricee = pppri;
	var stringdata = "qty=1&idhidd="+id+"&namehdd="+name+"&pricehdd="+pricee+"&vari="+vari+"&singlebtn=active";
	$.ajax({
		url:'homesells.php',
		type:'GET',
		data:stringdata,
		cache:false,
		success: function(){
			$("#productlisted").load("homeproductlist.php");
			document.getElementById("shoplistdiv").setAttribute("style","display:block");document.getElementById("discounttxt").value="";
			$('#subtotallist').load("subtotalwork.php");
			$('#amounttakendiv').load("subtotalwork.php?amot=active");
			$('#discount_show').load("discount.php");
			}
		
		});
	
}

function slip_addpro(id,name,price,quant,pppri)
{
	
	pricee = pppri;
	var stringdata = "qty=1&idhidd="+id+"&namehdd="+name+"&pricehdd="+pricee+"&singlebtn=active";
	$.ajax({
		url:'slipload.php',
		type:'GET',
		data:stringdata,
		cache:false,
		success: function(){
			$("#productlisted").load("homeproductlist.php");
			document.getElementById("shoplistdiv").setAttribute("style","display:block");document.getElementById("discounttxt").value="";
			$('#subtotallist').load("subtotalwork.php");
			$('#amounttakendiv').load("subtotalwork.php?amot=active");
			$('#discount_show').load("discount.php");
			}
		
		});
	
}

function deleteprofl(id)
{	
$.ajax({
	url:'del_pro_frm_list.php',
	type:'GET',
	data:'delid='+id,
	cache:false,
	success: function(){
		$("#productlisted").load("homeproductlist.php");
		<?php if($count_home_sells[0] == 1){?>document.getElementById("shoplistdiv").setAttribute("style","display:none");document.getElementById("discounttxt").value="";<?php } ?>
		$('#subtotallist').load("subtotalwork.php");
		$('#amounttakendiv').load("subtotalwork.php?amot=active");
		$('#discount_show').load("discount.php");
}
});
}

function subpro(id,name,price,quant,pppri,repstatus,vari)
{
	pricee = pppri;
	var stringdata = "qty=-1&idhidd="+id+"&namehdd="+name+"&pricehdd="+pricee+"&vari="+vari+"&singlebtn=active&repstatus = "+repstatus;
	$.ajax({
		url:'homesells.php',
		type:'GET',
		data:stringdata,
		cache:false,
		success: function(){
			$("#productlisted").load("homeproductlist.php");
			document.getElementById("shoplistdiv").setAttribute("style","display:block");document.getElementById("discounttxt").value="";
			$('#subtotallist').load("subtotalwork.php");
			$('#amounttakendiv').load("subtotalwork.php?amot=active");
			$('#discount_show').load("discount.php");
			}
		
		});

}

function slip_subpro(id,name,price,quant,pppri,repstatus)
{
	pricee = pppri;
	var stringdata = "qty=-1&idhidd="+id+"&namehdd="+name+"&pricehdd="+pricee+"&singlebtn=active";
	$.ajax({
		url:'slipload.php',
		type:'GET',
		data:stringdata,
		cache:false,
		success: function(){
			$("#productlisted").load("homeproductlist.php");
			document.getElementById("shoplistdiv").setAttribute("style","display:block");document.getElementById("discounttxt").value="";
			$('#subtotallist').load("subtotalwork.php");
			$('#amounttakendiv').load("subtotalwork.php?amot=active");
			$('#discount_show').load("discount.php");
			}
		
		});

}




function checkboxstatus(id)
{
	var checkboxdata = document.getElementById("checkss"+id);
	if(checkboxdata.checked == true)
	{
		$.ajax({
			url:'updaterepstatus.php?data=1&id='+id,
			cache:false,
			data:id,
			success: function(){
				$("#productlisted").load("homeproductlist.php");
			
			$('#subtotallist').load("subtotalwork.php");
			$('#amounttakendiv').load("subtotalwork.php?amot=active");
			$('#discount_show').load("discount.php");
				checkboxdata.checked = true;
				}
			});
	}
	else
	{
		$.ajax({
			url:'updaterepstatus.php?data=0&id='+id,
			cache:false,
			data:id,
			success: function(){
				$("#productlisted").load("homeproductlist.php");
			
			$('#subtotallist').load("subtotalwork.php");
			$('#amounttakendiv').load("subtotalwork.php?amot=active");
			$('#discount_show').load("discount.php");
				checkboxdata.checked = false;
				}
			});
	}
	
}
</script>
<script>
function ddel_unch()
{
	
	$.ajax({
		url:'homeproductlist.php?unchdel=active',
		cache:false,
		data:'active',
		success: function(){
			$("#productlisted").load("homeproductlist.php");
			$('#subtotallist').load("subtotalwork.php");
			$('#amounttakendiv').load("subtotalwork.php?amot=active");
			$('#discount_show').load("discount.php");
			}
		});
		
	
	
}

function chall()
{
	$.ajax({
		url:'homeproductlist.php?chall=active',
		cache:false,
		data:'active',
		success: function(){
			$("#productlisted").load("homeproductlist.php");
			$('#subtotallist').load("subtotalwork.php");
			$('#amounttakendiv').load("subtotalwork.php?amot=active");
			$('#discount_show').load("discount.php");
			}
		});
}
</script>