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
    $totalPrice = 0;
    $machineIdQuantityPairs = array();

    ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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
            <h5>Total:</h5>
        </div>
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

$cartBuyLink = "cart_buy.php?totalPrice=" . urlencode(number_format($totalPrice, 2)) . "&items=" . implode(',', $cartItems);

               ?>
               <a href="<?php echo $cartBuyLink; ?>"><button class="btn checkout-btn" id="submitButton"style="background-color:#ffd700;padding: 10px 50px; font-size : 18px;">Proceed to buy</button></a>            </div>
       
 </div>
    </div>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script>
        document.getElementById('submitButton').addEventListener('click', updateTotalPrice);
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
            return {
                currentQuantity: currentQuantity,
                overallTotal: overallTotal
            };
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
