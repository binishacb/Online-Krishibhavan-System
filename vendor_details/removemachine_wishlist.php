<?php
session_start();
include('../dbconnection.php');

if (isset($_POST['wishlist_id'])) {
    $wishlist_id = $_POST['wishlist_id'];

    // Implement the logic to update the status to 0 instead of deleting
    $updateCartItemQuery = "UPDATE wishlist SET status = '0' WHERE wishlist_id = '$wishlist_id'";
    $updateCartItemResult = $con->query($updateCartItemQuery);

    if ($updateCartItemResult) {
        // Redirect back to the shopping cart page after successful update
        header("Location: view_wishlist.php");
        exit();
    } else {
        // Handle the case where the update fails, e.g., show an error message
        echo "Error updating item status in the cart.";
    }
} else {
    // Handle the case where cart_id is not provided
    echo "Invalid request.";
}

