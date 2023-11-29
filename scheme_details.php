<?php
session_start();
include('dbconnection.php');

if (!isset($_SESSION['useremail'])) {
    header('Location: index.php');
    exit();
}

// Check if the scheme ID is provided in the query parameters
if (isset($_GET['scheme_id'])) {
    $scheme_id = $_GET['scheme_id'];

    // Query to retrieve the scheme's details based on the scheme_id
    $sql = "SELECT * FROM schemes WHERE scheme_id = $scheme_id";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $scheme_name = $row['scheme_name'];
        $scheme_description = $row['scheme_description'];
        $eligibility = $row['eligibility'];
        $start_date = $row['start_date'];
        $end_date = $row['end_date'];
    } 
    else {
        echo "Scheme not found.";
        exit();
    }
} 
else {
    // Handle the case where no scheme ID is provided
    echo "Scheme ID not provided.";
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Additional CSS styles */
        body {
            background-color: #f0f0f0;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
        }

        table {
            border: 2px solid darkgreen;
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 2px solid darkgreen;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: darkgreen;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        h2 {
            color: darkgreen;
        }
        
        .apply-button-container {
            text-align: center;
            margin-top: 20px;
        }
        .apply-button {
            background-color: darkgreen;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

    </style>
</head>
<body>

<?php
include('navbar/navbar_officer.php');
?>
 <div class="container mt-5">
        <h2>Scheme Details: <?php echo $scheme_name; ?></h2>
        <table>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
            <tr>
                <td><strong>Scheme Name:</strong></td>
                <td><?php echo $scheme_name; ?></td>
            </tr>
            <tr>
                <td><strong>Description:</strong></td>
                <td><?php echo $scheme_description; ?></td>
            </tr>
            <tr>
                <td><strong>Eligibility:</strong></td>
                <td><?php echo $eligibility; ?></td>
            </tr>
            <tr>
                <td><strong>Start Date:</strong></td>
                <td><?php echo $start_date; ?></td>
            </tr>
            <tr>
                <td><strong>End Date:</strong></td>
                <td><?php echo $end_date; ?></td>
            </tr>
        </table>
        <div class="apply-button-container">
        <button class="apply-button" data-toggle="modal" data-target="#eligibilityModal">Apply</button>
        </div>
    </div>



<!-- Bootstrap Modal -->
<div class="modal fade" id="eligibilityModal" tabindex="-1" role="dialog" aria-labelledby="eligibilityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eligibilityModalLabel">Check Eligibility</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Display the eligibility criteria specific to the selected scheme -->
             
                <!-- Add eligibility options specific to the "Weather Based Crop Insurance" scheme -->
                <p>Eligibility Criteria:</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="landCriteria">
                    <label class="form-check-label" for="landCriteria">
                        Farmer has at least 50 cents of land
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="cropCriteria">
                    <label class="form-check-label" for="cropCriteria">
                        Crop should be Paddy
                    </label>
                </div>
                <!-- Add more options as needed -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- Add a button to perform the eligibility check -->
                <button type="button" class="btn btn-primary" onclick="performEligibilityCheck()">Check Eligibility</button>
            </div>
        </div>
    </div>
</div>

<?php include('footer/footer.php'); ?>

<!-- Add Bootstrap JS and jQuery scripts at the end of the body -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function performEligibilityCheck() {
      var landCriteria = document.getElementById("landCriteria").checked;
        var cropCriteria = document.getElementById("cropCriteria").checked;

        // Check if both conditions are true
        if (landCriteria && cropCriteria) {
            alert("Eligibility checked successfully! You are eligible to apply for the scheme.");
        } else {
            alert("You are not eligible to apply for the scheme. Please check the eligibility criteria.");
        }
       
    }
</script>

</body>
</html>


