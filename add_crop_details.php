<!DOCTYPE html>
<html>
<?php
session_start();
include('dbconnection.php');
include('navbar/navbar_farmer.php');
?>

<head>
    <title>Add Land Area</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    .container {
        max-width: 600px;
        /* Adjust the maximum width as needed */
    }

    .my-2 {
        margin-top: 10px;
        margin-bottom: 10px;
    }
    </style>
</head>

<?php
// Include your database connection file

if (isset($_POST['submit'])) {
    $land_area = $_POST["land_area"];
    $crop_name = $_POST["crop_name"];

    $useremail = $_SESSION["useremail"];

    $sql = "INSERT INTO `land_details`(`farmer_email`, `land_area`, `crop_name`) VALUES ('$useremail', '$land_area', '$crop_name')";

    // Execute the SQL statement
    $insert_query_run = mysqli_query($con, $sql);

    if ($insert_query_run) {
        echo "<script>alert('Added successfully'); window.location = 'farmer_add_crop_details.php';</script>";
    } else {
        echo "<script>alert('Failed to add data'); window.location = 'farmer_add_crop_details.php';</script>";
    }
}

if (isset($_POST["delete_id"])) {
    $delete_id = $_POST["delete_id"];

    // Add a condition to target entries with land_area value of 0.00
    $sql = "DELETE FROM land_details WHERE id = $delete_id";

    $delete_query_run = mysqli_query($con, $sql);

    if ($delete_query_run) {
        echo "<script>alert('Entry deleted successfully.'); window.location = 'farmer_add_crop_details.php';</script>";
    } else {
        echo "<script>alert('Failed to delete entry.'); window.location = 'farmer_add_crop_details.php';</script>";
    }
}
?>

<body>
    <br>
    <br>

    <div class="container my-2">
        <h2 class="my-2">Add Land Area</h2>
        <form action="#" method="post">
            <div class="form-group">
                <label for="land_area">Land Area (acres):</label>
                <input type="text" class="form-control" id="land_area" name="land_area" required>
            </div>
            <div class="form-group">
                <label for="crop_name">Cultivation Crop Name:</label>
                <input type="text" class="form-control" id="crop_name" name="crop_name" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <br>
    <br>
    <div class="container my-2">
        <h2 class="my-2">Land Details</h2>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <?php echo "Land Details under " . $_SESSION["useremail"]; ?>
                    <th>Land Area (acres)</th>
                    <th>Cultivation Crop Name</th>
                    <th>Action</th> <!-- Add a column for the delete button -->
                </tr>
            </thead>
            <tbody>
                <?php
                $useremail = $_SESSION["useremail"];
                $sql = "SELECT id, land_area, crop_name FROM land_details WHERE farmer_email = '$useremail'";
                $result = mysqli_query($con, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row["land_area"] . " Acre </td>";
                        echo "<td>" . $row["crop_name"] . "</td>";
                        // Add a delete button with a form to delete the entry
                        echo '<td>
                            <form method="post">
                                <input type="hidden" name="delete_id" value="' . $row["id"] . '">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No land details found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>