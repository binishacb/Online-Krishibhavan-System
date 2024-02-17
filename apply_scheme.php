<?php
session_start();
include('dbconnection.php');

if (isset($_SESSION['useremail'])) {
    $farmer_email = $_SESSION['useremail'];
} else {
    // Handle the case where the farmer is not logged in
    header("Location: login.php"); // Redirect to login page
    exit();
}
$farmer="SELECT farmer.farmer_id FROM farmer JOIN login ON farmer.log_id = login.log_id WHERE login.email = '$farmer_email'";
$result=mysqli_query($con,$farmer);
$row=$result->fetch_assoc();
$farmer_id=$row['farmer_id'];

function generateSchemeForm($schemeID) {
    switch ($schemeID) {
        case 15:
            // Rice Development Scheme Form
            return <<<HTML
            <div class="container">
            <label for="applicant_name">Name of the Applicant:</label>
            <input type="text" id="applicant_name" name="applicant_name" required>

            <label for="applicant_address">Address:</label>
            <textarea id="applicant_address" name="applicant_address" rows="4" required></textarea>

            <label>Gender:</label>
            <div class="radio-group">
                <label><input type="radio" name="gender" value="male" required> Male</label>
                <label><input type="radio" name="gender" value="female" required> Female</label>
            </div>
                    

            <label for="phone_number">Phone Number:</label>
            <input type="number" id="phone_number" name="phone_number" required><br>

            <label for="krishibhavan_name">Name of the Krishibhavan:</label>
            <input type="text" id="krishibhavan_name" name="krishibhavan_name" required>

            <!-- <label for="land_tax">Land Tax (PDF):</label>
            <input type="file" id="land_tax" name="land_tax" accept=".pdf" required><br> -->


            <label for="land_area">Land area(acres):</label>
            <input type="decimal" id="land_area" name="land_area" required><br><br>
           <center> <button type="submit">Submit Application</button></center></div>
        HTML;
        case 16:
            return <<<HTML
            <div class="container">
            <label for="applicant_name">Name of the Applicant:</label>
            <input type="text" id="applicant_name" name="applicant_name" required>

            <label for="applicant_address">Address:</label>
            <textarea id="applicant_address" name="applicant_address" rows="4" required></textarea>

            <label>Gender:</label>
            <div class="radio-group">
                <label><input type="radio" name="gender" value="male" required> Male</label>
                <label><input type="radio" name="gender" value="female" required> Female</label>
            </div>
                    

            <label for="phone_number">Phone Number:</label>
            <input type="number" id="phone_number" name="phone_number" required><br>

            <label for="krishibhavan_name">Name of the Krishibhavan:</label>
            <input type="text" id="krishibhavan_name" name="krishibhavan_name" required>

            <!-- <label for="land_tax">Land Tax (PDF):</label>
            <input type="file" id="land_tax" name="land_tax" accept=".pdf" required><br> -->


            <label for="land_area">Land Area having coconut cultivation(in acres):</label>
            <input type="decimal" id="land_area" name="land_area" required><br>
            <button type="submit">Submit Application</button></center></div>
        HTML;

        case 17:
            // Rice Development Scheme Form
            return <<<HTML
            <div class="container">
            <label for="applicant_name">Name of the Applicant:</label>
            <input type="text" id="applicant_name" name="applicant_name" required>

            <label for="applicant_address">Address:</label>
            <textarea id="applicant_address" name="applicant_address" rows="4" required></textarea>

            <label>Gender:</label>
            <div class="radio-group">
                <label><input type="radio" name="gender" value="male" required> Male</label>
                <label><input type="radio" name="gender" value="female" required> Female</label>
            </div>
                    

            <label for="phone_number">Phone Number:</label>
            <input type="number" id="phone_number" name="phone_number" required><br>

            <label for="krishibhavan_name">Name of the Krishibhavan:</label>
            <input type="text" id="krishibhavan_name" name="krishibhavan_name" required>

            <!-- <label for="land_tax">Land Tax (PDF):</label>
            <input type="file" id="land_tax" name="land_tax" accept=".pdf" required><br> -->


            <label for="land_area">Land area having paddy cultivation(in acres):</label>
            <input type="decimal" id="land_area" name="land_area" required>
            <button type="submit">Submit Application</button></center></div>
        HTML;


        // Add more cases for additional scheme IDs as necessary

        default:
            return "No form available for the specified scheme ID.";
    }
}

