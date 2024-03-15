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

    <br><br><br>

    <h2 class="text-center">My Orders</h2>

    <div class="container mt-4">
        <?php
        $getOrderDetailsQuery = "SELECT sa.*, machine_name, machine_image FROM shipping_address sa 
                                JOIN machines ON sa.machine_id = machines.machine_id 
                                WHERE farmer_id = '$farmerID'";
        $orderResult = $con->query($getOrderDetailsQuery);

        if ($orderResult->num_rows > 0) {
            // while ($rowOrder = $orderResult->fetch_assoc()) {
                foreach ($orderResult as $rowOrder) {
                    $formId = 'receiptForm_' . $rowOrder['shipping_id'];
                    $buttonId = 'submitReceiptBtn_' . $rowOrder['shipping_id'];
        ?>
                <div class="row mb-4">
                    <div class="col-md-8 mx-auto">
                        <div class="card">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img src="uploads/<?php echo $rowOrder['machine_image']; ?>" class="card-img" alt="Machine Image">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $rowOrder['machine_name']; ?></h5>
                                        <!-- <p class="card-text">Shipping Address: <?php echo $rowOrder['shipping_id']; ?></p>
                                        <p class="card-text">Shipping Address: <?php echo $rowOrder['machine_id']; ?></p> -->
                                        <p class="card-text">Shipping Address: <?php echo $rowOrder['address']; ?></p>
                                        <p class="card-text">Order Date: <?php echo $rowOrder['order_date']; ?></p>
                                        <p class="card-text">Payment Status: <span class="badge <?php echo ($rowOrder['status'] == 2) ? 'bg-success' : 'bg-warning'; ?>"><?php echo ($rowOrder['status'] == 2) ? 'Paid' : 'Pending'; ?></span></p>
                                        <?php if ($rowOrder['status'] == 2) : ?>
                                            <p class="card-text">Order Status:
    <span class="badge
        <?php
            if ($rowOrder['tracking_status'] == 1) {
                echo 'bg-info';
            } elseif ($rowOrder['tracking_status'] == 2) {
                echo 'bg-primary';
            } elseif ($rowOrder['tracking_status'] == 3) {
                echo 'bg-success';
            } elseif ($rowOrder['tracking_status'] == 0) {
                echo 'bg-light';
            }
        ?>
    ">
        <?php
            if ($rowOrder['tracking_status'] == 1) {
                echo 'Order Processing';
            } elseif ($rowOrder['tracking_status'] == 2) {
                echo 'Shipped';
            } elseif ($rowOrder['tracking_status'] == 3) {
                echo 'Delivered';
            } elseif ($rowOrder['tracking_status'] == 0) {
                echo 'Order confirmed';
            }
        ?>
    </span>
</p>

<form id="<?php echo $formId; ?>" action="view_receipt.php" method="POST" style="display: none;">
                        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($rowOrder['shipping_id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="machine_id" value="<?php echo htmlspecialchars($rowOrder['machine_id'], ENT_QUOTES, 'UTF-8'); ?>">
                    </form>

                    <!-- Use unique button ID for each iteration -->
                    <button id="<?php echo $buttonId; ?>" class="btn btn-primary" onclick="submitReceiptForm('<?php echo $formId; ?>')">View Receipt</button>
<!-- <button class="btn btn-primary" onclick="submitReceiptForm()">View Receipt</button> -->

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

                                                    <td>
                                                    <?php
$trackingStatus = $rowOrder['tracking_status'];
$cancel_return_status = $rowOrder['cancel_return_status'];
if ($trackingStatus == 3) {
    
    
     if ($cancel_return_status == 'returned') {
        // If the order is already returned, display "Order Returned" text
        echo '<button class="btn btn-secondary" disabled>Order Returned</button>';
    } else {
        // If the order is in status 3 and not yet cancelled or returned, display "Return Order" button
        $returnModalId = 'returnOrderModal_' . uniqid(); // Generate unique ID
        echo '<button class="btn btn-warning" data-toggle="modal" data-target="#' . $returnModalId . '" onClick="showReturnModal(' . $trackingStatus . ')">Return Order</button>';
    }
} else {
    if ($cancel_return_status == 'cancelled') {
        // If the order is already cancelled, display "Order Cancelled" text
        echo '<button class="btn btn-secondary" disabled>Order Cancelled</button>';
    }else{
    // For other cases, display "Cancel Order" button
    $cancelModalId = 'cancelOrderModal_' . uniqid(); // Generate unique ID
    echo '<button class="btn btn-danger" data-toggle="modal" data-target="#' . $cancelModalId . '" onClick="showCancelModal(' . $trackingStatus . ')">Cancel Order</button>';
}
}
?>


                                                    </td>
                                                <?php endif; ?>



<div class="modal fade" id="<?php echo $returnModalId; ?>" tabindex="-1" role="dialog" aria-labelledby="returnOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="returnOrderModalLabel">Reasons for Return</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to return this order? Please provide the reason below:</p>
                
                <form id="returnOrderForm" action="handle_return_order.php" method="post">
                    <input type="hidden" name="farmer_id" value="<?php echo $rowOrder['farmer_id']; ?>">
                    <input type="hidden" name="machine_id" value="<?php echo $rowOrder['machine_id']; ?>">
                    <input type="hidden" name="order_id" value="<?php echo $rowOrder['shipping_id']; ?>">

                    <div class="form-group">
        <label for="returnReason">Select Reason for Return:</label>
        <select class="form-control" id="returnReason" name="return_reason">
            <option value="" disabled selected>Select reason</option>
            <option value="damaged_product">Damaged Product</option>
            <option value="quality_issues">Quality Issues</option>
            <option value="received_wrong_product">Received Wrong Product</option>
        </select>
    </div>

                    <div class="form-check">
                            <label for="cancelOtherReason">Other Reason (if any):</label>
                            <textarea class="form-control" name = "other_reasons" id="returnOtherReason" rows="3" placeholder="Enter other reason"></textarea>
                        </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" onclick="submitReturnOrder()">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>







    <div class="modal fade" id="<?php echo $cancelModalId; ?>" tabindex="-1" role="dialog" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
 
    
    <!-- <div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog" aria-labelledby="cancelOrderModalLabel" aria-hidden="true"> -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">Reasons for Cancellation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this order? Please provide the reason below:</p>
                      <!-- <p>farmer_id<?php echo  $rowOrder['farmer_id']; ?></p>
                      <p>machine_id<?php echo  $rowOrder['machine_id']; ?></p>
                      <p>order_id<?php echo  $rowOrder['shipping_id']; ?></p> -->

                <form id="cancelOrderForm" action="handle_cancel_order.php" method="post">
                    <input type="hidden" name="farmer_id" value="<?php echo $rowOrder['farmer_id']; ?>">
                    <input type="hidden" name="machine_id" value="<?php echo $rowOrder['machine_id']; ?>">
                    <input type="hidden" name="order_id" value="<?php echo $rowOrder['shipping_id']; ?>">
                    <input type="hidden" name="selectedReasons" id="selectedReasons">
                    <input type="hidden" name="otherReasons" id="cancelOtherReason">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="changed_mind" id="changedMindCheckbox" name="selectedReasons[]">
                            <label class="form-check-label" for="changedMindCheckbox">
                                Changed Mind
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="not_required_anymore" id="notRequiredAnymoreCheckbox" name="selectedReasons[]">
                            <label class="form-check-label" for="notRequiredAnymoreCheckbox">
                                Product Not Required Anymore
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="found_better_price" id="foundBetterPriceCheckbox" name="selectedReasons[]">
                            <label class="form-check-label" for="foundBetterPriceCheckbox">
                                Found Better Price
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ordered_wrong_product" id="orderedWrongProductCheckbox" name="selectedReasons[]">
                            <label class="form-check-label" for="orderedWrongProductCheckbox">
                                Ordered Wrong Product
                            </label>
                        </div>
                        <div class="form-check">
                            <label for="cancelOtherReason">Other Reason (if any):</label>
                            <textarea class="form-control" id="cancelOtherReason" rows="3" placeholder="Enter other reason"></textarea>
                        </div>
                        <div class="modal-footer">
                        <button class="btn btn-danger cancelOrderBtn" data-toggle="modal" data-target="#cancelOrderModal" 
    data-orderid="<?php echo $rowOrder['shipping_id']; ?>"
    data-machineid="<?php echo $rowOrder['machine_id']; ?>">Cancel Order</button>



                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                </form>
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








    <script>
        function showReturnModal(trackingStatus) {
            var orderId = $('#returnOrderModalBtn').data('orderid');
    var machineId = $('#returnOrderModalBtn').data('machineid');
    console.log("Order ID:", orderId);
    console.log("Machine ID:", machineId);
            $('#returnOrderModal').modal('show');
        }



        $(document).ready(function() {
    $('.cancelOrderBtn').on('click', function() {
        var orderId = $(this).data('orderid');
        var machineId = $(this).data('machineid');

        console.log("Order ID:", orderId);
        console.log("Machine ID:", machineId);

        // Show the modal
        $('#cancelOrderModal').modal('show');
    });
});


        function submitReturnOrder() {
           
            var damagedChecked = $('#damagedCheckbox').prop('checked');
            var qualityIssuesChecked = $('#qualityIssuesCheckbox').prop('checked');

            $('#returnOrderModal').modal('hide');
        }

        function submitCancelOrder() {
            // Handle the submission of the cancel order form here
            // You can use JavaScript to get the selected checkboxes and process them
            // For example:
            var changedMindChecked = $('#changedMindCheckbox').prop('checked');
            var notRequiredAnymoreChecked = $('#notRequiredAnymoreCheckbox').prop('checked');
            var foundBetterPriceChecked = $('#foundBetterPriceCheckbox').prop('checked');
            var orderedWrongProductChecked = $('#orderedWrongProductCheckbox').prop('checked');

            // Perform actions based on selected checkboxes
            // ...

            // Close the modal
            $('#cancelOrderModal').modal('hide');
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>




</html>