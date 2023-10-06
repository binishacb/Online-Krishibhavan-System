<?php
session_start();
if(isset($_SESSION['auth']))
{
    unset($_SESSION['auth']);
    unset($_SESSION['auth_user']);
    $_SESSION['message'] = "Logged out successfully";
    session_destroy(); // Destroy the session
}
header('Location:landingpage.php');
?>