<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../dbconnection.php');
session_start();

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'vendor') {
    header('Location: ../index.php'); 
    exit(); 
}

$vendorEmail = $_SESSION['useremail'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Machine</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">




</head>
<?php

// Fetch vendor_id from the database based on the session email
$vendorIdquery = "SELECT v.vendor_id  FROM vendor v  INNER JOIN login l ON v.log_id = l.log_id  WHERE l.email = '$vendorEmail'";
$result = $con->query($vendorIdquery);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $vendorId = $row['vendor_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "hai";
        $productName = $_POST["productName"];
        $categoryName = $_POST['category'];
        $description = $_POST["description"];
        $capacity = $_POST["capacity"];
        $quantity = $_POST["quantity"];
        $fare_per_day = $_POST['fare_per_day'];
        $fare_per_hour = $_POST['fare_per_hour'];
        $security_amt = $_POST['security_amt'];
echo $productName;
          // Fetch category ID from the database based on the selected category name
          $categoryQuery = "SELECT type_id FROM machine_type WHERE type_name = '$categoryName'";
          $categoryResult = $con->query($categoryQuery);
  
          if ($categoryResult->num_rows > 0) {
            // echo "hello";
              $categoryRow = $categoryResult->fetch_assoc();
              $categoryId = $categoryRow['type_id'];
  echo $categoryId;
              // Check if image exists
              if (!empty($_FILES['image']) && $_FILES['image']['error'] == 4) {
                  echo "<script>alert('Image does not exist');</script>";
              } else {
                  if (!empty($_FILES['image'])) {
                      $fileName = $_FILES['image']['name'];
                      $fileSize = $_FILES['image']['size'];
                      $tmpName = $_FILES["image"]["tmp_name"];
                      $validImageExtension = ['jpg', 'jpeg', 'png'];
                      $imageExtension = explode('.', $fileName);
                      $imageExtension = strtolower(end($imageExtension));
                      
                      if (!in_array($imageExtension, $validImageExtension)) {
                          echo "<script>alert('Invalid image extension');</script>";
                      } else if ($fileSize > 1000000) {
                          echo "<script>alert('Image size is too large');</script>";
                      } else {
                          $newImageName = uniqid();
                          $newImageName = $newImageName . '.' . $imageExtension;
                          move_uploaded_file($tmpName, '../uploads/' . $newImageName);
  
                          $insert_machine = "INSERT INTO rent_product (fare_per_hour, fare_per_day, rp_name, type_id, rp_description, rp_capacity, rp_quantity, rp_image, security_amt, availability_status, vendor_id) VALUES ('$fare_per_hour', '$fare_per_day', '$productName', '$categoryId', '$description', '$capacity', '$quantity', '$newImageName', '$security_amt', 'available', '$vendorId')";
  
                          if ($con->query($insert_machine) == TRUE) {
                              echo "<script>alert('Inserted successfully!!');</script>";
                          } else {
                              echo "<script>alert('Insertion failed !!');</script>";
                          }
                      }
                  }
              }
          } else {
              // Handle the case where category is not found
              echo "<script>alert('Category not found.');</script>";
          }
      }
  } else {  
      // Handle the case where vendor_id is not found for the given email
      echo "<script>alert('Vendor ID not found .');</script>";
  }
  ?>
               

<body>
<?php
include('./navbar_vendor.php');
?>
<br><br><br><br><br><br>
    <div class="container">
    <div class="card" style="max-width: 50%; margin: 0 auto;">
            <div class="card-header">
                <h2 class="card-title">Add Rental Machine</h2>
            </div>
            <div class="card-body">
            <form action="" method="post" onsubmit="return validateForm()" enctype="multipart/form-data">

            <div class="form-group">
                <label for="productName">Product Name (Product name must be followed by the vendor name ):</label>
                
                <input type="text" class="form-control" id="productName" name="productName" >
                <div id="productNameError"  class="invalid-feedback"></div>
                <div id="productNameWarning"  class="invalid-feedback"></div>
            
            </div>

            <div class="form-group">
                    <label for="category">Category:</label>
                    <select class="form-control" id="category" name="category">
                    <option value="" disabled selected>Select category</option>
                    <option value="brushcutter">Brush Cutter</option>
                    <option value="sprayer">Sprayer</option>
                    <option value="pumpset">Pumpset</option>
                    <option value="weeder">Weeder</option>
                </select>
                <div id="categoryError"  class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description"  ></textarea>
                <div id="descriptionError"  class="invalid-feedback"></div>
                <div id="descriptionWarning"  class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="capacity">Capacity (in litre):</label>
                <input type="number" step="any" class="form-control" id="capacity" name="capacity"  >
                <div id="capacityError"  class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity (in numbers):</label>
                <input type="number" class="form-control" id="quantity" name="quantity" >
                <div id="quantityError"  class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="fare_per_hour">Fare per hour (INR):</label>
                <input type="number" step="any" class="form-control" id="fare_per_hour" name="fare_per_hour" >
                <div id="farePerHourWarning"  class="invalid-feedback"></div>
                <div id="farePerHourError"  class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="fare_per_day">Fare per day (INR):</label>
                <input type="number" step="any" class="form-control" id="fare_per_hour" name="fare_per_day" >
                <div id="farePerDayWarning"  class="invalid-feedback"></div>
                <div id="farePerDayError"  class="invalid-feedback"></div>
            </div>
            <div class="form-group">
                <label for="fare_per_hour">Security amount (INR):</label>
                <input type="number" step="any" class="form-control" id="security_amt" name="security_amt" >
                <div id="securityAmtWarning"  class="invalid-feedback"></div>
                <div id="securityAmtError"  class="invalid-feedback"></div>
            </div>


            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control-file" id="image" name="image" accept=".jpg, .jpeg, .png" onsubmit="return validateFile();">
                
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

  
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



</body>
</html>



