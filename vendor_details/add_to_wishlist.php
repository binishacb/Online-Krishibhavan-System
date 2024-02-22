<?php
session_start();
include('../dbconnection.php');

header('Location: machinedetails_farmer.php');
exit; // Ensure that no further code is executed after the redirect

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'farmer') {
    header('Location:../index.php');
    exit();
}

if (isset($_GET['machine_id'])) {
    $machine_id = $_GET['machine_id'];
    echo $machine_id;
    echo "success";

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
        echo "Machine is already in your wishlist";
    } else {
        // Add to wishlist
        $wishlistInsertSql = "INSERT INTO wishlist (machine_id, farmer_id,status) VALUES ('$machine_id', '1')";
        if ($con->query($wishlistInsertSql) === TRUE) {
            echo "Machine added to wishlist";
        } else {
            echo "Error adding to wishlist";
        }
    }
} else {
    echo "farmer ID not found";
}
}
else{
   echo "machine id not found";
}
