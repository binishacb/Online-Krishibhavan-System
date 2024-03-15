<?php
session_start();
include('dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve values from the form
    $farmer_id = $_POST["farmer_id"];
    $machine_id = $_POST["machine_id"];
    // Change 'shipping_id' to 'order_id' as per your form data
    $order_id = $_POST["order_id"];
    $selectedReasons = $_POST['selectedReasons'];
    $otherReason = $_POST['cancelOtherReason'];

    // Insert the data into the cancel_orders table
    $insertQuery = "INSERT INTO cancel_orders (cancel_type_id, farmer_id, machine_id, shipping_id, other_reasons) VALUES ";

    $values = [];

    // Check if 'selectedReasons' is set and not empty
    if (!empty($selectedReasons)) {
        // Loop through the selected reasons and add them to the values array
        foreach ($selectedReasons as $reason) {
            // Define otherReason based on the current reason
            $otherReason = ""; // You need to define $otherReason based on your logic

            switch ($reason) {
                case 'changed_mind':
                    $values[] = "(1, $farmer_id, $machine_id, $order_id, '$otherReason')";
                    break;
                case 'not_required_anymore':
                    $values[] = "(2, $farmer_id, $machine_id, $order_id, '$otherReason')";
                    break;
                case 'found_better_price':
                    $values[] = "(3, $farmer_id, $machine_id, $order_id, '$otherReason')";
                    break;
                case 'ordered_wrong_product':
                    $values[] = "(4, $farmer_id, $machine_id, $order_id, '$otherReason')";
                    break;
                default:
                    // Handle any other cases if needed
                    break;
            }
        }
    }

    // Join the values with commas and execute the query
    if (!empty($values)) {
        $insertQuery .= implode(", ", $values);
        if ($con->query($insertQuery)) {
            // Update the cancel_return_status field in the shipping_address table to 'cancelled'
            $updateQuery = "UPDATE shipping_address SET cancel_return_status = 'cancelled' WHERE shipping_id = $order_id";
            if ($con->query($updateQuery)) {
                // Form submitted successfully
                echo "<script>alert('Order cancelled successfully'); window.location.href = 'vieworderdetails_farmer.php';</script>";
            } else {
                // Error updating cancel_return_status
                echo "<script>alert('Error updating cancel_return_status: " . $con->error . "');</script>";
            }
        } else {
            // Error inserting into cancel_orders
            echo "<script>alert('Error cancelling order: " . $con->error . "');</script>";
        }
    } else {
        // No checkboxes selected
        echo "<script>alert('Please select a reason'); window.location.href = 'vieworderdetails_farmer.php';</script>";
    }
}
