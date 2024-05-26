<?php
session_start();
include('../dbconnection.php');

if (isset($_SESSION['useremail'])) {
    $farmer_email = $_SESSION['useremail'];
} else {
    
    header("Location: ../login.php"); 
    exit();
}
$farmer="SELECT farmer.farmer_id FROM farmer JOIN login ON farmer.log_id = login.log_id WHERE login.email = '$farmer_email'";
$result=mysqli_query($con,$farmer);
$row=$result->fetch_assoc();
$farmer_id=$row['farmer_id'];
$machineId = $_GET['id'];

$viewMachineQuery = "SELECT * FROM rent_product JOIN rental_orders ON rent_product.rp_id = rental_orders.rp_id WHERE rent_product.rp_id = '$machineId'";
$viewMachineResult = $con->query($viewMachineQuery);

if ($viewMachineResult->num_rows > 0) {
    $machineRow = $viewMachineResult->fetch_assoc();
    $maxQuantity = $machineRow['rp_quantity'];


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
    include('../navbar/navbar_rental.php');
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
                        <input type="datetime-local" class="form-control" id="rentDate" name="rentDate" placeholder="Select Rent Date" oninput="validateRentDate()" onchange="checkAvailability()">
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
                <input type="hidden" id="machineId" value="<?php echo $machineId; ?>">

                <button id="rzp-button1" onclick="pay_now()" data-farmer_id="<?php echo $farmer_id ?>"
                        data-productid="<?php echo $machineId ?>" data-order_id = "<?php echo $orderId ?>"
                        class="btn btn-primary buynow"> 
                        Buy Now
                    </button>
                </form>
            </div>
        </div>
    </div>
<?php
} else {

echo '<p class="text-center">Machine not found.</p>';
exit();
}

?>
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
    return totalPrice;
}

    // Add event listeners to rental and return date fields
    document.getElementById('rentDate').addEventListener('input', calculateTotalPrice);
    document.getElementById('returnDate').addEventListener('input', calculateTotalPrice);

</script>



<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script type="text/javascript">
function pay_now() {
    var address = document.getElementById('address').value; // Get address value
    var rentDate = document.getElementById('rentDate').value; // Get rent date value
    var returnDate = document.getElementById('returnDate').value;

    var totalPrice = calculateTotalPrice();
    console.log(totalPrice);
    var amount = Math.round(totalPrice * 100);
    var productid = $('#rzp-button1').data('productid');
    var productname = $('#rzp-button1').data('productname');
    var farmer_id = $('#rzp-button1').data('farmer_id');
    //var quantity = $('#rzp-button1').data('quantity');
    var orderid =  $('#rzp-button1').data('order_id');
    var name = "Agrocompanion";
    var quantity = parseInt(document.getElementById('quantity').value);
   console.log(quantity);
    var options = {
        "key": "rzp_test_hMUuzye65m5BJJ",
        "amount": amount,
        "currency": "INR",
        "name": name,
       
        "handler": function(response) {
            console.log(amount);
            console.log(response);
            console.log('Sending AJAX request with data:', {
            'payment_id': response.razorpay_payment_id,
            'order_id': orderid,
            'amount': amount,
            'machineId': productid,
            'farmer_id': farmer_id,
            'quantity':quantity,
            'address': address, // Include address in data
                'rentDate': rentDate, // Include rent date in data
                'returnDate': returnDate
            });
            $.ajax({
                url: 'payment.php',
                type: 'POST',
                data: {
                    'payment_id': response.razorpay_payment_id,
                    'amount': amount,
                    'name': name,
                    'product_id': productid,
                    'farmer_id': farmer_id,
                    'order_id': orderid,
                    'quantity':quantity,
                    'address': address, // Include address in data
                    'rentDate': rentDate, // Include rent date in data
                    'returnDate': returnDate 
                },
                success: function(data) {
                    console.log(data);
                    window.location.href = 'rental_orderDetails.php';
                   
                }
            });
        
        },
    };

    var rzp1 = new Razorpay(options);

    rzp1.on('payment.failed', function(response) {
        alert(response.error.code);
    });

    rzp1.open();
}

</script>


<script>
        function checkAvailability() {
            var rentDate = document.getElementById('rentDate').value; // Get selected rent date
            var machineId = document.getElementById('machineId').value;

            
            $.ajax({
                url: 'check_availability.php', // PHP script to check availability
                type: 'POST',
                data: {
                    rentDate: rentDate,
                    machineId: machineId
                },
                success: function(response) {
                    // Display the closest available date to the user
                    $('#closestDate').html(response);
                }
            });
        }

        // Call checkAvailability function when rent date changes
        document.getElementById('rentDate').addEventListener('change', checkAvailability);
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
            minDate.setDate(currentDate.getDate() + 2);

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
