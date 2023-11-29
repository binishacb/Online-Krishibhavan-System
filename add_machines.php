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
    <title>Add Machines</title>
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

        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 50px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .checkbox-group {
            margin-bottom: 10px; /* Adjust the margin as needed */
        }

        .checkbox-group label {
            display: inline-block;
            margin-right: 0px; /* Adjust the spacing between checkbox and label */
        }

        .checkbox-group input[type="checkbox"] {
            margin-right: 0px; /* Adjust the spacing between checkboxes */
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

       
    </style>
    <script>

        function showFields(option) {
            var buyFields = document.getElementById("buyFields");
            var rentFields = document.getElementById("rentFields");

            if (option === "buy") {
                buyFields.style.display = "block";
                rentFields.style.display = "none";
            } else {
                rentFields.style.display = "block";
                buyFields.style.display = "none";
            }
        }

        function calculateSalesPrice() {
            var productPriceInput = document.getElementById("productPrice");
            var discountInput = document.getElementById("discount");
            var salesPriceInput = document.getElementById("salesPrice");

            var productPrice = parseFloat(productPriceInput.value);
            var discount = parseFloat(discountInput.value);

            // Check if both product price and discount are present
            if (!isNaN(productPrice) && !isNaN(discount)) {
                // Calculate sales price
                var salesPrice = productPrice - (productPrice * discount / 100);

                // Display the calculated sales price
                salesPriceInput.value = salesPrice.toFixed(2);
            } else {
                // Clear the sales price if either product price or discount is missing
                salesPriceInput.value = '';
            }
        }
    </script>
</head>
<body>
<?php
include('navbar/navbar_admin.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST["productName"];
    $categoryName = $_POST["category"];
    $description = $_POST["description"];
    $capacity = $_POST["capacity"];
    $quantity = $_POST["quantity"];
   // $userLog = $_POST["userLog"];
   
    $categoryId = "SELECT type_id FROM machine_type WHERE type_name = '$categoryName'";
    $pdfPath = ''; 
    if (!empty($_FILES['userLog'])) {
        $userLogFile = $_FILES['userLog'];
        
        // Check if there was no error during file upload
        if ($userLogFile['error'] == UPLOAD_ERR_OK) {
            $tmpName = $userLogFile['tmp_name'];
            $pdfName = uniqid() . '_' . $userLogFile['name']; // Unique name to avoid conflicts
            $pdfPath = 'uploads/' . $pdfName; // Specify the directory where you want to store the file

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
            move_uploaded_file($tmpName,'uploads/'. $newImageName);
            $insert_machine = "INSERT INTO machines (machine_name, type_id, description, capacity, quantity, machine_image,userlog) VALUES ('$productName', (SELECT type_id FROM machine_type WHERE type_name = '$categoryName'), '$description', '$capacity', '$quantity', '$newImageName','$pdfPath')";
           
            if ($con->query($insert_machine) == TRUE) {
                $lastMachineId = $con->insert_id;
              
            if (isset($_POST["action"])) {
                // Check if "buy" is in the array
                if (in_array("buy", $_POST["action"])) {
            // Process buy action
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
                        $farePerDay = $_POST["farePerDay"];
                        echo $farePerDay;
                        // Insert into 'rent_machines' table with foreign key reference
                        $sqlRent = "INSERT INTO rent_product (rp_id, fare_per_hour, fare_per_day) VALUES ('$lastMachineId', '$farePerHour', '$farePerDay')";
                        
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

?>  
    <h2>Add Machine</h2>

    <form action="" method="post" onsubmit="return validateForm()" enctype="multipart/form-data">
        <label for="productName">Product Name:</label>
        <input type="text" id="productName" name="productName" required oninput="validateProductName()">
        <div id="productNameError" style="color: red;"></div><br>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="" disabled selected>Select category</option>
            <option value="tractor">Brush Cutter</option>
            <option value="sprayer">Sprayer</option>
            <option value="pumpset">Pumpset</option>
            <option value="pumpset">Weeder</option>
        </select><br>


        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>

        <label for="capacity">Capacity(in litre):</label>
        <input type="number" step="any" id="capacity" name="capacity"><br>

        <label for="quantity">Quantity(in numbers):</label>
        <input type="number" id="quantity" name="quantity" required><br>

       

            <div class="checkbox-group">
            <label>Choose action: </label><br>
            <label for="buy">Buy</label>
            <input type="checkbox" id="buy" name="action[]" value="buy" onclick="showFields('buy')">

            <label for="rent">Rent</label>
            <input type="checkbox" id="rent" name="action[]" value="rent" onclick="showFields('rent')">
        </div>

        <div id="buyFields" style="display: none;">
            <label for="productPrice">Product Price(INR):</label>
            <input type="number" step="any" id="productPrice" name="productPrice" required oninput="calculateSalesPrice()"><br>

            <label for="discount">Discount (%):</label>
            <input type="number" step="any" id="discount" name="discount" oninput="calculateSalesPrice()"><br>

            <label for="salesPrice">Sales Price(INR):</label>
            <input type="number" step="any" id="salesPrice" name="salesPrice" readonly><br>
        </div>

        <div id="rentFields" style="display: none;">
            <label for="farePerHour">Fare Per Hour(INR):</label>
            <input type="number" step="any" id="farePerHour" name="farePerHour"><br>

            <label for="farePerDay">Fare Per Day(INR):</label>
            <input type="number" step="any" id="farePerDay" name="farePerDay"><br>
        </div>


            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept=".jpg, .jpeg, .png"  onsubmit="return validateFile();"><br>

            <label for="userLog">User Log (PDF):</label>
            <input type="file" id="userLog" name="userLog" accept=".pdf"><br> 

            <input type="submit" value="Submit">
    </form>

</body>
</html>

