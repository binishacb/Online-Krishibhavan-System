<?php
session_start();
// Include necessary files
include('dbconnection.php');

// Check if the overall total price is set in the URL
if(isset($_GET['overall_total'])) {
    $overallTotal = $_GET['overall_total'];

    // Display the overall total price
    echo "Overall Total Price: ₹" . $overallTotal;
} else {
    echo "Overall Total Price not provided.";
}
?>
