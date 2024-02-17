
<?php
include('dbconnection.php');
require 'vendor/autoload.php';

use Ramsey\Uuid\Uuid;
?>


<?php
if(isset($_POST['submit']))
{
  $firstname = $_POST['firstName'];
  $lastname = $_POST['lastName'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $password = $_POST['password'];
  $hashed_password = md5($password);
  $shopname = $_POST['shopName'];
  $licence_no = $_POST['licence_no'];
  $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
  // $vendorId = "VEN-" . Uuid::uuid4()->toString();
  $vendorId = "VEN-" . substr(Uuid::uuid4()->toString(), 0, 6);
// echo $vendorId;

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
          // Insert data into the 'farmer' table
  
          // First, insert a record into the 'login' table to get the 'log_id'
        
          $insert_login_query = "INSERT INTO login(email, password, role_id) VALUES ('$email', '$hashed_password', 4)";
          $insert_login_query_run = mysqli_query($con, $insert_login_query);
  
          if ($insert_login_query_run) {
              // Get the 'log_id' of the newly inserted 'login' record
              $log_id = mysqli_insert_id($con);
  
              // Now, insert the farmer data with the obtained 'log_id'
              $query_vendor = "INSERT INTO `vendor`(`v_id`,`firstName`,`lastName`, `phone_no`, `shopName`, `log_id`, `licence_no`,`licence_image`) VALUES ('$vendorId','$firstname','$lastname', '$phone', '$shopname', '$log_id','$licence_no','$fileName')";
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

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vendor registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
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
    <form class="row g-3 needs-validation" method="POST" action="" enctype="multipart/form-data" novalidate>
    <div class="col-md-6">
    <label for="validationCustom01" class="form-label">First name</label>
    <input type="text" class="form-control" name="firstName" id="validationCustom01" required>
    <div class="invalid-feedback">
      First name is required.
    </div>
  </div>
  <div class="col-md-6">
    <label for="validationCustom02" class="form-label">Last name</label>
    <input type="text" class="form-control" name="lastName" id="validationCustom02"  oninput="validateFirstName(this.value)" required>
                                    <div id="firstName-warning" class="invalid-feedback"></div>
                                    <div id="firstName-error" class="invalid-feedback"></div>
    <div class="invalid-feedback">
    Last name is required.
    </div>
  </div>


  <div class="col-md-6">
    <label for="validationCustom03" class="form-label">Email</label>
    <input type="email" class="form-control" name="email" id="validationCustom03" required>
    <div class="invalid-feedback">
    Email is required.
    </div>
  </div>

  <div class="col-md-6">
    <label for="validationCustom04" class="form-label">Mobile</label>
    <input type="number" class="form-control" name="phone" id="validationCustom04" required>
    <div class="invalid-feedback">
    Mobile is required.
    </div>
  </div>

  <div class="col-md-6">
    <label for="validationCustom05" class="form-label">Shop name</label>
    <input type="text" class="form-control" name="shopName" id="validationCustom05" required>
    <div class="invalid-feedback">
     Shop name is required.
    </div>
  </div>

  <div class="col-md-6">
    <label for="validationCustom06" class="form-label">Licence number</label>
    <input type="text" class="form-control" name="licence_no" id="validationCustom06" required>
    <div class="invalid-feedback">
     License is required.
    </div>
  </div>


<div class="input-group mb-3">
  <input type="file" class="form-control col-6" id="pdfFile" name="pdfFile" required>
  <label class="input-group-text" for="inputGroupFile02">Upload</label>
  <div class="invalid-feedback">
      Please upload the license .
    </div>
</div>

<div class="col-md-6">
    <label for="validationCustom07" class="form-label">Password</label>
    <input type="text" class="form-control" name="password" id="validationCustom07"  required>
    <div class="invalid-feedback">
    password is required.
    </div>
  </div>


  <div class="col-12">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
      <label class="form-check-label" for="invalidCheck">
        I agree to terms and conditions of this websites .Please read our <a href="#">terms and conditions</a>...
      </label>
      <div class="invalid-feedback">
        You must agree before submitting.
      </div>
    </div>
  </div>

  <div class="col-12">
    <button class="btn btn-primary" type="submit" name="submit">Submit</button>
  </div>
</form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
<script>
  (() => {
    'use strict';

    const forms = document.querySelectorAll('.needs-validation');

    // Loop over forms and perform actions on file input change
    Array.from(forms).forEach(form => {
      const fileInput = form.querySelector('.form-control[type="file"]');
      const maxSize = 10 * 1024 * 1024; // 10MB

      fileInput.addEventListener('change', () => {
        const fileSize = fileInput.files[0]?.size;

        // Validate the file size
        if (fileSize && fileSize > maxSize) {
          showErrorFeedback('Image size must not exceed 2MB');
        } else {
          removeErrorFeedback('file-size-feedback');
        }
      });
      form.addEventListener('submit', event => {
        const fileSize = fileInput.files[0]?.size;

        // Validate the file size
        if (fileSize && fileSize > maxSize) {
          showErrorFeedback('Image size must not exceed 10MB');
          event.preventDefault();
          event.stopPropagation();
        } else {
          removeErrorFeedback();
        }

        // Validate if the file is empty
        if (!fileInput.files[0]) {
          showErrorFeedback('Please upload the license.');
          event.preventDefault();
          event.stopPropagation();
        }

        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }

        form.classList.add('was-validated');
      }, false);

      // Function to show error feedback
      function showErrorFeedback(message) {
        const errorFeedback = form.querySelector('.file-size-feedback');

        if (!errorFeedback) {
          const feedbackDiv = document.createElement('div');
          feedbackDiv.classList.add('invalid-feedback', 'file-size-feedback');
          feedbackDiv.innerHTML = message;
          fileInput.parentElement.appendChild(feedbackDiv);
        }

        fileInput.classList.add('is-invalid');
      }

      // Function to remove error feedback
      function removeErrorFeedback() {
        const errorFeedback = form.querySelector('.file-size-feedback');
        if (errorFeedback) {
          errorFeedback.remove();
        }

        fileInput.classList.remove('is-invalid');
      }
    });
  })();
</script>


<script>
function validateFirstName(firstName) {
    // Get elements for first name validation
    const firstNameInput = document.getElementById('firstName');
    const firstNameWarning = document.getElementById('firstName-warning');
    const firstNameError = document.getElementById('firstName-error');
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
    } 
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
</body>
</html>