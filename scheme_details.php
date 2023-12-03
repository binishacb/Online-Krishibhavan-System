<?php
session_start();
include('dbconnection.php');

if (!isset($_SESSION['useremail'])) {
    header('Location: index.php'); // Redirect to index.php
    exit(); // Stop further execution of the current script
}

if (!isset($_GET['scheme_id'])) {
    header('Location: schemes.php'); // Redirect back if no scheme_id is provided
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Additional CSS styles */
        body {
            background-color: #f0f0f0;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
        }

        table {
            border: 2px solid darkgreen;
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 2px solid darkgreen;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: darkgreen;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        h2 {
            color: darkgreen;
        }
        
                .apply-button-container {
            display: flex;
            align-items: center;
            justify-content: center;
            
}
        .apply-button {
            background-color: darkgreen;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        } 
                /* Additional CSS styles
        .apply-button-container {
            text-align: center;
            margin-top: 20px;
        }

        /* .apply-button {
            background-color: darkgreen;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        } */

        .apply-button:hover {
            background-color: #005700; /* Darken the color on hover */
        }


    </style>
</head>
<body>

<?php
include('navbar/navbar_officer.php');
?>
<?php
$schemeID = $_GET['scheme_id'];
$farmer_email = $_SESSION['useremail'];

// Retrieve scheme details
$schemeDetailsQuery = "SELECT * FROM schemes WHERE scheme_id = '$schemeID'";
$schemeDetailsResult = $con->query($schemeDetailsQuery);

if ($schemeDetailsResult->num_rows > 0) {
    $schemeDetails = $schemeDetailsResult->fetch_assoc();
    
        $scheme_name = $schemeDetails['scheme_name'];
        $scheme_description = $schemeDetails['scheme_description'];
        $eligibility = $schemeDetails['eligibility'];
        $start_date = $schemeDetails['start_date'];
        $end_date = $schemeDetails['end_date'];
?>
    <div class="container mt-5">
    <h2>Scheme Details: <?php echo $scheme_name; ?></h2>
    <table>
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>
        <tr>
            <td><strong>Scheme Name:</strong></td>
            <td><?php echo $scheme_name; ?></td>
        </tr>
        <tr>
            <td><strong>Description:</strong></td>
            <td><?php echo $scheme_description; ?></td>
        </tr>
        <tr>
            <td><strong>Eligibility:</strong></td>
            <td><?php echo $eligibility; ?></td>
        </tr>
        <tr>
            <td><strong>Start Date:</strong></td>
            <td><?php echo $start_date; ?></td>
        </tr>
        <tr>
            <td><strong>End Date:</strong></td>
            <td><?php echo $end_date; ?></td>
        </tr>
    </table>
    <br><br>
    <!-- <div class="apply-button-container">
    <button class="apply-button" data-toggle="modal" data-target="#eligibilityModal">Apply</button> -->
 

    


<?php

    // Check eligibility
    $cropExistsQuery = "SELECT * FROM farmers_crops WHERE farmer_email = '$farmer_email' AND acres >= {$schemeDetails['acres']} AND crop = '{$schemeDetails['crop']}'";
    $cropExistsResult = $con->query($cropExistsQuery);

    // Display Apply button based on eligibility
    if ($schemeDetails['end_date'] >= date('Y-m-d')) {
        if ($cropExistsResult->num_rows > 0) {
            // Crop and acres exist, display the "Apply" button
            ?>
            <div class="apply-button-container"><?php
            echo '<a href="?scheme_id=' . urlencode($schemeID) . '" class="btn btn-primary">Apply</a>';
        } else {
            // Crop and acres do not exist, display a disabled button
            ?><center><?php
            echo '<button class="btn btn-secondary" disabled>Not Eligible</button>';?></center><?php
        }
        ?>
        </div></div>
        <?php

    }}
// Close the database connection
$con->close();
?>

</body>
</html>