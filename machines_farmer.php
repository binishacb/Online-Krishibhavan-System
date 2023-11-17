<?php
session_start();
include('dbconnection.php');
if (!isset($_SESSION['useremail'])) {
    header('Location: index.php'); // Redirect to index.php
    exit(); // Stop further execution of the current script
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Machines</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .machine-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .machine-card {
            width: 300px;
            margin: 20px;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }

        .machine-card img {
            max-width: 100%;
            border-radius: 4px;
        }
        .price-container {
            display: flex;
           /* justify-content: space-between; */
            margin-top: 10px;
        }

        .discount {
            font-size: 18px;
            color: #e44d26; /* Your desired color for discount text */
        }

        .actual-price {
            font-weight: bold;
        }
        .product_price{
            margin-top:5px;
            margin-right:2px;
            text-decoration: line-through;
            display:inline-block;
        }
      
    </style>
</head>
<body>
<?php
include('navbar/navbar_admin.php');
?>
<h2>View Machines in Stock</h2>

<div class="machine-container">
    <?php
    // Retrieve machines data from the database for those in stock
    $sql = "SELECT m.machine_name, m.machine_image,bp.product_price,bp.discount,bp.sales_price FROM machines m INNER JOIN buy_product bp on m.bp_id = bp.bp_id  ";
    // WHERE stock > 0";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='machine-card'>
                    <img src='uploads/{$row['machine_image']}' alt='{$row['machine_name']}' style='max-width: 100%;'>
                    <h3>{$row['machine_name']}</h3>
                    <div class='price-container'>
                    <div class='discount'>-{$row['discount']}%</div> 
                    <div class='actual-price'>₹{$row['sales_price']}</div>
                    </div>

                    M.R.P.:<div class='product_price'> ₹{$row['product_price']}</div>
                    <div class='btn btn-primary'>Buy Now</div>
                
                    
                </div>";
        }
    } else {
        echo "No machines in stock.";
    }

    $con->close();
    ?>
</div>

</body>
</html>
