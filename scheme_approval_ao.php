<?php
session_start();
include('dbconnection.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require './vendor/autoload.php';
if (!isset($_SESSION['useremail'])) {
    header("Location: login.php"); 
    exit();
}


function sendEmailNotification($email, $subject, $message) {
 
    $mail = new PHPMailer(true);
    $mail->isSMTP();
$mail->SMTPAuth = true;
$mail->Host = 'smtp.gmail.com';
$mail->Username = 'agrocompanion2023@gmail.com'; // Your Gmail email address
$mail->Password = 'wwme uijt eygq xqas'; // Your Gmail password or App Password
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('agrocompanion2023@gmail.com', 'Agrocompanion');

    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;
    if ($mail->send()) {
        echo "<script>alert('Email sent to the farmer.');</script>";
        header('Location: Scheme_verification_ao.php');
    } else {
        echo "<script>alert('Error sending email: . $mail->ErrorInfo');</script>";
    }
}









if(isset($_POST['approve']) && isset($_POST['application_id'])) {
    
    // Handle approval
    $application_id = $_POST['application_id'];
    
    // Update application status to Approved (status code: 4)
    $update_query = "UPDATE scheme_application SET application_status = 4 WHERE application_id = '$application_id'";
    if($con->query($update_query)) {
        // Fetch farmer details to send email
        $farmer_email_query = "SELECT login.email FROM scheme_application
            JOIN farmer ON scheme_application.farmer_id = farmer.farmer_id
            JOIN login ON farmer.log_id = login.log_id
            WHERE scheme_application.application_id = '$application_id'";
        $farmer_email_result = $con->query($farmer_email_query);
        if ($farmer_email_result && $farmer_email_row = $farmer_email_result->fetch_assoc()) {
            $email = $farmer_email_row['email'];
            $subject = "Your application has been approved";
            $message = "<h5>Your application with ID: $application_id has been approved.</h5>";
            sendEmailNotification($email, $subject, $message);
        } else {
            echo "<script>alert('Error fetching farmer email.');</script>";
        }
    } else {
        echo "<script>alert('Error updating status: . $con->error');</script>";
    }
}



elseif(isset($_POST['reject']) && isset($_POST['application_id']) && isset($_POST['rejection_reason'])) {
    
    // Handle rejection
    $application_id = $_POST['application_id'];
    $rejection_reason = $_POST['rejection_reason'];
 
    // Update application status to Rejected (status code: 5)
    $update_query = "UPDATE scheme_application SET application_status = 5 WHERE application_id = '$application_id'";
    if($con->query($update_query)) {
        // Fetch farmer details to send email
        $farmer_email_query = "SELECT login.email FROM scheme_application
            JOIN farmer ON scheme_application.farmer_id = farmer.farmer_id
            JOIN login ON farmer.log_id = login.log_id
            WHERE scheme_application.application_id = '$application_id'";
        $farmer_email_result = $con->query($farmer_email_query);
        if ($farmer_email_result && $farmer_email_row = $farmer_email_result->fetch_assoc()) {
            $email = $farmer_email_row['email'];
            $subject = "Your application has been rejected";
            $message = "<h5>Your application with ID: $application_id has been rejected. Reason: $rejection_reason</h5>";
            sendEmailNotification($email, $subject, $message);
        } else {
            echo "<script>alert('Error fetching farmer email.');</script>";
        }
    } else {
        echo "<script>alert('Error updating status: . $con->error');</script>";
    }
}