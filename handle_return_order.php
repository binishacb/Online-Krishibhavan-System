<?php
session_start();
include('dbconnection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $farmer_id = $_POST['farmer_id'];
    $machine_id = $_POST['machine_id'];
    $shipping_id = $_POST['order_id'];
    $reason = $_POST['return_reason'];
    $other_reasons = $_POST['other_reasons'];

    // Query the return_orders_reasons table to find the correct return_type_id
    $query = "SELECT return_type_id FROM return_orders_reasons WHERE return_reason = '$reason'";
    $result = mysqli_query($con, $query);

    // Check if the query was successful
    if ($result) {
        // Fetch the result
        $row = mysqli_fetch_assoc($result);
        $return_type_id = $row['return_type_id'];

        // Construct the SQL query to insert data into the return_order table
        $sql = "INSERT INTO return_order (return_type_id, farmer_id, machine_id, other_reasons, shipping_id) 
                VALUES ('$return_type_id', '$farmer_id', '$machine_id', '$other_reasons', '$shipping_id')";

        // Execute the query
        if (mysqli_query($con, $sql)) {
            // Update the return_cancel_status field in the shipping_address table
            $update_query = "UPDATE shipping_address SET cancel_return_status = 'returned' WHERE shipping_id = '$shipping_id'";
            if (mysqli_query($con, $update_query)) {
                // Alert message and redirection
                echo "<script>alert('Order returned successfully .'); window.location.href = 'vieworderdetails_farmer.php';</script>";
            } else {
                echo "<script>alert('Error updating cancel_return_status: " . mysqli_error($con) . "'); window.location.href = 'vieworderdetails_farmer.php';</script>";
            }
        } else {
            echo "<script>alert('Error inserting return order: " . mysqli_error($con) . "'); window.location.href = 'vieworderdetails_farmer.php';</script>";
        }
    } else {
        echo "<script>alert('Error retrieving return type ID: " . mysqli_error($con) . "'); window.location.href = 'vieworderdetails_farmer.php';</script>";
    }

    // Close the connection
    mysqli_close($con);
} else {
    echo "Invalid request.";
}
?>
