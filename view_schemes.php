<?php
session_start();
include('dbconnection.php');
if (!isset($_SESSION['useremail'])) {
    header('Location: index.php'); // Redirect to index.php
    exit(); // Stop further execution of the current script
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    /* Additional CSS styles */
    .scheme-box {
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        margin-bottom: 20px;
    }
    .scheme-box .btn-more-details {
    position: absolute;
    bottom: 18px; /* Adjust the bottom position as needed */
    left: 50%; /* Center the button horizontally */
    transform: translateX(-50%); /* Adjust for centering */
    background-color: green;
}
    .scheme-box a {
        margin-top: 10px;
        padding: 5px 10px;
        display: inline-block;
    }
    </style>
</head>

<body>
    <?php
    include('navbar/navbar_farmer.php');
    echo '<br><br><br><br>';
    $farmer_email = $_SESSION['useremail'];
    $getFarmerIdQuery ="SELECT farmer.farmer_id FROM farmer JOIN login ON farmer.log_id = login.log_id WHERE login.email = '$farmer_email'";
    $getFarmerIdResult = $con->query($getFarmerIdQuery);

    // Check if farmer_id exists
    if ($getFarmerIdResult->num_rows > 0) {
        $rowFarmerId = $getFarmerIdResult->fetch_assoc();
        $farmer_id = $rowFarmerId['farmer_id'];



    $sql = "SELECT scheme_id, scheme_name, acres, crop, end_date FROM schemes  WHERE end_date >= CURDATE()"; 
    $result = $con->query($sql);

    // Check if there are schemes to display
    if ($result->num_rows > 0) {
        echo '<div class="container"><div class="row">';
        while ($row = $result->fetch_assoc()) {
            $schemeID = $row['scheme_id'];
            $schemeName = $row['scheme_name'];
            $acres = $row['acres'];
            $crop = $row['crop'];
            $endDate = strtotime($row['end_date']);
            
            // Calculate the difference in days between the current date and the end date
            $currentDate = time();
            $daysUntilEndDate = round(($endDate - $currentDate) / (60 * 60 * 24));

            // Check if the end date is nearly 5 days away
            $endDateColor = ($daysUntilEndDate <= 5) ? 'style="color: red;"' : '';
           

            // Check if crop and acres exist in the farmers_crops table
            $cropExistsQuery = "SELECT * FROM farmers_crops WHERE farmer_email = '$farmer_email' AND acres >= $acres AND crop = '$crop'";
            $cropExistsResult = $con->query($cropExistsQuery);

            $schemeAppliedQuery = "SELECT * FROM scheme_application WHERE scheme_id = $schemeID AND farmer_id = $farmer_id";
            $schemeAppliedResult = $con->query($schemeAppliedQuery);
            $schemeApplied = ($schemeAppliedResult->num_rows > 0);
            
            echo '<div class="col-md-4">';
            echo '<div class="card scheme-box">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $schemeName . '</h5>';
            if ($daysUntilEndDate <= 5 && $row['end_date'] >= date('Y-m-d')) {
                echo '<span class="badge badge-danger">Expires Soon</span>';
            }
            echo '<p class="card-text" ' . $endDateColor . '>End Date: ' . date('Y-m-d', $endDate) . '</p>';
            // Check if the scheme is still active
            if ($row['end_date'] >= date('Y-m-d')) {
                echo '<div class="d-flex justify-content-between align-items-center">';
                if ($schemeApplied) {
                    echo '<button class="btn btn-danger" disabled>Applied</button>';
                } elseif ($cropExistsResult->num_rows > 0) {
                    // Crop and acres exist, display the "Apply" button
                    echo '<a href="apply_scheme.php?scheme_id=' . urlencode($schemeID) . '" name="apply" class="btn btn-primary">Apply</a>';
                } else {
                    // Crop and acres do not exist, display a disabled button
                    echo '<button class="btn btn-secondary" disabled>Not Eligible</button>';
                }
               
                echo '</div>';
            } else {
                echo '<div class="d-flex justify-content-between align-items-center">';
                echo '<button class="btn btn-secondary" disabled>Expired</button>';
                echo '</div>';
            }
            echo '<a href="scheme_details.php?scheme_id=' . urlencode($schemeID) . '" class="btn btn-secondary btn-more-details" style="margin-left: 20px;">More Details</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            
            
            
        }
        echo '</div></div>'; // Close the row and container
    } 
    else {
        echo '<div class="container"><div class="alert alert-info" role="alert">No schemes to display.</div></div>';
    }
}
    else {
        // Handle error if farmer_id is not found
        echo '<div class="container"><div class="alert alert-danger" role="alert">Error: Farmer ID not found.</div></div>';
    }
?>
<br><br><br><br><br><br><br><br><br><br><br><br>
<?php
    // Close the database connection
    $con->close();

    include('footer/footer.php');
    ?>
</body>

</html>