<?php
include('dbconnection.php');

if (isset($_GET['officer_id'])) {
    $officer_id = $_GET['officer_id'];

    // Update the officer's status to false
    $sql = "UPDATE officer SET status = 1 WHERE officer_id = $officer_id";
    
    if ($con->query($sql) === TRUE) {
        // Successfully updated the officer's status
        header("Location: admin_add_officer.php"); // Redirect back to the officers management page
    } else {
        // Handle the error if the update fails
        echo "Error updating officer status: " . $con->error;
    }
} else {
    // Handle the case where 'officer_id' is not provided in the URL
    echo "Invalid request.";
}
?>
