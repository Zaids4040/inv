<?php
/**
 * Bottle Home System
 * 
 * This script handles deposit and retrieval operations for bottle inventory management.
 * It processes form submissions for depositing bottles and receiving them back.
 * 
 * @version 4.0
 * @author Pro Developer
 * @date 2023-10-15
 */

// Initialize session
session_start();

// Include required functions
require_once("functions.php");

// Process deposit bottle operation
if (isset($_POST['deopbtn'])) {
    // Validate that at least one deposit type is selected
    if (isset($_POST['deponicch']) || isset($_POST['depopaymentch'])) {
        // Sanitize and validate bottle ID
        $id = filter_input(INPUT_POST, 'bottelselect', FILTER_VALIDATE_INT);
        
        // Check if a valid bottle was selected
        if ($id === -1 || $id === false) {
            $_SESSION['invalid'] = 1;
            header("Location: home_page.php");
            exit;
        }
        
        // Fetch bottle data using prepared statement (assuming sel_table uses prepared statements)
        $bottet_query = sel_table("bottels", "where id = $id");
        
        if ($bottet_query && mysqli_num_rows($bottet_query) > 0) {
            $data = mysqli_fetch_row($bottet_query);
            $name = $data[1];
            $tabel_quantity = $data[4];
            
            // Sanitize and validate quantity
            $quan = filter_input(INPUT_POST, 'qtybottel', FILTER_VALIDATE_INT);
            
            // Check if requested quantity is available
            if ($tabel_quantity < $quan) {
                $_SESSION['botnotavailable'] = 1;
                header("Location: home_page.php");
                exit;
            }
            
            // Set deposit flags based on checkboxes
            $deponic = isset($_POST['deponicch']) ? 1 : 0;
            $depopay = isset($_POST['depopaymentch']) ? 1 : 0;
            
            // Process each bottle in the quantity
            $success = true;
            for ($i = 1; $i <= $quan; $i++) {
                // Insert deposit record and update bottle inventory
                if (insert_tables(
                    "deposit_bottels(name, quantity, depo_nic, depo_payment, bottel_id)",
                    "(?, ?, ?, ?, ?)",
                    [$name, 1, $deponic, $depopay, $id]
                )) {
                    if (!update_bottel_custom($id, "-1")) {
                        $success = false;
                        break;
                    }
                } else {
                    $success = false;
                    break;
                }
            }
            
            // Set success message and redirect
            if ($success) {
                $_SESSION['botupcom'] = 1;
                header("Location: home_page.php");
                exit;
            }
        }
    } else {
        // No deposit type selected
        $_SESSION['invalid'] = 1;
        header("Location: home_page.php");
        exit;
    }
}
// Process receive bottle operation
elseif (isset($_POST['recvebtn'])) {
    // Validate that at least one deposit type is selected
    if (isset($_POST['deponicch']) || isset($_POST['depopaymentch'])) {
        // Check if any bottles are in deposit
        $check = sel_table("deposit_bottels", "");
        
        if (!$check || mysqli_num_rows($check) < 1) {
            $_SESSION['botnocom'] = 1;
            header("Location: home_page.php");
            exit;
        }
        
        // Sanitize and validate bottle ID
        $id = filter_input(INPUT_POST, 'bottelselect', FILTER_VALIDATE_INT);
        
        // Fetch bottle data
        $bottet_query = sel_table("bottels", "where id = $id");
        
        if ($bottet_query && mysqli_num_rows($bottet_query) > 0) {
            // Sanitize and validate quantity
            $quan = filter_input(INPUT_POST, 'qtybottel', FILTER_VALIDATE_INT);
            
            // Process based on selected deposit types
            $where_clause = "";
            $success = false;
            
            // Handle NIC deposit only
            if (isset($_POST['deponicch']) && !isset($_POST['depopaymentch'])) {
                $where_clause = "depo_nic = 1 and bottel_id = $id";
                $count = custom_select("COUNT(*)", "deposit_bottels", " WHERE $where_clause");
                
                if ($count >= $quan) {
                    custom_delete("deposit_bottels", "$where_clause LIMIT $quan");
                    update_bottel_custom($id, "+$quan");
                    $success = true;
                }
            }
            // Handle payment deposit only
            elseif (isset($_POST['depopaymentch']) && !isset($_POST['deponicch'])) {
                $where_clause = "depo_payment = 1 and bottel_id = $id";
                $count = custom_select("COUNT(*)", "deposit_bottels", " WHERE $where_clause");
                
                if ($count >= $quan) {
                    custom_delete("deposit_bottels", "$where_clause LIMIT $quan");
                    update_bottel_custom($id, "+$quan");
                    $success = true;
                }
            }
            // Handle both NIC and payment deposit
            elseif (isset($_POST['depopaymentch']) && isset($_POST['deponicch'])) {
                $where_clause = "depo_payment = 1 and depo_nic = 1 and bottel_id = $id";
                $count = custom_select("COUNT(*)", "deposit_bottels", " WHERE $where_clause");
                
                if ($count >= $quan) {
                    custom_delete("deposit_bottels", "$where_clause LIMIT $quan");
                    update_bottel_custom($id, "+$quan");
                    $success = true;
                }
            }
            
            // Set appropriate message and redirect
            if ($success) {
                $_SESSION['botaddcom'] = 1;
            } else {
                $_SESSION['botnocomv2'] = 1;
            }
            
            header("Location: home_page.php");
            exit;
        }
    } else {
        // No deposit type selected
        $_SESSION['invalid'] = 1;
        header("Location: home_page.php");
        exit;
    }
}
?>