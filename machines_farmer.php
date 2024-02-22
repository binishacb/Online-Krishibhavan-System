<?php
include('dbconnection.php');
session_start();
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'farmer') {
    header('Location:index.php'); 
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Machines</title>
    <!-- Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<?php include('navbar/navbar_machines.php'); ?>

<div class="container mt-4">
    <h2>View Machines in Stock</h2>
<br>
    <div class="row">
        <?php
        $sql = "SELECT m.machine_id,m.machine_name, m.machine_image,bp.product_price,bp.discount,bp.sales_price FROM machines m INNER JOIN buy_product bp on m.bp_id = bp.bp_id WHERE m.m_quantity > 0 and status = 0";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='col-md-4 mb-4'>
                <div class='card h-100 d-flex '>
                    <img src='uploads/{$row['machine_image']}' alt='{$row['machine_name']}' class='card-img-top' style='height:350px; width:350px; object-fit: cover;'>
                    <div class='card-body flex-grow-1'>
                        <h5 class='card-title'>{$row['machine_name']}</h5>
                        <div class='price-container d-flex justify-content-between'>
                            <div class='discount' style='color:red;'>-{$row['discount']}%</div> 
                            <div class='actual-price' style='padding-left:10px;'>₹{$row['sales_price']}</div>
                        </div>
                        <br>
                        <p class='card-text'  style='text-decoration: line-through;'>M.R.P.: ₹{$row['product_price']}</p>
                        <a href='machinedetails_farmer.php?machine_id={$row['machine_id']}' class='btn btn-primary'>View Details</a>
                    </div>
                </div>
            </div>";
        
            }
        } else {
            echo "<div class='col-md-12'>
                    <p>No machines in stock.</p>
                  </div>";
        }

        $con->close();
        ?>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
