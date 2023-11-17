<?php
session_start();
include('dbconnection.php');

if (!isset($_SESSION['useremail'])) {
    header('Location: index.php');
    exit();
}

// Check if the scheme ID is provided in the query parameters
if (isset($_GET['scheme_id'])) {
    $scheme_id = $_GET['scheme_id'];

    // Query to retrieve the scheme's details based on the scheme_id
    $sql = "SELECT * FROM schemes WHERE scheme_id = $scheme_id";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $scheme_name = $row['scheme_name'];
        $scheme_description = $row['scheme_description'];
        $eligibility = $row['eligibility'];
        $start_date = $row['start_date'];
        $end_date = $row['end_date'];
    } else {
        // Handle the case where the scheme with the provided ID doesn't exist
        echo "Scheme not found.";
        exit();
    }
} else {
    // Handle the case where no scheme ID is provided
    echo "Scheme ID not provided.";
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
            text-align: center;
            margin-top: 20px;
        }
        .apply-button {
            background-color: darkgreen;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php
include('navbar/navbar_officer.php');
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
        <div class="apply-button-container">
            <button class="apply-button">Apply</button>
        </div>
    </div>

<?php
include('footer/footer.php');
?>

</body>
</html>
