<?php
include('dbconnection.php');
echo "hi";
if (isset($_POST['payment_id']) && isset($_POST['amount']) && isset($_POST['name']) && isset($_POST['farmer_id']) && isset($_POST['product_id']) && isset($_POST['order_id'])) {
    $paymentId = $_POST['payment_id'];
    $amount = $_POST['amount'];
    $name = $_POST['name'];
    $farmer_id =  $_POST['farmer_id'];
    $product_id =  $_POST['product_id'];
    $order_id = $_POST['order_id'];
// echo  $amount;
// echo  $farmer_id;
// echo $product_id;
// echo $order_id;
//     // Check if the product is available in the required quantity
    $checkAvailability = "SELECT m_quantity FROM machines WHERE machine_id = $product_id";
    $availabilityResult = $con->query($checkAvailability);

    if ($availabilityResult->num_rows > 0) {
        $row = $availabilityResult->fetch_assoc();
        $currentQuantity = $row['m_quantity'];

        // Check if there is enough quantity to sell
        if ($currentQuantity >= 1) { // Assuming each purchase reduces the quantity by 1, adjust if necessary
            // Calculate the new quantity after the purchase
            $newQuantity = $currentQuantity - 1; // Adjust this based on your purchase logic

            // Update the quantity in the database
            $updateQuantityQuery = "UPDATE machines SET quantity = $newQuantity WHERE machine_id = $product_id";
            $con->query($updateQuantityQuery);

            // Insert data into the orders table with payment status as 'success'
            $paymentStatus = 'success'; // Assuming 'success' is the desired payment status
            $sql = "INSERT INTO orders ( machine_id, payment_id, farmer_id, amount, payment_status) 
                    VALUES ( '$product_id', '$paymentId', '$farmer_id', '$amount', 1)";

            if ($con->query($sql)) {
                // Redirect to a success page or do other post-purchase processing
                // header("Location: payment_success.php");
                echo "Purchase successful!";
            } else {
                echo "Error: " . $con->error;
            }
        } else {
            echo "Not enough quantity available.";
        }
    } else {
        echo "Product not found.";
    }
}







