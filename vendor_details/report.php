<?php
session_start();
include('../dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Details</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Additional custom styling */
    .table th, .table td {
        vertical-align: middle;
    }

  </style>
</head>
<body>
<?php
include('navbar_vendor.php');
?>
<div class="container mt-4">
    <h3 class="mb-3 text-center">Orders Report</h3>
    
    <div class="row justify-content-end mb-3">
    <div class="col-auto">
  
            <?php
             $email = $_SESSION['useremail'];
             $vendor_query = "SELECT vendor.vendor_id FROM vendor JOIN login ON vendor.log_id = login.log_id WHERE login.email = '$email'";
             $vendor_result = mysqli_query($con, $vendor_query);

             if ($vendor_result && $vendor_row = mysqli_fetch_assoc($vendor_result)) {
                 $vendor_id = $vendor_row['vendor_id'];
 
            // Get the monthly order count
            $monthly_order_count_query = "SELECT DATE_FORMAT(order_date, '%Y-%m') AS month, COUNT(shipping_id) AS order_count
                                          FROM shipping_address JOIN machines ON machines.machine_id = shipping_address.machine_id
                                          WHERE vendor_id = '$vendor_id'
                                          GROUP BY DATE_FORMAT(order_date, '%Y-%m')";
            $monthly_order_count_result = mysqli_query($con, $monthly_order_count_query);
            $monthly_order_counts = array();
            while ($row = mysqli_fetch_assoc($monthly_order_count_result)) {
                $monthly_order_counts[$row['month']] = $row['order_count'];
            }
?>

<label for="monthFilter" class="form-label">Filter by Month:</label> 
<select id="monthFilter" class="form-select">
    <option value="">All</option>
    <?php
        foreach ($monthly_order_counts as $month => $order_count) {
            echo "<option value='$month'>$month</option>";
        }
    ?>
</select>

        </div>
          </div>
<br>


    <!-- Table structure -->
    <div class="table-responsive">
        <table id="orderTable" class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Order Date</th>
                    <th>Customer Address</th>
                    <th>Machine Name</th>
                    <th>Quantity (in no.s)</th>
                    <th>Total Price (in INR)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
             
                    $totalPrice = 0; 
                    $totalCancelledAmount = 0; 
                    $cancelledOrdersCount = 0;
                    $returnOrdersCount =0;
                    $totalReturnedAmount =0;
                    

                    // Fetching orders
                    $order_query = "SELECT shipping_address.*, machines.machine_name, farmer.firstname, farmer.lastname FROM shipping_address
                        JOIN machines ON shipping_address.machine_id = machines.machine_id
                        JOIN farmer ON shipping_address.farmer_id = farmer.farmer_id
                        WHERE machines.vendor_id = '$vendor_id'";

                    $order_result = mysqli_query($con, $order_query);

                    while ($order_row = mysqli_fetch_assoc($order_result)) {
                       
                        $totalPrice += $order_row['total_price'];

                      
                        if ($order_row['cancel_return_status'] == 'cancelled') {
                            $cancelledOrdersCount++; 
                            $totalCancelledAmount += $order_row['total_price']; 
                        }
                        else if ($order_row['cancel_return_status'] == 'returned') {
                          $returnOrdersCount++; 
                          $totalReturnedAmount += $order_row['total_price']; 
                      }
                      $totalProfit = $totalPrice -  $totalCancelledAmount - $totalReturnedAmount;
                   
                ?>
                <tr>
                    <td><?php echo $order_row['order_id']; ?></td>
                    <td><?php echo $order_row['order_date']; ?></td>
                    <td><?php echo $order_row['firstname'] . ' ' . $order_row['lastname'] . "<br>" . $order_row['address'] . "<br>Phone no: " . $order_row['phone_no']; ?></td>
                    <td><?php echo $order_row['machine_name']; ?></td>
                    <td><?php echo $order_row['quantity']; ?></td>
                    <td>Rs <?php echo $order_row['total_price']; ?></td>
                </tr>
                <?php
                    }

                 
                    echo '<tr>';
                    echo '<td colspan="5" class="text-end"><strong>Total amount:</strong></td>';
                    echo '<td><strong>Rs ' . $totalPrice . '</strong></td>';
                    echo '</tr>';

                    // Display cancelled orders count and total cancelled amount
                    echo '<tr>';
                    echo '<td colspan="5" class="text-end"><strong>Cancelled Orders Count:</strong></td>';
                    echo '<td><strong>' . $cancelledOrdersCount . '</strong></td>';
                    echo '</tr>';
                    echo '<tr>';
                    echo '<td colspan="5" class="text-end"><strong>Total Cancelled Amount:</strong></td>';
                    echo '<td><strong>Rs ' . $totalCancelledAmount . '</strong></td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td colspan="5" class="text-end"><strong>Returned Orders Count:</strong></td>';
                    echo '<td><strong> ' .  $returnOrdersCount . '</strong></td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td colspan="5" class="text-end"><strong>Total returned order amount:</strong></td>';
                    echo '<td><strong>Rs ' . $totalReturnedAmount . '</strong></td>';
                    echo '</tr>';

                    echo '<tr>';
                    echo '<td colspan="5" class="text-end"><strong>Total income:</strong></td>';
                    echo '<td><strong>Rs ' . $totalProfit . '</strong></td>';
                    echo '</tr>';

                } else {
                    echo "Error fetching vendor details.";
                }

                mysqli_close($con);
                ?>
            </tbody>
        </table>
    </div>
</div>
<div class="d-print-none print-btn">
    <a class="btn btn-primary" onclick="window.print()">Print report</a>
</div>


<!-- Place this JavaScript code after the table and before the closing </body> tag -->
<script>
    // Function to filter the table based on selected month
    document.getElementById('monthFilter').addEventListener('change', function() {
        var selectedMonth = this.value; // Get the selected month from the dropdown
        var tableRows = document.querySelectorAll('#orderTable tbody tr'); // Get all table rows
        
        // Loop through each row
        tableRows.forEach(function(row) {
            var rowMonth = row.cells[1].textContent.substring(0, 7); // Extract the month from the second cell (order date)
            
            // Show or hide the row based on the selected month
            if (selectedMonth === '' || rowMonth === selectedMonth) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>


</body>
</html>
