<?php
session_start();
include('../dbconnection.php');

// if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'vendor') {
//     header('Location: ../index.php');
//     exit();
// }
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
$email = $_SESSION['useremail'];
$vendor_query = "SELECT vendor.vendor_id FROM vendor JOIN login ON vendor.log_id = login.log_id WHERE login.email = '$email'";
$vendor_result = mysqli_query($con, $vendor_query);

if ($vendor_result && $vendor_row = mysqli_fetch_assoc($vendor_result)) {
    $vendor_id = $vendor_row['vendor_id'];
    // SELECT  ROW_NUMBER() OVER (ORDER BY orders.id) AS sl_no,
    // Assuming you have a table named 'orders' with relevant columns and a column 'machine_id'
    $order_query = "SELECT shipping_address.*,  machines.machine_name, farmer.firstname,farmer.lastname,cancel_orders.cancel_date,cancel_orders.cancel_type_id FROM shipping_address
        JOIN machines ON shipping_address.machine_id = machines.machine_id
        JOIN farmer ON shipping_address.farmer_id = farmer.farmer_id
        JOIN cancel_orders ON shipping_address.shipping_id = cancel_orders.shipping_id 
        WHERE machines.vendor_id = '$vendor_id' and shipping_address.cancel_return_status = 'cancelled'";

    $order_result = mysqli_query($con, $order_query);

    if ($order_result && mysqli_num_rows($order_result) > 0) {
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Your Orders</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.1/css/bootstrap.min.css">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.19.0/dist/font/bootstrap-icons.css" rel="stylesheet">
        </head>

        <body>
            <?php include('./navbar_vendor.php'); ?>
            <div class="container mt-3">
                <h2 class="text-center p-2 text-primary">Order cancellation  list</h2>
                <div class="mb-3 position-relative" style="width: 300px; margin-left: auto;">
                
                    <input type="text" class="form-control position-absolute top-0 end-0 border-4" id="searchInput" placeholder="Search by Machine Name">
                </div>
                <br><br>
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Order ID</th>
                            <th>Cancellation Date</th>
                            <th>Customer Address</th>
                            <th>Machine Name</th>
                            <th>Quantity(in no.s)</th>
                            <th>Total Price</th>
                            <th>Payment Status</th>
                            <th>Cancellation reasons</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while ($order_row = mysqli_fetch_assoc($order_result)) {

                            $order_id =  $order_row['order_id'];
                            $cancel_id =$order_row['cancel_type_id'];
                            
                        ?>
                            <tr>
                                <td><?php echo $order_row['order_id']; ?></td>
                                <td><?php echo $order_row['cancel_date']; ?></td>
                                <td><?php echo $order_row['firstname'] . ' ' . $order_row['lastname'] . "<br>" . $order_row['address'] . "<br>Phone no: " . $order_row['phone_no']; ?></td>
                                <td><?php echo $order_row['machine_name']; ?></td>
                                <td><?php echo $order_row['quantity']; ?></td>
                                <td>Rs <?php echo $order_row['total_price']; ?></td>
                                <td>
                                    <?php if ($order_row['status'] == 2) { ?>
                                        <span class="badge bg-success">Paid</span>
                                    <?php } else if ($order_row['status'] == 1) { ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php } ?>
                                </td>
                                
                                <?php


                                    $cancelReasonQuery = "SELECT cancel_reasons  FROM cancel_order_reasons  JOIN cancel_orders ON cancel_order_reasons.cancel_type_id = cancel_orders.cancel_type_id  WHERE cancel_orders.cancel_type_id = $cancel_id";

                                    $cancelReasonResult = mysqli_query($con,$cancelReasonQuery);
                                    $reason = mysqli_fetch_assoc($cancelReasonResult);
                                    ?>
                            
                            <td>
                            <?php echo $reason['cancel_reasons']; ?> 
                              </td>
                                      
                            </tr>
                                    <?php
                                }
                                ?>
                    </tbody>
                </table>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.35.1/dist/apexcharts.min.js"></script>

        </body>

        </html>
<?php
                        
    
}else {
        echo "No orders found.";
    }
} else {
    echo "Error fetching vendor details.";
}

mysqli_close($con);
?> 