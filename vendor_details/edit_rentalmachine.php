<?php
include('../dbconnection.php');
session_start();
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'vendor') {
    header('Location: ../index.php'); 
    exit(); 
}
if (isset($_GET['id'])) {
    $machine_id = $_GET['id'];

    // Retrieve machine data based on the provided machine ID
    $sql = "SELECT rent_product.*, machine_type.type_name
            FROM rent_product 
            LEFT JOIN machine_type ON rent_product.type_id = machine_type.type_id
            WHERE rp_id = $machine_id";

    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Machine not found.";
        exit();
    }
} else {
    echo "Invalid request. Machine ID not provided.";
    exit();
}

// Handle form submission for updates
if (isset($_POST['update'])) {
    // Validate and update the editable fields
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $product_price = $_POST['fare_per_hour'];
    
 
    
    $updateMachines= "UPDATE rent_product SET rp_description = '$description', rp_quantity = '$quantity',fare_per_hour = '$product_price' WHERE rp_id = $machine_id";
    // Perform update query
   // $updateBuyProduct = "UPDATE buy_product SET product_price = '$product_price', discount = '$discount' WHERE bp_id = (SELECT bp_id FROM machines WHERE machine_id = $machine_id)";

    // Update rent_product table
   // $updateRentProduct = "UPDATE rent_product SET fare_per_hour = '$fare_per_hour', fare_per_day = '$fare_per_day' WHERE rp_id = (SELECT rp_id FROM machines WHERE machine_id = $machine_id)";


    if ($con->query($updateMachines) == TRUE ){
        echo "<script>alert('Record updated successfully')</script>";
    } else {
        echo "<script>alert('Error updating record:  . $con->error')</script>";
    }
}
  
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Details</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php
    include('navbar_vendor.php');
    ?>
    <br><br>
   <div class="container pt-4  ">
   <div class="card mt-5" style="max-width: 50%; margin: 0 auto;">
   <div class="card-body">
  
   <h1 class="card-title text-center">Edit details</h1>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" class="form-control" name="product_name" value="<?php echo $row['rp_name']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="product_category">Category:</label>
                <input type="text" class="form-control" name="category" value="<?php echo $row['type_name']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" class="form-control" name="description" value="<?php echo $row['rp_description']; ?>" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity (in No):</label>
                <input type="text" class="form-control" name="quantity" value="<?php echo $row['rp_quantity']; ?>" required>
            </div>

            <div class="form-group">
                <label for="fare_per_hour">Fare per hour(INR):</label>
                <input type="text" class="form-control" name="fare_per_hour" value="<?php echo $row['fare_per_hour']; ?>" required>
            </div>

        

            <div class="mt-3 text-center">
                <button type="submit" name="update" id="update" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
   </div>
    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
