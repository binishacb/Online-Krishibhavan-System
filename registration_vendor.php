<?php
include('dbconnection.php');
require 'vendor/autoload.php';

use Ramsey\Uuid\Uuid;
?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendor registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>


<?php
if (isset($_POST['submit']))
{
  $firstname = $_POST['firstName'];
  $lastname = $_POST['lastName'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $shopName = $_POST['shopName'];
  $licence_no = $_POST['licence_no'];
  $pdfFile = $_FILES['pdfFile']['name']; // Assuming file input name is "pdfFile"
  $password = $_POST['password'];
  $hashed_password = md5($password);
  $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
  // $vendorId = "VEN-" . Uuid::uuid4()->toString();
  $vendorId = "VEN-" . substr(Uuid::uuid4()->toString(), 0, 6);
  $targetDir = "uploads/";
  $targetFile = $targetDir . basename($_FILES["pdfFile"]["name"]);
  $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
  
  // Check if the file is a PDF and within the size limit
  if ($fileType != "pdf" || $_FILES["pdfFile"]["size"] > 2000000) {
      echo "<script>alert('Only PDF files with a size up to 2mb are allowed.');</script>";
  } else {
      // Move the uploaded file to the target directory
      if (move_uploaded_file($_FILES["pdfFile"]["tmp_name"], $targetFile)) {
          $fileName = $_FILES["pdfFile"]["name"];
          $folder_path = $targetDir;
          $time_stamp = date('Y-m-d H:i:s');
  
       
        // Check if the email already exists in the database
        $check_email_query = "SELECT email FROM login WHERE email='$email' LIMIT 1";
        $check_email_query_run = mysqli_query($con, $check_email_query);
    
        if (mysqli_num_rows($check_email_query_run) > 0) {
            echo "<script>alert('Email id already exists'); window.location = 'registration_vendor.php';</script>";
        } else {
            
            $insert_login_query = "INSERT INTO login(email, password, role_id) VALUES ('$email', '$hashed_password', 4)";
            $insert_login_query_run = mysqli_query($con, $insert_login_query);
    
            if ($insert_login_query_run) {
                // Get the 'log_id' of the newly inserted 'login' record
                $log_id = mysqli_insert_id($con);
    
                // Now, insert the farmer data with the obtained 'log_id'
                $query_vendor = "INSERT INTO `vendor`(`v_id`,`firstName`,`lastName`, `phone_no`, `shopName`, `log_id`, `licence_no`,`licence_image`) VALUES ('$vendorId','$firstname','$lastname', '$phone', '$shopName', '$log_id','$licence_no','$fileName')";
                $query_vendor_run = mysqli_query($con, $query_vendor);
                
                if ($query_vendor_run) {
                   
                        echo "<script>alert('Registration successful'); window.location = 'registration_success.php';</script>";
                    }
              else {
                    echo "<script>alert('Registration failed'); window.location = 'registration_vendor.php';</script>";
                }
            } else {
                echo "<script>alert('Registration failed'); window.location = 'registration_vendor.php';</script>";
            }
        }
    }
    
      }
    }
  
?>



<body>
  <?php
  include('navbar/public_navbar.php');
  ?>
<div class="container mt-4">
    <div class="card">
        <div class="card-header text-center" style="font-weight: bold; color: black; font-size: 24px;">
            VENDOR REGISTRATION
        </div>
        <div class="card-body">
            <form class="row g-3 needs-validation" method="POST" action="" onsubmit="return validateForm()" enctype="multipart/form-data" novalidate>
                <div class="col-md-6">
                    <label for="validationCustom01" class="form-label">First name</label>
                    <input type="text" class="form-control" name="firstName" id="validationCustom01" oninput="validateFirstName(this.value)" required>
                    <div id="firstname-warning" class="invalid-feedback"></div>
                    <div id="firstname-error" class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom02" class="form-label">Last name</label>
                    <input type="text" class="form-control" name="lastName" id="validationCustom02" oninput="validateLastName(this.value)" required>
                    <div id="lastname-warning" class="invalid-feedback"></div>
                    <div id="lastname-error" class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom03" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" oninput="validateEmail(this.value)" required>
                    <div id="email-warning" class="invalid-feedback"></div>
                    <div id="email-error" class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom04" class="form-label">Mobile</label>
                    <input type="text" class="form-control" name="phone" id="validationCustom04" oninput="validatePhone(this.value)" required>
                    <div id="phone-warning" class="invalid-feedback"></div>
                    <div id="phone-error" class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom05" class="form-label">Shop name</label>
                    <input type="text" class="form-control" name="shopName" id="validationCustom05" oninput="validateShopName(this.value)" required>
                    <div id="shopname-warning" class="invalid-feedback"></div>
                    <div id="shopname-error" class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom06" class="form-label">License number</label>
                    <input type="text" class="form-control" name="licence_no" id="validationCustom06" oninput="validateLicense(this.value)" required>
                    <div id="license-warning" class="invalid-feedback"></div>
                    <div id="license-error" class="invalid-feedback"></div>
                </div>
                <div class="input-group mb-3">
                    <input type="file" class="form-control col-6" id="pdfFile" oninput="validatePDF(this.value)" name="pdfFile" required>
                    <label class="input-group-text" for="inputGroupFile02">Upload</label>
                    <div id="file-warning" class="invalid-feedback"></div>
                    <div id="file-error" class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom07" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="validationCustom07" oninput="validatePassword(this.value)" required>
                    <div id="password-warning" class="invalid-feedback"></div>
                    <div id="password-error" class="invalid-feedback"></div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit" name="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function validateFirstName(firstName) {
    const firstNameInput = document.getElementById('validationCustom01');
    const firstNameWarning = document.getElementById('firstname-warning');
    const firstNameError = document.getElementById('firstname-error');

    if (firstName.trim() === '') {
        firstNameWarning.textContent = 'Warning: First name field is empty.';
        firstNameInput.classList.add('is-invalid');
        firstNameError.textContent = '';
        return false;
    } else if (!/^[a-zA-Z]{3,25}$/.test(firstName)) {
        firstNameWarning.textContent = 'Warning: First name must be 3 to 25 characters, no numbers or special characters allowed.';
        firstNameInput.classList.add('is-invalid');
        firstNameError.textContent = '';
        return false;
    } else {
        firstNameInput.classList.remove('is-invalid');
        firstNameWarning.textContent = '';
        firstNameError.textContent = '';
        return true;
    }
}

function validateLastName(lastName) {
    const lastNameInput = document.getElementById('validationCustom02');
    const lastNameWarning = document.getElementById('lastname-warning');
    const lastNameError = document.getElementById('lastname-error');

    if (lastName.trim() === '') {
        lastNameWarning.textContent = 'Warning: Last name field is empty.';
        lastNameInput.classList.add('is-invalid');
        lastNameError.textContent = '';
        return false;
    } else if (!/^[a-zA-Z]{1,15}$/.test(lastName)) {
        lastNameWarning.textContent = 'Warning: Last name maximum 25 characters, no numbers or special characters allowed.';
        lastNameInput.classList.add('is-invalid');
        lastNameError.textContent = '';
        return false;
    } else {
        lastNameInput.classList.remove('is-invalid');
        lastNameWarning.textContent = '';
        lastNameError.textContent = '';
        return true;
    }
}


    function validateEmail(email) {
        const emailRegex = /^[A-Za-z][A-Za-z0-9._%+-]*@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;
        const emailInput = document.getElementById('email');
        const emailWarning = document.getElementById('email-warning');
        const emailError = document.getElementById('email-error');

        if (email.trim() === '') {
            emailWarning.textContent = 'Warning: Email field is empty.';
            emailInput.classList.add('is-invalid');
            emailError.textContent = '';
            return false;
        }

        if (emailRegex.test(email)) {
            emailInput.classList.remove('is-invalid');
            emailWarning.textContent = '';
            emailError.textContent = '';
            return true;
        } else {
            emailInput.classList.add('is-invalid');
            emailWarning.textContent = '';
            emailError.textContent = 'Error: Invalid email address';
            return false;
        }
    }

    function validatePhone(phone) {
    const phoneInput = document.getElementById('validationCustom04');
    const phoneWarning = document.getElementById('phone-warning');
    const phoneError = document.getElementById('phone-error');

    if (phone.trim() === '') {
        phoneWarning.textContent = 'Warning: Mobile field is empty.';
        phoneInput.classList.add('is-invalid');
        phoneError.textContent = '';
        return false;
    } else if (!/^\d{10}$/.test(phone)) {
        phoneWarning.textContent = 'Warning: Phone number must be 10 digits.';
        phoneInput.classList.add('is-invalid');
        phoneError.textContent = '';
        return false;
    } 
    else if (/(\d)\1{5}/.test(phone)){
        phoneWarning.textContent = 'Warning: Phone number should not contain repeating digits.';
        phoneInput.classList.add('is-invalid');
        phoneError.textContent = '';
        return false;
    }
    else {
        phoneInput.classList.remove('is-invalid');
        phoneWarning.textContent = '';
        phoneError.textContent = '';
        return true;
    }
}


function validateShopName(shopName) {
    const shopNameInput = document.getElementById('validationCustom05');
    const shopNameWarning = document.getElementById('shopname-warning');
    const shopNameError = document.getElementById('shopname-error');

    if (shopName.trim() === '') {
        shopNameWarning.textContent = 'Warning: Shop name field is empty.';
        shopNameInput.classList.add('is-invalid');
        shopNameError.textContent = '';
        return false;
    } else if (shopName.length < 3 || shopName.length > 25) {
        shopNameWarning.textContent = 'Warning: Shop name must be between 3 and 25 characters.';
        shopNameInput.classList.add('is-invalid');
        shopNameError.textContent = '';
        return false;
    } else {
        shopNameInput.classList.remove('is-invalid');
        shopNameWarning.textContent = '';
        shopNameError.textContent = '';
        return true;
    }
}



function validateLicense(license) {
    const licenseInput = document.getElementById('validationCustom06');
    const licenseWarning = document.getElementById('license-warning');
    const licenseError = document.getElementById('license-error');

    if (license.trim() === '') {
        licenseWarning.textContent = 'Warning: License number field is empty.';
        licenseInput.classList.add('is-invalid');
        licenseError.textContent = '';
        return false;
    } else if (license.trim().length !== 6 || !/^\d{6}$/.test(license.trim())) {
        licenseWarning.textContent = 'Warning: License number should be exactly 6 digits.';
        licenseInput.classList.add('is-invalid');
        licenseError.textContent = '';
        return false;
    } else {
        licenseInput.classList.remove('is-invalid');
        licenseWarning.textContent = '';
        licenseError.textContent = '';
        return true;
    }
}

function validatePassword(password) {
    const passwordInput = document.getElementById('validationCustom07');
    const passwordWarning = document.getElementById('password-warning');
    const passwordError = document.getElementById('password-error');

    // Minimum length validation
    if (password.trim().length < 8) {
        passwordWarning.textContent = 'Warning: Password must be at least 8 characters long.';
        passwordInput.classList.add('is-invalid');
        passwordError.textContent = '';
        return false;
    }

    // Additional criteria validation
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasDigit = /\d/.test(password);
    const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

    if (!(hasUppercase && hasLowercase && hasDigit && hasSpecialChar)) {
        passwordWarning.textContent = 'Warning: Password must include at least one uppercase letter, one lowercase letter, one digit, and one special character.';
        passwordInput.classList.add('is-invalid');
        passwordError.textContent = '';
        return false;
    }

    // If all validations pass
    passwordInput.classList.remove('is-invalid');
    passwordWarning.textContent = '';
    passwordError.textContent = '';
    return true;
}



    function validatePDF(input) {
    const fileInput = input;
    const fileWarning = document.getElementById('file-warning');
    const fileError = document.getElementById('file-error');


    if (!fileInput.value) {
        fileWarning.textContent = 'Warning: Please upload a  file.';
        fileInput.classList.add('is-invalid');
        fileError.textContent = '';
        return false;
    } else {
        fileInput.classList.remove('is-invalid');
        fileWarning.textContent = '';
        fileError.textContent = '';
        return true;
    }
}


function validateForm() {
    const firstName = document.getElementById('validationCustom01').value;
    const lastName = document.getElementById('validationCustom02').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('validationCustom04').value;
    const shopName = document.getElementById('validationCustom05').value;
    const license = document.getElementById('validationCustom06').value;
    const password = document.getElementById('validationCustom07').value;
    const pdfFileInput = document.getElementById('pdfFile');

    const isFirstNameValid = validateFirstName(firstName);
    const isLastNameValid = validateLastName(lastName);
    const isEmailValid = validateEmail(email);
    const isPhoneValid = validatePhone(phone);
    const isShopNameValid = validateShopName(shopName);
    const isLicenseValid = validateLicense(license);
    const isPasswordValid = validatePassword(password);
    const isPdfFileValid = validatePDF(pdfFileInput);

    return isFirstNameValid && isLastNameValid && isEmailValid && isPhoneValid && isShopNameValid && isLicenseValid && isPasswordValid && isPdfFileValid;
}

</script>
</body>
</html>