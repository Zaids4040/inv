<?php require_once('adminheader.php'); ?>

<div class="container">
    <div class="container">
        <div class='row'>
            <div style='width:20%'>
                <input type='text' name='explistserchtxt' onkeyup='search()' id='explistserchtxt' placeholder='Search'/>
            </div>
            <div style='width:20%'>
                <input type='date' name='explistserchddtxt' onchange='search()' id='explistserchddtxt' placeholder='Search By Date'/>
            </div>
        </div>
    </div>
    <div id='explistdv'></div>
</div>

<?php require_once('adminfooter.php'); ?>

<script>
// Global scope mein search function
function search() {
    const searchText = $('#explistserchtxt').val();
    const searchDate = $('#explistserchddtxt').val();
    
    $.ajax({
        url: "explist.php",        // URL to send the request to
        type: "POST",              // Set the method to POST
        data: {
            search: searchText,    // Pass the search text as data
            searchdate: searchDate       // Pass the search date as data
        },
        success: function(response) {
            $('#explistdv').html(response);  // Update the content of #explistdv with the response
        },
        error: function(xhr, status, error) {
            console.log("Error: " + error);
        }
    });

}

// Page load hone par initial load
document.addEventListener('DOMContentLoaded', function() {
    $('#explistdv').load("explist.php");
});
</script>