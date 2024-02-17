<?php
include('../dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $machineId = $_POST['id'];
   // $productId = $_GET['id'];

    // Validate the product ID (you may want to add more validation)
    if (!is_numeric($machineId)) {
        // Handle invalid input, redirect or show an error message
        header('Location: view_products.php');
        exit();
    }

    // Perform the delete operation
    $updatequery = "UPDATE  machines SET status=1 where machine_id= $machineId";
    $updateResult = $con->query($updatequery);

    if ($updateResult) {
        // Redirect to the view_products.php page after successful deletion
        header('Location: machine_details.php');
        exit();
    } else {
        // Handle the case where the deletion fails
        echo "Error deleting product: " . $con->error;
    }
} else {
    // Handle cases where the request method or product ID is not set
    header('Location: machine_details.php');
    exit();
}
$con->close();