<?php
session_start();
include('dbconnection.php');
$farmer_email = $_SESSION['useremail'];
$getFarmerIdQuery = "SELECT farmer.farmer_id FROM farmer JOIN login ON farmer.log_id = login.log_id WHERE login.email = '$farmer_email'";
$getFarmerIdResult = $con->query($getFarmerIdQuery);

if ($getFarmerIdResult->num_rows > 0) {
    $rowFarmerId = $getFarmerIdResult->fetch_assoc();
    $farmer_id = $rowFarmerId['farmer_id'];
    $getCartItemsQuery = "SELECT cart.*, CONCAT(cart.machine_id) as items, machines.machine_name, machines.machine_image, buy_product.*, machines.m_quantity AS available_quantity, machines.status as machine_status
        FROM cart
        JOIN machines ON cart.machine_id = machines.machine_id
        JOIN buy_product ON machines.bp_id = buy_product.bp_id
        WHERE cart.farmer_id = '$farmer_id' AND cart.status = '1'";
    $getCartItemsResult = $con->query($getCartItemsQuery);
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

</head>
<body>
    <?php
    include('navbar/navbar_machines.php');
    ?>
    <div class="container mt-5 machine-details">
        <h2>Shopping Cart</h2>
        <?php
        $totalPrice = 0;
        $machineIdQuantityPairs = array();
        while ($rowCartItem = $getCartItemsResult->fetch_assoc()) {
            $availability =  $rowCartItem['available_quantity'];
            $machines_status = $rowCartItem['machine_status'];
            $machine_id = $rowCartItem['machine_id'];
            ?>
            <div class="row border mb-3 p-3">
                <div class="col-md-2">
                    <img src="uploads/<?php echo $rowCartItem['machine_image']; ?>" alt="<?php echo $rowCartItem['machine_name']; ?>" width="200" height="200">
                </div>
                <div class="col-md-6 ml-auto mr-3">
                    <h5><?php echo $rowCartItem['machine_name']; ?></h5>
                    <div class="input-group mb-3">
                        <form id="quantityForm_<?php echo $rowCartItem['cart_item_id']; ?>" class="form-inline">
                            <?php
                            if ($machines_status == 0) {
                            ?>
                                <select class="form-control" id="quantity_<?php echo $rowCartItem['cart_item_id']; ?>" onchange="updateTotalPrice(<?php echo $rowCartItem['cart_item_id']; ?>, <?php echo $rowCartItem['available_quantity']; ?>, <?php echo $rowCartItem['sales_price']; ?>)">
                                    <?php
                                    for ($i = 1; $i <= $availability; $i++) {
                                        $selected = ($i == $rowCartItem['quantity']) ? 'selected' : '';
                                        echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                                    }
                                    ?>
                                </select>
                            <?php
                            }
                            ?>
                        </form>
                    </div>
                    <form method="post" action="removemachine_cart.php">
                        <input type="hidden" name="cart_item_id" value="<?php echo $rowCartItem['cart_item_id']; ?>">
                        <br><br>
                        <button type="submit" class="btn btn-danger">Remove</button>
                    </form>
                </div>
                <?php
                if ($machines_status == 0) {
                ?>
                    <div class="col-md-2">
                        <p class="total-price_<?php echo $rowCartItem['cart_item_id']; ?>">₹<?php echo $rowCartItem['sales_price']; ?></p>
                    </div>
                    <?php
                    $totalPrice += $rowCartItem['sales_price'] * $rowCartItem['quantity'];

                    $machineIdQuantityPairs[$rowCartItem['items']] = $rowCartItem['quantity'];
                } else {
                    ?>
                    <div class="col-md-2">
                        <p class="text-danger">This machine is currently unavailable.</p>
                    </div>
                <?php
                }
                ?>
            </div>
  
        <?php
        }
        ?>
    </div>

    <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-2">
            <div id="overall-total">Overall Total: ₹<?php echo number_format($totalPrice, 2); ?></div>
        </div>
        <div class="row mt-2">
            <div class="col-md-3">
                <?php
   $cartItems = array();
foreach ($machineIdQuantityPairs as $machineId => $quantity) {
    $cartItems[] = "$machineId:$quantity";
}
?>
      </div>
    </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script>
        function updateTotalPrice(cartItemId, availableQuantity, unitPrice) {
            var quantitySelect = document.getElementById('quantity_' + cartItemId);
            var totalElement = document.querySelector('.total-price_' + cartItemId);
            var overallTotalDisplay = document.getElementById('overall-total');

            var currentQuantity = parseInt(quantitySelect.value, 10);
            var newTotal = currentQuantity * unitPrice;
            totalElement.textContent = '₹' + newTotal.toFixed(2);
            // Update the overall total price
            var totalPrices = document.querySelectorAll('[class^="total-price_"]');
            var overallTotal = 0;
            totalPrices.forEach(function (totalPrice) {
                overallTotal += parseFloat(totalPrice.textContent.replace('₹', ''));
            });
            overallTotalDisplay.textContent = 'Overall Total: ₹' + overallTotal.toFixed(2);
            console.log(typeof overallTotal);
            console.log(overallTotal); 
        }
        // function redirectToCartBuyPage() {
      
        //     var overallTotalDisplay = document.getElementById('overall-total').textContent;
        //     var overallTotal = parseFloat(overallTotalDisplay.replace('Overall Total: ₹', ''));
        //     window.location.href = 'cart_buy.php?overall_total=' + encodeURIComponent(overallTotal.toString());
        // }
    </script>

<button id="rzp-button1" onclick="pay_now()" data-farmer_id="<?php echo $farmer_id ?>"
                        data-productid="<?php echo $machineId ?>" 
                        class="btn btn-primary buynow"> 
                        Buy Now
                    </button>


 



<script type="text/javascript">
function pay_now() {
    var overallTotalDisplay = document.getElementById('overall-total').textContent;
    var overallTotal = parseFloat(overallTotalDisplay.replace('Overall Total: ₹', ''));
    var amount = Math.round(overallTotal * 100); // Convert amount to paise
    var productid = $('#rzp-button1').data('productid');
    var farmer_id = $('#rzp-button1').data('farmer_id');
    var name = "Agrocompanion";

    var options = {
        "key": "rzp_test_hMUuzye65m5BJJ",
        "amount": amount, // Use dynamically calculated amount
        "currency": "INR",
        "name": name,
       
        "handler": function(response) {
            console.log(response);
            $.ajax({
                url: 'payment.php',
                type: 'POST',
                data: {
                    'payment_id': response.razorpay_payment_id,
                    'amount': amount,
                    'name': name,
                    'product_id': productid,
                    'farmer_id': farmer_id,
                },
                success: function(data) {
                    console.log(data);
                    window.location.href = 'cart_details.php';
                }
            });
        },
    };

    var rzp1 = new Razorpay(options);
    rzp1.open();
}



</script>



</body>

</html>
<?php
} else {
header("Location: login.php");
exit();
}
?>
