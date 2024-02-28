<?php
session_start();
include('../dbconnection.php');

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'farmer') {
    header('Location:../index.php');
    exit();
}

$farmer_email = $_SESSION['useremail'];
$getFarmerIdQuery = "SELECT farmer.farmer_id FROM farmer JOIN login ON farmer.log_id = login.log_id WHERE login.email = '$farmer_email'";
$getFarmerIdResult = $con->query($getFarmerIdQuery);

if ($getFarmerIdResult->num_rows > 0) {
    $rowFarmerId = $getFarmerIdResult->fetch_assoc();
    $farmer_id = $rowFarmerId['farmer_id'];

    // Fetch wishlist items for the current farmer
    $wishlistQuery = "SELECT machines.*,wishlist.* FROM wishlist JOIN machines ON wishlist.machine_id = machines.machine_id WHERE wishlist.farmer_id = '$farmer_id' and wishlist.status = '1'";
    $wishlistResult = $con->query($wishlistQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
    
</head>
<body>
    <?php
    include('../navbar/navbar_farmer_market.php');
    ?>
<div class="container">
    <h1 class="mt-3">Your Wishlist</h1>

    <?php if ($wishlistResult->num_rows > 0) { ?>
        <div class="row mt-3">
            <?php while ($row = $wishlistResult->fetch_assoc()) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card 100-h">
                        <?php
                        // Fetch machine details (replace 'your_image_column' and 'your_price_column' with actual column names)
                        $machineDetailsQuery = "SELECT m.machine_image, bp.sales_price FROM machines m join buy_product bp on m.bp_id = bp.bp_id WHERE machine_id = '{$row['machine_id']}'";
                        $machineDetailsResult = $con->query($machineDetailsQuery);

                        if ($machineDetailsResult->num_rows > 0) {
                            $machineDetails = $machineDetailsResult->fetch_assoc();
                            $machineImage = $machineDetails['machine_image'];
                            $machinePrice = $machineDetails['sales_price'];
                        ?>

                        <!-- Card body with small size -->
                        <div class="card-body">
                            <!-- Display machine image -->
                            <img src="../uploads/<?php echo $machineImage; ?>" alt="Machine Image" class="card-img-top mb-3" style="height: 400px; object-fit: cover;">

                            <!-- Display machine name -->
                            <h5 class="card-title"><?php echo $row['machine_name']; ?></h5>
                            <!-- Display machine price -->
                            <p class="card-text">Price: <?php echo $machinePrice; ?></p>

                            <!-- Add to Cart and Remove buttons -->
                            <div class="row">
                            <div class="col-md-6 d-flex justify-content-start">
                            <a href="../machinedetails_farmer.php?machine_id=<?php echo $row['machine_id']; ?>" class="btn btn-warning btn-block">View Machine</a>


                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                <form method="post" action="removemachine_wishlist.php">
                                    <input type="hidden" name="wishlist_id" value="<?php echo $row['wishlist_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-block">Remove</button>
                                </form>
                            </div>
                        </div>

                        </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <p class="mt-3">Your wishlist is empty.</p>
    <?php } ?>
</div>

<!-- <script>
    // Add to Cart function (replace with your actual function)
    function addToCart(machineId) {
        alert('Added to Cart');
        // Add your logic to add the item to the cart
    }

    // Remove from Wishlist function (replace with your actual function)
    function removeFromWishlist(wishlistId) {
        alert('Removed from Wishlist');
        // Add your logic to remove the item from the wishlist
    }
</script> -->


<!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


    </div>

   
</body>
</html>

<?php
} else {
    echo "Farmer ID not found";
}
?>
