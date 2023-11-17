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
    <title>View Products</title>
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

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4caf50;
            color: white;
        }
            a button {
        /*background-color: #4CAF50; /* Green color */
        background-color:red;
        color: white;
        padding: 8px 16px;
        border: none;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 4px;
    }

    a button:hover {
        background-color: #45a049; /* Darker green color on hover */
    }
    </style>
</head>
<body>
<?php
include('navbar/navbar_admin.php');
?>
<h2>View Machines</h2>
<?php
// Retrieve products data from the database
$sql = "SELECT machines.*, machines.machine_id,machine_type.type_name, buy_product.product_price, buy_product.discount, buy_product.sales_price, rent_product.fare_per_hour, rent_product.fare_per_day 
        FROM machines 
        LEFT JOIN machine_type ON machines.type_id = machine_type.type_id
        LEFT JOIN buy_product ON machines.bp_id = buy_product.bp_id
        LEFT JOIN rent_product ON machines.rp_id = rent_product.rp_id";

$result = $con->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Product Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Capacity (litre)</th>
                <th>Quantity</th>
                <th>Product Price</th>
                <th>Discount (%)</th>
                <th>Sales Price</th>
                <th>Fare Per Hour</th>
                <th>Fare Per Day</th>
                <th>Image</th>
                <th>User Log</th>
                <th>Action</th>
                <th>Stock</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['machine_name']}</td>
                <td>{$row['type_name']}</td>
                <td>{$row['description']}</td>
                <td>{$row['capacity']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['product_price']}</td>
                <td>{$row['discount']}</td>
                <td>{$row['sales_price']}</td>
                <td>{$row['fare_per_hour']}</td>
                <td>{$row['fare_per_day']}</td>
                <td><img src='uploads/{$row['machine_image']}' alt='Machine Image' style='max-width: 100px;'></td>

                <td>{$row['userlog']}</td>
                <td>
                <a href='edit_product.php?id={$row['machine_id']}'><button>Edit</button></a></td>
               
            </td>

            </tr>";
    }

    echo "</table>";
} else {
    echo "No products found.";
}

$con->close();
?>

</body>
</html>
