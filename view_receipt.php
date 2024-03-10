
<?php
session_start();
include('dbconnection.php');

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'farmer') {
    header('Location: index.php'); 
    exit(); 
}
$farmer_email = $_SESSION['useremail'];
$farmerIdquery = "SELECT f.farmer_id  FROM farmer f  INNER JOIN login l ON f.log_id = l.log_id  WHERE l.email = '$farmer_email'";
$result = $con->query($farmerIdquery);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $farmerId = $row['farmer_id'];
$order_id = $_POST['order_id'];
$machine_id = $_POST['machine_id'];

// Fetch data from the shipping_address table based on order_id
$sql = "SELECT sa.*,m.machine_name FROM shipping_address as sa JOIN machines as m ON sa.machine_id = m.machine_id WHERE shipping_id = $order_id";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    $shipping_data = $result->fetch_assoc();
} 
else{
    echo "<script>alert('No orders');</script>";
}
}
else{
    echo "<script>alert('Invalid farmer id');</script>";
}

$con->close();
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php
include('navbar/navbar_machines.php');
?>
<br><br><br>
<div class="container">

    <div class="card mt-4">
        <div class="card-header">
            <div class="d-print-none">
            <a class="btn btn-primary" onclick="window.print()">Print Invoice</a>

            </div>
            <h2 class="text-center">Invoice</h2>
        </div>
        <div class="card-body">

            <div class="row">
                <!-- Company Address -->
                <div class="col-md-6">
                    <p><strong>GST No:</strong> GST547368728799</p>
                    <p><strong>Company Address:</strong></p>
                    <p>Krishibhavan</p>
                    <p>123 Main Street,</p>
                    <p>Kerala, India</p>
                </div>
            
                <div class="col-md-6">
                    <p><strong>Invoice ID:</strong> 26729479249</p>
                    <p><strong>Order Date:</strong> <?php echo $shipping_data['order_date']; ?></p>
                    <p><strong>Order ID:</strong> <?php echo $shipping_data['order_id']; ?></p>
                    <p><strong>Farmer e-mail:</strong> <?php echo $farmer_email; ?></p>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> <?php echo $shipping_data['machine_name']; ?></td>
                        <td> <?php echo $shipping_data['total_price']; ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="text-right">
                <p>Total Amount: INR <?php echo $shipping_data['total_price']; ?> <br>Inclusive of all taxes</p>
            </div>

            <!-- <h3>Payment Details</h3>
            <p>Payment Status: Paid</p>
            <p>Payment Amount: {{ payment.payment_amount }}</p>
            <p>Payment Date: {{ payment.payment_datetime }}</p> -->

        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
