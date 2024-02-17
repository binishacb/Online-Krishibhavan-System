<?php
session_start();
include('dbconnection.php');

// Assuming you have a valid database connection in $con

// Check if the user is an officer (you can customize this based on your user roles)
if (!isset($_SESSION['useremail'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}
?>
<html>
    <head>
        <style>
            body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 800px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 12px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

button {
    background-color: #4CAF50;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}
</style>

    </head>
    <body>
       <?php include('navbar/navbar_officer.php');

       // Fetch the list of schemes applied by farmers
$query = "SELECT sa.*, s.scheme_name, f.firstname as farmer_name FROM scheme_application sa
          JOIN schemes s ON sa.scheme_id = s.scheme_id
          JOIN farmer f ON sa.farmer_id = f.farmer_id";
$result = $con->query($query);

if ($result) {
    echo '<table border="1">
            <tr>
                <th>Scheme Name</th>
                <th>Applicant Name</th>
                <th>Address</th>
                <th>Gender</th>
                <th>Phone Number</th>
                <th>Krishibhavan</th>
                <th>Land Area</th>
                <th>Status</th>
                <th>Action</th>
            </tr>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . $row['scheme_name'] . '</td>
                <td>' . $row['farmer_name'] . '</td>
                <td>' . $row['address'] . '</td>
                <td>' . $row['gender'] . '</td>
                <td>' . $row['phone_number'] . '</td>
                <td>' . $row['krishibhavan'] . '</td>
                <td>' . $row['land_area'] . '</td>
                <td>' . ($row['application_status'] == 1 ? 'Processing' : 'Verified') . '</td>
                <td>';
                
                if ($row['application_status'] == 1) {
                    echo '<form action="" method="post">
                            <input type="hidden" name="application_id" value="' . $row['application_id'] . '">
                            <button type="submit" name="verify">Verify</button>
                          </form>';
                   }   else {
            echo 'Already verified';
        }

        echo '</td></tr>';
    }

    echo '</table>';
} else {
    echo 'Error executing query: ' . $con->error;
}

// Close the database connection when done
$con->close();
?>
    </body>
</html>