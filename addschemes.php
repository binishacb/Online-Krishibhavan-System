<?php
// Start or resume the session
session_start();
include('dbconnection.php');
if (!isset($_SESSION['useremail'])) {
    header('Location: index.php'); // Redirect to index.php
    exit(); // Stop further execution of the current script
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
    /* Additional CSS styles */
    .card {
        max-width: 500px;
        margin: 0 auto;
        /* Center the card horizontally */
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        background-color: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        /* Drop shadow for the form container */
    }

    .card-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    /* .btn-primary {
            width:50%;
        } */

    blockquote,
    q {
        quotes: none;
    }

    blockquote:before,
    blockquote:after,
    q:before,
    q:after {
        content: '';
        content: none;
    }
    </style>
</head>

<body>

    <?php
include('navbar/navbar_officer.php');
?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Scheme Registration</h5><br>
                        <form class="needs-validation" method="post" action="#" onsubmit="return validateForm()">
                            <!--novalidate>  -->

                            <div class="form-group">
                                <label for="scheme_name">Scheme Name:</label>
                                <input type="text" class="form-control" id="scheme_name" name="scheme_name"
                                    placeholder="Enter the scheme name" oninput="validateSchemeName(this.value)"
                                    required>
                                <div id="scheme_name-warning" class="invalid-feedback"></div>
                                <div id="scheme_name-error" class="invalid-feedback"></div>

                            </div>

                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description"
                                    placeholder="Enter scheme description" oninput="validateDescription(this.value)"
                                    required></textarea>
                                <div id="description-warning" class="invalid-feedback"></div>
                                <div id="description-error" class="invalid-feedback"></div>
                            </div>

                            <div class="form-group">
                                <label for="eligibility">Eligibility:</label>
                                <input type="text" class="form-control" id="eligibility" name="eligibility"
                                    placeholder="Enter eligibility criteria" oninput="validateEligibility(this.value)"
                                    required>
                                <div id="eligibility-warning" class="invalid-feedback"></div>
                                <div id="eligibility-error" class="invalid-feedback"></div>
                            </div>

                            <div class="form-group">
                                <label for="acres">Land area(cent):</label>
                                <input type="number" class="form-control" id="acres" name="acres"
                                    placeholder="Enter land area in cents" required>
                                <!-- You can add additional validation feedback elements if needed -->
                            </div>

                            <div class="form-group">
                                <label for="crop">Cultivated Crop:</label>
                                <select class="form-control" id="crop" name="crop" required>
                                    <option value="" disabled selected>Select a crop</option>
                                    <option value="wheat">Wheat</option>
                                    <option value="coconut">Coconut</option>
                                    <option value="rice">Rice</option>
                                    <option value="rice">Pepper</option>
                                   
                                </select>
                            </div>




                            <div class="form-group">
                                <label for="start_date">Start Date:</label>
                                <input type="datetime-local" class="form-control" id="start_date" name="start_date"
                                    oninput="validateStartDate()" required>
                                <div id="start_date-warning" class="invalid-feedback"></div>
                                <div id="start_date-error" class="invalid-feedback"></div>

                            </div>

                            <div class="form-group">
                                <label for="end_date">End Date:</label>
                                <input type="datetime-local" class="form-control" id="end_date" name="end_date"
                                    oninput="validateEndDate()" required>
                                <div id="end_date-warning" class="invalid-feedback"></div>
                                <div id="end_date-error" class="invalid-feedback"></div>
                            </div>
                            <div class="form-error" id="form-error" style="color: red; display: none;">

                            </div>
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_POST['submit'])) {
    // Retrieve the new scheme's start and end dates from the form
    $SchemeName=$_POST['scheme_name'];
    $description = $_POST['description'];
    $eligibility = $_POST['eligibility'];
    $acres = $_POST['acres'];
    $crop = $_POST['crop'];
    $newSchemeStartDate = $_POST['start_date'];
    $newSchemeEndDate = $_POST['end_date'];

    // Check if there are any existing schemes that overlap with the new scheme
    $sql = "SELECT * FROM schemes WHERE scheme_name = '$SchemeName'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        // There are overlapping schemes or a scheme with the same name, so you can't add the new scheme
        echo "<script>alert('Error: The new scheme overlaps with an existing scheme or has the same name.')</script>";
    }  else {
        // No overlapping schemes and valid end date, so you can add the new scheme to the database
        $insertSQL = "INSERT INTO schemes (scheme_name, scheme_description, eligibility,acres,crop, start_date, end_date) VALUES ('$SchemeName', '$description', '$eligibility','$acres','$crop', '$newSchemeStartDate', '$newSchemeEndDate')";
        
        if ($con->query($insertSQL) == TRUE) {
            echo "<script>alert('New scheme added successfully');</script>";
        } else {
            echo "<script>alert('Error: . $insertSQL . <br> . $con->error');</script>";
        }
    }

    // Close the database connection
    $con->close();
}
?>


    <script>
    function validateSchemeName(schemeName) {


        const schemeNameInput = document.getElementById('scheme_name');
        const schemeNameWarning = document.getElementById('scheme_name-warning');
        const schemeNameError = document.getElementById('scheme_name-error');

        const errorMessages = [];

        if (schemeName === '') {
            errorMessages.push('Warning: Scheme Name field is empty.');
        }

        if (schemeName.length < 10) {
            errorMessages.push('Error: Scheme Name should contain at least 10 characters.');
        }


        if (/(\w{3,})\1{1,}/.test(schemeName) || /(\b\w+\b)\s+\1{2,}/.test(schemeName)) {
            errorMessages.push('Error: Scheme Name should not contain repeating characters or words.');
        }

        if (/\d/.test(schemeName) || /[!@#$%^&*()_+{}\[\]:;<>,.?~\\/-]/.test(schemeName)) {
            errorMessages.push('Error: Scheme Name should not contain numbers or special characters.');
        }

        if (errorMessages.length > 0) {
            schemeNameInput.classList.add('is-invalid');
            schemeNameWarning.textContent = '';
            schemeNameError.textContent = errorMessages.join(' ');
            return false;
        } else {
            schemeNameInput.classList.remove('is-invalid');
            schemeNameInput.style.border = '2px solid green';
            schemeNameWarning.textContent = '';
            schemeNameError.textContent = '';
            return true;
        }
    }

    function validateDescription(description) {

        const descriptionInput = document.getElementById('description');
        const descriptionWarning = document.getElementById('description-warning');
        const descriptionError = document.getElementById('description-error');

        const errorMessages = [];

        if (description === '') {
            errorMessages.push('Warning: Description field is empty.');
        }

        if (description.length > 500) {
            errorMessages.push('Error: Description should not exceed 500 characters.');
        }

        if (description.length < 20) {
            errorMessages.push('Error: Description should contain atleast 20 characters.');
        }


        if (/(\w{2,})\1{3,}/.test(description) || /(\b\w+\b)\s+\1{2,}/.test(description)) {
            errorMessages.push('Error: Scheme Name should not contain repeating characters or words.');
        }
        // if (/(.)\1{3,}/.test(description)) {
        //     errorMessages.push('Error: Description should not contain repeating characters.');
        // }

        // if (/\b\w+\b\s+\1{3,}/.test(description)) {
        //     errorMessages.push('Error: Description should not contain repeating words .');
        // }

        if (errorMessages.length > 0) {
            descriptionInput.classList.add('is-invalid');
            descriptionWarning.textContent = '';
            descriptionError.textContent = errorMessages.join(' ');
            return false;
        } else {
            descriptionInput.classList.remove('is-invalid');
            descriptionInput.style.border = '2px solid green';
            descriptionWarning.textContent = '';
            descriptionError.textContent = '';
            return true;
        }
    }

    function validateEligibility(eligibility) {
        const eligibilityInput = document.getElementById('eligibility');
        const eligibilityWarning = document.getElementById('eligibility-warning');
        const eligibilityError = document.getElementById('eligibility-error');

        const errorMessages = [];

        if (eligibility === '') {
            errorMessages.push('Warning: Description field is empty.');
        }

        if (eligibility.length > 500) {
            errorMessages.push('Error: Description should not exceed 500 characters.');
        }

        if (eligibility.length < 20) {
            errorMessages.push('Error: Description should contain atleast 20 characters.');
        }


        if (/(\w{2,})\1{3,}/.test(eligibility) || /(\b\w+\b)\s+\1{4,}/.test(eligibility)) {
            errorMessages.push('Error: There should not be contain repeating characters or words.');
        }
        // if (/(.)\1{3,}/.test(description)) {
        //     errorMessages.push('Error: Description should not contain repeating characters.');
        // }

        // if (/\b\w+\b\s+\1{3,}/.test(description)) {
        //     errorMessages.push('Error: Description should not contain repeating words .');
        // }

        if (errorMessages.length > 0) {
            eligibilityInput.classList.add('is-invalid');
            eligibilityWarning.textContent = '';
            eligibilityError.textContent = errorMessages.join(' ');
            return false;
        } else {
            eligibilityInput.classList.remove('is-invalid');
            eligibilityInput.style.border = '2px solid green';
            eligibilitynWarning.textContent = '';
            eligibilityError.textContent = '';
            return true;
        }
    }

    function validateStartDate() {
        const startDateInput = document.getElementById('start_date');
        const startDateWarning = document.getElementById('start_date-warning');
        const startDateError = document.getElementById('start_date-error');

        const errorMessages = [];
        const startDateValue = new Date(startDateInput.value);
        const today = new Date();

        if (startDateInput.value.trim() === '') {
            errorMessages.push('Error: Start date cannot be empty.');
        }

        if (startDateValue < today) {
            errorMessages.push('Error: Start date should be after the current date.');
        }

        const maxStartDate = new Date(today);
        maxStartDate.setMonth(maxStartDate.getMonth() + 3);

        if (startDateValue > maxStartDate) {
            errorMessages.push('Error: Start date should not exceed 3 months from the current date.');
        }

        if (errorMessages.length > 0) {
            startDateInput.classList.add('is-invalid');
            startDateWarning.textContent = '';
            startDateError.textContent = errorMessages.join(' ');
            return false;
        } else {
            startDateInput.classList.remove('is-invalid');
            startDateInput.style.border = '2px solid green';
            startDateWarning.textContent = '';
            startDateError.textContent = '';
            return true;
        }
    }

    function validateEndDate() {
        const endDateInput = document.getElementById('end_date');
        const endDateWarning = document.getElementById('end_date-warning');
        const endDateError = document.getElementById('end_date-error');

        const errorMessages = [];
        const endDateValue = new Date(endDateInput.value);
        const today = new Date();

        if (endDateInput.value.trim() === '') {
            errorMessages.push('Error: End date cannot be empty.');
        }

        const startDateInput = document.getElementById('start_date');
        const startDateValue = new Date(startDateInput.value);

        if (endDateValue <= today) {
            errorMessages.push('Error: End date should be after the current date.');
        }

        const minEndDate = new Date(startDateValue);
        minEndDate.setDate(minEndDate.getDate() + 7); // At least one week after the start date.

        if (endDateValue < minEndDate) {
            errorMessages.push('Error: End date should be at least one week after the Start Date.');
        }

        const maxEndDate = new Date(startDateValue);
        maxEndDate.setMonth(maxEndDate.getMonth() + 6); // Not exceeding 6 months from the start date.

        if (endDateValue > maxEndDate) {
            errorMessages.push('Error: End date should not exceed 6 months from the Start Date.');
        }

        if (errorMessages.length > 0) {
            endDateInput.classList.add('is-invalid');
            endDateWarning.textContent = '';
            endDateError.textContent = errorMessages.join(' ');
            return false;
        } else {
            endDateInput.classList.remove('is-invalid');
            endDateInput.style.border = '2px solid green';
            endDateWarning.textContent = '';
            endDateError.textContent = '';
            return true;
        }
    }


    function validateForm() {
        let isValid = true;
        const isSchemeNameValid = validateSchemeName(document.getElementById('scheme_name').value);
        const isDescriptionValid = validateDescription(document.getElementById('description').value);
        const isEligibilityValid = validateEligibility(document.getElementById('eligibility').value);
        const isstartDateFieldsValid = validateStartDate();
        const isendDateFieldsValid = validateEndDate();

        // Check if any of the fields are empty
        if (
            document.getElementById('scheme_name').value.trim() === '' ||
            document.getElementById('description').value.trim() === '' ||
            document.getElementById('eligibility').value.trim() === '' ||
            document.getElementById('start_date').value.trim() === '' ||
            document.getElementById('end_date').value.trim() === ''
        ) {
            // Display a generic error message
            const formError = document.getElementById('form-error');
            formError.style.display = 'block';
            formError.textContent = 'Please fill in all required fields.';
            return false; // Prevent form submission
        }

        if (!isSchemeNameValid || !isDescriptionValid || !isEligibilityValid || !isstartDateFieldsValid || !
            isendDateFieldsValid) {
            const formError = document.getElementById('form-error');
            formError.style.display = 'block';
            return false; // Prevent form submission
        } else {
            return true;
        }
        // Allow form submission if all validations pass
    }
    </script>
    <?php
	include('footer/footer.php')
?>