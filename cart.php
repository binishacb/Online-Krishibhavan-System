<?php
session_start();
include('dbconnection.php');
    if (isset($_POST['machine_id']) && isset($_POST['quantity']) && isset($_POST['total_price'])) {
        $machine_id = $_POST['machine_id'];
        $machine_qty = $_POST['quantity'];
        $total_price = $_POST['total_price'];

    
        $farmer_email = $_SESSION['useremail'];
        $getFarmerIdQuery ="SELECT farmer.farmer_id FROM farmer JOIN login ON farmer.log_id = login.log_id WHERE login.email = '$farmer_email'";
        $getFarmerIdResult = $con->query($getFarmerIdQuery);
    
        // Check if farmer_id exists
        if ($getFarmerIdResult->num_rows > 0) {
            $rowFarmerId = $getFarmerIdResult->fetch_assoc();
            $farmer_id = $rowFarmerId['farmer_id'];
        // echo $farmer_id;
        // echo $machine_id;
            // Check if the product is already in the cart for the farmer
            $checkCartSql = "SELECT * FROM cart WHERE farmer_id = '$farmer_id' AND machine_id = '$machine_id'";
            $checkResult = $con->query($checkCartSql);
        
            if ($checkResult->num_rows > 0) {
                // If the product is already in the cart, update the quantity
                $updateCartSql = "UPDATE cart SET quantity = quantity + $machine_qty WHERE farmer_id = '$farmer_id' AND machine_id = '$machine_id'";
                $con->query($updateCartSql);
            } else {
                // If the product is not in the cart, insert a new record
                $insertCartSql = "INSERT INTO cart (farmer_id, machine_id, quantity, total_price, status) VALUES ('$farmer_id', '$machine_id', '$machine_qty', '$total_price', '1')";
                $con->query($insertCartSql);
            }
        }
        
        }       

