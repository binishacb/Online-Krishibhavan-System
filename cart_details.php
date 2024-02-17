<?php
session_start();
include('dbconnection.php');
// Fetch cart items for the logged-in farmer
$farmer_email = $_SESSION['useremail'];
$getFarmerIdQuery = "SELECT farmer.farmer_id FROM farmer JOIN login ON farmer.log_id = login.log_id WHERE login.email = '$farmer_email'";
$getFarmerIdResult = $con->query($getFarmerIdQuery);

if ($getFarmerIdResult->num_rows > 0) {
    $rowFarmerId = $getFarmerIdResult->fetch_assoc();
    $farmer_id = $rowFarmerId['farmer_id'];

$getCartItemsQuery = "SELECT cart.*, machines.machine_name, machines.machine_image, buy_product.*, machines.m_quantity AS available_quantity
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
</head>
<body>
<?php
include('navbar/navbar_farmer.php');
?>
<div class="container mt-5 machine-details">
    <h2>Shopping Cart</h2>
    
    <?php
    while ($rowCartItem = $getCartItemsResult->fetch_assoc()) {
        //echo $rowCartItem['available_quantity'];
        ?>
<script>
    function updateTotalPrice(cartItemId, availableQuantity, unitPrice) {
        var quantitySelect = document.getElementById('quantity_' + cartItemId);
        var totalElement = document.querySelector('.total-price_' + cartItemId);
      
        var currentQuantity = parseInt(quantitySelect.value, 10);
        
        if (currentQuantity > availableQuantity) {
            alert('There are only ' + availableQuantity + ' machine(s) left.');
            quantitySelect.value = availableQuantity; 
            currentQuantity = availableQuantity; // Set current quantity to available quantity
        }
        
        var newTotal = currentQuantity * unitPrice;
        totalElement.textContent = '₹' + newTotal.toFixed(2);
    }
</script>
        <div class="row border mb-3 p-3">
            <div class="col-md-2">
                <img src="uploads/<?php echo $rowCartItem['machine_image']; ?>" alt="<?php echo $rowCartItem['machine_name']; ?>" width="200" height="200">
            </div>
            <div class="col-md-6 ml-auto mr-3">
                <h5><?php echo $rowCartItem['machine_name']; ?></h5>
                <div class="input-group mb-3">
                    <form id="quantityForm_<?php echo $rowCartItem['cart_item_id']; ?>" class="form-inline">
                    <select class="form-control" id="quantity_<?php echo $rowCartItem['cart_item_id']; ?>" onchange="updateTotalPrice(<?php echo $rowCartItem['cart_item_id']; ?>, <?php echo $rowCartItem['available_quantity']; ?>, <?php echo $rowCartItem['sales_price']; ?>)">

                            <?php
                            // Assuming a maximum quantity of 10, you can adjust this based on your requirements
                            for ($i = 1; $i <= 10; $i++) {
                                $selected = ($i == $rowCartItem['quantity']) ? 'selected' : '';
                                echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                            }
                            ?>
                        </select>
                    </form>
                </div>
                
               
                
                <form method="post" action="removemachine_cart.php">
                    <input type="hidden" name="cart_item_id" value="<?php echo $rowCartItem['cart_item_id']; ?>">
                    <br><br>
                    <button type="submit" class="btn btn-danger">Remove</button>
                </form>
            </div>
            <div class="col-md-2">
                <p class="total-price_<?php echo $rowCartItem['cart_item_id']; ?>">₹<?php echo $rowCartItem['total_price']; ?></p>
            </div>
        </div>
        <?php
    }
    ?>

    <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-2">
            <h5>Total:</h5>
        </div>
        <div class="col-md-2">
            <?php
            // Calculate total price for all products in the cart
            $calculateTotalQuery = "SELECT SUM(total_price) AS total FROM cart WHERE farmer_id = '$farmer_id' AND status = '1'";
            $calculateTotalResult = $con->query($calculateTotalQuery);
            
            if ($calculateTotalResult) {
                $rowTotal = $calculateTotalResult->fetch_assoc();
                $totalPrice = $rowTotal['total'];
                echo '<strong>₹' . number_format($totalPrice, 2) . '</strong>'; 
            } else {
                echo '₹0.00';
            }
            ?>
            <br><br>
        </div>
        <div class="row mt-2">
            <div class="col-md-3">
                <a href="cart_buy.php"><button class="btn checkout-btn" style="background-color:#ffd700;padding: 10px 50px; font-size : 18px;">Proceed to buy</button></a>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>

<?php
} else {
    // Handle the case where the farmer is not logged in
    header("Location: login.php"); // Redirect to the login page
    exit();
}
?>
