<?php 
session_start();
include('dbconnection.php');

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'farmer') {
  header('Location:index.php');
  exit();
}

?>

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
    .error-message {
      color: red;
    }
  </style>
</head>
<body>

<?php include('navbar/navbar_farmer.php'); ?>



<?php 
    
    if (isset($_GET['machine_id']) && isset($_GET['quantity']) && isset($_GET['total_price'])) {
      $machine_id = $_GET['machine_id'];
      $quantity = $_GET['quantity'];
      $total_price = $_GET['total_price'];
      

      $email = $_SESSION['useremail'];

      $query = "SELECT farmer.farmer_id FROM farmer
                JOIN login ON farmer.log_id = login.log_id
                WHERE login.email = '$email'";
      
      $queryResult = mysqli_query($con, $query);
  
      if ($queryResult) {
          $row = mysqli_fetch_assoc($queryResult);
          $farmer_id = $row['farmer_id'];
      $sql = "SELECT * FROM machines m INNER JOIN buy_product bp ON m.bp_id = bp.bp_id WHERE machine_id = $machine_id"; 
      $result = mysqli_query($con, $sql);
      $row = mysqli_fetch_array($result);

      if (isset($_POST['submit'])) {
       
        $address = $_POST['address'];
        $phone_no = $_POST['phone'];
        echo "Address: " . $address . "<br>";
        echo "Phone Number: " . $phone_no . "<br>";
        $order_query = "INSERT INTO shipping_address (order_id, address,phone_no,farmer_id,machine_id,quantity,total_price,status) VALUES ('$orderId', '$address','$phone_no','$farmer_id','$machine_id','$quantity','$total_price','1')";
        $order_query_run = mysqli_query($con, $order_query);

        
        if ($order_query_run) {
          echo "<script>alert('Order placed successfully');</script>";
          echo "<script>window.location.href='buy.php?order_id=$orderId&machine_id=$machine_id';</script>";
          exit();
        } else {
          echo "<script>alert('Order failed');</script>";
        }
      }
    }
    else{
      echo "<script>alert('Farmer id not found')</script>";
    }
  }
    ?>

<div class="container mt-3">
  <div class="row">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-10 mb-5">
          <h2 class="text-center p-2 text-primary">Fill in the details to complete your order</h2>
          <h4>Product Details:</h4>

          <table class="table table-bordered" width="500px">
              <tr>
                <th>Product Name:</th>
                <td><?php echo $row['machine_name']?></td>
                <td rowspan="4" class="text-center">
                  <img src="uploads/<?php echo $row['machine_image']; ?>" alt="<?php echo $row['machine_name']; ?>" width="200" height="200">
                </td>
              </tr>
              <tr>
                  <th>Quantity:</th>
                  <td><?php echo $quantity; ?></td>
              </tr>
              <tr>
                <th>Total Price: </th>
                <td>M.R.P.: ₹<?php echo $total_price; ?></td>
              </tr>
            </table>



          <h4>Enter your details</h4>
          <form action="" method="POST" accept-charset="utf-8" onsubmit="return validateForm()">
            <div class="form-group">
              <label for="address">Delivery Address:</label>
              <input type="text" id="address" name="address" class="form-control" placeholder="Enter your full address" oninput="validateAddress()">
              <div id="addressError" class="error-message"></div>
            </div>
            <div class="form-group">
              <label for="phone">Phone Number:</label>
              <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter your phone number" oninput="validatePhoneNumber()">
              <div id="phoneError" class="error-message"></div>
            </div>
            <button type="submit" name="submit" id="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function validateAddress() {
    var addressInput = document.getElementById('address').value;
    var addressError = document.getElementById('addressError');

    // Check if the input is not empty
    if (addressInput.trim() === '') {
      addressError.textContent = 'Address is required';
      return false;
    }

    // Check if the address length is between 5 and 100 characters
    if (addressInput.length < 5 || addressInput.length > 100) {
      addressError.textContent = 'Address should be between 5 and 100 characters.';
      return false;
    }

    // Check if the address contains only letters, numbers, spaces, and common punctuation
    if (!/^[a-zA-Z0-9\s\.,-]*$/.test(addressInput)) {
      addressError.textContent = 'Invalid characters in the address.';
      return false;
    }

    // Clear any previous error message if validation passes
    addressError.textContent = '';
    return true;
  }

  function validatePhoneNumber() {
    var phoneInput = document.getElementById('phone').value;
    var phoneError = document.getElementById('phoneError');
    var phonePattern = /^\d{10}$/;

    // Check if the input is not empty
    if (phoneInput === '') {
      phoneError.textContent = 'Phone number is required';
      return false;
    }

    // Check if the phone number is exactly 10 digits
    if (!phonePattern.test(phoneInput)) {
      phoneError.textContent = 'Please enter a valid 10-digit phone number';
      return false;
    }

    // Clear any previous error message if validation passes
    phoneError.textContent = '';
    return true;
  }

  function validateForm() {
    var addressInput = document.getElementById('address').value.trim();
    var phoneInput = document.getElementById('phone');
    var addressError = document.getElementById('addressError');
    var phoneError = document.getElementById('phoneError');
    var phonePattern = /^\d{10}$/;

    // Validate address
    if (!validateAddress()) {
      return false;
    }

    // Validate phone number
    if (!validatePhoneNumber()) {
      return false;
    }

    // Clear any previous error message if validation passes
    return true;
  }
</script>

</body>
</html>
