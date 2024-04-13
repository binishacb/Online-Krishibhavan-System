<!DOCTYPE html>
<?php
include('dbconnection.php');
session_start(); 
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'farmer') {
    header('Location: index.php'); 
    exit(); 
}
?>
<html>

<head>
    <title>Farmer Dashboard</title>
   
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php
    include('navbar/navbar_farmer.php');
    ?>
    <br>
    <div class="container">
        <div class="card">
            <h5 class="card-header">Welcome <?php
                if (isset($_SESSION['useremail'])) {
                    echo '' . $_SESSION['useremail'];
                }
                ?></h5>
            <div class="card-body">
                <h5 class="card-title">Explore Agriculture</h5>
                <p class="card-text">Dear Farmer, your hard work and dedication are the foundation of our agriculture.
                    Keep nurturing the land, and it will reward you with abundance.</p>
                
            </div>
        </div>

       
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card h-100 d-flex">
            <img src="images/b6.jpg" class="card-img-top" alt="Crops">
            <div class="card-body">
                <h5 class="card-title">Crop Information</h5>
                <p class="card-text">Learn about different crops, their cultivation, and best practices.</p>
                <a href="farmer_add_crop.php" class="btn btn-success">Explore Crops</a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100 d-flex">
            <img src="images/schemes.jpg" class="card-img-top" alt="Government Schemes">
            <div class="card-body">
                <h5 class="card-title">Government Schemes</h5>
                <p class="card-text">Find out about government schemes and benefits for farmers.</p>
                <a href="view_schemes.php" class="btn btn-info">View Schemes</a>
            </div>
        </div>
    </div>
</div>


       
<h3 class="mt-4">Agricultural Machines</h3>
<div class="row mt-4">
    <!-- Latest Machines Card -->
    <div class="col-md-6">
        <div class="card h-100 d-flex">
            <img src="images/machines.jpg" class="card-img-top" alt="Agriculture Tips">
            <div class="card-body">
                <h5 class="card-title">Latest Machines</h5>
                <p class="card-text">Explore the newest agricultural machines for enhanced efficiency in farming.</p>
                <a href="machines_farmer.php" name ="view_machines" id = "view_machines" class="btn btn-primary">View Machines</a>
            </div>
        </div>
    </div>
    
    <!-- Rental Machines Card -->
    <div class="col-md-6">
        <div class="card h-100 d-flex">
            <img src="images/machines.jpg" class="card-img-top" alt="Rent Agricultural Machines">
            <div class="card-body">
                <h5 class="card-title">Rental Machines</h5>
                <p class="card-text">Rent advanced agricultural machines for temporary use in your farming activities.</p>
                <a href="./vendor_details/view_rentalmachines.php" class="btn btn-primary">View Rental Machines </a>
            </div>
        </div>
    </div>
</div>




        
    </div>
<?php
include('footer/footer.php');
?>
</body>

</html>