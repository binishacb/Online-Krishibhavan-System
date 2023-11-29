<?php 
include('dbconnection.php');
$pid=$_GET['machine_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>order</title>
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

<div class="container mt-3">
<div class="row">
<?php 
$sql="SELECT * from machines m  INNER JOIN buy_product bp on m.bp_id=bp.bp_id WHERE machine_id=$pid"; 
$result=mysqli_query($con,$sql);
$row=mysqli_fetch_array($result);
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $address = $_POST['address'];
  $phone = $_POST['phone'];
  $pin = $_POST['pin'];
  $district = $_POST['district'];
  $place = $_POST['place'];

echo $name;
  // Insert data into the shipping_address table
  $order_query = "INSERT INTO shipping_address (name, address, phone_number, pin_code, district, place) VALUES ('$name', '$address', '$phone', '$pin', '$district', '$place')";
  $order_query_run = mysqli_query($con, $order_query);

  $order_id= mysqli_insert_id($con);
  echo($order_id);

if($order_query_run){
  echo "<script>alert('order placed successfully');</script>";
  header("Location: buy.php?order_id=$order_id&machine_id=$pid");
  exit();
}
else{
  echo "<script>alert('order failed');</script>";
}

}
?>


<div class="container">
            <div class = "row justify-content-center">
                <div class="col-md-10 mb-5">
                    <h2 class="text-center p-2 text-primary">Fill the details to complete your order</h2>
                    <h3>product details : </h3>
                    <table class="table table-bordered" width="500px">
                        <tr>
                            <th>Product Name:</th>
                        <td><?php echo $row['machine_name']?></td>
                        <td rowspan="4" class="text-center">  <img src="uploads/<?php echo $row['machine_image']; ?>" alt="<?php echo $row['machine_name']; ?>" width="200" height="200"></td>
                        </tr>
                        <tr>
                            <th>Total Price: </th>
                            <td><?php echo $row['sales_price']?></td>

                        </tr>


                    </tr>
                    </table>
                    <h4>Enter your details</h4>
                    <form action="" method="POST" accept-charset="utf-8">
                      <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required>
                      </div>
                      <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" class="form-control" placeholder="Enter your address" required>
                      </div>
                      <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="number" id="phone" name="phone" class="form-control" placeholder="Enter your phone number" required>
                      </div>
                      <div class="form-group">
                        <label for="pin">Pin Code:</label>
                        <input type="number" id="pin" name="pin" class="form-control" placeholder="Enter your pin code" required>
                      </div>
                      <div class="form-group">
                        <label for="district">District:</label>
                        <input type="text" id="district" name="district" class="form-control" placeholder="Enter your District" required>
                      </div>
                      <div class="form-group">
                        <label for="place">Place:</label>
                        <input type="text" id="place" name="place" class="form-control" placeholder="Enter your place" required>
                      </div>
                      <button type="submit" name="submit" class="btn btn-primary">Place order</button>
                    </form>
                    


</div>
</div>                                         
</body>
</html>
