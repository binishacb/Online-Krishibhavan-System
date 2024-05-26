<?php
// Include your database connection file
include('../dbconnection.php');

// Check if the user is logged in
session_start();
if (!isset($_SESSION['useremail'])) {
    header('Location: login.php');
    exit();
}

// Retrieve the vendor details based on the login email
$userEmail = $_SESSION['useremail'];
$selectQuery = "SELECT * FROM vendor v INNER JOIN login l ON v.log_id = l.log_id WHERE l.email = '$userEmail'";
$result = mysqli_query($con, $selectQuery);
$vendorDetails = mysqli_fetch_assoc($result);

if (!$vendorDetails) {
    // Redirect to login page if vendor details are not found
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Profile</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
   <?php
   include('navbar_vendor.php');
   ?>
    <div class="container mt-7">
        <div class="card">
            <div class="card-header">
                <h2>Vendor Profile</h2>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> <?php echo $vendorDetails['firstName'] . ' ' . $vendorDetails['lastName']; ?></p>
                <p><strong>Email:</strong> <?php echo $vendorDetails['email']; ?></p>
                <p><strong>Phone Number:</strong> <?php echo $vendorDetails['phone_no']; ?></p>
                <p><strong>Shop Name:</strong> <?php echo $vendorDetails['shopName']; ?></p>
                <p><strong>License Number:</strong> <?php echo $vendorDetails['licence_no']; ?></p>
                <!-- Add more vendor details as needed -->
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS scripts (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
