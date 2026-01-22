<?php
/**
 * PDF Generation Test
 * 
 * This script demonstrates basic PDF generation using FPDF library.
 * It creates a simple PDF document and outputs it to the browser.
 * 
 * @version 1.0
 * @author Pro Developer
 * @date 2023-10-15
 */

// Include the FPDF library
require_once('reportiong/fpdf182/fpdf.php');

// Create a new PDF document instance
$pdf = new FPDF();

// Add a new page to the document
$pdf->AddPage();

// Output the PDF to the browser
// I: send the file inline to the browser
// D: send to the browser and force download
// F: save to a local file
// S: return the document as a string
$pdf->Output('I', 'test.pdf');
?>