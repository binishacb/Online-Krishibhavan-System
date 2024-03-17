<?php
session_start();
include('dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $farmer_id = $_POST['farmer_id'];
    $machine_id = $_POST['machine_id'];
    $shipping_id = $_POST['order_id'];
    $reason = $_POST['return_reason'];
    $other_reasons = $_POST['other_reasons'];
    $returnType = $_POST['returnType'];
    $query = "SELECT return_type_id FROM return_orders_reasons WHERE return_reason = '$reason'";
    $result = mysqli_query($con, $query);

        if(empty($reason)){
            echo "<script>alert('Please select the reason for return the product'); window.location.href = 'vieworderdetails_farmer.php';</script>";
            }
            else if(empty($returnType))
            {
                echo "<script>alert('Select whether you want a return or a replacement'); window.location.href = 'vieworderdetails_farmer.php';</script>";
            }
        else{
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $return_type_id = $row['return_type_id'];


            $sql = "INSERT INTO return_order (return_type_id, farmer_id, machine_id, other_reasons, shipping_id) 
                    VALUES ('$return_type_id', '$farmer_id', '$machine_id', '$other_reasons', '$shipping_id')";

            if (mysqli_query($con, $sql)) {
               // echo "<script>alert('type: " . $returnType . "'); window.location.href = 'vieworderdetails_farmer.php';</script>";

                $update_query = "UPDATE shipping_address SET cancel_return_status = '$returnType' WHERE shipping_id = '$shipping_id'";
                if (mysqli_query($con, $update_query)) {
                    echo "<script>alert('Order return request sent successfully.'); window.location.href = 'vieworderdetails_farmer.php';</script>";
                } else {
                    echo "<script>alert('Error updating cancel_return_status: " . mysqli_error($con) . "'); window.location.href = 'vieworderdetails_farmer.php';</script>";
                }
            } else {
                echo "<script>alert('Error: " . mysqli_error($con) . "'); window.location.href = 'vieworderdetails_farmer.php';</script>";
            }
        } else {
            echo "<script>alert('Error retrieving the type_id.'); window.location.href = 'vieworderdetails_farmer.php';</script>";
        }
    }

    mysqli_close($con);
} else {
    echo "<script>alert('Invalid request'); window.location.href = 'vieworderdetails_farmer.php';</script>";
}

