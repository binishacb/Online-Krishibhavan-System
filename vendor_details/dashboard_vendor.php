<!DOCTYPE html>
<?php
include('../dbconnection.php');
session_start();

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] != 'vendor') {
  header('Location: ../index.php');
  exit();
}




$email = $_SESSION['useremail'];
$vendor_query = "SELECT vendor.vendor_id FROM vendor JOIN login ON vendor.log_id = login.log_id WHERE login.email = '$email'";
$vendor_result = mysqli_query($con, $vendor_query);

if ($vendor_result && $vendor_row = mysqli_fetch_assoc($vendor_result)) {
    $vendor_id = $vendor_row['vendor_id'];

    // Fetch count of machine categories
    $count_query = "SELECT machines.vendor_id, COUNT(shipping_address.shipping_id) AS order_count, COUNT(DISTINCT machines.type_id) AS category_count, COUNT(DISTINCT machines.machine_id) AS total_machines
    FROM machines LEFT JOIN shipping_address ON machines.machine_id = shipping_address.machine_id
    WHERE machines.vendor_id = '$vendor_id' GROUP BY machines.vendor_id";
    $count_result = mysqli_query($con, $count_query);

    if ($count_result && mysqli_num_rows($count_result) > 0) {
        // Total count of machine categories
        $total_count = 0;
        $total_machines = 0;
        $order_count = 0;
        while ($count_row = mysqli_fetch_assoc($count_result)) {
            $total_count += $count_row['category_count'];
            $total_machines += $count_row['total_machines'];
            $order_count += $count_row['order_count'];
        }

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


<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendor Dashboard</title>
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <?php
    include('navbar_vendor.php');
    ?>
    <br><br><br>

    <div class="container-fluid">

      <div class="row">
        
        <div class="col-lg-4">
          <div class="row">
            <div class="col-lg-12">
            
              <div class="card overflow-hidden" style="background-color: #ADD8E6;">
                <div class="card-body p-4">
                  <h5 class="card-title mb-9 fw-semibold">Machine Categories</h5>
                  <div class="row align-items-center">
                    <div class="col-8">
                      <h4 class="fw-semibold mb-3"></h4>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                      <span class="me-1 rounded-circle bg-light-success round-20 d-flex align-items-center justify-content-center">
                        <i class="ti ti-arrow-up-left text-success"></i>
                      </span>
                      <p class="fs-3 mb-0">Count:<?php echo $total_count; ?></p>
                    </div>
                  </div>
                </div>
              </div>



            </div>
          </div>
        </div>

       
        <div class="col-lg-4">
          <div class="row">
            <div class="col-lg-12">
           
              <div class="card overflow-hidden" style="background-color: #ADD8E6;">
                <div class="card-body p-4">
                  <h5 class="card-title mb-9 fw-semibold">Total machines</h5>
                  <div class="row align-items-center">
                    <div class="col-8">
                      <h4 class="fw-semibold mb-3"></h4>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                      <span class="me-1 rounded-circle bg-light-success round-20 d-flex align-items-center justify-content-center">
                        <i class="ti ti-arrow-up-left text-success"></i>
                      </span>
                      <p class="fs-3 mb-0">Count:<?php echo $total_machines; ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="row">
            <div class="col-lg-12">
            
              <div class="card overflow-hidden" style="background-color: #ADD8E6;">
                <div class="card-body p-4">
                  <h5 class="card-title mb-9 fw-semibold">Total orders</h5>
                  <div class="row align-items-center">
                    <div class="col-8">
                      <h4 class="fw-semibold mb-3"></h4>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                      <span class="me-1 rounded-circle bg-light-success round-20 d-flex align-items-center justify-content-center">
                        <i class="ti ti-arrow-up-left text-success"></i>
                      </span>
                      <p class="fs-3 mb-0">Count:<?php echo $order_count; ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


      
        <div class="row">
  <div class="col-lg-8 d-flex align-items-strech">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
          <div class="mb-3 mb-sm-0">
            <h5 class="card-title fw-semibold">Sales Overview</h5>
          </div>
          <div>
            <select id="month-select" class="form-select">
              <?php
                // Generate options for month select
                foreach ($monthly_order_counts as $month => $order_count) {
                    echo "<option value='$month'>$month</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <canvas id="chart"></canvas>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Get the selected month
  var monthSelect = document.getElementById('month-select');
  var selectedMonth = monthSelect.options[monthSelect.selectedIndex].value;

  // Get monthly order counts from PHP
  var monthlyOrderCounts = <?php echo json_encode($monthly_order_counts); ?>;

  // Prepare chart data
  var labels = Object.keys(monthlyOrderCounts);
  var data = Object.values(monthlyOrderCounts);

  // Render chart
  var ctx = document.getElementById('chart').getContext('2d');
  var chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Orders',
        data: data,
        fill: false,
        borderColor: 'rgb(75, 192, 192)',
        tension: 0.1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  
  monthSelect.addEventListener('change', function() {
    selectedMonth = this.value;
    
  });
</script>


          <?php
    } else {
        echo "No machine categories found.";
    }
} else {
    echo "Error fetching vendor details.";
}

mysqli_close($con);
?>
        
        
        
        
        
        

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</body>
</html>
