<?php
session_start();
include('dbconnection.php');
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] != 'agricultural_officer') {
    header('Location: index.php'); 
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Officer Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Add custom styles here */
    </style>
</head>

<body>
    <?php
    include('navbar/navbar_officer.php');
    ?>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h5>Welcome Officer <?php
                    // if (isset($_SESSION['useremail'])) {
                    //     echo '' . $_SESSION['useremail'];
                    // }
                    ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <img src="images/b2.jpg" class="card-img-top" alt="Image">
                            <div class="card-body">
                                <p class="card-text">Details of the schemes </p>
                                <a href="scheme_verification_ao.php" id="addScheme" class="btn btn-success">View schemes details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text">Details of the schemes </p>
                                <a href="addschemes.php" class="btn btn-success">Add schemes </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
