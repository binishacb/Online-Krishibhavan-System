<?php
session_start();
include('../dbconnection.php');

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'vendor') {
    header('Location: ../index.php');
    exit();
}

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$email = $_SESSION['useremail'];
$vendor_query = "SELECT vendor.vendor_id FROM vendor JOIN login ON vendor.log_id = login.log_id WHERE login.email = '$email'";
$vendor_result = mysqli_query($con, $vendor_query);
if ($vendor_result && $vendor_row = mysqli_fetch_assoc($vendor_result)) {
    $vendor_id = $vendor_row['vendor_id'];   
    $order_query = "SELECT shipping_address.*,  machines.machine_name, farmer.firstname, farmer.lastname FROM shipping_address
        JOIN machines ON shipping_address.machine_id = machines.machine_id
        JOIN farmer ON shipping_address.farmer_id = farmer.farmer_id
        WHERE machines.vendor_id = '$vendor_id'";
    $order_result = mysqli_query($con, $order_query);

    if ($order_result && mysqli_num_rows($order_result) > 0) {
        
        
        $report_data = array();
        while ($order_row = mysqli_fetch_assoc($order_result)) {
            $report_data[] = array(
                'Order ID' => $order_row['order_id'],
                'Order Date' => $order_row['order_date'],
                'Customer Address' => $order_row['firstname'] . ' ' . $order_row['lastname'] . "<br>" . $order_row['address'] . "<br>Phone no: " . $order_row['phone_no'],
                'Machine Name' => $order_row['machine_name'],
                'Quantity' => $order_row['quantity'],
                'Total Price' => 'Rs ' . $order_row['total_price'],
                'Payment Status' => ($order_row['status'] == 2) ? 'Paid' : 'Pending',
                'Order Status' => ($order_row['tracking_status'] == 1) ? 'Under process' : (($order_row['tracking_status'] == 2) ? 'Shipped' : 'Delivered'),
                'Return/Cancelled orders' => ($order_row['cancel_return_status'] == 'cancelled') ? 'Order Cancelled' : (($order_row['cancel_return_status'] == 'returned') ? 'Order Returned' : ''),
            );
        }


        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="orders_report.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, array_keys($report_data[0])); 
        foreach ($report_data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit;
    } else {
        echo "No orders found.";
    }
} else {
    echo "Error fetching vendor details.";
}
mysqli_close($con);