<?php
include('../dbconnection.php');
session_start();
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'vendor') {
    header('Location: ../index.php'); 
    exit(); 
}
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

<body>
    <?php
include('./navbar_vendor.php');


$vendorEmail = $_SESSION['useremail'];
$vendorIdquery = "SELECT v.vendor_id  FROM vendor v  INNER JOIN login l ON v.log_id = l.log_id  WHERE l.email = '$vendorEmail'";
$result = $con->query($vendorIdquery);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $vendorId = $row['vendor_id'];



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST["productName"];
    $categoryName = $_POST["category"];
    $description = $_POST["description"];
    $capacity = $_POST["capacity"];
    $quantity = $_POST["quantity"];
 

    $categoryId = "SELECT type_id FROM machine_type WHERE type_name = '$categoryName'";
    $pdfPath = ''; 
    if (!empty($_FILES['userLog'])) {
        $userLogFile = $_FILES['userLog'];
        
        // Check if there was no error during file upload
        if ($userLogFile['error'] == UPLOAD_ERR_OK) {
            $tmpName = $userLogFile['tmp_name'];
            $pdfName = uniqid() . '_' . $userLogFile['name']; // Unique name to avoid conflicts
            $pdfPath = '../uploads/' . $pdfName; // Specify the directory where you want to store the file

            // Move the uploaded file to the specified directory
            move_uploaded_file($tmpName, $pdfPath);
        } else {
            // Handle file upload error
            echo "<script>alert('File upload error');</script>";
        }
    }

    if (!empty($_FILES['image']) && $_FILES['image']['error'] == 4) {
        echo "<script>alert('Image does not exist');</script>";
    }
    else{
        if (!empty($_FILES['image'])) {
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $tmpName = $_FILES["image"]["tmp_name"];
        $validImageExtension = ['jpg','jpeg','png'];
        $imageExtension = explode('.',$fileName);
        $imageExtension = strtolower(end($imageExtension));
        if(!in_array($imageExtension,$validImageExtension)){   
            echo "<script>alert('Invalid image extension');</script>";
        }
        else if($fileSize  > 1000000)
        {
            echo "<script>alert('Image size is too large');</script>";
        }
        else{
            $newImageName = uniqid();
            $newImageName = $newImageName . '.' . $imageExtension;
            move_uploaded_file($tmpName,'../uploads/'. $newImageName);

            $insert_machine = "INSERT INTO machines (machine_name, type_id, description, capacity, m_quantity, machine_image,userlog,vendor_id) VALUES ('$productName', (SELECT type_id FROM machine_type WHERE type_name = '$categoryName'), '$description', '$capacity', '$quantity', '$newImageName','$pdfPath',' $vendorId')";
           
            if ($con->query($insert_machine) == TRUE) {
                $lastMachineId = $con->insert_id;
              
            if (isset($_POST["action"])) {
                // Check if "buy" is in the array
                if (in_array("buy", $_POST["action"])) {
            
                    $productPrice = $_POST["productPrice"];
                    $discount = $_POST["discount"];
                    $salesPrice = $_POST["salesPrice"];
                    
                    // Insert into 'buy_machines' table with foreign key reference
                    $sqlBuy = "INSERT INTO buy_product (bp_id, product_price, discount, sales_price) VALUES ('$lastMachineId', '$productPrice', '$discount', '$salesPrice')";
                    
                    if ($con->query($sqlBuy) !== TRUE) {
                        echo "Error inserting into buy_machines: " . $con->error;
                    }
                    
                    // Get the last inserted bp_id and update 'machines' table
                    $bpIdResult = $con->query("SELECT LAST_INSERT_ID() AS bp_id");
                    if ($bpIdResult->num_rows > 0) {
                        $bpRow = $bpIdResult->fetch_assoc();
                        $bpId = $bpRow["bp_id"];

                        // Update 'machines' table with the fetched bp_id
                        $con->query("UPDATE machines SET bp_id = '$bpId' WHERE machine_id = '$lastMachineId'");
                    }
        }

                    // Check if "rent" is in the array
                    if (in_array("rent", $_POST["action"])) {
                        // Process rent action
                        $farePerHour = $_POST["farePerHour"];
                       $rp_quantity = $_POST['rp_quantity'];
                       $security_amt  = $_POST['security_amt'];
                      
                        $sqlRent = "INSERT INTO rent_product (rp_id, fare_per_hour,rp_quantity,rp_name,type_id,rp_description,rp_capacity,rp_image,availability_status,security_amt,vendor_id) VALUES ('$lastMachineId', '$farePerHour', '$rp_quantity','$productName',  (SELECT type_id FROM machine_type WHERE type_name = '$categoryName'),'$description', '$capacity', '$newImageName','available','$security_amt','$vendorId' )";
                        
                        if ($con->query($sqlRent) !== TRUE) {
                            echo "Error inserting into rent_machines: " . $con->error;
                        }
                        else{
                        // Get the last inserted rp_id and update 'machines' table
                        $rpIdResult = $con->query("SELECT LAST_INSERT_ID() AS rp_id");
                        if ($rpIdResult->num_rows > 0) {
                            $rpRow = $rpIdResult->fetch_assoc();
                            $rpId = $rpRow["rp_id"];

                            // Update 'machines' table with the fetched rp_id
                            $con->query("UPDATE machines SET rp_id = '$rpId' WHERE machine_id = '$lastMachineId'");
                        }
                    }}

                    echo "<script>alert('Inserted successfully');</script>";
                } else {
                    echo "<script>alert('No action selected');</script>";
                }
                    }
                        }
                    }

    }
}
}
else{
    echo "<script>alert('Vendor id not found');</script>";

}
?>  
<br><br><br><br><br><br>
    <div class="container">
    <div class="card" style="max-width: 50%; margin: 0 auto;">
            <div class="card-header">
                <h2 class="card-title">Add Machine</h2>
            </div>
            <div class="card-body">
                <form action="" method="post" onsubmit="return validateForm()" enctype="multipart/form-data">
            <div class="form-group">
                <label for="productName">Product Name:</label>
                
                <input type="text" class="form-control" id="productName" name="productName" required oninput="validateProductName()">
                <div id="productNameError"  class="invalid-feedback"></div>
                <div id="productNameWarning"  class="invalid-feedback"></div>
            
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="" disabled selected>Select category</option>
                    <option value="tractor">Brush Cutter</option>
                    <option value="sprayer">Sprayer</option>
                    <option value="pumpset">Pumpset</option>
                    <option value="weeder">Weeder</option>
                </select>
                <div id="categoryError"  class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required oninput="validateDescription()"></textarea>
                <div id="descriptionError"  class="invalid-feedback"></div>
                <div id="descriptionWarning"  class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="capacity">Capacity (in litre):</label>
                <input type="number" step="any" class="form-control" id="capacity" name="capacity" required oninput="validateCapacity()">
                <div id="capacityError"  class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity (in numbers):</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required oninput="validateQuantity()">
                <div id="quantityError"  class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                <label>Choose action: </label><br>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label" for="buy">Buy</label>
                                <input class="form-check-input" type="checkbox" id="buy" name="action[]" value="buy" onclick="showFields('buy')">
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label" for="rent">Rent</label>
                                <input class="form-check-input" type="checkbox" id="rent" name="action[]" value="rent" onclick="showFields('rent')">
                            </div>
                        </div>
                    </div>

                    <div id="buyFields" style="display: none;">
                        <!-- Buy Fields -->
                        <div class="form-group">
                            <label for="productPrice">Product Price(INR):</label>
                            <input type="number" step="any" class="form-control" id="productPrice" name="productPrice" required oninput="calculateSalesPrice()">
                        </div>

                        <div class="form-group">
                            <label for="discount">Discount (%):</label>
                            <input type="number" step="any" class="form-control" id="discount" name="discount" oninput="calculateSalesPrice()">
                        </div>

                        <div class="form-group">
                            <label for="salesPrice">Sales Price(INR):</label>
                            <input type="number" step="any" class="form-control" id="salesPrice" name="salesPrice" readonly>
                        </div>
                    </div>

                    <div id="rentFields" style="display: none;">
                       
                        <div class="form-group">
                            <label for="farePerHour">Fare Per Hour(INR):</label>
                            <input type="number" step="any" class="form-control" id="farePerHour" name="farePerHour">
                        </div>

                        <div class="form-group">
                            <label for="rp_quantity">Quantity(no.):</label>
                            <input type="number" step="any" class="form-control" id="rp_quantity" name="rp_quantity">
                        </div>
                        <div class="form-group">
                            <label for="security_amt">Security amount(INR):</label>
                            <input type="number" step="any" class="form-control" id="security_amt" name="security_amt">
                        </div>
                    </div>

            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control-file" id="image" name="image" accept=".jpg, .jpeg, .png" onsubmit="return validateFile();">
                
            </div>

            <div class="form-group">
                <label for="userLog">User Log (PDF):</label>
                <input type="file" class="form-control-file" id="userLog" name="userLog" accept=".pdf">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function showFields(action) {
            var buyFields = document.getElementById('buyFields');
            var rentFields = document.getElementById('rentFields');

            if (action === 'buy') {
                buyFields.style.display = 'block';
                rentFields.style.display = 'none';
            } else if (action === 'rent') {
                buyFields.style.display = 'none';
                rentFields.style.display = 'block';
            } else {
                buyFields.style.display = 'none';
                rentFields.style.display = 'none';
            }
        }

        function calculateSalesPrice() {
        var productPriceInput = document.getElementById('productPrice');
        var discountInput = document.getElementById('discount');
        var salesPriceInput = document.getElementById('salesPrice');

        var productPrice = parseFloat(productPriceInput.value) || 0;
        var discount = parseFloat(discountInput.value) || 0;

        if (productPrice !== 0 && discount !== 0) {
            var salesPrice = productPrice - (productPrice * (discount / 100));
            salesPriceInput.value = salesPrice.toFixed(2);
        } else {
            salesPriceInput.value = '';
        }
    }



 
    function validateProductName() {
        var productNameInput = document.getElementById('productName');
        var productNameError = document.getElementById('productNameError');
        var productNameWarning =document.getElementById('productNameWarning');
        var productName = productNameInput.value.trim();

        if (productName === '') {
            productNameError.textContent = 'Product Name is required.';
            productNameInput.classList.add('is-invalid');
            return false;
        } else if (productName.length < 3) {
            productNameInput.classList.add('is-invalid');
            productNameWarning.textContent = '';
            productNameError.textContent = 'Error: Product Name should contain at least 3 letters.';
            return false; // Return false to prevent form submission
        } 
        else if (productName.length > 100) {
            productNameInput.classList.add('is-invalid');
            productNameWarning.textContent = '';
            productNameError.textContent = 'Error: Name exceeds the maximum character limit of 30.';
            return false; // Return false to prevent form submission
        }
        else if (/^(.)\1+$/i.test(productName)) {
            productNameInput.classList.add('is-invalid');
            productNameWarning.textContent = '';
            productNameError.textContent = 'Error: Name should be meaningful and not consist of repeating characters.';
            return false;
        }
        else {
            productNameInput.classList.remove('is-invalid');
            productNameInput.style.border = '2px solid green';
            productNameWarning.textContent = '';
            productNameError.textContent = '';
            return true; // Return true if validation is successful
        }
    
        }


    function validateDescription() {
    var descriptionInput = document.getElementById('description');
    var descriptionError = document.getElementById('descriptionError');
    var descriptionWarning = document.getElementById('descriptionWarning');
    var description = descriptionInput.value.trim();

    if (description === '') {
        descriptionError.textContent = 'Product description is required.';
        descriptionInput.classList.add('is-invalid');
        return false;
    } else if (description.length < 10) {
        descriptionInput.classList.add('is-invalid');
        descriptionWarning.textContent = '';
        descriptionError.textContent = 'Error: Description should contain at least 10 letters.';
        return false;
    } else {
        descriptionInput.classList.remove('is-invalid');
        descriptionInput.style.border = '2px solid green';
        descriptionWarning.textContent = '';
        descriptionError.textContent = '';
        return true;
    }
}
    
