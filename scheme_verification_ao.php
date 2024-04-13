
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
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" ></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
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
    $officer_query = "SELECT officer.officer_id,officer.krishibhavan_id FROM officer JOIN login ON officer.log_id = login.log_id WHERE login.email = '$email' and officer.designation_id=1";
    $officer_result = mysqli_query($con, $officer_query);
    if ($officer_result && $officer_row = mysqli_fetch_assoc($officer_result)) {
        $officer_id = $officer_row['officer_id'];
        $k_id = $officer_row['krishibhavan_id'];


        $query = "SELECT sa.*, s.scheme_name  FROM scheme_application sa  JOIN schemes s ON sa.scheme_id = s.scheme_id WHERE sa.krishibhavan_id='$k_id' AND sa.application_status <> 1";
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
                
        echo '</td>';
            


                
            echo '<td>';
               

                if ($row['application_status'] == 2) {?>
               <form id="rejectForm<?php echo $row['application_id']; ?>" action="scheme_approval_ao.php" method="POST">
    <input type="hidden" name="application_id" value="<?php echo $row['application_id'] ?>">
    <input type="hidden" name="rejection_reason" id="rejection_reason_<?php echo $row['application_id']; ?>">
    <button type="submit" class="btn btn-success" name="approve">Approve</button><br>
    <button type="submit" class="btn btn-danger rejectBtn" name="reject" data-applicationid="<?php echo $row['application_id']; ?>" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>

</form>


                    <?php
                } else {
                    if($row['application_status'] == 4){?>
                    <button type="submit" name="verify">Approved</button>
               <?php }
               elseif($row['application_status'] == 5){?>
 <button type="submit" class = "btn btn-danger" name="rejected">Rejected</button>
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
    
    
    ?>

<!-- Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to reject this application?</p>
                <textarea class="form-control" id="rejectionReason" rows="3" placeholder="Enter reason for rejection"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="confirmReject">Reject</button>
            </div>
        </div>
    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Event listener for Reject button click
            $('.rejectBtn').click(function() {
                var applicationId = $(this).data('applicationid');
                $('#rejection_reason_application_id').val(applicationId); // Set application ID in modal input
                $('#rejectModal').modal('show'); // Show modal
            });

            // Event listener for Confirm Reject button click
            $('#confirmReject').click(function() {
                var applicationId = $('#rejection_reason_application_id').val();
                var rejectionReason = $('#rejectionReason').val().trim();
                if (rejectionReason !== '') {
                    $('#rejection_reason_' + applicationId).val(rejectionReason); // Set rejection reason in form input
                    $('#rejectForm' + applicationId).submit(); // Submit form
                } else {
                    alert('Please enter a reason for rejection.');
                }
            });
        });
    </script>

  




</body>

</html>