<?php
include('dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   
</head>

<body>
<?php
    include('./vendor_details/navbar_vendor.php');
?>
    <div class="container mt-4">
        <h2>View Machines</h2>
        <?php
     
        $sql = "SELECT machines.*, machines.machine_id, machine_type.type_name, buy_product.product_price, buy_product.discount, buy_product.sales_price, rent_product.fare_per_hour, rent_product.fare_per_day 
                FROM machines 
                LEFT JOIN machine_type ON machines.type_id = machine_type.type_id
                LEFT JOIN buy_product ON machines.bp_id = buy_product.bp_id
                LEFT JOIN rent_product ON machines.rp_id = rent_product.rp_id  WHERE machines.m_quantity > 0";

        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            echo "<div class='table-responsive'>
                    <table class='table table-striped table-hover'>
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                                
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
                            </tr>
                        </thead>
                        <tbody>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['machine_name']}</td>
                        <td>{$row['type_name']}</td>
                       
                        <td>{$row['capacity']}</td>
                        <td>{$row['m_quantity']}</td>
                        <td>{$row['product_price']}</td>
                        <td>{$row['discount']}</td>
                        <td>{$row['sales_price']}</td>
                        <td>{$row['fare_per_hour']}</td>
                        <td>{$row['fare_per_day']}</td>
                        <td><img src='uploads/{$row['machine_image']}' alt='Machine Image' style='max-width: 100px;'></td>
                        <td>{$row['userlog']}</td>
                        <td>
                            <a href='edit_product.php?id={$row['machine_id']}' class='btn btn-primary'>Edit</a>
                        </td>
                    </tr>";
            }

            echo "</tbody></table></div>";
        } else {
            echo "<p class='alert alert-info'>No products found.</p>";
        }

        $con->close();
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
