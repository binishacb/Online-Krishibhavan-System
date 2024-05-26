<?php
include('../dbconnection.php');
session_start();
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'vendor') {
    header('Location: ../index.php'); 
    exit(); 
}

$vendorEmail = $_SESSION['useremail'];

?>

<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Machine</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>

<?php

// Fetch vendor_id from the database based on the session email
$vendorIdquery = "SELECT v.vendor_id  FROM vendor v  INNER JOIN login l ON v.log_id = l.log_id  WHERE l.email = '$vendorEmail'";
$result = $con->query($vendorIdquery);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $vendorId = $row['vendor_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // echo "hai";
        $productName = $_POST["productName"];
        $categoryName = $_POST['category'];
        $description = $_POST["description"];
        $capacity = $_POST["capacity"];
        $quantity = $_POST["quantity"];
        $fare_per_day = $_POST['fare_per_day'];
        $fare_per_hour = $_POST['fare_per_hour'];
        $security_amt = $_POST['security_amt'];
//echo $productName;
          
          $categoryQuery = "SELECT type_id FROM machine_type WHERE type_name = '$categoryName'";
          $categoryResult = $con->query($categoryQuery);
  
          if ($categoryResult->num_rows > 0) {
            // echo "hello";
              $categoryRow = $categoryResult->fetch_assoc();
              $categoryId = $categoryRow['type_id'];
  //echo $categoryId;
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
             
              echo "<script>alert('Category not found.');</script>";
          }
      }
  } else {  
     
      echo "<script>alert('Vendor ID not found .');</script>";
  }
  ?>


<?php
include('./navbar_vendor.php');
?>
<br><br><br><br><br><br>
<div class="container">
    <div class="card" style="max-width: 80%; margin: 0 auto;">
        <div class="card-header">
            <h2 class="card-title">Add Rental Machine</h2>
        </div>
        <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="productName">Product Name:</label>
                            <input type="text"
                                   oninput="validateProductName(this.value)"
                                   class="form-control" id="productName" name="productName">
                            <span id="productNameError" class="error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category">Category:</label>
                            <select 
                                oninput="validatecategory(this.value)"
                                class="form-control" id="category" name="category">
                                <option value="" disabled selected>Select category</option>
                                <option value="brushcutter">Brush Cutter</option>
                                <option value="sprayer">Sprayer</option>
                                <option value="pumpset">Pumpset</option>
                                <option value="weeder">Weeder</option>
                            </select>
                            <span id="categoryError" class="error"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea 
                        oninput="validatedescription(this.value)"
                        class="form-control" id="description" name="description"></textarea>
                    <span id="descriptionError" class="error"></span>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="capacity">Capacity (in litre):</label>
                            <input type="number"  oninput="validateCapacity(this.value)" step="any" class="form-control" id="capacity" name="capacity">
                            <span id="capacityError" class="error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="quantity">Quantity (in numbers):</label>
                            <input type="number"   oninput="validatequantity(this.value)" class="form-control" id="quantity" name="quantity">
                            <span id="quantityError" class="error"></span>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fare_per_hour">Fare per hour (INR):</label>
                            <input type="number"  oninput="validatefareperhour(this.value)" step="any" class="form-control" id="fare_per_hour" name="fare_per_hour">
                            <span id="fare_per_hourError" class="error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fare_per_day">Fare per day (INR):</label>
                            <input type="number"  oninput="validatefareperday(this.value)" step="any" class="form-control" id="fare_per_day" name="fare_per_day">
                            <span id="fare_per_dayError" class="error"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="security_amt">Security amount (INR):</label>
                    <input type="number"  oninput="validateSecurity(this.value)" step="any" class="form-control" id="security_amt" name="security_amt">
                    <span id="security_amtError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="image">Image:</label>
                    <input type="file"  oninput="validateImage(this.value)"  class="form-control-file" id="image" name="image" accept=".jpg, .jpeg, .png">
                    <span id="imageError" class="error"></span>
                </div>
                <div class="text-center mt-3">
    <button type="submit" name="submit" class="btn btn-primary mr-2">Submit</button>
    <button type="reset" class="btn btn-secondary" >Clear</button>
</div>

            </form>
        </div>
    </div>
