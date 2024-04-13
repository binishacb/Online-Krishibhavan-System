<?php
session_start();
include('dbconnection.php');
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'farmer') {
    header('Location: index.php');
    exit();
}

$email = $_SESSION['useremail'];
$getFarmerIdQuery = "SELECT farmer_id FROM farmer JOIN login ON farmer.log_id = login.log_id WHERE login.email = '$email'";
$res = $con->query($getFarmerIdQuery);

if ($res) {
    $row = $res->fetch_assoc();
    $farmerID = $row['farmer_id'];
} else {
    echo "<script>alert('Farmer ID not found')</script>";
}




$getSchemeApplicationsQuery = "SELECT sa.*,s.*
FROM scheme_application as sa JOIN schemes as s ON sa.scheme_id = s.scheme_id
WHERE farmer_id = $farmerID";

$schemeApplicationsResult = $con->query($getSchemeApplicationsQuery);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Applied schemes</title>
</head>

<body>

    <div class="container">
        <h1 class="mt-4 mb-4">Schemes Applied by Farmer</h1>
        <div class="row">
            <?php while ($row = $schemeApplicationsResult->fetch_assoc()) : 
                 $isActive = $row['application_status'] != 4 && $row['application_status'] != 5;
            ?>
                <div class="col-md-6">
                    <div class="card scheme-card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['scheme_name']; ?></h5>
                            <p class="card-text">Application ID: <?php echo $row['application_id']; ?></p>
                            <p class="card-text">Status: 
                                <?php 
                                if ($row['application_status'] == 1) {
                                    echo "Approval Pending";
                                } elseif ($row['application_status'] == 3) {
                                    echo "<span class='text-danger'>Application Rejected</span>";
                                } elseif ($row['application_status'] == 4) {
                                    echo "Application Approved";
                                } elseif ($row['application_status'] == 5) {
                                    echo "<span class='text-danger'>Application Rejected</span>";
                                }
                                ?>
                            </p>
                            <p class="card-text">Applied Date: <?php echo $row['applied_date']; ?></p>
                            <p class="card-text">End Date: <?php echo $row['end_date']; ?></p>
                            <?php if ($isActive) : ?>
                                <a href="#" class="btn btn-primary">Edit</a>
                            <?php else : ?>
                                <button class="btn btn-secondary inactive-btn" disabled>Edit</button>
                                <?php endif; ?>
                         
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>

</html>