<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Vendor dashboard</title>
  <link rel="shortcut icon" type="image/png" href="/Binishaprg//src//assets//images//logos//favicon.png" />
  <!-- <base href="../"> -->
  <link rel="stylesheet" href="/Binishaprg/src/assets/css/styles.min.css" />
<!-- Themeify Icons -->
<link rel="stylesheet" href="path/to/themeify-icons.css">

<!-- FontAwesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.19.0/dist/css/bootstrap-icons.min.css" rel="stylesheet">

</head>
<body>
 <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar bg-light fixed">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        
        <!-- Sidebar navigation-->

     
<div class="container">
  <div class="row">
    <div class="col text-center">
      <img src="/Binishaprg/src/assets/images/profile/user-1.jpg" class="img-fluid rounded-circle mb-3" alt="Profile Picture" 
      style="width: 100px; height: 100px;">
  
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="height:40px">
        <?php
      // Make sure to start the session
      if (isset($_SESSION['useremail'])) {
        echo '<p>' . $_SESSION['useremail'] . '</p>';
      }
      ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
          <!-- <li><a class="dropdown-item" href="#">My Profile</a></li> -->
          <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>


        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <!-- <span class="hide-menu">Home</span> -->
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./dashboard_vendor.php" aria-expanded="false">
                <span>
                  <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./machine_details.php" aria-expanded="false">
                <span>
                  <i class="fas fa-cogs"></i>
                </span>
                <span class="hide-menu">Product list</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="./view_orderdetails.php" aria-expanded="false">
                <span>
                <i class="fas fa-shopping-cart"></i>

                </span>
                <span class="hide-menu">Order list</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="./view_cancelList.php" aria-expanded="false">
                <span>
                <i class="fas fa-times-circle"></i>

                </span>
                <span class="hide-menu">Cancellation list</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./view_returnList.php" aria-expanded="false">
                <span>
                <i class="fas fa-exchange-alt"></i>

                </span>
                <span class="hide-menu">Returned  list</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./view_replacedList.php" aria-expanded="false">
                <span>
                <i class="fas fa-sync-alt"></i>

                </span>
                <span class="hide-menu">Replaced machines list</span>
              </a>
            </li>



            <li class="sidebar-item">
              <a class="sidebar-link" href="./report.php" aria-expanded="false">
                <span>
                <i class="fas fa-chart-line"></i>
                </span>
                <span class="hide-menu">Order report</span>
              </a>
            </li>

            <li class="sidebar-item">
              <a class="sidebar-link" href="../vendor_details/rentalmachines.php" aria-expanded="false">
                <span>
                <i class="fas fa-industry"></i>

                </span>
                <span class="hide-menu">Rent machines</span>
              </a>
            </li>

            </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header bg-custom-blue">
        <nav class="navbar navbar-expand-lg navbar-light fixed-top">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
              
            </li>
            <!-- <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
            </li> -->
          </ul>
         
        </nav>
      </header>
  <script src="/Binishaprg/src/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="/Binishaprg/src/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/Binishaprg/src/assets/js/sidebarmenu.js"></script>
  <script src="/Binishaprg/src/assets/js/app.min.js"></script>
  <script src="/Binishaprg/src/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="/Binishaprg/src/assets/libs/simplebar/dist/simplebar.js"></script>
  <script src="/Binishaprg/src/assets/js/dashboard.js"></script>
</body>
</html>