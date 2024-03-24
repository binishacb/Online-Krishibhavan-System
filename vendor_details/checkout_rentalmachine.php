<?php

include('../dbconnection.php');
session_start();

$machineId = $_GET['id'];

$viewMachineQuery = "SELECT * FROM rent_product WHERE rp_id = '$machineId'";
$viewMachineResult = $con->query($viewMachineQuery);

if ($viewMachineResult->num_rows > 0) {
    $machineRow = $viewMachineResult->fetch_assoc();
    $maxQuantity = $machineRow['rp_quantity'];

} else {

    echo '<p class="text-center">Machine not found.</p>';
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Checkout</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    

    <style>
        .error-message {
            color: red;
        }
    </style>
</head>

<body>
    <?php
    include('../navbar/navbar_farmer.php');
    ?>
    <div class="container">
        <h2 class="text-center mt-3 mb-3">Rental Checkout</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="card h-70">
                    <img src="../uploads/<?php echo $machineRow['rp_image']; ?>" class=" img-fluid"
                        alt="<?php echo $machineRow['rp_name']; ?>" style="width:400px;height: 350px;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $machineRow['rp_name']; ?></h5>
                        <p>price per hour<?php echo $machineRow['fare_per_hour']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <form action="" method="post" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <select class="form-control" id="quantity" name="quantity" oninput="validateQuantity()">
                            <?php for ($i = 1; $i <= $maxQuantity; $i++) : ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                        <span id="quantityError" class="error-message"></span>
                    </div>
                    <div class="form-group">
                        <label for="rentDate">Rent Date:</label>
                        <input type="datetime-local" class="form-control" id="rentDate" name="rentDate" placeholder="Select Rent Date" oninput="validateRentDate()">
                        <span id="rentDateError" class="error-message"></span>
                    </div>
                    <div class="form-group">
                        <label for="returnDate">Return Date:</label>
                        <input type="datetime-local" class="form-control" id="returnDate" name="returnDate"
                            placeholder="Select Return Date" oninput="validateReturnDate()">
                        <span id="returnDateError" class="error-message"></span>
                    </div>
                    <div class="form-group">
                        <label for="address">Delivery address:</label>
                        <input type="text" class="form-control" id="address" name="address"
                            placeholder="Enter your full address with contact no" oninput="validateAddress()">
                        <span id="addressError" class="error-message"></span>
                    </div>
                    <div class="form-group">
                    <!-- <label>Total Price:</label> -->
                    <!-- <p id="totalPrice" oninput="calculateTotalPrice()"></p> -->


    <p id="totalPrice" data-fare-per-hour="<?php echo $machineRow['fare_per_hour']; ?>" data-security-amount="<?php echo $machineRow['security_amt']; ?>"  oninput="calculateTotalPrice()"></p>


                </div>

                    <button type="submit" name="submit" class="btn btn-primary">Continue</button>
                </form>
            </div>
        </div>
    </div>



    <script>
function calculateTotalPrice() {
    var quantity = parseInt(document.getElementById('quantity').value);
    var rentDate = new Date(document.getElementById('rentDate').value);
    var returnDate = new Date(document.getElementById('returnDate').value);

    var durationMs = returnDate - rentDate;
    var durationHours = durationMs / (1000 * 60 * 60);
    console.log("hours",durationHours);
    var farePerHour = parseFloat(document.getElementById('totalPrice').getAttribute('data-fare-per-hour')); 
    var securityAmount = parseFloat(document.getElementById('totalPrice').getAttribute('data-security-amount')); 
    console.log("fare",farePerHour);
    console.log("security",securityAmount);
    var subtotal = quantity * durationHours * farePerHour;
    var totalPrice = subtotal + securityAmount;
    console.log("total",totalPrice);
    document.getElementById('totalPrice').innerText = 'Total Price: ₹' + totalPrice.toFixed(2);
}

    // Add event listeners to rental and return date fields
    document.getElementById('rentDate').addEventListener('input', calculateTotalPrice);
    document.getElementById('returnDate').addEventListener('input', calculateTotalPrice);

</script>







    
    <script>
        function validateForm() {
            // Clear previous error messages
            clearValidationErrors();

            // Validate Quantity
            validateQuantity();

            // Validate Rent Date
            validateRentDate();

            // Validate Return Date
            validateReturnDate();

            // Validate Delivery Address
            validateAddress();

            // If there are errors, prevent form submission
            return document.querySelectorAll('.error-message').length === 0;
        }

        function clearValidationErrors() {
            // Clear previous error messages
            var errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(function (errorMessage) {
                errorMessage.textContent = '';
            });
        }

        function validateQuantity() {
            var quantity = document.getElementById('quantity').value;
            var quantityError = document.getElementById('quantityError');
            if (quantity.trim() === '') {
                quantityError.textContent = 'Rent Date is required.';
            }

            if (!isNumeric(quantity) || quantity < 1) {
                quantityError.textContent = 'Please select a valid quantity.';
            }
        }

        function validateRentDate() {
            var rentDateInput = document.getElementById('rentDate');
            var rentDate = rentDateInput.value;
            var rentDateError = document.getElementById('rentDateError');

            // Check if the input is not empty
            if (rentDate.trim() === '') {
                rentDateError.textContent = 'Rent Date is required.';
                return;
            }

            // Parse the selected date
            var selectedDate = new Date(rentDate);
            
            // Get the current date
            var currentDate = new Date();
            
            // Calculate the minimum allowed date (3 days from the current date)
            var minDate = new Date(currentDate);
            minDate.setDate(currentDate.getDate() + 3);

            // Calculate the maximum allowed date (2 months from the current date)
            var maxDate = new Date(currentDate);
            maxDate.setMonth(currentDate.getMonth() + 2);

            // Check if the selected date is within the allowed range
            if (selectedDate < minDate || selectedDate > maxDate) {
                rentDateError.textContent = 'Rent Date should be between ' + minDate.toLocaleDateString() + ' and ' + maxDate.toLocaleDateString() + '.';
            } else {
                rentDateError.textContent = ''; // Clear any previous error message
            }
        }
        function validateReturnDate() {
    var rentDateInput = document.getElementById('rentDate');
    var returnDateInput = document.getElementById('returnDate');
    var rentDate = new Date(rentDateInput.value);
    var returnDate = new Date(returnDateInput.value);
    var returnDateError = document.getElementById('returnDateError');

    // Check if the input is not empty
    if (returnDateInput.value.trim() === '') {
        returnDateError.textContent = 'Return Date is required.';
        return;
    }

    // Check if return date is after rent date
    if (returnDate <= rentDate) {
        returnDateError.textContent = 'Return Date should be after Rent Date.';
        return;
    }

    // Calculate the maximum allowed return date (1 month from the rent date)
    var maxReturnDate = new Date(rentDate);
    maxReturnDate.setMonth(rentDate.getMonth() + 1);

    // Check if the return date is within the allowed range
    if (returnDate > maxReturnDate) {
        returnDateError.textContent = 'Return Date should not exceed one month from Rent Date.';
    } else {
        returnDateError.textContent = ''; // Clear any previous error message
    }
}
function validateAddress() {
    var addressInput = document.getElementById('address');
    var address = addressInput.value;
    var addressError = document.getElementById('addressError');

    // Check if the input is not empty
    if (address.trim() === '') {
        addressError.textContent = 'Delivery address is required.';
        return;
    }

    // Check if the address length is less than 5 characters
    if (address.length < 5) {
        addressError.textContent = 'Address should be at least 5 characters.';
        return;
    }

    // Check if the address length exceeds 100 characters
    if (address.length > 100) {
        addressError.textContent = 'Address should not exceed 100 characters.';
        return;
    }

    // Clear any previous error message if validation passes
    addressError.textContent = '';
}


        function isNumeric(value) {
            return /^-?\d+$/.test(value);
        }
    </script>
</body>

</html>
