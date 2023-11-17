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
    /* Additional CSS styles */
    .scheme-box {
        max-width: 800px;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        position: relative;
        /* Add this line */
        display: flex;
        /* Use flex display to align buttons horizontally */
        flex-direction: column;
        /* Stack buttons vertically */
        align-items: center;
        /* Center align the buttons */
    }

    .scheme-box a {
        margin-top: 10px;
        /* Add this line to create spacing between buttons */
        padding: 5px 10px;
        /* Add padding to make buttons smaller */
        display: inline-block;
        /* Adjust the display to inline-block to make the buttons appear side by side */
    }
    </style>
</head>

<body>
    <?php
     include('navbar/navbar_farmer.php');
    
   
    $sql = "SELECT scheme_id,scheme_name, end_date FROM schemes WHERE end_date >= NOW()";
    $result = $con->query($sql);
     // Check if there are schemes to display
    if ($result->num_rows > 0) {
        $count = 0; // Initialize a counter to keep track of schemes in each row
        echo '<div class="row">';
        while ($row = $result->fetch_assoc()) {
        $schemeID = $row['scheme_id'];
        $schemeName = $row['scheme_name'];
        $endDate = strtotime($row['end_date']);
        
        // Calculate the difference in days between the current date and the end date
        $currentDate = time();
        $daysUntilEndDate = round(($endDate - $currentDate) / (60 * 60 * 24));

        // Check if the end date is nearly 5 days away
        $endDateColor = ($daysUntilEndDate <= 5) ? 'style="color: red;"' : '';

        echo '<div class="col-md-6">';
        echo '<div class="scheme-box">';
       
        echo '<h3>' . $schemeName . '</h3>';
        echo '<p ' . $endDateColor . '>End Date: ' . date('Y-m-d', $endDate) . '</p>';
        
        // Add a "View Details" button with a link to the details page
        echo '<a href="scheme_details.php?scheme_id=' . urlencode($schemeID) . '" class="btn btn-primary">View Details</a>';
        echo '<a href="apply.php?scheme_id=' . urlencode($schemeID) . '" class="btn btn-success">Apply</a>';
        echo '</div>';
        echo '</div>';

        $count++;
        if ($count % 2 == 0) {
            // Close the row after every two schemes
            echo '</div><div class="row">';
        }
    }
    echo '</div>'; // Close the last row
} else {
    echo 'No schemes to display.';
}

// Close the database connection
$con->close();

    include('footer/footer.php');
    ?>
</body>

</html>