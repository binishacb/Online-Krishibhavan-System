<?php
session_start();
include('dbconnection.php');
if (!isset($_SESSION['useremail'])) {
    header('Location: index.php'); // Redirect to index.php
    exit(); // Stop further execution of the current script
}

if (isset($_GET['id'])) {
    $machine_id = $_GET['id'];

    // Retrieve machine data based on the provided machine ID
    $sql = "SELECT machines.*, machine_type.type_name, buy_product.product_price, buy_product.discount, buy_product.sales_price, rent_product.fare_per_hour, rent_product.fare_per_day 
            FROM machines 
            LEFT JOIN machine_type ON machines.type_id = machine_type.type_id
            LEFT JOIN buy_product ON machines.bp_id = buy_product.bp_id
            LEFT JOIN rent_product ON machines.rp_id = rent_product.rp_id
            WHERE machine_id = $machine_id";

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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and update the editable fields
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $product_price = $_POST['product_price'];
    $discount = $_POST['discount'];
    $fare_per_hour = $_POST['fare_per_hour'];
    $fare_per_day = $_POST['fare_per_day'];
    
    $updateMachines= "UPDATE machines SET description = '$description', quantity = '$quantity' WHERE machine_id = $machine_id";
    // Perform update query
    $updateBuyProduct = "UPDATE buy_product SET product_price = '$product_price', discount = '$discount' WHERE bp_id = (SELECT bp_id FROM machines WHERE machine_id = $machine_id)";

    // Update rent_product table
    $updateRentProduct = "UPDATE rent_product SET fare_per_hour = '$fare_per_hour', fare_per_day = '$fare_per_day' WHERE rp_id = (SELECT rp_id FROM machines WHERE machine_id = $machine_id)";


    if ($con->query($updateMachines) == TRUE && $con->query($updateBuyProduct) == TRUE && $con->query($updateRentProduct) == TRUE){
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $con->error;
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
    <title>Edit Machine</title>
    <style>
        /* Your existing styles here */

h2 {
    text-align: center;
    color: #333;
}

form {

    max-width: 500px; 
    margin: 0px auto;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    border-radius: 8px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

input {
    width: 100%;
    padding: 8px;
    margin-bottom: 12px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 25px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    display: block;
    margin: auto;
}

button:hover {
    background-color: #45a049;
}

    </style>
</head>
<body>
<?php
include('navbar/navbar_admin.php');
?>
<br><br>
<h2>Edit details</h2><br><br>
<form method="post" action="">
    <label for="product_name">Product Name</label>
    <input type="text" name="product_name" value="<?php echo $row['machine_name']; ?>" readonly>

    <label for="product_category">Category</label>
    <input type="text" name="category" value="<?php echo $row['type_name']; ?>"readonly>

    <label for="description">Description:</label>
    <input type="text" name="description" value="<?php echo $row['description']; ?>" required>

    <label for="quantity">Quantity:</label>
    <input type="text" name="quantity" value="<?php echo $row['quantity']; ?>" required>

    <label for="product_price">Product Price:</label>
    <input type="text" name="product_price" value="<?php echo $row['product_price']; ?>" required>

    <label for="discount">Discount (%):</label>
    <input type="text" name="discount" value="<?php echo $row['discount']; ?>" required>
    
    <?php if ($row['fare_per_hour'] != null): ?>
    <label for="fare_per_hour">Fare Per Hour:</label>
    <input type="text" name="fare_per_hour" value="<?php echo $row['fare_per_hour']; ?>" required>
    <?php endif; ?>

    <?php if($row['fare_per_day'] != null): ?>
    <label for="fare_per_day">Fare Per Day:</label>
    <input type="text" name="fare_per_day" value="<?php echo $row['fare_per_day']; ?>" required>
    <?php endif; ?>

    <br><br><br>
   <center> <button type="submit">Update</button> </center>
</form>
</body>
</html>
