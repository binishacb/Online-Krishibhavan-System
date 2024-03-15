<?php
session_start();
include('../dbconnection.php');

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'vendor') {
    header('Location: ../index.php');
    exit();
}
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
$email = $_SESSION['useremail'];
$vendor_query = "SELECT vendor.vendor_id FROM vendor JOIN login ON vendor.log_id = login.log_id WHERE login.email = '$email'";
$vendor_result = mysqli_query($con, $vendor_query);

if ($vendor_result && $vendor_row = mysqli_fetch_assoc($vendor_result)) {
    $vendor_id = $vendor_row['vendor_id'];
    // SELECT  ROW_NUMBER() OVER (ORDER BY orders.id) AS sl_no,
    // Assuming you have a table named 'orders' with relevant columns and a column 'machine_id'
    $order_query = "SELECT shipping_address.*,  machines.machine_name, farmer.firstname,farmer.lastname FROM shipping_address
        JOIN machines ON shipping_address.machine_id = machines.machine_id
        JOIN farmer ON shipping_address.farmer_id = farmer.farmer_id
       
        WHERE machines.vendor_id = '$vendor_id'";

    $order_result = mysqli_query($con, $order_query);

    if ($order_result && mysqli_num_rows($order_result) > 0) {
?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Your Orders</title>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.1/css/bootstrap.min.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.19.0/dist/font/bootstrap-icons.css" rel="stylesheet">
        </head>

        <body>
            <?php include('./navbar_vendor.php'); ?>
            <div class="container mt-3">
                <h2 class="text-center p-2 text-primary">Your Orders</h2>
                <div class="mb-3 position-relative" style="width: 300px; margin-left: auto;">
                    <input type="text" class="form-control position-absolute top-0 end-0 border-4" id="searchInput" placeholder="Search by Machine Name">
                </div>
                <br><br>
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Customer Address</th>
                            <th>Machine Name</th>
                            <th>Quantity(in no.s)</th>
                            <th>Total Price</th>
                            <th>Payment Status</th>
                            <th>Order Status</th>
                            <th>Return/Cancelled orders</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order_row = mysqli_fetch_assoc($order_result)) {

                            $order_id =  $order_row['order_id'];

                        ?>
                            <tr>
                                <td><?php echo $order_row['order_id']; ?></td>
                                <td><?php echo $order_row['order_date']; ?></td>
                                <td><?php echo $order_row['firstname'] . ' ' . $order_row['lastname'] . "<br>" . $order_row['address'] . "<br>Phone no: " . $order_row['phone_no']; ?></td>
                                <td><?php echo $order_row['machine_name']; ?></td>
                                <td><?php echo $order_row['quantity']; ?></td>
                                <td>Rs <?php echo $order_row['total_price']; ?></td>
                                <td>
                                    <?php if ($order_row['status'] == 2) { ?>
                                        <span class="badge bg-success">Paid</span>
                                    <?php } else if ($order_row['status'] == 1) { ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <input type="hidden" name="shipping_id" value="<?php echo $order_row['shipping_id']; ?>">
                                        <input type="hidden" name="form_submitted" value="1">
                                        <select name="order_status" class="form-select">
                                        <option value="1" <?php echo $order_row['tracking_status'] == 1 ? "selected" : ($order_row['tracking_status'] == 2 || $order_row['tracking_status'] == 3 ? 'disabled' : ''); ?>>Under process</option>
                                        <option value="2" <?php echo $order_row['tracking_status'] == 2 ? "selected" : ($order_row['tracking_status'] == 3 ? 'disabled' : ''); ?>>Shipped</option>
                                        <option value="3" <?php echo $order_row['tracking_status'] == 3 ? "selected" : ''; ?> <?php echo $order_row['tracking_status'] == 3 ? 'disabled' : ''; ?>>Delivered</option>
                                    </select>
                                        <?php
                                         $disableButton = $order_row['status'] == 1;
                                         ?>
                                         
                                         <button type="submit" name="update_status" class="btn btn-success" style="background-color: cornflowerblue" <?php echo $disableButton ? 'disabled' : ''; ?>>
                                             Update Status
                                         </button>
                                        
                                    </form>
                                </td>

<td>
    <?php
    $return_cancel_status = $order_row['cancel_return_status'];
   
if($return_cancel_status == 'cancelled'){?>
    
    <span class="badge bg-danger">Order Cancelled</span>
<?php
}
elseif($return_cancel_status == 'returned')
{?>
    <span class="badge bg-danger">Order Returned</span>
    <?php
}
?>
</td>

                    <?php
                        } 
                        if (isset($_POST['form_submitted']) && isset($_POST['update_status'])) {
                           
                            $shipping_id = $_POST['shipping_id'];
                            $new_status = $_POST['order_status'];
                            $update_query = "UPDATE shipping_address SET tracking_status = '$new_status' WHERE shipping_id = '$shipping_id'";
                            if ($con->query($update_query) === TRUE) {
                                echo "<script>alert('Status updated successfully.')</script>";
                                echo "<script>window.location.href = window.location.href;</script>";
                            } else {
                                echo "<script>alert('Error updating status: . $con->error')</script>";
                            }
                        }
                            ?>

                            </tr>
                    </tbody>
                </table>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.35.1/dist/apexcharts.min.js"></script>


            <script>
                $(document).ready(function() {
                    $("#searchInput").on("input", function() {
                        var value = $(this).val().toLowerCase().replace(/\s/g, ''); // Remove spaces and convert to lowercase
                        $("tbody tr").filter(function() {
                            var machineName = $(this).find("td:nth-child(4)").text().toLowerCase().replace(/\s/g, ''); // Adjust the column index as needed
                            $(this).toggle(machineName.indexOf(value) > -1);
                        });
                    });
                });
            </script>

        </body>

        </html>
<?php
    } else {
        echo "No orders found.";
    }
} else {
    echo "Error fetching vendor details.";
}

mysqli_close($con);
?>