<?php
session_start();
include('dbconnection.php');

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'farmer') {
    header('Location: index.php');
    exit();
}

if (isset($_POST['submit'])) {
    $address = mysqli_real_escape_string($con, $_POST['address']);
   
    function generateRandomString($length) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $randomString = '';

        // Add 3 random letters
        for ($i = 0; $i < 3; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Add 6 random numbers
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $numbers[rand(0, strlen($numbers) - 1)];
        }

        return $randomString;
    }

    $orderId = generateRandomString(9);   
    $insertAddressQuery = "INSERT INTO shipping_address (order_id,farmer_id, address) VALUES ('$orderId', '$address')";
    $insertAddressResult = mysqli_query($con, $insertAddressQuery);

    if ($insertAddressResult) {
       
        header("Location:#?order_id=$orderId");
        exit();
    } else {
        echo "<script>alert('Failed to insert address. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
</head>
<body>

<?php include('navbar/navbar_farmer.php'); ?>

<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center p-2 text-primary">Fill in the details to complete your order</h2>

          
            <h4>Enter your details</h4>
            <form action="" method="POST" accept-charset="utf-8">
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" class="form-control" placeholder="Enter your address" required>
                </div>
                <button type="submit" name="submit" class="btn checkout-btn" style="background-color:#ffd700;padding: 10px 50px; font-size : 18px;">Proceed to buy</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
