<?php
session_start();
include('dbconnection.php');
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] !== 'farmer') {
    header('Location: index.php');
    exit();
}

if(isset($_POST['update_application'])){
    // Retrieve form data
    $application_id = $_POST['application_id'];
    $new_name = $_POST['name'];
    $new_address = $_POST['address'];
    // $new_phone = $_POST['phone'];
    $new_land_area = $_POST['land_area'];

    // Update query
    $updateQuery = "UPDATE scheme_application SET name='$new_name', address='$new_address', land_area='$new_land_area' WHERE application_id='$application_id'";
    $result = $con->query($updateQuery);
    if($result){
        echo "<script>alert('Details updated successfully')</script>";
    } else {
        echo "<script>alert('Failed to update details')</script>";
    }
}

if(isset($_GET['application_id'])){
    $application_id = $_GET['application_id'];
    $getSchemeApplicationQuery = "SELECT * FROM scheme_application WHERE application_id='$application_id'";
    $result = $con->query($getSchemeApplicationQuery);
    if($result && $result->num_rows > 0){
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $address = $row['address'];
        // $phone = $row['phone_number'];
        $land_area = $row['land_area'];
    } else {
        echo "<script>alert('Scheme application not found')</script>";
        // Redirect or handle the situation where the scheme application is not found
    }
} else {
    echo "<script>alert('Scheme application ID not provided')</script>";
    // Redirect or handle the situation where the scheme application ID is not provided
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Edit Scheme Application</title>
</head>
<body>
    <?php
    include('navbar/navbar_farmer.php');
    ?>
<div class="container">
        <h1 class="mt-4 mb-4">Edit Scheme Application</h1>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Application Details</h5>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <input type="hidden" name="application_id" value="<?php echo $application_id; ?>">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?php echo $name; ?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" class="form-control" name="address" id="address" value="<?php echo $address; ?>">
                    </div>
                    <!-- <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" class="form-control" name="phone" id="phone" value="<?php echo $phone; ?>">
                    </div> -->
                    <div class="form-group">
                        <label for="land_area">Land Area:</label>
                        <input type="text" class="form-control" name="land_area" id="land_area" value="<?php echo $land_area; ?>">
                    </div>
                    <button type="submit" name="update_application" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
