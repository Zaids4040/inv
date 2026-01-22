<?php
require_once('adminheader.php');
?>
<div class="container">
<div class="row mb-3">
<div class="col-md-3">
<input type='text' name='expserchtxt' id='expserchtxt' class="form-control" placeholder='Search'/>
</div>
</div>
<div id='vexpence'></div>
</div>
<?php
require_once('adminfooter.php');
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
// Load expense data initially
$('#vexpence').load("viewexp.php");
// Add search functionality
$('#expserchtxt').on('keyup', function() {
const searchText = $(this).val();
$('#vexpence').load("viewexp.php", { search: searchText });
});
});
</script>