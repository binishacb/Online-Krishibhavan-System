<?php 
session_start();
include('dbconnection.php');

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'farmer') {
  header('Location:index.php');
  exit();
}

?>
    <?php 
    
    if (isset($_GET['machine_id']) && isset($_GET['m_quantity']) && isset($_GET['total_price'])) {
      $machine_id = $_GET['machine_id'];
      $quantity = $_GET['m_quantity'];
      $total_price = $_GET['total_price'];
      echo $total_price;
      $sql = "SELECT * FROM machines m INNER JOIN buy_product bp ON m.bp_id = bp.bp_id WHERE machine_id = $machine_id"; 
      $result = mysqli_query($con, $sql);
      $row = mysqli_fetch_array($result);

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $address = $_POST['address'];
        $order_query = "INSERT INTO shipping_address (order_id, address) VALUES ('$orderId', '$address')";
        $order_query_run = mysqli_query($con, $order_query);

        $order_id = mysqli_insert_id($con);
        if ($order_query_run) {
          echo "<script>alert('Order placed successfully');</script>";
          echo "<script>window.location.href='buy.php?order_id=$order_id&machine_id=$machine_id';</script>";
          exit();
        } else {
          echo "<script>alert('Order failed');</script>";
        }
      }
    }
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Order</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    button {
      background-color: #4CAF50;
      color: white; 
      padding: 10px 20px; 
      font-size: 16px; 
      border: none;
      border-radius: 4px; 
      cursor: pointer;
    }

    button:hover {
      background-color: #45a049; 
    }
  </style>
</head>
<body>

<?php 
include('navbar/navbar_farmer.php'); 
?>

<div class="container mt-3">
  <div class="row">
    <?php
    function generateRandomString($length) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $randomString = '';

        // Add 3 random letters
        for ($i = 0; $i < 3; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Add 6 random numbers
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $numbers[rand(0, strlen($numbers) - 1)];
        }

        return $randomString;
    }

    $orderId = generateRandomString(9);
    ?>


 <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-10 mb-5">
          <h2 class="text-center p-2 text-primary">Fill in the details to complete your order</h2>
         
            <h3>Product Details:</h3>
            <table class="table table-bordered" width="500px">
              <tr>
                <th>Product Name:</th>
                <td><?php echo $row['machine_name']?></td>
                <td rowspan="4" class="text-center">
                  <img src="uploads/<?php echo $row['machine_image']; ?>" alt="<?php echo $row['machine_name']; ?>" width="200" height="200">
                </td>
              </tr>
              <tr>
                <th>Total Price: </th>
                <td>M.R.P.: ₹<?php echo $total_price; ?></td>
              </tr>
            </table>
          
          <h4>Enter your details</h4>
          <form action="" method="POST" accept-charset="utf-8">
            <div class="form-group">
              <label for="address">Address:</label>
              <input type="textbox" id="address" name="address" class="form-control" placeholder="Enter your address" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
