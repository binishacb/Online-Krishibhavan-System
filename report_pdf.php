<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include('dbconnection.php');
require_once('fpdf186/fpdf.php');

if (!isset($_SESSION['useremail'])) {
    header("Location: login.php"); 
    exit();
}

// Function to generate PDF report
function generatePDFReport($data) {
    // Create new PDF instance
    $pdf = new FPDF();
    $pdf->AddPage('L', array(216, 279));
    //$pdf->AddPage();

    // Set font
    $pdf->SetFont('Arial','B',22);

    // Add title
    $pdf->Cell(0,10,'Scheme Application Report',0,1,'C');
    $pdf->Ln(20);
    $pdf->SetMargins(10, 10, 10);
    // Add table headers
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(50,10,'Scheme Name',1,0,'C');
    $pdf->Cell(40,10,'Applicant Name',1,0,'C');
    $pdf->Cell(40,10,'Address',1,0,'C');
    $pdf->Cell(30,10,'Phone Number',1,0,'C');
    $pdf->Cell(50,10,'Status',1,1,'C');

    // Add table data
    $pdf->SetFont('Arial','',10);
    foreach ($data as $row) {
        $pdf->Cell(50,20,$row['scheme_name'],1,0,'C');
        $pdf->Cell(40,20,$row['name'],1,0,'C');
        $pdf->Cell(40,20,$row['address'],1,0,'C');
        $pdf->Cell(30,20,$row['phone_number'],1,0,'C');
        if ($row['application_status'] == 2) {
            $pdf->Cell(50,20,'Verified by Assistant Officer',1,1,'C');
        } elseif ($row['application_status'] == 3) {
            $pdf->Cell(50,20,'Rejected by Assistant Officer',1,1,'C');
        } elseif ($row['application_status'] == 4) {
            $pdf->Cell(50,20,'Application Approved',1,1,'C');
        } elseif ($row['application_status'] == 5) {
            $pdf->Cell(50,20,'Application Rejected',1,1,'C');
        }
    }

    // Output PDF
    $pdf->Output('report.pdf', 'D');
}

$email = $_SESSION['useremail'];
$officer_query = "SELECT officer.officer_id,officer.krishibhavan_id FROM officer JOIN login ON officer.log_id = login.log_id WHERE login.email = '$email' and officer.designation_id=1";
$officer_result = mysqli_query($con, $officer_query);

$data = array();
if ($officer_result && $officer_row = mysqli_fetch_assoc($officer_result)) {
    $officer_id = $officer_row['officer_id'];
    $k_id = $officer_row['krishibhavan_id'];

    $query = "SELECT sa.*, s.scheme_name FROM scheme_application sa JOIN schemes s ON sa.scheme_id = s.scheme_id WHERE sa.krishibhavan_id='$k_id' AND sa.application_status <> 1";
    $result = $con->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Only include necessary fields in $data
            $data[] = array(
                'scheme_name' => $row['scheme_name'],
                'name' => $row['name'],
                'address' => $row['address'],
                'phone_number' => $row['phone_number'],
                'application_status' => $row['application_status']
            );
        }
    }
}

// Generate PDF report
generatePDFReport($data);
?>
