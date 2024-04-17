<?php
session_start();
include('dbconnection.php');
if (!isset($_SESSION['useremail'])) {
    header("Location: login.php"); 
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

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
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
    $email = $_SESSION['useremail'];
    $officer_query = "SELECT officer.officer_id,officer.krishibhavan_id FROM officer JOIN login ON officer.log_id = login.log_id WHERE login.email = '$email' and officer.designation_id=2";
    $officer_result = mysqli_query($con, $officer_query);
    if ($officer_result && $officer_row = mysqli_fetch_assoc($officer_result)) {
        $officer_id = $officer_row['officer_id'];
        $k_id = $officer_row['krishibhavan_id'];


        $query = "SELECT sa.*, s.scheme_name  FROM scheme_application sa  
          JOIN schemes s ON sa.scheme_id = s.scheme_id where krishibhavan_id='$k_id'";

        $result = $con->query($query);
        // echo "<pre>";
        // print_r($result->fetch_all(MYSQLI_ASSOC));
        // echo "</pre>";

        if ($result) {
            echo '<table border="1">
            <tr>
                <th>Scheme Name</th>
                <th>Applicant Name</th>
                <th>Address</th>
                <th>Gender</th>
                <th>Phone Number</th>
                <th>Land tax receipt no.</th>
                <th>Land Area(in cents)</th>
                <th>Tax receipt</th>
                <th>Status</th>
                <th>Action</th>
            </tr>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                <td>' . $row['scheme_name'] . '</td>
                <td>' . $row['name'] . '</td>
                <td>' . $row['address'] . '</td>
                <td>' . $row['gender'] . '</td>
                <td>' . $row['phone_number'] . '</td>
                <td>' . $row['land_tax'] . '</td>

                <td>' . $row['land_area'] . '</td>
               
                <td><a href="uploads/' . $row['tax_image'] . '" class="btn btn-primary" target="_blank" download>View land tax</a></td>

                <td>' . ($row['application_status'] == 1 ? 'Processing' : 'Verified') . '</td>
                <td>';
                

                if ($row['application_status'] == 1) {
                   
                    echo '<form action="verify_scheme.php" method="post">
                            <input type="hidden" name="application_id" value="' . $row['application_id'] . '">
                            <input type="hidden" name="land_tax" value="' . $row['land_tax'] . '">
                            <button type="submit" name="verify">Verify</button>
                          </form>';
                }   else {
                    if($row['application_status'] == 2){?>
                    <button type="submit" name="verify">Verified</button>
               <?php }
               elseif($row['application_status'] == 3){?>
 <button type="submit" class = "btn btn-danger" name="verify">Rejected</button>
 <?php
               }
                }
                echo '</td></tr>';
            }

            echo '</table>';
        } else {
            echo 'Error executing query: ' . $con->error;
        }
    }
    
    $con->close();
    ?>
</body>

</html>