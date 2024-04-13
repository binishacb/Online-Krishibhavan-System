<?php
session_start();
include('dbconnection.php'); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Officers Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php
    include('navbar/navbar_admin.php');
   
  $k_id = base64_decode($_GET['krishibhavan_id']);
    function checkOfficerExists($con, $designation_id,$k_id)
    {
        $sql = "SELECT * FROM officer WHERE designation_id = $designation_id and status=0 and krishibhavan_id =$k_id";
        $result = $con->query($sql);
        return $result->num_rows;
    }
    // Function to get the first names and IDs of officers with a specific designation
    function getOfficerData($con, $designation_id,$k_id)
    {
        $officerData = array();
        $sql = "SELECT officer_id, firstname,lastname FROM officer WHERE designation_id = $designation_id and status=0 and krishibhavan_id =$k_id";
        $result = $con->query($sql);
        while ($row = $result->fetch_assoc()) {
            $officerData[] = $row;
        }
        return $officerData;
    }

    // Get the counts and officer data for each designation
    $agricultureOfficerCount = checkOfficerExists($con, 1,$k_id);
    $assistantOfficerCount = checkOfficerExists($con, 2,$k_id);
    $marketingOfficerCount = checkOfficerExists($con, 3,$k_id);
    $staffCount = checkOfficerExists($con,4,$k_id);
    $agricultureOfficerData = getOfficerData($con, 1,$k_id);
    $assistantOfficerData = getOfficerData($con, 2,$k_id);
    $marketingOfficerData = getOfficerData($con, 3,$k_id);
    $staffData = getOfficerData($con, 4,$k_id);
    ?>
    <div class="container mt-5">
        <h1>Officers Management</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header ">Agriculture Officer</div>
                    <div class="card-body">
                        <!-- Add Agriculture Officer Cards Here -->
                        <?php
                        $availableAgricultureButtons = 1 - $agricultureOfficerCount;
                        for ($i = 0; $i < $agricultureOfficerCount; $i++) {
                            $officer_id = $agricultureOfficerData[$i]['officer_id'];
                            $firstname = $agricultureOfficerData[$i]['firstname'];
                            $lastname = $agricultureOfficerData[$i]['lastname'];
                            echo "<div class='d-flex justify-content-between'>
                                <div>$firstname $lastname</div>
                                <a href='delete_officer.php?officer_id=$officer_id' class='btn btn-danger btn-sm  my-1'>Delete</a>
                            </div>";
                        }
                        if ($availableAgricultureButtons > 0) {
                            for ($i = 0; $i < $availableAgricultureButtons; $i++) {
                                echo "<a href='officer_registration.php?designation_name=Agriculture%20Officer&k_id=$k_id' class='btn btn-primary btn-sm  my-1'>Add Officer</a><br>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header ">Assistant Officer</div>
                    <div class="card-body">
                        <!-- Add Assistant Officer Cards Here -->
                        <?php
                        $availableAssistantButtons = 2 - $assistantOfficerCount;
                        for ($i = 0; $i < $assistantOfficerCount; $i++) {
                            $officer_id = $assistantOfficerData[$i]['officer_id'];
                            $firstname = $assistantOfficerData[$i]['firstname'];
                            $lastname = $assistantOfficerData[$i]['lastname'];
                            echo "<div class='d-flex justify-content-between'>
                                <div>$firstname $lastname</div>
                                <a href='delete_officer.php?officer_id=$officer_id' class='btn btn-danger btn-sm  my-1'>Delete</a>
                            </div>";
                        }
                        if ($availableAssistantButtons > 0) {
                            for ($i = 0; $i < $availableAssistantButtons; $i++) {
                                echo "<a href='officer_registration.php?designation_name=Assistant%20Officer&k_id=$k_id' class='btn btn-primary btn-sm  my-1'>Add Officer</a><br>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Marketing Officer</div>
                    <div class="card-body">
                        <!-- Add Marketing Officer Cards Here -->
                        <?php
                        $availableMarketingButtons = 1 - $marketingOfficerCount;
                        for ($i = 0; $i < $marketingOfficerCount; $i++) {
                            $officer_id = $marketingOfficerData[$i]['officer_id'];
                            $firstname = $marketingOfficerData[$i]['firstname'];
                            $lastname = $marketingOfficerData[$i]['lastname'];
                            echo "<div class='d-flex justify-content-between'>
                                <div>$firstname $lastname</div>
                                <a href='delete_officer.php?officer_id=$officer_id' class='btn btn-danger btn-sm  my-1' >Delete</a>
                            </div>";
                        }
                        if ($availableMarketingButtons > 0) {
                            for ($i = 0; $i < $availableMarketingButtons; $i++) {
                                echo "<a href='officer_registration.php?designation_name=Marketing%20Officer&k_id=$k_id' class='btn btn-primary btn-sm  my-1'>Add Officer</a><br>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Staff</div>
                    <div class="card-body">
                        <p>Staff Count: <?php echo $staffCount; ?></p>
                        <p>You can Add 5 Staffs.
                        </p>
                        <!-- Add Staff Cards Here -->
                        <?php
                        $availableStaffButtons = 5 - $staffCount;
                        for ($i = 0; $i < $staffCount; $i++) {
                            $officer_id = $staffData[$i]['officer_id'];
                            $firstname = $staffData[$i]['firstname'];
                            $lastname = $staffData[$i]['lastname'];
                            echo "<div class='d-flex justify-content-between'>
                                <div>$firstname $lastname</div>
                                <a href='delete_officer.php?officer_id=$officer_id' class='btn btn-danger btn-sm my-1'>Delete</a>
                            </div>";
                        }
                        if ($availableStaffButtons > 0) {
                            for ($i = 0; $i < $availableStaffButtons; $i++) {
                                echo "<a href='officer_registration.php?designation_name=Staffs&k_id=$k_id' class='btn btn-primary btn-sm  my-1'>Add Officer</a><br>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>