<?php
session_start();
include('dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values from the form
    $farmer_id = $_POST["farmer_id"];
    $machine_id = $_POST["machine_id"];
    // Change 'shipping_id' to 'order_id' as per your form data
    $order_id = $_POST["order_id"];
    $selectedReason = $_POST['selectedReason'];
    $otherReason = $_POST['cancelOtherReason'];

    // Fetch cancel_reason_id from cancel_reasons table based on selected reason
    $cancelReasonQuery = "SELECT cancel_type_id FROM cancel_order_reasons WHERE cancel_reasons = '$selectedReason'";
    $cancelReasonResult = mysqli_query($con, $cancelReasonQuery);
    if ($cancelReasonResult) {
        $cancelReasonRow = mysqli_fetch_assoc($cancelReasonResult);
        $cancel_reason_id = $cancelReasonRow['cancel_type_id'];

        // Insert the data into the cancel_orders table
        $insertQuery = "INSERT INTO cancel_orders (cancel_type_id, farmer_id, machine_id, shipping_id, other_reasons) VALUES ";
        $insertQuery .= "('$cancel_reason_id', '$farmer_id', '$machine_id', '$order_id', '$otherReason')";

        if (mysqli_query($con, $insertQuery)) {
            // Update the cancel_return_status field in the shipping_address table to 'cancelled'
            $updateQuery = "UPDATE shipping_address SET cancel_return_status = 'cancelled' WHERE shipping_id = '$order_id'";
            if (mysqli_query($con, $updateQuery)) {
                // Form submitted successfully
                echo "<script>alert('Order cancellation request sent successfully'); window.location.href = 'vieworderdetails_farmer.php';</script>";
            } else {
                // Error updating cancel_return_status
                echo "<script>alert('Order cancellation failed'); window.location.href = 'vieworderdetails_farmer.php';</script>";
            }
        } else {
            // Error inserting into cancel_orders
            echo "<script>alert('Error cancelling order: " . mysqli_error($con) . "');</script>";
        }
    } else {
        // Error fetching cancel_reason_id
        echo "<script>alert('Error fetching cancel_reason_id: " . mysqli_error($con) . "');</script>";
    }
}
?>
