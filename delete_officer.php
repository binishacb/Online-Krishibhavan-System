<?php
include('dbconnection.php');

if (isset($_GET['officer_id'])) {
    $officer_id = $_GET['officer_id'];

    $sql = "DELETE FROM officer WHERE officer_id = $officer_id";
    if ($con->query($sql) === TRUE) {
        // Successfully deleted the officer
        header("Location: admin_add_officer.php"); // Redirect back to the officers management page
    } else {
        // Handle the error if the deletion fails
        echo "Error deleting officer: " . $con->error;
    }
} else {
    // Handle the case where 'officer_id' is not provided in the URL
    echo "Invalid request.";
}
?>