<?php
session_start();
include('dbconnection.php');
if(isset($_GET['token']))
{
    $token = $_GET['token'];
    $verify_query = "SELECT verify_token,verify_status FROM login WHERE verify_token='$token' LIMIT 1";
    $verify_query_run = mysqli_query($con,$verify_query);

    if(mysqli_num_rows($verify_query_run) > 0 )
    {
        $row = mysqli_fetch_array($verify_query_run);
        //echo $row['verify_token'];
        if($row['verify_status'] == "0")
        {
            $clicked_token = $row['verify_token'];
            $update_query ="UPDATE login SET verify_status='1' WHERE verify_token='$clicked_token' LIMIT 1";
            $update_query_run = mysqli_query($con,$update_query);
            
            if($update_query_run)
            {
                echo "<script>alert('Your Account has been verified successfully.!'); 
                window.location = 'login.php';</script>";
               // $_SESSION['status'] = "Your Account has been verified successfully.!";
                //header("Location: login.php");
                exit(0);

            }
            else
            {
               // $_SESSION['status'] = "Verification failed.!";
               // header("Location: login.php");
               echo "<script>alert('Your Account  verification failed.!'); 
               window.location = 'registration.php';</script>";
                exit(0);
            }
        }
        else
        {
            echo "<script>alert('Email already verified. Please login!'); 
            window.location = 'login.php';</script>";
           // $_SESSION['status'] = "Email already verified. Please login";
            //header("Location: login.php");
            exit(0);
        }
    }
    else{
       // $_SESSION['status'] = "This token does not exists";
       // header("Location: login.php");
       echo "<script>alert('This token does not exists!'); 
       window.location = 'login.php';</script>";
    }
}
