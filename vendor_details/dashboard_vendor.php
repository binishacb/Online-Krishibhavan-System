<!DOCTYPE html>
<?php
include('../dbconnection.php');
session_start();

if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] != 'vendor') {
  header('Location: ../index.php');
  exit();
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
<!-- 
    <div class="container-fluid">

      <div class="row">
        <!-- 
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
                      <p class="fs-3 mb-0">Count:</p>
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
                      <p class="fs-3 mb-0">Count:</p>
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
                      <p class="fs-3 mb-0">Count:</p>
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
                    <select class="form-select">
                      <option value="1">March 2023</option>
                      <option value="2">April 2023</option>
                      <option value="3">May 2023</option>
                      <option value="4">June 2023</option>
                    </select>
                  </div>
                </div>
                <div id="chart"></div>
              </div>
            </div>
          </div> -->

          <div id="heading-container">
        <h5 class="card-title text-center mt-3">Welcome, <?php echo $_SESSION['useremail']; ?></h5>
    </div>

    <!-- Image Container -->
    <div id="image-container">
        <img src="../images/dashboard_vendor.jpg" class="card-img-top" alt="Vendor Image" id="dashboard-image">
    </div>



    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</body>
</html>
