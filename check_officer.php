<?php
// Include database connection file
include('dbconnection.php'); // Adjust this line according to your setup

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if krishibhavan_name is set in the POST data
    if (isset($_POST['krishibhavan_name'])) {
        // Retrieve selected krishibhavan_name from POST data
        $selectedKrishibhavan = $_POST['krishibhavan_name'];
        
        // Query to fetch krishibhavan_id based on krishibhavan_name
        $krishibhavanQuery = "SELECT krishibhavan_id FROM krishi_bhavan WHERE krishibhavan_name = '$selectedKrishibhavan'";
        $result = mysqli_query($con, $krishibhavanQuery);
        
        // Check if query was successful
        if ($result) {
            // Fetch the krishibhavan_id
            $row = mysqli_fetch_assoc($result);
            if ($row) {
                $krishibhavan_id = $row['krishibhavan_id'];
                $encoded_krishibhavan_id = base64_encode($krishibhavan_id);
                header("Location: admin_add_officer.php?krishibhavan_id=$encoded_krishibhavan_id");
                exit; // Stop further execution
            } else {
                echo "Error: No krishibhavan_id found for selected krishibhavan_name";
            }
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Krishibhavan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" >
</head>
<body>
    <?php
    include('navbar/navbar_admin.php');
    ?>

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Select Krishibhavan</h1>
                <form method="POST">
                    <div class="form-group">
                        <label for="krishibhavan_name">Name of the Krishibhavan:</label>
                        <select id="krishibhavan_name" name="krishibhavan_name" class="form-control" required>
                            <option value="" disabled selected>Select Krishibhavan</option>
                            <?php
                            $query = "SELECT krishibhavan_name FROM krishi_bhavan";
                            $result = mysqli_query($con, $query);

                            if ($result) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . $row['krishibhavan_name'] . '">' . $row['krishibhavan_name'] . '</option>';
                                }
                            } else {
                                echo '<option value="">Error fetching krishibhavan names</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
