<?php
include('../dbconnection.php');

if (
    isset($_POST['payment_id']) &&
    isset($_POST['amount']) &&
    isset($_POST['name']) &&
    isset($_POST['farmer_id']) &&
    isset($_POST['product_id']) &&
    isset($_POST['order_id']) &&
    isset($_POST['quantity']) &&
    isset($_POST['address']) &&
    isset($_POST['rentDate']) &&
    isset($_POST['returnDate'])
) {
    $paymentId = mysqli_real_escape_string($con, $_POST['payment_id']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']);
   
    $farmer_id = $_POST['farmer_id'];
    $product_id = $_POST['product_id'];
    $order_id = $_POST['order_id'];
    $quantity = $_POST['quantity'];
    $address = $_POST['address']; // Get address
    $rentDate = $_POST['rentDate']; // Get rent date
    $returnDate = $_POST['returnDate']; // Get return date
 
        $checkAvailability = "SELECT rp_quantity FROM rent_product WHERE rp_id = '$product_id'";
        $availabilityResult = $con->query($checkAvailability);

        if ($availabilityResult->num_rows > 0) {
            $row = $availabilityResult->fetch_assoc();
            $currentQuantity = $row['rp_quantity'];

            if ($currentQuantity >= $quantity) {
                $newQuantity = $currentQuantity - $quantity;

                $updateQuantityQuery = "UPDATE rent_product SET rp_quantity = $newQuantity WHERE rp_id = '$product_id'";
                if ($con->query($updateQuantityQuery)) {

                 
                    $insertQuery = "INSERT INTO rental_orders (payment_id, total_price, farmer_id,rp_id, rental_order_id, quantity, delivery_address, rental_date, return_date,payment_status)
                    VALUES ('$paymentId', '$amount', '$farmer_id', '$product_id', '$order_id', '$quantity', '$address', '$rentDate', '$returnDate','paid')";
    
                        
                        if ($con->query($insertQuery)) {
                           
                            echo "Purchase successful!";
                        } else {
                            throw new Exception("Error in order insertion: " . $con->error);
                        }
                  
                } else {
                    throw new Exception("Error in quantity update: " . $con->error);
                }
            } else {
                echo "Not enough quantity available.";
            }
       
 } 
}
