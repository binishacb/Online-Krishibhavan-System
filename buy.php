<?php
session_start();
include('dbconnection.php');



if (isset($_SESSION['useremail'])) {
    $farmer_email = $_SESSION['useremail'];
} else {
    // Handle the case where the farmer is not logged in
    header("Location: login.php"); // Redirect to login page
    exit();
}
$farmer="SELECT farmer.farmer_id FROM farmer JOIN login ON farmer.log_id = login.log_id WHERE login.email = '$farmer_email'";
$result=mysqli_query($con,$farmer);
$row=$result->fetch_assoc();
$farmer_id=$row['farmer_id'];




$pid = $_GET['machine_id'];
$sql = "SELECT * from machines m INNER JOIN buy_product bp on m.bp_id=bp.bp_id WHERE machine_id=$pid";
$sql_result = mysqli_query($con, $sql);
$data = mysqli_fetch_array($sql_result);

// Assuming you pass the order ID as a parameter in the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Validate and sanitize user input to prevent SQL injection
    $order_id = mysqli_real_escape_string($con, $order_id);

    // Retrieve order details from the database
    $order_details = "SELECT * from shipping_address where order_id = '$order_id'";

    $result = mysqli_query($con, $order_details);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        // Display order details
        $fullAddress = $row['name'] . ', ' . $row['address'] . ', <br>phone:' . $row['phone_number'] . ', <br>Pin Code: ' . $row['pin_code'] . ', <br>District: ' . $row['district'] . ', <br>Place: ' . $row['place'];
        ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Order Details</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    .product-details-container {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: left;
    }

    .product-details-container img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .product-details-container h3 {
        color: black;
    }

    .product-details-container p {
        margin: 5px 0;
    }

    .product-details-container strong {
        font-weight: bold;
    }
    </style>
</head>

<body>

    <div class="container mt-3">
        <h2 class="text-center p-2 text-primary">Order Details</h2>
        <div class="row justify-content-center">
            <div class="col-md-6 mb-5">
                <h3>Shipping Address:</h3>
                <p><?php echo $fullAddress; ?></p>
            </div>
            <div class="col-md-6 mb-5 product-details-container">
                <h3>Product Details:</h3>

                <img src="uploads/<?php echo $data['machine_image']; ?>" alt="<?php echo $data['machine_name']; ?>"
                    width="200" height="200">
                <p><strong>Product Name:</strong> <?php echo $data['machine_name']; ?></p>
                <p><strong>Total Price:</strong> Rs <?php echo $data['sales_price']; ?></p>
            </div>
            <div class="col-md-10 mb-5">
                <button id="rzp-button1" onclick="pay_now(this)" data-farmer_id="<?php echo $farmer_id ?>"
                    data-productid="<?php echo $data['machine_id']; ?>"
                    data-productname="<?php echo $data['machine_name']; ?>"
                    data-amount="<?php echo $data['sales_price']; ?>" class="btn btn-primary buynow">
                    Buy Now
                </button>


            </div>
        </div>
    </div>


</body>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script type="text/javascript">
function pay_now(button) {
    var amount = $(button).data('amount');
    var productid = $(button).data('productid');
    var productname = $(button).data('productname');
    var farmer_id = $(button).data('farmer_id');
    var name = "Binisha";
    var actual_amount = 100 * amount;

    var options = {
        "key": "rzp_test_aM3JBE4V3rZWsp",
        "amount": actual_amount,
        "currency": "INR",
        "name": name,
        "description": productname,
        "image": "razorpay.png",
        "handler": function(response) {
            console.log(response);
            $.ajax({
                url: 'pay.php',
                type: 'POST',
                data: {
                    'payment_id': response.razorpay_payment_id,
                    'amount': actual_amount,
                    'name': name,
                    'product_id': productid,
                    'farmer_id': farmer_id
                },
                success: function(data) {
                    console.log(data);
                    alert(response.razorpay_payment_id);
                    window.location.href = 'payment_success.php';
                }
            });
        },
    };

    var rzp1 = new Razorpay(options);

    rzp1.on('payment.failed', function(response) {
        alert(response.error.code);
        // Handle other failure alerts as needed
    });

    document.getElementById('rzp-button1').onclick = function(e) {
        rzp1.open();
        e.preventDefault();
    }
}
</script>





</html>
<?php
    } else {
        echo "Order not found.";
    }
} else {
    echo "Invalid request.";
}

mysqli_close($con);
?>