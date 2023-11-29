<?php
//connect the database
include('dbconnection.php');
 
 
if(isset($_POST['payment_id']) && isset($_POST['amount']) && isset($_POST['name']) && isset($_POST['farmer_id']) && isset($_POST['product_id']))
{
    $paymentId = $_POST['payment_id'];
    $amount = $_POST['amount'];
    $name = $_POST['name'];
    $farmer_id =  $_POST['farmer_id'];
    $product_id =  $_POST['product_id'];
    
 
    //insert data into database
    $sql="INSERT INTO orders ( machine_id, payment_id, farmer_id) VALUES ('$product_id', '$paymentId', ' $farmer_id')";
    $stmt=$con->prepare($sql);
    $stmt->execute();
    // header("Location: payment_success.php");
}
?>