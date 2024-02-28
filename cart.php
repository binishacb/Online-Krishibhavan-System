<?php
session_start();
include('dbconnection.php');

if (isset($_POST['machine_id']) && isset($_POST['sales_price'])) {
    $machine_id = $_POST['machine_id'];
    $sales_price = $_POST['sales_price'];

   
    $farmer_email = $_SESSION['useremail'];
    $getFarmerIdQuery = "SELECT farmer.farmer_id FROM farmer JOIN login ON farmer.log_id = login.log_id WHERE login.email = '$farmer_email'";
    $getFarmerIdResult = $con->query($getFarmerIdQuery);

    // Check if farmer_id exists
    if ($getFarmerIdResult->num_rows > 0) {
        $rowFarmerId = $getFarmerIdResult->fetch_assoc();
        $farmer_id = $rowFarmerId['farmer_id'];

        // Check if the product is already in the cart for the farmer
        $checkCartSql = "SELECT * FROM cart WHERE farmer_id = '$farmer_id' AND machine_id = '$machine_id'";
        $checkResult = $con->query($checkCartSql);

        if ($checkResult->num_rows > 0) {
            // If the product is already in the cart, set a message in the session
            $_SESSION['cart_message'] = "Product already in the cart!";
        } else {
            // If the product is not in the cart, insert a new record
            $insertCartSql = "INSERT INTO cart (farmer_id, machine_id, quantity, total_price, status) VALUES ('$farmer_id', '$machine_id', '1', '$sales_price', '1')";
            $con->query($insertCartSql);
            // Set a success message in the session
            $_SESSION['cart_message'] = "Product added to the cart successfully!";
        }
    }
}
