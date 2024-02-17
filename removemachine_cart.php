<?php
session_start();
include('dbconnection.php');

if (isset($_POST['cart_item_id'])) {
    $cart_id = $_POST['cart_item_id'];

    // Implement the logic to update the status to 0 instead of deleting
    $updateCartItemQuery = "UPDATE cart SET status = '0', quantity = '0' WHERE cart_item_id = '$cart_id'";
    $updateCartItemResult = $con->query($updateCartItemQuery);

    if ($updateCartItemResult) {
        // Redirect back to the shopping cart page after successful update
        header("Location: cart_details.php");
        exit();
    } else {
        // Handle the case where the update fails, e.g., show an error message
        echo "Error updating item status in the cart.";
    }
} else {
    // Handle the case where cart_id is not provided
    echo "Invalid request.";
}

