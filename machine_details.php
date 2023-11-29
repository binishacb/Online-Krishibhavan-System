<?php
session_start();
include('dbconnection.php');

if (!isset($_SESSION['useremail'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machine Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .machine-details {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            display: flex;
        }

        .machine-details-container img {
            max-width: 300px;
            border-radius: 8px 0 0 8px;
        }

        .machine-details-content {
            padding: 20px;
            flex-grow: 1;
        }

        .discount {
            font-size: 18px;
            color: #fff;
            background-color: #e44d26;
            padding: 5px 10px;
            border-radius: 4px;
            margin-bottom: 10px;
            display: inline-block;
        }

        .price-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .price {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .product-description {
            margin-top: 10px;
        }
       
    </style>
</head>
<body>
    <?php include('navbar/navbar_admin.php'); 
    
if (isset($_GET['machine_id'])) {
    $machine_id = $_GET['machine_id'];

    $machineSql = "SELECT m.machine_id, m.machine_name, m.machine_image, bp.product_price, bp.discount, bp.sales_price, m.description,rp.fare_per_hour,rp.fare_per_day
                   FROM machines m LEFT JOIN buy_product bp ON m.bp_id = bp.bp_id LEFT JOIN rent_product rp ON m.rp_id = rp.rp_id
                   WHERE m.machine_id = '$machine_id'";
    $result = $con->query($machineSql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
    } else {
        header('Location: error_page.php'); // Redirect to an error page if machine_id is not found
        exit();
    }
} else {
    header('Location: error_page.php'); // Redirect to an error page if machine_id is not provided
    exit();
}
?>
    <div class="machine-details">
        <img src="uploads/<?php echo $row['machine_image']; ?>" alt="<?php echo $row['machine_name']; ?>" width="200" height="200">
        <div class="machine-details-content">
            <div class="discount">-<?php echo $row['discount']; ?>%</div>
            <div class="price-container">
                <div class="price">₹<?php echo $row['sales_price']; ?></div>
            </div>
            <p>About this item</p>
            <p class="product-description"><?php echo $row['description']; ?></p>
            <a href="order.php?machine_id=<?php echo $row['machine_id']; ?>"><div class="btn btn-primary">Buy Now</div></a>
            <div class="rent-details">₹<?php echo $row['fare_per_hour']; ?>/hour</div>
            <div class="rent-details">₹<?php echo $row['fare_per_day']; ?>/day</div>
            <p>For renting the machines contact the krishibhavan</p>
            <p>Contact No: 0487 2272563</p>
        </div>
    </div>
</body>
</html>
