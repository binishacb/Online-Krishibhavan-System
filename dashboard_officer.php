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

   
    <style>
    
    </style>
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

        <!-- Additional Bootstrap elements for the officer's dashboard -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        
                        <p class="card-text">Details of the schemes </p>
                        <a href="scheme_verification.php" id = "addScheme" class="btn btn-success">View schemes details</a>
                    </div>
                    <!-- <div class="card-body">
                        <h5 class="card-title">Tasks</h5>
                        <p class="card-text">Details of the schemes </p>
                        <a href="addschemes.php" class="btn btn-success">Add schemes </a>
                    </div> -->
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <!-- <div class="card-body">
                        <h5 class="card-title">Reports</h5>
                        <p class="card-text">Access and generate reports.</p>
                        <a href="#" class="btn btn-info">View Reports</a>
                    </div> -->
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="alert alert-warning" role="alert">
                Important notice or announcement for officers.
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JavaScript files and your custom scripts here -->

</body>

</html>