<?php
session_start();
include('dbconnection.php');
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'farmer') {
    header('Location: index.php');
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
</head>

<body>
    <?php include('navbar/navbar_machines.php'); ?>
    <div class="container mt-4">

        <?php
        $getOrderDetailsQuery = "SELECT sa.*, machine_name, machine_image FROM shipping_address sa 
                                JOIN machines ON sa.machine_id = machines.machine_id 
                                WHERE farmer_id = '$farmerID'";
        $orderResult = $con->query($getOrderDetailsQuery);

        if ($orderResult->num_rows > 0) {
            while ($rowOrder = $orderResult->fetch_assoc()) {
        ?>
                <br><br><br><br>
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card" style="max-width: 60%;">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img src="uploads/<?php echo $rowOrder['machine_image']; ?>" class="card-img" alt="Machine Image">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $rowOrder['machine_name']; ?></h5>
                                        <p class="card-text">Shipping Address: <?php echo $rowOrder['address']; ?></p>
                                        <p class="card-text">Order Date: <?php echo $rowOrder['order_date']; ?></p>
                                        <p class="card-text">Payment Status: <span class="badge <?php echo ($rowOrder['status'] == 2) ? 'bg-success' : 'bg-warning'; ?>"><?php echo ($rowOrder['status'] == 2) ? 'Paid' : 'Pending'; ?></span></p>
                                        <?php if ($rowOrder['status'] == 2): ?>
                                            <p class="card-text">Order Status: 
                                                <span class="badge
                                                    <?php 
                                                        if ($rowOrder['tracking_status'] == 1) {
                                                            echo 'bg-info">Under Processing';
                                                        } elseif ($rowOrder['tracking_status'] == 2) {
                                                            echo 'bg-primary">Shipped';
                                                        } elseif ($rowOrder['tracking_status'] == 3) {
                                                            echo 'bg-success">Delivered';
                                                        } elseif ($rowOrder['tracking_status'] == 0) {
                                                            echo 'bg-light">Order confirmed';
                                                        }
                                                    ?>">
                                                </span>
                                            </p>
                                            <button class="btn btn-primary" onclick="downloadReceipt()">View Receipt</button>
                                            <button class="btn btn-danger" data-toggle="modal" data-target="#cancelOrderModal">Cancel Order</button>
                                        <?php endif; ?>

                                        <div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="cancelOrderModalLabel">Cancel Order</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="modal-body">
                                                            <p id="trackingStatusPlaceholder"></p>
                                                            <p>Are you sure you want to cancel this order? Please provide the reason below:</p>
                                                            <form>
                                                                <div class="form-group">
                                                                    <label for="cancelReason">Reasons for cancellation:</label>

                                                                    <?php if ($rowOrder['tracking_status'] == 3) : ?>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" value="Quality Issues" id="reasonQualityIssues">
                                                                            <label class="form-check-label" for="reasonQualityIssues">Quality Issues</label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" value="Received Wrong Product" id="reasonReceivedWrongProduct">
                                                                            <label class="form-check-label" for="reasonReceivedWrongProduct">Received Wrong Product</label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" value="Defective Product" id="reasonDefectiveProduct">
                                                                            <label class="form-check-label" for="reasonDefectiveProduct">Defective Product</label>
                                                                        </div>
                                                                    <?php endif; ?>

                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" value="Changed Mind" id="reasonChangedMind">
                                                                        <label class="form-check-label" for="reasonChangedMind">Changed Mind</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" value="Product Not Required Anymore" id="reasonNotRequired">
                                                                        <label class="form-check-label" for="reasonNotRequired">Product Not Required Anymore</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" value="Found Better Price" id="reasonBetterPrice">
                                                                        <label class="form-check-label" for="reasonBetterPrice">Found Better Price</label>
                                                                    </div>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" value="Ordered wrong product" id="reasonWrongProduct">
                                                                        <label class="form-check-label" for="reasonWrongProduct">Ordered Wrong Product</label>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="cancelOtherReason">Other Reason (if any):</label>
                                                                    <textarea class="form-control" id="cancelOtherReason" rows="3" placeholder="Enter other reason"></textarea>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p>No matching records found</p>";
        }
        ?>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
