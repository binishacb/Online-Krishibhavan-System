<?php
session_start();
include('dbconnection.php');
require_once('fpdf186/fpdf.php');
if (!isset($_SESSION['useremail'])) {
    header("Location: login.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <?php include('navbar/navbar_officer.php'); ?>

    <div class="container mt-5">
        <!-- Export Button -->
        <button id="exportBtn" class="btn btn-primary mb-3">Export to CSV</button>
        <a href="report_pdf.php"><button id="printBtn" class="btn btn-secondary mb-3">Print Report</button></a>

        <!-- Table -->
        <?php
        $email = $_SESSION['useremail'];
        $officer_query = "SELECT officer.officer_id,officer.krishibhavan_id FROM officer JOIN login ON officer.log_id = login.log_id WHERE login.email = '$email' and officer.designation_id=1";
        $officer_result = mysqli_query($con, $officer_query);
        if ($officer_result && $officer_row = mysqli_fetch_assoc($officer_result)) {
            $officer_id = $officer_row['officer_id'];
            $k_id = $officer_row['krishibhavan_id'];

            $query = "SELECT sa.*, s.scheme_name FROM scheme_application sa JOIN schemes s ON sa.scheme_id = s.scheme_id WHERE sa.krishibhavan_id='$k_id' AND sa.application_status <> 1";
            $result = $con->query($query);
            $data = array();
            if ($result && $result->num_rows > 0) {
                echo '<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Scheme Name</th>
                                <th>Applicant Name</th>
                                <th>Address</th>
                                <th>Gender</th>
                                <th>Phone Number</th>
                                <th>Land tax receipt no.</th>
                                <th>Land Area (in cents)</th>
                                
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>';

                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                    echo '<tr>
                            <td>' . $row['scheme_name'] . '</td>
                            <td>' . $row['name'] . '</td>
                            <td>' . $row['address'] . '</td>
                            <td>' . $row['gender'] . '</td>
                            <td>' . $row['phone_number'] . '</td>
                            <td>' . $row['land_tax'] . '</td>
                            <td>' . $row['land_area'] . '</td>
                            
                            <td>';
                            if ($row['application_status'] == 2) {
                                echo 'Verified by Assistant Officer';
                            } elseif ($row['application_status'] == 3) {
                                echo 'Rejected by Assistant Officer';
                            } elseif ($row['application_status'] == 4) {
                                echo 'Application Approved';
                            } elseif ($row['application_status'] == 5) {
                                echo 'Application Rejected';
                            }
                            echo '</td>
                            </tr>';
                    }
    
                    echo '</tbody></table>';
                } else {
                    echo '<p>No records found.</p>';
                }
             }
            ?>
             <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

        <!-- Script for Export Button -->
        <script>
    $(document).ready(function() {
        $('#exportBtn').click(function() {
            var csv = [];
            var rows = $('table tr');
            var headers = [];
            
            // Get table headers
            rows.first().find('th').each(function(index, header) {
                headers.push($(header).text());
            });
            csv.push(headers.join(','));

            // Get table data
            rows.each(function(index, element) {
                var rowData = [];
                $(element).find('td').each(function(index, cell) {
                    rowData.push($(cell).text());
                });
                csv.push(rowData.join(','));
            });

            // Generate CSV content
            var csvContent = csv.join('\n');

            // Create a temporary download link and trigger the download
            var link = document.createElement('a');
            link.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csvContent);
            link.download = 'report.csv';
            link.click();
        });
    });
</script>

    </div>
</body>
</html>