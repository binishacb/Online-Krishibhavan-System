<?php 
session_start();
include('dbconnection.php');

if (!isset($_SESSION['useremail'])) {
    header("Location: login.php"); 
    exit();
}

if (isset($_POST['verify'])) {
    $land_tax = $_POST['land_tax'];
    $appln_id = $_POST['application_id'];

    // Fetch data from land_tax table
    $query_land_tax = "SELECT * FROM land_tax WHERE land_tax = '$land_tax'";
    $result_land_tax = mysqli_query($con, $query_land_tax);
    
    $land_tax_rows = [];
    while ($row = mysqli_fetch_assoc($result_land_tax)) {
        $land_tax_rows[] = $row;
    }

    // Fetch data from scheme_application table
    $query_scheme_application = "SELECT * FROM scheme_application WHERE application_id = '$appln_id'";
    $result_scheme_application = mysqli_query($con, $query_scheme_application);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheme Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include('navbar/navbar_officer.php'); ?>
    <br><br><br><br><br>
    <div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Verification Results</h5>
        </div>
        <div class="card-body">
            <!-- Table of land tax information -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="thead-light">
                    <tr>
                        <th>Land Tax Number</th>
                        <th>Property Name</th>
                        <th>Address</th>
                        <th>Land Area (in cents)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                   foreach ($land_tax_rows as $row_land_tax) {
                    echo "<tr>";
                    echo "<td>" . $row_land_tax['land_tax'] . "</td>";
                    echo "<td>" . $row_land_tax['farmer_name'] . "</td>";
                    echo "<td>" . $row_land_tax['address'] . "</td>";
                    echo "<td>" . $row_land_tax['land_area'] . "</td>";
                    echo "</tr>";
                }
                    
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php 
    if (mysqli_num_rows($result_scheme_application) > 0 ) {
      
        $row_scheme_application = mysqli_fetch_assoc($result_scheme_application);
        if($land_tax_rows) {      
            // Check if the data matches
            if (strtolower(trim($row_scheme_application['name'])) != strtolower(trim($row_land_tax['farmer_name']))) {
                echo "Name doesn't match for Land tax number: " . $row_land_tax['land_tax'] . "<br>";
            }
            if (strtolower(trim($row_scheme_application['address'])) != strtolower(trim($row_land_tax['address']))) {
                echo "Address doesn't match for Land tax number: " . $row_land_tax['land_tax'] . "<br>";
            }
            if ($row_scheme_application['land_area'] != $row_land_tax['land_area']) {
                echo "Land Area doesn't match for Land tax number: " . $row_land_tax['land_tax'] . "<br>";
            }
            if (strtolower(trim($row_scheme_application['name'])) == strtolower(trim($row_land_tax['farmer_name'])) &&
                strtolower(trim($row_scheme_application['address'])) == strtolower(trim($row_land_tax['address'])) &&
                $row_scheme_application['land_area'] == $row_land_tax['land_area'])  {
                echo "All data provided matches with the Land tax number: " . $row_land_tax['land_tax'] . "<br>";
            }
        } 
        
                    }
    ?>
    <div class="text-center">
    <form action="scheme_approval.php" method="post">
        <input type="hidden" name="application_id" value="<?php echo $appln_id; ?>">
        <input type="hidden" name="land_tax" value="<?php echo $land_tax; ?>">
        <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">Reject</button>
    </form>
                </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="scheme_approval.php" method="post">
                        <div class="form-group">
                            <label for="rejectionReason">Reason for Rejection:</label>
                            <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="3"></textarea>
                        </div>
                        <input type="hidden" name="application_id" value="<?php echo $appln_id; ?>">
                        <input type="hidden" name="land_tax" value="<?php echo $land_tax; ?>">
                        <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
