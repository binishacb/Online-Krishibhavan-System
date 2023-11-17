<?php
    // Start a session (if not already started)
    session_start();
    include('dbconnection.php');
    if (!isset($_SESSION['useremail'])) {
        header('Location: index.php');
        exit();
    }
    
   // echo $_SESSION['useremail'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>editprofile_officer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .profile-container {
            width: 50%; /* Adjust the width to your preference */
            margin: 0 auto;
            padding: 20px 40px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }

        .profile-container h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .profile-details {
            margin: 10px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .profile-details label {
            font-weight: bold;
        }

        .profile-details input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .edit-button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .edit-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php
   include('navbar/navbar_officer.php');
    // Check if the farmer is logged in and their ID is stored in the session
    if (isset($_SESSION['useremail'])) {
        // Retrieve the farmer's email from the session
        $officeremail = $_SESSION['useremail'];
        // Fetch farmer details from the database
        $sql = "SELECT o.log_id, o.firstname,o.lastname, l.email, o.phone_no FROM officer AS o INNER JOIN login AS l ON o.log_id = l.log_id WHERE l.email = '$officeremail'";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $firstname = $row["firstname"];
            $lastname = $row["lastname"];
            // $email = $row["email"];
            $phone_no = $row["phone_no"];
           // $dob = $row["dob"];
        } else {
            echo "<script>alert('No officer found with email: ' . $officeremail)</script>";
        }
    } else {
        echo "<script>alert('Officer not logged in.');</script>";
        header('Location:login.php');
        exit;
    }
    
    // Check if the form is submitted for updating the profile
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $newFirstName = $_POST['new_firstname'];
        $newLastName = $_POST['new_lastname'];
        $newPhone = $_POST['new_phone'];
        //$newDob = $_POST['new_dob'];
        //$newEmail = $_POST['new_email'];
        // Update the profile in the database
        $updateSql = "UPDATE officer AS o INNER JOIN login AS l ON o.log_id = l.log_id SET o.firstname = '$newFirstName',o.lastname = '$newLastName', o.phone_no = '$newPhone' WHERE l.email = '$officeremail'";
         if ($con->query($updateSql) == TRUE) {
             echo "<script>alert('Profile updated successfully');</script>";
             //header('location:profile_officer.php'); 
              exit;
         } else {
             echo "Error updating profile: " . $con->error;
         }
    }
    
?>
    <div class="container">
        <div class="profile-container">
            <h2>Edit Profile:</h2>
           
            <form method="POST" onsubmit="return validateForm()">
                <div class="profile-details">
                    <label for="new_firstname">First Name:</label>
                    <input type="text" name="new_firstname" id="new_firstname" value="<?php echo $firstname; ?>"  oninput="validateFirstName(this.value)" required>
                    <div id="firstname-warning" class="invalid-feedback"></div>
                    <div id="firstname-error" class="invalid-feedback"></div>
                </div>
                <div class="profile-details">
                    <label for="new_lastname">Last Name:</label>
                    <input type="text" name="new_lastname" id="new_lastname"  value="<?php echo $lastname; ?>"  oninput = "validateLastName(this.value)"required>
                    <div id="lastname-warning" class="invalid-feedback"></div>
                    <div id="lastname-error" class="invalid-feedback"></div>
                </div>
                <div class="profile-details">
                    <label for="new_phone">Phone Number:</label>
                    <input type="text" name="new_phone" id="new_phone" value="<?php echo $phone_no; ?>" oninput ="validatePhone(this.value)" required>
                    <div id="phone-warning" class="invalid-feedback"></div>
                    <div id="phone-error" class="invalid-feedback"></div>
                </div>
                <!-- <div class="profile-details">
                    <label for="new_dob">Date of Birth:</label>
                    <input type="text" name="new_dob" value="<?php echo $dob; ?>">
                </div> -->
                <button type="submit" id="buttonname" class="edit-button">Save Changes</button>
            </form>
        </div>
    </div><br><br><br><br><br><br><br><br><br>
<?php
    include('footer/footer.php');
?>
</body>
</html>