function validateQuantity() {
    var quantityInput = document.getElementById('quantity');
    var quantityError = document.getElementById('quantityError');
    var quantity = parseInt(quantityInput.value);

    if (isNaN(quantity) || quantity === 0) {
        quantityError.textContent = 'Quantity should be a valid number and greater than 0.';
        quantityInput.classList.add('is-invalid');
        return false;
    } else if (quantity < 0) {
        quantityError.textContent = 'Quantity should not be negative.';
        quantityInput.classList.add('is-invalid');
        return false;
    } else if (quantity > 20) {
        quantityError.textContent = 'Quantity should not exceed the maximum limit of 20.';
        quantityInput.classList.add('is-invalid');
        return false;
    } else {
        quantityInput.classList.remove('is-invalid');
        quantityInput.style.border = '2px solid green';
        quantityError.textContent = '';
        return true;
    }
}

function validateCapacity() {
    var capacityInput = document.getElementById('capacity');
    var capacityError = document.getElementById('capacityError');
    var capacity = parseFloat(capacityInput.value);

    if (isNaN(capacity) || capacity <= 0) {
        capacityError.textContent = 'Capacity should be a valid positive number and greater than 0.';
        capacityInput.classList.add('is-invalid');
        return false;
    } else if (capacity > 25) {
        capacityError.textContent = 'Capacity should not exceed the maximum limit of 25.';
        capacityInput.classList.add('is-invalid');
        return false;
    } else {
        capacityInput.classList.remove('is-invalid');
        capacityInput.style.border = '2px solid green';
        capacityError.textContent = '';
        return true;
    }
}


    function validateForm() {
        var isFormValid = true;


        isFormValid &= validateProductName();
        isFormValid &= validateDescription();
        isFormValid &= validateQuantity();
        isFormValid &= validateCapacity();
        // Add similar lines for other fields

        if (!isFormValid) {
            
            return false;
        }

        // If form is valid, you can proceed with form submission
        return true;
    }

        
    </script>
</body>

</html>
