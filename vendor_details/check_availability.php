<?php
include('../dbconnection.php');

if(isset($_POST['rentDate']) && isset($_POST['machineId'])) {
    $rentDate = $_POST['rentDate'];
    $machineId = $_POST['machineId'];
    
    // Query to check if the machine is available for the selected date
    $availabilityQuery = "SELECT MIN(return_date) AS closest_return_date FROM rented_machines WHERE machine_id = '$machineId' AND return_date > '$rentDate'";
    $availabilityResult = $con->query($availabilityQuery);
    
    if($availabilityResult->num_rows > 0) {
        $availabilityRow = $availabilityResult->fetch_assoc();
        $closestReturnDate = $availabilityRow['closest_return_date'];
        
        if($closestReturnDate) {
            // If machine is available, display the closest return date
            echo "Closest available return date: " . date("Y-m-d", strtotime($closestReturnDate));
        } else {
            // If no available date found, display appropriate message
            echo "No upcoming availability for the selected date.";
        }
    } else {
        // If query fails, display error message
        echo "Error checking availability.";
    }
}
?>
