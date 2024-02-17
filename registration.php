
<?php
session_start();
include('dbconnection.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require './vendor/autoload.php';
function sendemail_verify($email, $verify_token)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.gmail.com';
        $mail->Username = 'agrocompanion2023@gmail.com'; // Your Gmail email address
        $mail->Password = 'wwme uijt eygq xqas'; // Your Gmail password or App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('agrocompanion2023@gmail.com', 'Agrocompanion');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Email verification from Agrocompanion';
        $email_template = "
            <h2>You are registered with Agrocompanion</h2>
            
            <h5>Verify your email address to login with the below given link</h5>
            <br><br>
            <a href='http://localhost/binishaprg/verify-email.php?token=$verify_token'>Click me</a>";
        $mail->Body = $email_template;
        $mail->send();
     
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if (isset($_POST['submit'])) {
    // Get form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $hashed_password = md5($password);
    $dob = $_POST['dob'];

    // Check if the email already exists in the database
    $check_email_query = "SELECT email FROM login WHERE email='$email' LIMIT 1";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if (mysqli_num_rows($check_email_query_run) > 0) {
        echo "<script>alert('Email id already exists'); window.location = 'registration.php';</script>";
    } else {
        // Insert data into the 'farmer' table

        // First, insert a record into the 'login' table to get the 'log_id'
        $verify_token = md5(rand());
        $insert_login_query = "INSERT INTO login(email, password, verify_token, role_id) VALUES ('$email', '$hashed_password', '$verify_token', 2)";
        $insert_login_query_run = mysqli_query($con, $insert_login_query);

        if ($insert_login_query_run) {
            // Get the 'log_id' of the newly inserted 'login' record
            $log_id = mysqli_insert_id($con);

            // Now, insert the farmer data with the obtained 'log_id'
            $query_farmer = "INSERT INTO `farmer`(`firstname`,`lastname`, `phone_no`, `dob`, `log_id`) VALUES ('$firstname','$lastname', '$phone', '$dob', '$log_id')";
            $query_farmer_run = mysqli_query($con, $query_farmer);
            
            if ($query_farmer_run) {
                if (sendemail_verify($email, $verify_token)) {
                    echo "<script>alert('Registration successful'); window.location = 'registration_success.php';</script>";
                }
            } else {
                echo "<script>alert('Registration failed'); window.location = 'registration.php';</script>";
            }
        } else {
            echo "<script>alert('Registration failed'); window.location = 'registration.php';</script>";
        }
    }
}

/*else {
    echo "Form submission not detected";
}*/
?>
<!DOCTYPE html>


<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    /* Additional CSS styles */
    .card {
        max-width: 400px;
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

    .btn-primary {
        width: 100%;
    }



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

    body {
        background-color: #4CAF50;
        /* Green background color */
        background: linear-gradient(45deg, #4CAF50, #FFC107);
        /* Gradient from green to orange */
    }

    /*
.registration-container {
    background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background for the form container 
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Drop shadow for the form container 
}*/
    </style>
</head>

<body>
    <title>Farmer Registration</title>
    </head>

    <body>
        <?php
        include('navbar/public_navbar.php')
        ?>


        <br>
        <div id="google_element">
            <script src="http://translate.google.com/translate_a/element.js?cb=loadGoogleTranslate"></script>
            <script>
            function loadGoogleTranslate() {
                new google.translate.TranslateElement("google_element");
            }
            </script>
        </div>


        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Registration</h5>
                            <form class="needs-validation" method="post" action="#" onsubmit="return validateForm()"
                                novalidate>

                                <div class="form-group">
                                    <label for="firstname">First Name:</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter your first name" oninput="validateFirstName(this.value)" required>
                                    <div id="firstname-warning" class="invalid-feedback"></div>
                                    <div id="firstname-error" class="invalid-feedback"></div>
                                </div>

                                <div class="form-group">
                                    <label for="lastname">Last Name:</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter your last name" oninput="validateLastName(this.value)" required>
                                    <div id="lastname-warning" class="invalid-feedback"></div>
                                    <div id="lastname-error" class="invalid-feedback"></div>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="text" class="form-control" id="email" name="email"
                                        placeholder="Enter your email id" oninput="validateEmail(this.value)" required>

                                    <div id="email-warning" class="invalid-feedback"></div>
                                    <div id="email-error"></div>
                                </div>


                                <div class="form-group">
                                    <label for="phone">Phone Number:</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Enter phone number" oninput="validatePhone(this.value)" required>
                                    <div id="phone-warning" class="invalid-feedback"></div>
                                    <div id="phone-error"></div>
                                </div>

                                <div class="form-group">
                                    <label for="dob">Date of Birth:</label>
                                    <input type="date" class="form-control" id="dob" name="dob"
                                        placeholder="Enter the date of birth" oninput="validateDOB(this.value)"
                                        required>
                                    <div id="dob-warning" class="invalid-feedback"></div>
                                    <div id="dob-error"></div>
                                </div>




                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Enter the password" oninput="validatePassword(this.value)"
                                        required>
                                    <div id="password-warning" class="invalid-feedback"></div>
                                    <div id="password-error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="confirmPassword">Confirm Password:</label>
                                    <input type="password" class="form-control" id="confirmPassword"
                                        placeholder="Confirm password" name="confirmPassword"
                                        oninput="validateConfirmPassword(this.value)" required>
                                    <div id="confirmPassword-warning" class="invalid-feedback"></div>
                                    <div id="confirmPassword-error"></div>
                                </div>


                                <div class="form-group">
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                            <p class="text-center">Already a user? <a href="login.php">Login Now</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Include Bootstrap JS (optional) -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <!-- Include JavaScript for form validation (assuming you have this script) -->
        <script src="your-validation-script.js"></script>



    <script>
    function validateFirstName(firstName) {
    // Get elements for first name validation
    const firstNameInput = document.getElementById('firstname');
    const firstNameWarning = document.getElementById('firstname-warning');
    const firstNameError = document.getElementById('firstname-error');
    firstName = firstName.trim();

       
        // Convert to capital letters
        firstName = firstName.toUpperCase();

        // Update the input field with the capitalized first name
        firstNameInput.value = firstName;
    // Validate first name
    if (firstName === '') {
        firstNameWarning.textContent = 'Warning: First Name field is empty.';
        firstNameInput.classList.add('is-invalid');
        firstNameError.textContent = '';
        return false; // Return false to prevent form submission
    } else if (firstName.length < 3) {
        firstNameInput.classList.add('is-invalid');
        firstNameWarning.textContent = '';
        firstNameError.textContent = 'Error: First Name should contain at least 3 letters.';
        return false; // Return false to prevent form submission
    } else if (!/^[a-zA-Z]+$/.test(firstName)) {
        firstNameInput.classList.add('is-invalid');
        firstNameWarning.textContent = '';
        firstNameError.textContent = 'Error: First Name should not contain numbers or special characters.';
        return false; // Return false to prevent form submission
    } //else if (/(\b\w+\b)\s+\1/.test(firstName)) {
    //     firstNameInput.classList.add('is-invalid');
    //     firstNameWarning.textContent = '';
    //     firstNameError.textContent = 'Error: First Name should not contain continuous repeating words.';
    //     return false; // Return false to prevent form submission
    // }
    else if (firstName.length > 30) {
        firstNameInput.classList.add('is-invalid');
        firstNameWarning.textContent = '';
        firstNameError.textContent = 'Error: Name exceeds the maximum character limit of 30.';
        return false; // Return false to prevent form submission
    }
    else if (/^(.)\1+$/i.test(firstName)) {
        firstNameInput.classList.add('is-invalid');
        firstNameWarning.textContent = '';
        firstNameError.textContent = 'Error: Name should be meaningful and not consist of repeating characters.';
        return false;
    }
    else {
        firstNameInput.classList.remove('is-invalid');
        firstNameInput.style.border = '2px solid green';
        firstNameWarning.textContent = '';
        firstNameError.textContent = '';
        return true; // Return true if validation is successful
    }
}
function validateLastName(lastName) {
    // Get elements for last name validation
    const lastNameInput = document.getElementById('lastname');
    const lastNameWarning = document.getElementById('lastname-warning');
    const lastNameError = document.getElementById('lastname-error');

    // Remove leading and trailing whitespace
    lastName = lastName.trim();

    // Convert to capital letters
    lastName = lastName.toUpperCase();

    // Update the input field with the capitalized last name
    lastNameInput.value = lastName;
    // Ensure at least two alphabets and convert to capital letters
    if (lastName.length < 2) {
        lastNameInput.classList.add('is-invalid');
        lastNameWarning.textContent = '';
        lastNameError.textContent = 'Error: Last Name should contain at least two alphabets.';
        return false; // Return false to prevent form submission
    }
    else if (/^(.)\1+$/i.test(lastName)) {
        lastNameInput.classList.add('is-invalid');
        lastNameWarning.textContent = '';
        lastNameError.textContent = 'Error: Name should be meaningful and not consist of repeating characters.';
        return false;
    }
    else if (lastName.length > 30) {
        lastNameInput.classList.add('is-invalid');
        lastNameWarning.textContent = '';
        lastNameError.textContent = 'Error: Name exceeds the maximum character limit of 30.';
        return false; // Return false to prevent form submission
    }

else{
    // Clear any previous validation messages
    lastNameInput.classList.remove('is-invalid');
    lastNameInput.style.border = '2px solid green';
    lastNameWarning.textContent = '';
    lastNameError.textContent = '';
    return true; // Return true if validation is successful
}
}
        function validateEmail(email) {
            //const emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
            const emailRegex = /^[A-Za-z][A-Za-z0-9._%+-]*@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;

            //const emailRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;

            const emailInput = document.getElementById('email');
            const emailWarning = document.getElementById('email-warning');
            const emailError = document.getElementById('email-error');
           
            if (email === '') {
                // emailInput.insertAdjacentHTML('afterend', '<div class="alert alert-danger">Please fill in the Email field before continuing.</div>');
                emailWarning.textContent = 'Warning: Email field is empty.';
                emailInput.style.border = '2px solid red';
                emailWarning.style.color = 'red';
                emailError.textContent = '';
                return false; // Return false to prevent form submission
            }

            if (emailRegex.test(email)) {
                emailInput.style.border = '2px solid green';
                emailWarning.textContent = '';
                emailError.textContent = '';
                return true; // Return true if validation is successful
            } else {
                emailInput.style.border = '2px solid red';
                emailWarning.textContent = '';
                emailError.style.color = 'red';
                emailError.textContent = 'Error: Invalid email address';
                return false; // Return false to prevent form submission
            }
        }

        function validatePhone(phone) {
    const phoneRegex = /^[0-9]{10}$/;
    const phoneInput = document.getElementById('phone');
    const phoneWarning = document.getElementById('phone-warning');
    const phoneError = document.getElementById('phone-error');

    if (phone === '') {
        phoneInput.style.border = '2px solid red';
        phoneWarning.style.color = 'red';
        phoneError.textContent = '';
        return false; // Return false to prevent form submission
    }

    if (phoneRegex.test(phone)) {
        if (/(\d)\1{5}/.test(phone)) {
            phoneInput.style.border = '2px solid red';
            phoneWarning.textContent = '';
            phoneError.style.color = 'red';
            phoneError.textContent = 'Error: Phone number should not contain repeating digits.';
            return false; // Return false to prevent form submission
        } else {
            phoneInput.style.border = '2px solid green';
            phoneWarning.textContent = '';
            phoneError.textContent = '';
            return true; // Return true if validation is successful
        }
    } else {
        phoneInput.style.border = '2px solid red';
        phoneWarning.textContent = '';
        phoneError.style.color = 'red';
        phoneError.textContent = 'Error: Invalid phone number. Please enter a 10-digit number.';
        return false; // Return false to prevent form submission
    }
}


        function validatePassword(password) {
            const passwordInput = document.getElementById('password');
            const passwordWarning = document.getElementById('password-warning');
            const passwordError = document.getElementById('password-error');

            if (password === '') {
                // passwordInput.insertAdjacentHTML('afterend', '<div class="alert alert-danger">Please fill in the Password field before continuing.</div>');
                passwordWarning.textContent = 'Warning: Password field is empty.';
                passwordInput.style.border = '2px solid red';
                passwordWarning.style.color = 'red';
                passwordError.textContent = '';
                return false; // Return false to prevent form submission
            } else {
                const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{6,100}$/;

                if (passwordRegex.test(password)) {
                    passwordInput.style.border = '2px solid green';
                    passwordWarning.textContent = '';
                    passwordError.textContent = '';
                    return true; // Return true if validation is successful
                } else {
                    passwordInput.style.border = '2px solid red';
                    passwordWarning.textContent = '';
                    passwordError.style.color = 'red';
                    passwordError.textContent =
                        'Error: Password must be at least 6 characters long and include at least one number, one lowercase letter, one uppercase letter, and one special character.';
                    return false; // Return false if the password doesn't meet the criteria
                }
            }
        }


        function validateConfirmPassword(confirmPassword) {
            const password = document.getElementById('password').value;
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const confirmPasswordWarning = document.getElementById('confirmPassword-warning');
            const confirmPasswordError = document.getElementById('confirmPassword-error');

            if (confirmPassword === '') {
                //  confirmPasswordInput.insertAdjacentHTML('afterend', '<div class="alert alert-danger">Please fill in the Confirm Password field before continuing.</div>');
                confirmPasswordWarning.textContent = 'Warning: Confirm Password field is empty.';
                confirmPasswordInput.style.border = '2px solid red';
                confirmPasswordWarning.style.color = 'red';
                confirmPasswordError.textContent = '';
                return false; // Return false to prevent form submission
            } else if (confirmPassword !== password) {
                confirmPasswordInput.style.border = '2px solid red';
                confirmPasswordWarning.textContent = '';
                confirmPasswordWarning.style.color = 'red';
                confirmPasswordError.textContent = 'Error: Passwords do not match.';
                return false; // Return false to prevent form submission
            } else {
                confirmPasswordInput.style.border = '2px solid green';
                confirmPasswordWarning.textContent = '';
                confirmPasswordError.textContent = '';
                return true; // Return true if validation is successful
            }
        }



        function validateDOB(dob) {
            const dobInput = document.getElementById('dob');
            const dobWarning = document.getElementById('dob-warning');
            const dobError = document.getElementById('dob-error');

            if (dob === '') {
               // dobInput.insertAdjacentHTML('afterend','<div class="alert alert-danger">Please fill in the DOB field before continuing.</div>');
                dobWarning.textContent = 'Warning: Date of Birth field is empty.';
                dobInput.style.border = '2px solid red';
                dobWarning.style.color = 'red';
                dobError.textContent = '';
                return false; // Return false to prevent form submission
            } else {
                const currentDate = new Date();
                const inputDate = new Date(dob);
                if (isNaN(inputDate.getTime())) {
                    dobInput.style.border = '2px solid red';
                    dobWarning.textContent = '';
                    dobError.style.color = 'red';
                    dobError.textContent = 'Error: Invalid Date of Birth. Please enter a valid date.';
                    return false; // Return false if the date is invalid
                }

                // Calculate the difference in years
                const yearsDifference = currentDate.getFullYear() - inputDate.getFullYear();

                // Check if the DOB is at least 10 years in the past and not in the future
                if (yearsDifference < 10 || inputDate > currentDate) {
                    dobInput.style.border = '2px solid red';
                    dobWarning.textContent = '';
                    dobError.style.color = 'red';
                    dobError.textContent =
                        'Error: Invalid Date of Birth. The date should be at least 10 years in the past and not in the future.';
                    return false; // Return false if the conditions are violated
                } else {
                    dobInput.style.border = '2px solid green';
                    dobWarning.textContent = '';
                    dobError.textContent = '';
                    return true; // Return true if validation is successful
                }
            }
        }
        /*

                function clearAlert(inputElement) {
            // Remove any alert messages associated with the input element
            const alertDiv = inputElement.nextElementSibling;
            if (alertDiv && alertDiv.classList.contains('alert')) {
                alertDiv.remove();
            }
        }
        */

        function validateForm() {
            // Check both email and password validation results
            const isNameValid = validateName(document.getElementById('name').value);
            const isEmailValid = validateEmail(document.getElementById('email').value);
            const isPasswordValid = validatePassword(document.getElementById('password').value);
            const isDOBValid = validateDOB(document.getElementById('dob').value);
            const isConfirmPasswordValid = validateConfirmPassword(document.getElementById('confirmPassword').value);


            // Only allow form submission if all validations are true
            return isNameValid && isEmailValid && isPasswordValid && isPhoneValid && isDOBValid &&
                isConfirmPasswordValid;

        }
        </script>
        <br>
        <br>
        <br>
        <?php
        include('footer/footer.php')
        ?>
    </body>

</html>