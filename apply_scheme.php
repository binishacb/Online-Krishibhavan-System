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
$farmer = "SELECT farmer.farmer_id FROM farmer JOIN login ON farmer.log_id = login.log_id WHERE login.email = '$farmer_email'";
$result = mysqli_query($con, $farmer);
$row = $result->fetch_assoc();
$farmer_id = $row['farmer_id'];

function generateSchemeForm($schemeID)
{
    $commonFields = <<<HTML
        <label for="applicant_name">Name of the Applicant:</label>
        <input type="text" id="applicant_name" name="applicant_name" required>

        <label for="applicant_address">Address:</label>
        <textarea id="applicant_address" name="applicant_address" rows="2" required></textarea>

        <label>Gender:</label>
        <div class="radio-group">
            <label><input type="radio" name="gender" value="male" required> Male</label>
            <label><input type="radio" name="gender" value="female" required> Female</label>
        </div>

        <label for="phone_number">Phone Number:</label>
        <input type="number" id="phone_number" name="phone_number" required><br>

        <div style="display: inline-block;">
            <label for="krishibhavan_name" style="display: inline;">Name of the Krishibhavan:</label>
            <select id="krishibhavan_name" name="krishibhavan_name" required>
                <option value="">Select Krishibhavan</option>
                <option value="KB chazhur">KB chazhur</option>
                <option value="KB anthikkad">KB anthikkad</option>
                <option value="KB pala">KB pala</option>
                <option value="KB kalpetta">KB kalpetta</option>
            </select>
        </div>

    HTML;

    switch ($schemeID) {
        case 15:
            // Rice Development Scheme Form
            $extraFields = <<<HTML
            <br>
                <label for="land_area">Land area having rice cultivation(in cent):</label>
                <input type="decimal" id="land_area" name="land_area" required><br><br>
                <label for="land_tax_receipt_no">Land Tax Receipt Number:</label>
                <input type="text" id="land_tax_receipt_no" name="land_tax_receipt_no" required><br><br>
                <label for="land_tax_receipt">Land Tax Receipt (PDF):</label>
                <input type="file" id="land_tax_receipt" name="pdfFile" accept=".pdf" required><br><br>
            HTML;
            break;
        case 16:
            // Coconut Cultivation Scheme Form
            $extraFields = <<<HTML
            <br>
                <label for="land_area">Land area having coconut cultivation(in cent):</label>
                <input type="decimal" id="land_area" name="land_area" required><br>
                <label for="land_tax_receipt_no">Land Tax Receipt Number:</label>
                <input type="text" id="land_tax_receipt_no" name="land_tax_receipt_no" required><br><br>
                <label for="land_tax_receipt">Land Tax Receipt (PDF):</label>
                <input type="file" id="land_tax_receipt" name="pdfFile" accept=".pdf" required><br><br>
            HTML;
            break;
        case 17:
            // Paddy Cultivation Scheme Form
            $extraFields = <<<HTML
            <br>
                <label for="land_area">Land area having paddy cultivation(in cent):</label>
                <input type="decimal" id="land_area" name="land_area" required><br>
                <label for="land_tax_receipt_no">Land Tax Receipt Number:</label>
                <input type="text" id="land_tax_receipt_no" name="land_tax_receipt_no" required><br><br>
                <label for="land_tax_receipt">Land Tax Receipt (PDF):</label>
                <input type="file" id="land_tax_receipt" name="pdfFile" accept=".pdf" required><br><br>
            HTML;
            break;
            case 18:
                // Paddy Cultivation Scheme Form
                $extraFields = <<<HTML
                <br>
                    <label for="land_area">Land area having wheat cultivation(in cent):</label>
                    <input type="decimal" id="land_area" name="land_area" required><br>
                    <label for="land_tax_receipt_no">Land Tax Receipt Number:</label>
                    <input type="text" id="land_tax_receipt_no" name="land_tax_receipt_no" required><br><br>
                    <label for="land_tax_receipt">Land Tax Receipt (PDF):</label>
                    <input type="file" id="land_tax_receipt" name="pdfFile" accept=".pdf" required><br><br>
                HTML;
                break;
                case 21:
                    // Paddy Cultivation Scheme Form
                    $extraFields = <<<HTML
                    <br>
                        <label for="land_area">Land area having spices cultivation(in cent):</label>
                        <input type="decimal" id="land_area" name="land_area" required><br>
                        <label for="land_tax_receipt_no">Land Tax Receipt Number:</label>
                        <input type="text" id="land_tax_receipt_no" name="land_tax_receipt_no" required><br><br>
                        <label for="land_tax_receipt">Land Tax Receipt (PDF):</label>
                        <input type="file" id="land_tax_receipt" name="pdfFile" accept=".pdf" required><br><br>
                    HTML;
                    break;
        default:
            return "No form available for the specified scheme ID.";
    }

    // Combine common fields with scheme-specific fields
    $form = <<<HTML
        <div class="container">
            $commonFields
            $extraFields
            <button type="submit">Submit Application</button>
        </div>
    HTML;

    return $form;
}