// Check if the scheme_id parameter is set in the URL
if (isset($_GET['scheme_id'])) {
    // Get the scheme ID from the URL
    $schemeID = $_GET['scheme_id'];

    // Output or log the scheme ID for verification
    // echo "Received Scheme ID: $schemeID";

    // Generate the corresponding scheme form content
    $formContent = generateSchemeForm($schemeID);
} else {
    // Redirect to a default page if scheme_id is not provided
    header('Location: view_schemes.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head><style>
/* Basic reset to remove default browser styles */
body, h1, h2, h3, p, ul, li, form, button {
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

.container {
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

h2 {
    color: #333;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}
/* Radio button styling */
.radio-group label {
    display: inline-block;
    margin-right: 15px; /* Adjust the spacing as needed */
}

.radio-group input[type="radio"] {
    vertical-align: middle;
}


input[type="text"],
input[type="tel"],
input[type="email"],
textarea,
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 12px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

input[type="file"] {
    margin-top: 5px;
}

button {
    background-color: #007BFF;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}


/* Radio button styling */
label input[type="radio"] {
    margin-right: 5px;
}

/* Form layout adjustments for smaller screens */
@media screen and (max-width: 600px) {
    input, select, textarea {
        width: calc(100% - 20px);
        margin-right: 0;
    }
}
</style>
<script>
        function validateForm() {
            var applicantName = document.getElementById("applicant_name").value;
            var applicantAddress = document.getElementById("applicant_address").value;
            var gender = document.querySelector('input[name="gender"]:checked');
            var phoneNumber = document.getElementById("phone_number").value;
            var krishibhavanName = document.getElementById("krishibhavan_name").value;
            var landArea = document.getElementById("land_area").value;

            if (applicantName === "" || applicantAddress === "" || gender === null || phoneNumber === "" || krishibhavanName === "" || landArea === "") {
                alert("All fields are required");
                return false;
            }

            // Validate name to not contain special characters or numbers
            var nameRegex = /^[a-zA-Z ]+$/; // Only allow letters and spaces
            if (!nameRegex.test(applicantName)) {
                alert("Name must not contain special characters or numbers");
                return false;
            }

            // You can add more specific validation rules here based on your requirements

            return true;
        }
    </script>
</head>
<body>
  
    <?php
   include('navbar/navbar_farmer.php');
    
    ?>    
    <center><?php
        $scheme="Select scheme_name from schemes where scheme_id='$schemeID'";
        $schemeResult = $con->query($scheme);
        $schemeRow = $schemeResult->fetch_assoc();
        $scheme_name=$schemeRow['scheme_name'];
        ?>
    <h2><?php echo $scheme_name?> Application Form</h2></center>
    <form action="" method="post" onsubmit="return validateForm();">
        <?php echo $formContent; ?>
<center>
        <!-- <button type="submit">Submit Application</button></center></div> -->
    </form>

    <?php 
      $schemeID = $_GET['scheme_id'];
      $existingApplicationQuery = "SELECT * FROM scheme_application WHERE farmer_id = '$farmer_id' AND scheme_id = '$schemeID'";
      $existingApplicationResult = $con->query($existingApplicationQuery);
      //echo  $existingApplicationResult;
      if ($existingApplicationResult->num_rows > 0) {
        
          echo "<script>alert('You have already applied for this scheme.');window.location = 'view_schemes.php';</script>";
          
      }
      else{
  
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if the form data is submitted using the POST method
    
        // Validate and sanitize the data (customize this based on your requirements)
        $applicantName = htmlspecialchars($_POST['applicant_name']);
        $applicantAddress = htmlspecialchars($_POST['applicant_address']);
        $gender = htmlspecialchars($_POST['gender']);
        $phoneNumber = htmlspecialchars($_POST['phone_number']);
        $krishibhavanName = htmlspecialchars($_POST['krishibhavan_name']);
        $land_area = htmlspecialchars($_POST['land_area']);
      
        $sql = "INSERT INTO scheme_application (farmer_id,scheme_id,name, address, gender, phone_number, krishibhavan, land_area, application_status) VALUES ('$farmer_id','$schemeID','$applicantName', '$applicantAddress', '$gender', '$phoneNumber', '$krishibhavanName', '$land_area', '1')";

        if ($con->query($sql) == TRUE) {
            echo "<script>alert('Application submitted successfully');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
    }
        }
    
        
    
        $con->close();




    
    ?>
</body>
</html>
