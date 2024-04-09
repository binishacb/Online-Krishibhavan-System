<?php
session_start();
include('dbconnection.php');
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['useremail'])) {
    header("Location: login.php"); 
    exit();
}


if (isset($_POST['application_id'], $_POST['land_tax'], $_POST['action'])) {
    $appln_id = $_POST['application_id'];
    $land_tax = $_POST['land_tax'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        // Update application status to 2 in scheme_application table
        $update_query = "UPDATE scheme_application SET application_status = 2 WHERE application_id = '$appln_id'";
        if (mysqli_query($con, $update_query)) {
            
            header('Location: scheme_verification.php');
            exit();
            // Additional logic if needed after approval
        } else {
            echo "Error updating application status: " . mysqli_error($con);
        }
    } elseif ($action === 'reject') {
        // Update application status to 3 in scheme_application table
        $update_query = "UPDATE scheme_application SET application_status = 3 WHERE application_id = '$appln_id'";
        mysqli_query($con, $update_query);
        $rejectionReason = mysqli_real_escape_string($con, $_POST['rejection_reason']);

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
            $mail->addAddress($_SESSION['useremail']); // Assuming user's email is stored in session

            $mail->isHTML(true);
            $mail->Subject = "Application Rejected";
            $mail->Body = "Your application has been rejected due to the following reason: <br> $rejectionReason";
            $mail->send();
            // echo "<script>alert('Email sent successfully.');</script>";
        } catch (Exception $e) {
            echo 'Error sending email: ' . $mail->ErrorInfo;
        }
    }
} else {
    echo "Invalid request.";
}
?>
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
            <form action="" method="post">
                <div class="form-group">
                    <label for="rejectionReason">Reason for Rejection:</label>
                    <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="3"></textarea>
                    <!-- Hidden fields to pass application details -->
                    <input type="hidden" name="application_id" value="<?php echo $appln_id; ?>">
                    <input type="hidden" name="land_tax" value="<?php echo $land_tax; ?>">
                    <input type="hidden" name="action" value="reject">
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Reject</button>
            </form>
        </div>
    </div>
</div>
</div>
<script>
        // Function to open the reject modal
        function openRejectModal() {
            $('#rejectModal').modal('show');
        }
    </script>