</div>


    <script>
    function validateProductName(productName) {
        const productNameError = document.getElementById('productNameError');
        if (productName.trim() === '') {
            productNameError.textContent = 'Product Name is required.';
            return false;
        } else {
            productNameError.textContent = '';
            return true;
        }
    }

    function validatecategory(category) {
        const categoryError = document.getElementById('categoryError');
        if (category === '') {
            categoryError.textContent = 'Category is required.';
            return false;
        } else {
            categoryError.textContent = '';
            return true;
        }
    }

    function validatedescription(description) {
        const descriptionError = document.getElementById('descriptionError');
        if (description.trim() === '') {
            descriptionError.textContent = 'Description is required.';
            return false;
        } else {
            descriptionError.textContent = '';
            return true;
        }
    }

    function validateCapacity(capacity) {
    const capacityError = document.getElementById('capacityError');
    if (capacity.trim() === '') {
        capacityError.textContent = 'Capacity is required.';
        return false;
    } else if (parseFloat(capacity) <= 0) {
        capacityError.textContent = 'Capacity must be greater than zero.';
        return false;
    } else {
        capacityError.textContent = '';
        return true;
    }
}

function validatequantity(quantity) {
    const quantityError = document.getElementById('quantityError');
    if (quantity.trim() === '') {
        quantityError.textContent = 'Quantity is required.';
        return false;
    } else if (parseInt(quantity) <= 0) {
        quantityError.textContent = 'Quantity must be greater than zero.';
        return false;
    } else {
        quantityError.textContent = '';
        return true;
    }
}

function validatefareperhour(farePerHour) {
    const farePerHourError = document.getElementById('fare_per_hourError');
    if (farePerHour.trim() === '') {
        farePerHourError.textContent = 'Fare per hour is required.';
        return false;
    } else if (parseFloat(farePerHour) <= 0) {
        farePerHourError.textContent = 'Fare per hour must be greater than zero.';
        return false;
    } else {
        farePerHourError.textContent = '';
        return true;
    }
}

function validatefareperday(farePerDay) {
    const farePerDayError = document.getElementById('fare_per_dayError');
    if (farePerDay.trim() === '') {
        farePerDayError.textContent = 'Fare per day is required.';
        return false;
    } else if (parseFloat(farePerDay) <= 0) {
        farePerDayError.textContent = 'Fare per day must be greater than zero.';
        return false;
    } else {
        farePerDayError.textContent = '';
        return true;
    }
}

function validateSecurity(securityAmt) {
    const securityAmtError = document.getElementById('security_amtError');
    if (securityAmt.trim() === '') {
        securityAmtError.textContent = 'Security amount is required.';
        return false;
    } else if (parseFloat(securityAmt) <= 100) {
        securityAmtError.textContent = 'Security amount must be greater than 100.';
        return false;
    } else {
        securityAmtError.textContent = '';
        return true;
    }
}

    function validateImage(image) {
        const imageError = document.getElementById('imageError');
        if (image.trim() === '') {
            imageError.textContent = 'Image is required.';
            return false;
        } else {
            imageError.textContent = '';
            return true;
        }
    }



    function validateForm() {
        const productName = document.getElementById('productName').value;
        const category = document.getElementById('category').value;
        const description = document.getElementById('description').value;
        const capacity = document.getElementById('capacity').value;
        const quantity = document.getElementById('quantity').value;
        const farePerHour = document.getElementById('fare_per_hour').value;
        const farePerDay = document.getElementById('fare_per_day').value;
        const securityAmt = document.getElementById('security_amt').value;
        const image = document.getElementById('image').value;
        
        // Get other form field values similarly

        // Perform validation checks for all fields
        const isProductNameValid = validateProductName(productName);
        const isCategoryValid = validatecategory(category);
        const isDescriptionValid = validatedescription(description);
        const isCapacityValid = validateCapacity(capacity);
        const isQuantityValid = validatequantity(quantity);
        const isFarePerHourValid = validatefareperhour(farePerHour);
        const isFarePerDayValid = validatefareperday(farePerDay);
        const isSecurityAmtValid = validateSecurity(securityAmt);
        const isImageValid = validateImage(image);
        // Perform validation for other fields similarly

        // Return true only if all validations pass
        return isProductNameValid && isCategoryValid && isDescriptionValid && isCapacityValid && isQuantityValid && isFarePerHourValid && isFarePerDayValid && isSecurityAmtValid && isImageValid;;
        // Include validations for other fields similarly
    }
</script>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>

</body>
</html>