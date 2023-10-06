<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php';

function sendemail_verify($email, $verify_token)
{
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
        $mail->addAddress($email);

        


        $mail->isHTML(true);
        $mail->Subject = 'Email verification from Agrocompanion';
        $email_template = "
            <h2>You are registered with Agrocompanion</h2>
            
            <h5>Verify your email address to login with the below given link</h5>
            <br><br>
            <a href='http://localhost/agrocompanion/verify-email.php?token=$verify_token'>Click me</a>";
        $mail->Body = $email_template;
        $mail->send();
     
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if (isset($_POST['register_btn'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone_no = $_POST['phone_no'];

    $dob = $_POST['dob'];

    $verify_token = md5(rand());
    
    
  
   
    $check_email_query = "SELECT email FROM login WHERE email='$email' LIMIT 1";
    $check_email_query_run = mysqli_query($con, $check_email_query);
    if (mysqli_num_rows($check_email_query_run) > 0) 
    {
        echo "<script>alert('Email id already exists')</script>";
		header( "Location: register.php" );
      
      
    } else {
        // Insert user data into the database
        $query = "INSERT INTO login(email, password, verify_token) VALUES ('$email', '$password', '$verify_token')";
        $query_run = mysqli_query($con, $query);

        if ($query_run) {
            $val = "SELECT login_id FROM login WHERE email='$email'";
            if ($res = $con->query($val)) {
                foreach ($res as $data) {
                    $login_id = $data['login_id'];
                    $query_farmer = "INSERT INTO farmer(login_id, name, phone_no, dob) VALUES ('$login_id', '$name', '$phone_no', '$dob')";
                    if ($con->query($query_farmer) == TRUE && sendemail_verify($email, $verify_token)) 
                    {
                        echo "<script>alert('Registration successful. Please verify your email')</script>";
						header( "Location: verify_otp.php" );
                     
                       
                        exit();
                    }
                }
            }
        } else {
          
        }

       
    }
}
?>