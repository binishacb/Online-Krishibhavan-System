<?php
session_start();
include('../dbconnection.php');
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'farmer') {
    header('Location: ../index.php');
    exit();
}

$email = $_SESSION['useremail'];
$getFarmerIdQuery = "SELECT farmer_id FROM farmer JOIN login ON farmer.log_id = login.log_id WHERE login.email = '$email'";
$res = $con->query($getFarmerIdQuery);

if ($res) {
    $row = $res->fetch_assoc();
    $farmerID = $row['farmer_id'];
} else {
    echo "<script>alert('Farmer ID not found')</script>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Order Details</title>
    <style>
    /* Background overlay */
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0) !important; 
    }

    /* Modal content */
    .modal-content .modal-body {
    background-color: lightblue; /* Set the background color of the modal body to blue */
}
</style>
</head>

<body>
    <?php include('../navbar/navbar_rental.php'); ?>

    <br><br><br>

    <h2 class="text-center">My Orders</h2>

    <div class="container mt-4">
        <?php
        $getOrderDetailsQuery = "SELECT rental_orders.*, rp_name, rp_image FROM rental_orders 
                                JOIN rent_product ON rental_orders.rp_id = rent_product.rp_id 
                                WHERE farmer_id = '$farmerID'";
        $orderResult = $con->query($getOrderDetailsQuery);

        if ($orderResult->num_rows > 0) {
            foreach ($orderResult as $rowOrder) {
                $formId = 'receiptForm_' . $rowOrder['rental_id'];
                $buttonId = 'submitReceiptBtn_' . $rowOrder['rental_id'];
        ?>
                <div class="row mb-4">
                    <div class="col-md-8 mx-auto">
                        <div class="card">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img src="../uploads/<?php echo $rowOrder['rp_image']; ?>" class="card-img" alt="Machine Image">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $rowOrder['rp_name']; ?></h5>
                                       
                                        <p class="card-text">Order Date: <?php echo $rowOrder['rental_date']; ?></p>
                                        <p class="card-text">Payment Status: <span class="badge <?php echo ($rowOrder['payment_status'] == 'paid') ? 'bg-success' : 'bg-warning'; ?>"><?php echo ($rowOrder['payment_status'] == 'paid') ? 'Paid' : 'Pending'; ?></span></p>
                                      
                                        <form id="<?php echo $formId; ?>" action="viewRental_receipt.php" method="POST" style="display: none;">
                                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($rowOrder['rental_id'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="machine_id" value="<?php echo htmlspecialchars($rowOrder['rp_id'], ENT_QUOTES, 'UTF-8'); ?>">
                                        </form>
                                        <button id="<?php echo $buttonId; ?>" class="btn btn-primary" onclick="submitReceiptForm('<?php echo $formId; ?>')">View Receipt</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>

    <script>
        function submitReceiptForm(formId) {
            var form = document.getElementById(formId);
            if (form) {
                form.submit();
            } else {
                console.error("Form not found with ID: " + formId);
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
