<?php
ob_start();
session_start();
include('dbconnection.php');

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'farmer') {
    header('Location: index.php');
    exit();
}

if (isset($_POST['machine_id'])) {
    $machine_id = $_POST['machine_id'];

    $farmer_email = $_SESSION['useremail'];
    $getFarmerIdQuery = "SELECT farmer.farmer_id FROM farmer JOIN login ON farmer.log_id = login.log_id WHERE login.email = '$farmer_email'";
    $getFarmerIdResult = $con->query($getFarmerIdQuery);

    if ($getFarmerIdResult->num_rows > 0) {
        $rowFarmerId = $getFarmerIdResult->fetch_assoc();
        $farmer_id = $rowFarmerId['farmer_id'];

        // Check if the machine is already in the wishlist
        $wishlistCheckSql = "SELECT * FROM wishlist WHERE machine_id = '$machine_id' AND farmer_id = '$farmer_id'";
        $wishlistCheckResult = $con->query($wishlistCheckSql);
        if ($wishlistCheckResult->num_rows > 0) {
            $_SESSION['message'] = "Machine already in the wishlist";
        } else {
            // Add to wishlist
            $wishlistInsertSql = "INSERT INTO wishlist (machine_id, farmer_id, status) VALUES ('$machine_id', '$farmer_id', '1')";
            if ($con->query($wishlistInsertSql) === TRUE) {
                $_SESSION['message'] = "Machine added to wishlist";
            } else {
                $_SESSION['message'] = "Error adding to wishlist";
            }
        }
        
       
  
    } else {
        $_SESSION['message'] = "Farmer ID not found";
    }
} else {
    $_SESSION['message'] = "Machine ID not found";
}
