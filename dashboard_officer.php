<!DOCTYPE html>
<?php
session_start();
include('dbconnection.php');
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] != 'assistant_officer') {
    header('Location: index.php'); 
    exit();
}
?>
<html>

<head>
    <title>Officer Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php
    include('navbar/navbar_officer.php');
    ?>
    <br>
    <div class="container">
        <div class="card">
            <h5 class="card-header">Welcome Officer<?php
                // if (isset($_SESSION['useremail'])) {
                //     echo '' . $_SESSION['useremail'];
                // }
                ?></h5>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <img src="images/b2.jpg" class="card-img-top" alt="Image" style="max-height:500px" style="max-width:500px";>
                    <div class="card-body">
                        <p class="card-text">Details of the schemes </p>
                        <a href="scheme_verification.php" id="addScheme" class="btn btn-success">View schemes details</a>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Include Bootstrap JavaScript files and your custom scripts here -->
</body>

</html>
