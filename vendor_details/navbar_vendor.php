
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
              <a class="sidebar-link" href="/vendor_details/navbar_vendor.php" aria-expanded="false">
                <span>
                <i class="fas fa-shopping-cart"></i>

                </span>
                <span class="hide-menu">Order list</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="/vendor_details/navbar_vendor.php" aria-expanded="false">
                <span>
                <i class="fas fa-chart-line"></i>
                </span>
                <span class="hide-menu">Monthly order report</span>
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
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
             
              <li class="nav-item dropdown">
                
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="/Binishaprg/src/assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                 <?php
                        if (isset($_SESSION['useremail'])) {
                            echo '' .$_SESSION['useremail'];
                        }
                        ?>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                   
                    <a href="../logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div> 
              </li>
            </ul>
          </div>
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
