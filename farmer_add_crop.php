<?php
session_start();
include('dbconnection.php');

// Function to fetch and display crops and acres
function displayCropsAndAcres($con, $farmer_email) {
    $query = "SELECT * FROM `farmers_crops` WHERE `farmer_email` = '$farmer_email'";
    $result = mysqli_query($con, $query);

    if ($result) {
        echo "<table class='table table-bordered table-striped'>";
        echo "<thead><tr><th>Crop</th><th>Acres</th><th>Delete</th></tr></thead><tbody>";
        
        while ($row = mysqli_fetch_assoc($result)) 
        {
            echo "<tr>";
            echo "<td>" . $row['crop'] . "</td>";
            echo "<td>" . $row['acres'] . "</td>";
            echo "<td>";
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='delete_id' value='" . $row['id'] . "'>";
            echo "<button type='submit' name='delete' class='btn btn-danger'>Delete</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</tbody></table>";
    } else 
    {
        echo "Error: " . mysqli_error($con);
    }
}

if (isset($_POST['submit'])) {
    // Retrieve form data
    $acres = $_POST['acres'];
    $crop = $_POST['crop'];

    // Insert data into the database
    $farmer_email = $_SESSION['useremail'];
    $query = "INSERT INTO `farmers_crops` (`farmer_email`, `acres`, `crop`) VALUES ('$farmer_email', '$acres', '$crop')";
    $result = mysqli_query($con, $query);

    // Check if the insertion was successful
    if ($result) {
        // Display a success alert
        echo "<script>alert('Data inserted successfully!');</script>";
    } else {
        // Display an error alert
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}

if (isset($_POST['delete'])) {
    // Retrieve the row ID to be deleted
    $delete_id = $_POST['delete_id'];

    // Perform the deletion
    $delete_query = "DELETE FROM `farmers_crops` WHERE `id` = '$delete_id'";
    $delete_result = mysqli_query($con, $delete_query);

    if ($delete_result) {
        echo "<script>alert('Row deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Farmers Crops</title>
    <!-- Add your Bootstrap CSS link here -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <?php include('navbar/navbar_farmer.php'); ?>

    <div class="container">
        <h2>Add Land Information</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="acres">Number of Acres:</label>
                <input type="number" class="form-control" id="acres" name="acres" placeholder="Enter number of acres"
                    required>
                <!-- You can add additional validation feedback elements if needed -->
            </div>

            <div class="form-group">
                <label for="crop">Cultivated Crop:</label>
                <select class="form-control" id="crop" name="crop" required>
                    <option value="" disabled selected>Select a crop</option>
                    <option value="wheat">Wheat</option>
                    <option value="coconut">Coconut</option>
                    <option value="rice">Rice</option>
                    <option value="corn">Corn</option>
                    <option value="potato">Potato</option>
                    <option value="tomato">Tomato</option>
                    <option value="cotton">Cotton</option>
                    <option value="soybean">Soybean</option>
                    <option value="barley">Barley</option>
                    <option value="sugarcane">Sugarcane</option>
                </select>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>

        <h2>Crops and Acres Table</h2>
        <?php
        displayCropsAndAcres($con, $_SESSION['useremail']);
        ?>
    </div>

    <!-- Add your Bootstrap JS and Popper.js scripts here -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<br><br><br>
    <?php include('footer/footer.php'); ?>
</body>

</html>