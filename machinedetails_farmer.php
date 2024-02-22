<?php
session_start();
include('dbconnection.php');
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'farmer') {
    header('Location:index.php');
    exit();
}
?>

<?php
if (isset($_GET['machine_id'])) {
    $machine_id = $_GET['machine_id'];
    

    $farmer_email = $_SESSION['useremail'];
    $getFarmerIdQuery = "SELECT farmer.farmer_id FROM farmer JOIN login ON farmer.log_id = login.log_id WHERE login.email = '$farmer_email'";
    $getFarmerIdResult = $con->query($getFarmerIdQuery);
    
    if ($getFarmerIdResult->num_rows > 0) {
        $rowFarmerId = $getFarmerIdResult->fetch_assoc();
        $farmer_id = $rowFarmerId['farmer_id'];
    
if(isset($_POST['wishlist'])){
    // Check if the machine is already in the wishlist
    $wishlistCheckSql = "SELECT * FROM wishlist WHERE machine_id = '$machine_id' AND farmer_id = '$farmer_id'";
    $wishlistCheckResult = $con->query($wishlistCheckSql);

    if ($wishlistCheckResult->num_rows > 0) {
        $_SESSION['message']= "Machine is already in your wishlist";
    } else {
        // Add to wishlist
        $wishlistInsertSql = "INSERT INTO wishlist (machine_id, farmer_id,status) VALUES ('$machine_id','$farmer_id','1')";
        if ($con->query($wishlistInsertSql) === TRUE) {
            $_SESSION['message'] ="Machine added to wishlist";
        } else {
            $_SESSION['message'] = "Error adding to wishlist";
        }
    }
}   
}
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
    <?php include('navbar/navbar_machines.php'); ?>

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
                        <form id="quantityForm" class="form-inline" method="GET" action="order.php">
                            <span class="mr-2"><b>Quantity: </b></span>
                            <select class="form-select" id="quantity" name="quantity" style="width: 80px;">
                                <?php
                                    for ($i = 1; $i <= $row['m_quantity']; $i++) {
                                        echo "<option value=\"$i\">$i</option>";
                                    }
                                ?>
                            </select>
                            <br><br>
                            <input type="hidden" name="machine_id" value="<?php echo $row['machine_id']; ?>">
                        </form>
                    </div>


                    <div class="row mt-3">
    <div class="col-md-6">
        <button class="btn btn-warning px-4 addToCartBtn" data-machine-id="<?php echo htmlspecialchars($row['machine_id']);?>" data-sales-price="<?php echo htmlspecialchars($row['sales_price']);?>">
            <i class="fa fa-shopping-cart me-2" style="color: darkyellow;"></i>Add to Cart
        </button>
    </div>


  <!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var addToCartButton = document.querySelector('.addToCartBtn');
    var salesPrice = parseFloat(addToCartButton.getAttribute('data-sales-price'));

    // Fetch available quantity from PHP
    var availableQuantity = parseInt(<?php echo $row['m_quantity']; ?>);

    addToCartButton.addEventListener('click', function () {
        var totalPrice = salesPrice;

        $.ajax({
            type: 'POST',
            url: 'cart.php',
            data: {
                machine_id: addToCartButton.getAttribute('data-machine-id'),
                sales_price: totalPrice
            },
            success: function (response) {
                alertify.success("Product added to the cart");
            },
            error: function (error) {
                console.error('Error occurred...Please try again later');
            }
        });
    });
});
</script>




                        <div class="col-md-6">
                        <form action="" method="post">
                            <input type="hidden" name="machine_id" value="<?php echo $row['machine_id']; ?>">
                            <button type="submit" name="wishlist" class="btn btn-danger px-4">
                                <i class="fa fa-heart me-2"></i>Add to wishlist
                            </button>
                        </form>

                </div>
                </div>
                      

<br>

<div class="input-group mb-3 d-flex justify-content-center">
    <form id="buyNowForm" class="form-inline" method="GET" action="order.php">
        <input type="hidden" name="machine_id" value="<?php echo $row['machine_id']; ?>">
        <input type="hidden" name="quantity" id="buyNowQuantity">
        <input type="hidden" name="total_price" id="buyNowTotalPrice">
        <button type="submit" id="buyNowBtn" class="btn btn-primary btn-block"style="width: 500px;" >Buy Now</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var buyNowBtn = document.getElementById('buyNowBtn');

        if (buyNowBtn) {
            buyNowBtn.addEventListener('click', function() {
                var selectedQuantity = document.getElementById('quantity').value;
                var salesPrice = <?php echo $row['sales_price']; ?>;
                var totalPrice = selectedQuantity * salesPrice;
                totalPrice = parseFloat(totalPrice.toFixed(2));
                document.getElementById('buyNowQuantity').value = selectedQuantity;
                document.getElementById('buyNowTotalPrice').value = totalPrice;

                document.getElementById('buyNowForm').submit();
            });
        }
    });
</script>




<!-- 
                        <div class="row mt-3">                  
                            <button type="submit" id="buyNowBtn" class="btn btn-primary">Buy Now</button> 
                        </div>   -->



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


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
