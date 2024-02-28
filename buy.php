<?php
session_start();
include('dbconnection.php');

if (isset($_SESSION['useremail'])) {
    $farmer_email = $_SESSION['useremail'];
} else {
    
    header("Location: login.php"); 
    exit();
}


$farmer="SELECT farmer.farmer_id FROM farmer JOIN login ON farmer.log_id = login.log_id WHERE login.email = '$farmer_email'";
$result=mysqli_query($con,$farmer);
$row=$result->fetch_assoc();
$farmer_id=$row['farmer_id'];

if (isset($_GET['order_id']) && isset($_GET['machine_id'])) {
    $order_id = $_GET['order_id'];
    $pid = $_GET['machine_id'];

    $sql = "SELECT * from machines m INNER JOIN buy_product bp on m.bp_id = bp.bp_id WHERE machine_id = $pid";
    $sql_result = mysqli_query($con, $sql);
    $data = mysqli_fetch_array($sql_result);

    $order_details = "SELECT * from shipping_address where order_id = '$order_id'";

    $result = mysqli_query($con, $order_details);

    if ($result && $row = mysqli_fetch_assoc($result)) {
      
        $fullAddress = $row['address'];
        $phone_no = $row['phone_no'];

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
  <?php include('navbar/navbar_farmer.php'); ?>
    <div class="container mt-3">
        <h2 class="text-center p-2 text-primary">Order Details</h2>
        <div class="row justify-content-center">
            <div class="col-md-6 mb-5">
                <h3>Shipping Address:</h3>
                <p>Delivery address : <?php echo $fullAddress; ?></p>
                <p>Phone no : <?php echo $phone_no; ?></p>
                
            </div>
            <div class="col-md-6 mb-5 product-details-container">
                <h3>Product Details:</h3>

                <img src="uploads/<?php echo $data['machine_image']; ?>" alt="<?php echo $data['machine_name']; ?>" width="200" height="200">
                <p><strong>Product Name:</strong> <?php echo $data['machine_name']; ?></p>
                <p><strong>Total Price:</strong> Rs <?php echo $row['total_price']; ?></p>
                <p><strong>Order id </strong><?php echo $row['order_id']; ?></p>
            </div>
            <div class="col-md-10 mb-5">
                    <input type="hidden" id="order_id" value="<?php echo $row['order_id']; ?>">
                    <button id="rzp-button1" onclick="pay_now()" data-farmer_id="<?php echo $farmer_id ?>"
                        data-productid="<?php echo $data['machine_id']; ?>" data-order_id = "<?php echo $row['order_id'];?>" data-productname="<?php echo $data['machine_name']; ?>"
                        data-amount="<?php echo $row['total_price']; ?>" data-quantity="<?php echo $row['quantity']; ?>" class="btn btn-primary buynow">
                        Buy Now
                    </button>
                </div>
        </div>
    </div>



<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script type="text/javascript">
function pay_now() {
    var amount = $('#rzp-button1').data('amount');
    var productid = $('#rzp-button1').data('productid');
    var productname = $('#rzp-button1').data('productname');
    var farmer_id = $('#rzp-button1').data('farmer_id');
    var quantity = $('#rzp-button1').data('quantity');
    var orderid =  $('#rzp-button1').data('order_id');
    var name = "Agrocompanion";
    
    var actual_amount = 100 * amount;



    var options = {
        "key": "rzp_test_aM3JBE4V3rZWsp",
        "amount": actual_amount,
        "currency": "INR",
        "name": name,
        "description": productname,
        "handler": function(response) {
            console.log(response);
            console.log('Sending AJAX request with data:', {
                'payment_id': response.razorpay_payment_id,
                'amount': actual_amount,
                'name': name,
                'product_id': productid,
                'farmer_id': farmer_id,
                'order_id': orderid,
                'quantity':quantity
            });

            $.ajax({
                url: 'pay.php',
                type: 'POST',
                data: {
                    'payment_id': response.razorpay_payment_id,
                    'amount': actual_amount,
                    'name': name,
                    'product_id': productid,
                    'farmer_id': farmer_id,
                    'order_id': orderid,
                    'quantity':quantity
                },
                success: function(data) {
                    console.log(data);
                    // Redirect only after the AJAX request completes successfully
                    // window.location.href = 'payment_success.php?order_id=<?php echo $order_id?>';
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


<?php
    } else {
        echo "Order not found.";
    }
} else {
    echo "Invalid request.";
}
mysqli_close($con);
?>
</body>
</html>