<script>
    function validateFirstName(firstName) {
       
        const firstNameInput = document.getElementById('new_firstname');
        const firstNameWarning = document.getElementById('firstname-warning');
        const firstNameError = document.getElementById('firstname-error');
        firstName = firstName.trim();
        firstName = firstName.toUpperCase();
        firstNameInput.value = firstName;

        if (firstName === '') {
            firstNameWarning.textContent = 'Warning: First Name field is empty.';
            firstNameInput.classList.add('is-invalid');
            firstNameError.textContent = '';
            return false;
        } else if (firstName.length < 3) {
            firstNameInput.classList.add('is-invalid');
            firstNameWarning.textContent = '';
            firstNameError.textContent = 'Error: First Name should contain at least 3 letters.';
            return false;
        } else if (!/^[a-zA-Z]+$/.test(firstName)) {
            firstNameInput.classList.add('is-invalid');
            firstNameWarning.textContent = '';
            firstNameError.textContent = 'Error: First Name should not contain numbers or special characters.';
            return false;
        } else if (firstName.length > 30) {
            firstNameInput.classList.add('is-invalid');
            firstNameWarning.textContent = '';
            firstNameError.textContent = 'Error: Name exceeds the maximum character limit of 30.';
            return false;
        } else if (/^(.)\1+$/i.test(firstName)) {
            firstNameInput.classList.add('is-invalid');
            firstNameWarning.textContent = '';
            firstNameError.textContent = 'Error: Name should be meaningful and not consist of repeating characters.';
            return false;
        } else {
            firstNameInput.classList.remove('is-invalid');
            firstNameInput.classList.add('is-valid');
            firstNameInput.style.border = '2px solid green';
            firstNameWarning.textContent = '';
            firstNameError.textContent = '';
            return true;
        }
    }
    
    function validateLastName(lastName) {
        // Get elements for last name validation
        const lastNameInput = document.getElementById('new_lastname');
        const lastNameWarning = document.getElementById('lastname-warning');
        const lastNameError = document.getElementById('lastname-error');

        // Remove leading and trailing whitespace
        lastName = lastName.trim();

        // Convert to capital letters
        lastName = lastName.toUpperCase();

        // Update the input field with the capitalized last name
        lastNameInput.value = lastName;
        // Ensure at least two alphabets and convert to capital letters
        if (lastName === '') {
            lastNameWarning.textContent = 'Warning: First Name field is empty.';
            lastNameInput.classList.add('is-invalid');
            lastNameError.textContent = '';
            return false;
        } else if (lastName.length < 1) {
            lastNameInput.classList.add('is-invalid');
            lastNameWarning.textContent = '';
            lastNameError.textContent = 'Error: Last Name should contain at least two alphabets.';
            return false; // Return false to prevent form submission
        } else if (/(.)\1{2,}/i.test(lastName)) {
            lastNameInput.classList.add('is-invalid');
            lastNameWarning.textContent = '';
            lastNameError.textContent = 'Error: Name should be meaningful and not consist of repeating characters.';
            return false;
        } else if (lastName.length > 20) {
            lastNameInput.classList.add('is-invalid');
            lastNameWarning.textContent = '';
            lastNameError.textContent = 'Error: Name exceeds the maximum character limit of 20.';
            return false; // Return false to prevent form submission
        }
        //     else if (lastName.length === 2 && !/^[a-zA-Z ]+$/.test(lastName)) {
        //     lastNameInput.classList.add('is-invalid');
        //     lastNameWarning.textContent = '';
        //     lastNameError.textContent = 'Error: Last Name should contain exactly two alphabets or alphabets with spaces.';
        //     return false;
        // } 
        else if (lastName.length > 2 && !/^[a-zA-Z]+$/.test(lastName)) {
            lastNameInput.classList.add('is-invalid');
            lastNameWarning.textContent = '';
            lastNameError.textContent =
                'Error: Last Name should contain only alphabets.No numbers and special characters are allowed';
            return false;
        } else {
            // Clear any previous validation messages
            lastNameInput.classList.remove('is-invalid');
            lastNameInput.style.border = '2px solid green';
            lastNameWarning.textContent = '';
            lastNameError.textContent = '';
            return true; // Return true if validation is successful
        }
    }

    function hasRepeatingDigits(phone) {
        const repeatingDigitsRegex = /(.)\1{5}/; // Matches any digit repeated 6 or more times

        return repeatingDigitsRegex.test(phone);
    }

    function validatePhone(phone) {
        const phoneRegex = /^[0-9]{10}$/;
        const phoneInput = document.getElementById('new_phone');
        const phoneWarning = document.getElementById('phone-warning');
        const phoneError = document.getElementById('phone-error');

        if (phone === '') {
            phoneWarning.textContent = 'Warning: Phone number field is empty.';
            phoneInput.classList.add('is-invalid');
            phoneError.textContent = '';
            return false; // Return false to prevent form submission
        }

        if (phoneRegex.test(phone)) {
            if (hasRepeatingDigits(phone)) {
                phoneInput.classList.add('is-invalid');
                phoneWarning.textContent = '';
                phoneError.style.color = 'red';
                phoneError.textContent = 'Error: Phone number contains repeating digits.';
                return false; // Return false to prevent form submission
            } else {
                phoneInput.classList.remove('is-invalid');
                phoneInput.classList.add('is-valid');
                phoneWarning.textContent = '';
                phoneError.textContent = '';
                return true; // Return true if validation is successful
            }
        } else {
            phoneInput.classList.add('is-invalid');
            phoneWarning.textContent = '';
            phoneError.style.color = 'red';
            phoneError.textContent = 'Error: Invalid phone number. Please enter a 10-digit number.';
            return false; // Return false to prevent form submission
        }
    }
    function validateForm() {
       
        const isfirstNameValid = validateFirstName(document.getElementById('new_firstname').value);
        
        const islastNameValid = validateLastName(document.getElementById('new_lastname').value);
        const isPhoneValid = validatePhone(document.getElementById('new_phone').value);
       
        // return isFirstNameValid && isLastNameValid && isEmailValid && isPhoneValid && isKrishibhavanValid && isDesignationValid;

        if (!isFirstNameValid || !isLastNameValid  || !isPhoneValid ) {
            alert('Please correct the errors in the form before submitting.');
            return false; // Prevent form submission
        }
       
        return true;
    }
    </script>