<?php
session_start();
include('dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4CAF50;
        }

        p {
            font-size: 18px;
            color: #333;
        }
    </style>
</head>
<body>
   <?php include('navbar/navbar_farmer.php');?>
    <div class="container">
      <center>  <h1>Payment Successful</h1>
        <p>Thank you for your purchase!</p>
    </center>
   <h3><u>Order details</u></h3>

        <div>
            <?php
           
            
            // Assuming you have a variable $orderId that holds the order ID
            $orderId = $_GET['order_id'];

            $fetchShippingDetailsQuery = "SELECT * FROM shipping_address WHERE order_id = '$orderId'";
            $shippingResult = $con->query($fetchShippingDetailsQuery);

            if ($shippingResult->num_rows > 0) {
                $shippingRow = $shippingResult->fetch_assoc();
                $address = $shippingRow['address'];
                // Add more fields as needed

                echo "<p><strong>Shipping Name:</strong> $address</p>";
        }  
         $latestOrderIdQuery = "SELECT MAX(order_id) as latest_order_id FROM shipping_address";
        $latestOrderIdResult = $con->query($latestOrderIdQuery);
        
        if ($latestOrderIdResult && $latestOrderIdRow = $latestOrderIdResult->fetch_assoc()) {
            $latestOrderId = $latestOrderIdRow['latest_order_id'];

            // Query to fetch information from the orders table
            $fetchOrderQuery = "SELECT amount, payment_status, order_date FROM orders WHERE orderid = $latestOrderId";
            $result = $con->query($fetchOrderQuery);

            if ($result && $row = $result->fetch_assoc()) {
                $amount = number_format($row['amount']. 2);
                $orderStatus = ($row['payment_status'] == 1) ? 'Paid' : 'Not Paid';
                $orderDate = date('Y-m-d', strtotime($row['order_date']));

                echo "<p><strong>Payment Amount:INR </strong> $amount</p>";
                echo "<p><strong>Order Status:</strong> $orderStatus</p>";
                echo "<p><strong>Order Date:</strong> $orderDate</p>";

            }
        }
            ?>
        </div>
    </div>
</body>
</html>