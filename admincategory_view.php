<?php
require_once('adminheader.php');
?>

<div class="container">
<div class="container">
<div style="width:20%"><input type="text" id="searchselntxt" placeholder="Search By Name" /></div>
</div>
<div id="categorylistdiv"></div>
</div>
<?php
require_once('adminfooter.php');
?>

<script>
    $('#categorylistdiv').load('categorylist.php');
</script>