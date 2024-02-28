<?php
include('dbconnection.php');


if (
    isset($_POST['payment_id']) &&
    isset($_POST['amount']) &&
    isset($_POST['name']) &&
    isset($_POST['farmer_id']) &&
    isset($_POST['product_id']) &&
    isset($_POST['order_id']) &&
    isset($_POST['quantity'])
) {
    $paymentId = mysqli_real_escape_string($con, $_POST['payment_id']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $farmer_id = $_POST['farmer_id'];
    $product_id = $_POST['product_id'];
    $order_id = $_POST['order_id'];
    $quantity = $_POST['quantity'];
echo $order_id;
 

    
        $fetchAddressIdQuery = "SELECT shipping_id FROM shipping_address WHERE order_id = '$order_id'";
        $addressIdResult = $con->query($fetchAddressIdQuery);

    if ($addressIdResult->num_rows > 0) {
        $row = $addressIdResult->fetch_assoc();
        $shippingAddressId = $row['shipping_id'];
        echo $shippingAddressId;
        $checkAvailability = "SELECT m_quantity FROM machines WHERE machine_id = '$product_id'";
        $availabilityResult = $con->query($checkAvailability);

        if ($availabilityResult->num_rows > 0) {
            $row = $availabilityResult->fetch_assoc();
            $currentQuantity = $row['m_quantity'];

            if ($currentQuantity >= $quantity) {
                $newQuantity = $currentQuantity - $quantity;

                $updateQuantityQuery = "UPDATE machines SET m_quantity = $newQuantity WHERE machine_id = '$product_id'";
                if ($con->query($updateQuantityQuery)) {

                    // Update the status column in shipping_address table
                    $updateStatusQuery = "UPDATE shipping_address SET status = 2 WHERE shipping_id = '$shippingAddressId'";
                    if ($con->query($updateStatusQuery)) {

                        // Insert into the orders table
                        $insertOrderQuery = "INSERT INTO orders ( payment_id, payment_status, shipping_id) 
                                            VALUES ('$paymentId','1', '$shippingAddressId')";
                        
                        if ($con->query($insertOrderQuery)) {
                            $con->commit(); // Commit the transaction
                            echo "Purchase successful!";
                        } else {
                            throw new Exception("Error in order insertion: " . $con->error);
                        }
                    } else {
                        throw new Exception("Error updating status in shipping_address: " . $con->error);
                    }
                } else {
                    throw new Exception("Error in quantity update: " . $con->error);
                }
            } else {
                echo "Not enough quantity available.";
            }
        } else {
            echo "Product not found.";
        }
    }
 } 

