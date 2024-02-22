<?php
// Include your database connection file
include('../dbconnection.php');
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
    $vendorId = $_POST['vendor_id'];
    $approvalStatus = $_POST['approval_status'];

    // Fetch vendor details for email
    $selectQuery = "SELECT * FROM vendor v INNER JOIN login l ON v.log_id = l.log_id WHERE v.v_id = '$vendorId'";

    $result = mysqli_query($con, $selectQuery);
    $vendorDetails = mysqli_fetch_assoc($result);
    $to = $vendorDetails['email'];

    // Handle the approval logic
    if ($approvalStatus == 'approve') {
        // Update the database with the approval status
        $updateQuery = "UPDATE vendor SET status = '1' WHERE v_id = '$vendorId'";
        mysqli_query($con, $updateQuery);

        // Send approval email to the vendor
        $subject = "Application Approved";
        $message ="<h2>Application Approved</h2>
            
        <p>Dear {$vendorDetails['firstName']} {$vendorDetails['lastName']},</p>

        <p>We are pleased to inform you that your application with Vendor ID: {$vendorDetails['v_id']} has been approved. Welcome to our platform! We appreciate your interest in partnering with us.</p>

        <p>Here are some details about your approved application:</p>
        <ul>
            <li><strong>Vendor ID:</strong> {$vendorDetails['v_id']}</li>
            <li><strong>Vendor Name:</strong> {$vendorDetails['firstName']} {$vendorDetails['lastName']}</li>
            <li><strong>Email:</strong> {$vendorDetails['email']}</li>
            <li><strong>Phone Number:</strong> {$vendorDetails['phone_no']}</li>
            <li><strong>Shop Name:</strong> {$vendorDetails['shopName']}</li>
            <li><strong>Licence Number:</strong> {$vendorDetails['licence_no']}</li>
        </ul>

        <p>Thank you for choosing us as your partner. If you have any further questions or require assistance, feel free to reach out to our support team.</p>

        <p>Best regards,<br>The Admin Team</p>
    ";
    }
    // Handle the rejection logic
    elseif ($approvalStatus == 'reject') {
        // Check if the rejection has already been done
        $checkRejectionQuery = "SELECT status FROM vendor WHERE v_id = '$vendorId'";
        $result = mysqli_query($con, $checkRejectionQuery);
        $vendorStatus = mysqli_fetch_assoc($result)['status'];

        if ($vendorStatus != '2') {
            // Update the database with the rejection status
            $updateQuery = "UPDATE vendor SET status = '2' WHERE v_id = '$vendorId'";
            mysqli_query($con, $updateQuery);
            $rejectionReason = mysqli_real_escape_string($con, $_POST['rejection_reason']);

            // Send rejection email to the vendor
            $subject = "Application Rejected";
            $message = "Dear {$vendorDetails['firstName']} {$vendorDetails['lastName']},\n\nWe regret to inform you that your application with Vendor ID: {$vendorDetails['v_id']} has been rejected for the following reason:\n\n{$rejectionReason}\n\nIf you have any concerns, please contact us.\n\nBest regards,\nThe Admin Team";
        } 
    } 


  

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.gmail.com';
        $mail->Username = 'agrocompanion2023@gmail.com'; // Your Gmail email address
        $mail->Password = 'wwme uijt eygq xqas'; // Your Gmail password or App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('agrocompanion2023@gmail.com', 'Agrocompanion');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->send();

        // echo "<script>alert('Email sent successfully.');</script>";
    } catch (Exception $e) {
        echo 'Error sending email: ' . $mail->ErrorInfo;
    }
}

// Fetch vendor details with email and approval status from the database using a JOIN
$query = "SELECT l.email, v.* FROM login as l INNER JOIN vendor as v WHERE l.log_id = v.log_id";
$result = mysqli_query($con, $query);

// Check if there are any results
if (mysqli_num_rows($result) > 0) {
    // Start HTML markup
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Vendor Details</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    </head>
    <body>
<?php
include('../navbar/navbar_admin.php');
?>



<div class="container mt-4">
    <h3>Vendor Data</h3><br>
    <div class="table-responsive">
        <table class="table table-bordered" style="color:light;">
            <thead class="thead-light">
                <tr>
                    <!-- <th>Vendor ID</th> -->
                    <th>Vendor Name</th>
                    <th>Email</th>
                    <th>Phone no</th>
                    <th>Shop name</th>
                    <th>Licence no</th>
                    <th>Licence</th>
                    <th>Action</th>
                    <!-- Add more columns based on your vendor table structure -->
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through the results and display each vendor's details
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    // echo '<td>' . $row['v_id'] . '</td>';
                    echo '<td>' . $row['firstName'] . ' ' . $row['lastName'] . '</td>';
                    echo '<td>' . $row['email'] . '</td>';
                    echo '<td>' . $row['phone_no'] . '</td>';
                    echo '<td>' . $row['shopName'] . '</td>';
                    echo '<td>' . $row['licence_no'] . '</td>';

                    // Display the PDF link
                    echo '<td><a href="../uploads/' . $row['licence_image'] . '" class="btn btn-primary" target="_blank" download>View Licence</a></td>';

                    // Add approval status check and render appropriate button
                    $approvalStatus = $row['status'];
                    echo '<td>';
                    if ($approvalStatus == '0') {
                        echo '<form action="" method="post" class="d-flex" onsubmit="prepareRejectionReason()">
                                <input type="hidden" name="vendor_id" value="' . $row['v_id'] . '">
                                <button type="submit" name="approval_status" value="approve" class="btn btn-success me-2">Approve</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>
                              ';
                    } elseif ($approvalStatus == '1') {
                        echo '<button class="btn btn-success btn-disabled">Approved</button>';
                    } elseif ($approvalStatus == '2') {
                        echo '<button class="btn btn-danger btn-disabled">Rejected</button>';
                    }
                    
                    
                    echo '</td>';

                    echo '</tr>';
                }
                ?>
   <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Reject Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="rejectionReason" class="form-label">Reason for Rejection:</label>
                <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="3"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="approval_status" value="reject" class="btn btn-danger">Reject</button>
            </div>
        </div>
    </div>
</div>
</form>
<script>
    function prepareRejectionReason() {
        var rejectionReason = document.getElementById('rejectionReason').value;
        document.getElementById('hiddenRejectionReason').value = rejectionReason;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



    <?php
} else {
    echo 'No vendors found.';
}

// Close the database connection
mysqli_close($con);
?>




