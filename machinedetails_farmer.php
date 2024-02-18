<?php
session_start();
include('dbconnection.php');
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'farmer') {
    header('Location:index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machine Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('navbar/navbar_farmer.php'); ?>

    <?php
    if (isset($_GET['machine_id'])) {
        $machine_id = $_GET['machine_id'];

        $machineSql = "SELECT m.machine_id, m.machine_name, m.machine_image, bp.product_price, bp.discount, bp.sales_price, m.description,m.m_quantity
                       FROM machines m LEFT JOIN buy_product bp ON m.bp_id = bp.bp_id 
                       WHERE m.machine_id = '$machine_id'";
        $result = $con->query($machineSql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $availableQuantity = $row['m_quantity'];
            echo '<script>var availableQuantity = ' . json_encode($availableQuantity) . ';</script>';
    
            ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var addToCartButton = document.querySelector('.addToCartBtn');
    var salesPrice = parseFloat(addToCartButton.getAttribute('sales-price'));

    // Fetch available quantity from PHP
    var availableQuantity = parseInt(<?php echo $row['m_quantity']; ?>);

    // Set maximum quantity for the input field
    var quantityInput = document.getElementById('quantity');
    quantityInput.max = availableQuantity;
    var buyNowBtn = document.getElementById('buyNowBtn');

quantityInput.addEventListener('input', function () {
    buyNowBtn.href = 'order.php?machine_id=<?php echo $row['machine_id']; ?>&quantity=' + quantityInput.value;
});
    addToCartButton.addEventListener('click', function () {
        var quantity = parseInt(quantityInput.value, 10) || 1;

        // Check if the quantity exceeds the available quantity
        if (quantity > availableQuantity) {
            alert('There are only ' + availableQuantity + ' machine(s) available.');
        } else {
            var totalPrice = salesPrice * quantity;

            $.ajax({
                type: 'POST',
                url: 'cart.php',
                data: {
                    machine_id: addToCartButton.value,
                    quantity: quantity,
                    total_price: totalPrice
                },
                success: function (response) {
                    alertify.success("Product added to the cart");
                },
                error: function (error) {
                    console.error('Error occurred...Please try again later');
                }
            });
        }
    });
});
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<?php
    } 
    } else {
        echo "<script>alert('Machine ID not found');</script>";
        exit();
    }
 ?>

    <br><br>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card h-70">
                    <img src="uploads/<?php echo $row['machine_image']; ?>" alt="<?php echo $row['machine_name']; ?>" class="img-fluid" style="width:500px;height: 450px;">
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-70">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo ucfirst($row['machine_name']); ?></h3>
                        <div class="bg-danger text-white d-inline-block p-2 rounded mb-3">-<?php echo $row['discount']; ?>%</div>
                        <div class="d-flex justify-content-between mb-3">
                            <div class="price">₹<?php echo $row['sales_price']; ?></div>
                        </div>
                        <p class="card-text"><b>About this item</b></p>
                        <p class="card-text"><?php echo $row['description']; ?></p>


                        <div class="input-group mb-3">
                        <form id="quantityForm" class="form-inline">  <span class="mr-2"><b>Quantity: </b></span>
                            <button type="button" class="btn btn-outline-secondary decrement-btn" onclick="decrement()">-</button>
                            <input type="text" class="form-control text-center bg-white input-qty" id="quantity" value="1" disabled style="width: 80px;">
                            <button type="button" class="btn btn-outline-secondary increment-btn" onclick="increment()">+</button>
                            </form>
                        </div>

                        <div class="row mt-3">
                        <div class="col-md-6">
                        <button class="btn btn-warning px-4 addToCartBtn" value="<?php echo $row['machine_id'];?>" sales-price="<?php echo $row['sales_price'];?>">
                            <i class="fa fa-shopping-cart me-2" style="color: darkyellow;"></i>Add to Cart
                        </button>
                        </div>

                        <div class="col-md-6">
                        <form action="add_to_wishlist.php" method="post">
                            <input type="hidden" name="machine_id" value="<?php echo $row['machine_id']; ?>">
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="fa fa-heart me-2"></i>Add to wishlist
                            </button>
                        </form>

                </div>
                        </div>


<div class="row mt-3">
    <a href="order.php?machine_id=<?php echo $row['machine_id']; ?>&quantity=" id="buyNowBtn" class="btn btn-primary">Buy Now</a>
</div>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function increment() {
        var quantityInput = document.getElementById('quantity');
        var currentQuantity = parseInt(quantityInput.value, 10);

        // Ensure the quantity doesn't exceed the maximum limit
        if (currentQuantity < availableQuantity) {
            quantityInput.value = currentQuantity + 1;
        }
    }

    function decrement() {
        var quantityInput = document.getElementById('quantity');
        var currentQuantity = parseInt(quantityInput.value, 10);

        // Ensure the quantity doesn't go below 1
        if (currentQuantity > 1) {
            quantityInput.value = currentQuantity - 1;
        }
    }

</script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css"/>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>
<script>
    <?php 
    if(isset($_SESSION['message']))
    {
        ?>
        alertify.set('notifier','position','top-right');
        alertify.success('<?=$_SESSION['message'];?>');
        <?php
        unset($_SESSION['message']);
    }
    ?>
    </script>


     
</body>
</html>
