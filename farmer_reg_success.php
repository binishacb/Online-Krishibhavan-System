<!DOCTYPE html>
<?php
 echo "<script>alert('Registration successful. Please verify your email')</script>";
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success</title>

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css">

    <!-- Your custom styles -->
    <style>
    /* Add any custom styles here if needed */
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-5">
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Registration Successful</h4>
                    <p>Congratulations! Your registration was successful. Please verify your email.</p>
                </div>
                <a class="btn btn-primary" href="login.php">Log In</a>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS (optional, if needed) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>