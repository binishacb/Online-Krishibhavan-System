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

if(isset($_POST['approve']) && isset($_POST['application_id'])) {
    // Handle form submission
    $application_id = $_POST['application_id'];
    
    // Update application status
    $update_query = "UPDATE scheme_application SET application_status = 4 WHERE application_id = '$application_id'";
    if($con->query($update_query)) {
        // Fetch farmer ID from scheme_application table
        $farmer_id_query = "SELECT farmer_id FROM scheme_application WHERE application_id = '$application_id'";
        $farmer_id_result = $con->query($farmer_id_query);
        if ($farmer_id_result && $farmer_id_row = $farmer_id_result->fetch_assoc()) {
            $farmer_id = $farmer_id_row['farmer_id'];

            // Fetch log ID from farmer table using farmer ID
            $log_id_query = "SELECT log_id FROM farmer WHERE farmer_id = '$farmer_id'";
            $log_id_result = $con->query($log_id_query);
            if ($log_id_result && $log_id_row = $log_id_result->fetch_assoc()) {
                $log_id = $log_id_row['log_id'];

                // Fetch farmer email using log ID
                $email_query = "SELECT email FROM login WHERE log_id = '$log_id'";
                $email_result = $con->query($email_query);
                if ($email_result && $email_row = $email_result->fetch_assoc()) {
                    $email = $email_row['email'];
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
        $mail->Subject = "Your application  is approved";
        $email_template = "
          
            
            <h5>Your application with ID: $application_id has been approved.</h5>";
            
           
        $mail->Body = $email_template;
        
        if ($mail->send()) {
            echo "<script>alert('Email sent to the farmer.');</script>";
            header('Location:Scheme_verification_ao.php');
        
                    } else {
                       echo "<script>alert('Error sending email:  . $mail->ErrorInfo')</script>";
                    }
                } else {
                    echo "<script>alert('Error fetching farmer email.');</script>";
                }
            } else {
                echo "<script>alert('Error fetching log ID from farmer table.');</script>";
            }
        } else {
            echo "<script>alert('Error fetching farmer ID from scheme_application table.";
        }
    } else {
        // Error handling if the update fails
        echo "<script>alert('Error updating status: . $con->error');</script>";
    }
}

elseif(isset($_POST['reject']) && isset($_POST['application_id'])) {
    // Handle form submission for rejection
    $application_id = $_POST['application_id'];
    
    echo "<script>$('#rejectModal').modal('show');</script>";
}
?>


