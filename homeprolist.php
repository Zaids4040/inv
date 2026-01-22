<?php
session_start();
include("functions.php");
?>
  <div class="">
      	<div class="container-flude">
            <div class="row">
                <!--liter vale box ko display none kia hua h agr show krna ho to display none hata dena -->
                <div class="col-lg-3" style="display:none">
                    <div class="card">
                    
                        <div class="card-content">
                            <center> <span class="card-title activator grey-text text-darken-4"><p>Liter Calculator</p></span></center>
                            <?php
                            $row_query = sel_table("liter","");
                            if(mysqli_num_rows($row_query) > 0)
                            {
                            $row = mysqli_fetch_row($row_query);
                            ?>
                            <form action="home_litercrude.php" method="post">
                                <div class="input-field">
                                    <input type="number" <?php if(mysqli_num_rows($row_query) > 0){echo "value='1'";}else{} ?> id="lit_num_borrow" name="liter_borrowtxt"/>
                                    <label for="lit_num_borrow">Liter</label>
                                </div>
                                <div class="input-field">
                                    <input type="number" <?php if(mysqli_num_rows($row_query) > 0){echo "value='".$row[2]/$row[1]."'";}else{} ?> id="lit_amotxt" name="liter_amounttxtt"/>
                                    <input type="hidden" id="hidlit" name="hidlit"/> 
                                    <label for="lit_amotxt">Amount</label>
                                </div>
                                
                            
                                <div class="qty">
                                    <table>
                                        <tr>
                                            <td><span class="minus_liter minus bg-dark minspan">-</span></td>
                                            <td><input type="number" class="count_liter countinput" id="qtyliter" name="qtyliter" value="1"></td>
                                            
                                            <td><span class="plus_liter plus bg-dark minspan">+</span></td>
                                        </tr>
                                    </table>
                                </div>
                                <center><label id="totalit"></label></center>
                                <div class="input-field" style="margin-top:-10px;">
                                    <div class="row">
                                        <div class="col s6">
                                            <input type="number" placeholder="Amount Taken" id="lit_change_txt" name="lit_change_txt"/>
                                        </div>
                                        <div class="col s6">
                                            <input type="submit" value="Check Out" name="literbtn" class="btn btn-success" style="width:100%;" >
                                        </div>
                                    </div>
                                </div>
                                <center><b><label id="totalitchange"></label></b></center>
                            </form>
                            <script>
                                var liter = document.getElementById("lit_num_borrow");
                                var lit_amo = document.getElementById("lit_amotxt");
                                var lit_change_txt = document.getElementById("lit_change_txt");
                                var lit_quant = document.getElementById("qtyliter");
                                
                                
                                liter.addEventListener("keyup",function(){
                                document.getElementById("lit_amotxt").setAttribute("readonly","readonly");							
                                var finallitamount = liter.value * <?php echo $row[2]/$row[1]; ?>;
                                document.getElementById("lit_amotxt").setAttribute("value",finallitamount);
                                document.getElementById("totalit").innerHTML = "Total : "+finallitamount;
                                
                                });
                                
                                liter.addEventListener("change",function(){
                                document.getElementById("lit_amotxt").setAttribute("readonly","readonly");	
                                var finallitamount = liter.value * <?php echo $row[2]/$row[1]; ?>;
                                document.getElementById("lit_amotxt").setAttribute("value",finallitamount);
                                document.getElementById("totalit").innerHTML = "Total : "+finallitamount;
                                });
                                
                                
                                lit_amo.addEventListener("keyup",function(){
                                document.getElementById("lit_num_borrow").setAttribute("readonly","readonly");	
                                var fianllit = lit_amo.value * <?php echo $row[1]/$row[2]; ?>;
                                var quantxt = document.getElementById("qtyliter");
                                document.getElementById("lit_num_borrow").setAttribute("value",fianllit);
                                document.getElementById("totalit").innerHTML = "Total : "+lit_amo.value * quantxt.value;
                                });
                                $(document).on('click','.plus_liter',function(){
                                    $('.count_liter').val(parseInt($('.count_liter').val()) + 1 );
                                    document.getElementById("totalit").innerHTML = "Total : "+lit_amo.value * $('.count_liter').val();
                                });
                                $(document).on('click','.minus_liter',function(){
                                    $('.count_liter').val(parseInt($('.count_liter').val()) - 1 );
                                        document.getElementById("totalit").innerHTML = "Total : "+lit_amo.value * $('.count_liter').val();
                                    });
                            </script>
                            <?php
                            }
                            else{}
                            ?>
                            
                        </div>
                    </div>
                </div>
                <?php
                if(isset($_GET['data']) && strlen($_GET['data']) > 1)
                {
                   
                    $dataa = $_GET['data'];
                    $products_data = sel_table("products","where product_name like '%$dataa%' ORDER BY id LIMIT 200");	
                }
                else
                {
                    
                    $products_data = sel_table("products","ORDER BY id LIMIT 200");
                    
                }
                while($products = mysqli_fetch_row($products_data))
                {
                    
                ?>
                    <div class="col-lg-3">
                        <form id="productaddform">
                            <div class="card">
                                <div class="card-image waves-effect waves-block waves-light">
                                <!--image ko display none kia hua h agr image ko lagana h to display none hata dena -->
                                <input type="button" onClick="productadd(<?php echo $products[0]; ?>,'<?php echo $products[1]; ?>',<?php echo $products[2]; ?>,'singlebtn')" name="singlebtn" id="singlebtn" style="display:none;height:150px;width:100%;background-position: center;background-repeat: no-repeat; background-size: contain;border: none; background-image:url(<?php echo $products[11]; ?>);">

                                </div>
                                <div class="card-content">
                                <span onClick="productadd(<?php echo $products[0]; ?>,'<?php echo $products[1]; ?>',<?php echo $products[2]; ?>,'singlebtn')" class="card-title activator grey-text text-darken-4"><p style=" overflow: hidden;white-space: nowrap;text-overflow: ellipsis;"><?php echo $products[1]."<br> RS.".$products[2]; if($products[3] <= 0){echo "<b class='pull-right' style='color:red;'>Out Of Stock</b>";} ?></p></span>
                                <div class="row">
                                    <div class="col-md-6">
                                    
                                    </div>
                                    <!--more details ko display none kia hua h agr show krna ho to display none hata dena-->
                                    <DIV class="col-md-6" style="display:none">
                                        <span class="card-title activator grey-text text-darken-4"><i class="material-icons right">more_vert</i></span>
                                    </DIV>
                                </div>
                                <div class="qty">
                                    <table>
                                    <tr>
                                        <td><span class="minus<?php echo $products[0]; ?> minus bg-dark minspan">-</span></td>
                                        <td><input type="number" class="count<?php echo $products[0]; ?> count countinput" name="qty" id="qty<?php echo $products[0]; ?>" value="1"></td>
                                        
                                        <td><span class="plus<?php echo $products[0]; ?> plus bg-dark minspan">+</span></td>
                                        <td><button type="button" onClick="productadd(<?php echo $products[0]; ?>,'<?php echo $products[1]; ?>',<?php echo $products[2]; ?>,'cartonbtn')"  style="border:none;background-color:transparent !important;"><i class="material-icons right">inbox</i></button>
                                        </td>
                                    </tr>
                                    </table>
                                    </div>
                                <div class="row">
                                <input type="hidden" value="<?php echo $products[0]; ?>" name="idhidd" id="idhidd"/>
                                <input type="hidden" value="<?php echo $products[1]; ?>" name="namehdd" id="namehdd"/>
                                <input type="hidden" value="<?php echo $products[2]; ?>" name="pricehdd" id="pricehdd"/>     	
                                </div>
                                </div>
                            
                                <div class="card-reveal">
                                <span class="card-title grey-text text-darken-4"><?php echo $products[1]." RS.".$products[2]; ?><i class="material-icons right">close</i></span>
                                <p>
                                    <?php
                                    echo "Name: ".$products[1]."<br> RS: ".$products[2]."<br> Liters: ".$products[4]."<br> Stock: ".$products[3]."<br> Quantity in Carton: ".$products[5]."<br>";
                                    if($products[6] == 0)
                                    {
                                        echo "Carton Price: ".$products[2]*$products[5];								
                                    }
                                    else
                                    {
                                        echo "Carton Price: ".$products[6];
                                    }
                                    ?>
                                </p>
                                
                                <div class="qty">
                                    <table>
                                    <tr>
                                        <td><span class="minus<?php echo $products[0]; ?> minus bg-dark minspan">-</span></td>
                                        <td><input type="number" class="count<?php echo $products[0]; ?> count countinput" name="qty" value="1"></td>
                                        
                                        <td><span class="plus<?php echo $products[0]; ?> plus bg-dark minspan">+</span></td>
                                        <td><button type="button" onClick="productadd(<?php echo $products[0]; ?>,'<?php echo $products[1]; ?>',<?php echo $products[2]; ?>,'cartonbtn')"  style="border:none;background-color:transparent !important;"><i class="material-icons right">inbox</i></button></td>
                                    </tr>
                                    </table>
                                    </div>
                                <div class="row">
                                    <div class="col"><p> <input type="button" onClick="productadd(<?php echo $products[0]; ?>,'<?php echo $products[1]; ?>',<?php echo $products[2]; ?>,'singlebtn')" name="singlebtn" id="singlebtn" class="btn btn-success" style="width:100%;" value="Single" <?php if($products[3] <= 0){ echo "disabled"; } ?>></p></div>
                                </div>
                                
                                </div>
                            </div>
                        </form>
                    </div>
                
                    <?php
                }
                    ?>
                    <div class="col s3"></div>
                    <div class="col s3"></div>
                    <div class="col s3"></div>
            </div>			      
      	</div>
      </div>