// Check if the scheme_id parameter is set in the URL
if (isset($_GET['scheme_id'])) {
    // Get the scheme ID from the URL
    $schemeID = $_GET['scheme_id'];


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

<head>
    <style>
        /* Basic reset to remove default browser styles */
        body,
        h1,
        h2,
        h3,
        p,
        ul,
        li,
        form,
        button {
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
            margin-right: 15px;
            /* Adjust the spacing as needed */
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

            input,
            select,
            textarea {
                width: calc(100% - 20px);
                margin-right: 0;
            }
        }
    </style>

</head>

<body>

    <?php
    include('navbar/navbar_farmer.php');

    ?>
    <center><?php
            $scheme = "Select scheme_name from schemes where scheme_id='$schemeID'";
            $schemeResult = $con->query($scheme);
            $schemeRow = $schemeResult->fetch_assoc();
            $scheme_name = $schemeRow['scheme_name'];
            ?>
        <h2><?php echo $scheme_name ?> Application Form</h2>
    </center>
    <form action="" method="post" onsubmit="return validateForm()" enctype="multipart/form-data">
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
    } else {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $selectedKrishibhavan = $_POST['krishibhavan_name'];
            $applicantName = htmlspecialchars($_POST['applicant_name']);
            $applicantAddress = htmlspecialchars($_POST['applicant_address']);
            $gender = htmlspecialchars($_POST['gender']);
            $phoneNumber = htmlspecialchars($_POST['phone_number']);

            $land_area = htmlspecialchars($_POST['land_area']);
            $land_tax = htmlspecialchars($_POST['land_tax_receipt_no']);
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($_FILES["pdfFile"]["name"]);
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));


            $query = "SELECT krishibhavan_id FROM krishi_bhavan WHERE krishibhavan_name = '" . $selectedKrishibhavan . "'";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_assoc($result);
            $krishibhavanId = $row['krishibhavan_id'];

            if ($fileType != "pdf") {
                echo "<script>alert('Upload as pdf file');</script>";
            } else {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES["pdfFile"]["tmp_name"], $targetFile)) {
                    $fileName = $_FILES["pdfFile"]["name"];
                    $folder_path = $targetDir;

                    $sql = "INSERT INTO scheme_application (farmer_id,scheme_id,name, address, gender, phone_number, krishibhavan_id, land_area, application_status,land_tax,tax_image) VALUES ('$farmer_id','$schemeID','$applicantName', '$applicantAddress', '$gender', '$phoneNumber', '$krishibhavanId', '$land_area', '1','$land_tax','$fileName')";

                    if ($con->query($sql) == TRUE) {
                        echo "<script>alert('Application submitted successfully');</script>";
                    } else {
                        echo "Error: " . $sql . "<br>" . $con->error;
                    }
                }
            }
        }
    }

    $con->close();
    ?>

    <script>
        function validateForm() {
            var applicantName = document.getElementById("applicant_name").value.trim();
            var applicantAddress = document.getElementById("applicant_address").value.trim();
            var gender = document.querySelector('input[name="gender"]:checked');
            var phoneNumber = document.getElementById("phone_number").value.trim();
            var krishibhavanName = document.getElementById("krishibhavan_name").value.trim();
            var landArea = document.getElementById("land_area").value.trim();
            var landTaxReceiptNo = document.getElementById("land_tax_receipt_no").value.trim();

            // Check if any field is empty
            if (applicantName === "" || applicantAddress === "" || gender === null || phoneNumber === "" || krishibhavanName === "" || landArea === "" || landTaxReceiptNo === "") {
                alert("All fields are required");
                return false;
            }

            // Validate name to not contain special characters or numbers
            var nameRegex = /^[a-zA-Z\s]+$/; // Only allow letters and spaces
            if (!nameRegex.test(applicantName)) {
                alert("Name must only contain letters and spaces");
                return false;
            }

            // Validate phone number format
            var phoneRegex = /^\d{10}$/; // 10 digits without any other characters
            if (!phoneRegex.test(phoneNumber)) {
                alert("Please enter a valid 10-digit phone number");
                return false;
            }

            // Validate land tax receipt number format
            var taxReceiptRegex = /^[a-zA-Z]{3}\d{4}$/; // 3 letters followed by 4 digits
            if (!taxReceiptRegex.test(landTaxReceiptNo)) {
                alert("Land tax receipt number must start with 3 letters followed by 4 digits");
                return false;
            }

            // Additional validation rules can be added as needed

            return true; // Form is valid
        }
    </script>
</body>

</html>