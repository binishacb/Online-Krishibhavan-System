<?php
session_start();
include('dbconnection.php'); // Include your database connection file
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input from the form
    // $oldPassword = $_POST['old_password'];
    $oldPassword = trim($_POST['old_password']);
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    // Retrieve the user's current password from the database (replace 'users' and 'password_hash' with your actual table and column names)
    $useremail = $_SESSION['useremail'];
    $sql = "SELECT password FROM login WHERE email = '$useremail'";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row["password"];
// echo $hashedoldpassword;
//  echo $oldPassword;
//  echo $useremail;
        // Verify the old password
        if (md5($oldPassword) == $hashedPassword) {
            // Check if the new password and confirmation match
            if ($newPassword == $confirmPassword) {
                // Hash the new password (you can use password_hash with PASSWORD_BCRYPT)
                $newHashedPassword = md5($newPassword);
                // Update the password in the database
                $updateSql = "UPDATE login SET password = '$newHashedPassword' WHERE email = '$useremail'";
                if ($con->query($updateSql) == TRUE) {
                    echo "<script>alert('Password updated successfully!')</script>";
                } else {
                    echo "<script>alert('Error updating password: ' . $con->error)</script>";
                }
            } 
            else {
                echo "<script>alert('New password and confirmation do not match.)</script>";
            }
        } 
        else {
            echo "<script>alert('Old password is incorrect.')</script>";
        }
    } else {
        echo "User not found.";
    }
}

$con->close(); // Close the database connection
?>
<!DOCTYPE html>
<html>
<head>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #c1eac2;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;

}

form {
    width: 300px;
    margin: 0 auto;
    padding: 40px;
   
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    border-radius: 5px;
    text-align: center;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 10px;
    background-color: #cfe6ff; 
}

input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}
</style>
</head>
<body>
    <?php
    include('navbar\navbar_officer');
    ?>
    <!-- Your HTML form for changing the password -->
    <form method="POST" onsubmit="return validateForm()">
        <label for="old_password">Old Password:</label>
        <input type="password" name="old_password" id="old_password" required oninput="checkPasswordStrength()"><br>

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required oninput="checkPasswordStrength()"><br>
        <div id="password-strength"></div>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required oninput="checkPasswordMatch()"><br>
        <div id="password-match"></div>

        <input type="submit" value="Change Password">
    </form>
    <script>
        function checkPasswordStrength() {
            var newPassword = document.getElementById('new_password').value;
            var passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@#$%^&!])[A-Za-z\d@#$%^&!]{8,}$/;

            var passwordStrength = 'Password Strength: ';
            if (passwordPattern.test(newPassword)) {
                passwordStrength += 'Strong';
                document.getElementById('new_password').style.borderColor = 'green';
            } else {
                passwordStrength += 'Weak';
                document.getElementById('new_password').style.borderColor = 'red';
            }

            document.getElementById('password-strength').innerHTML = passwordStrength;
        }

        function checkPasswordMatch() {
            var newPassword = document.getElementById('new_password').value;
            var confirmPassword = document.getElementById('confirm_password').value;

            var passwordMatchMessage = '';
            if (newPassword === confirmPassword) {
                passwordMatchMessage = 'Passwords Match';
                document.getElementById('confirm_password').style.borderColor = 'green';
            } else {
                passwordMatchMessage = 'Passwords Do Not Match';
                document.getElementById('confirm_password').style.borderColor = 'red';
            }

            document.getElementById('password-match').innerHTML = passwordMatchMessage;
        }

        function validateForm() {
            var newPassword = document.getElementById('new_password').value;
            var confirmPassword = document.getElementById('confirm_password').value;

            if (!passwordPattern.test(newPassword)) {
                alert('New password must contain at least one capital letter, one small letter, one number, and one special character (@#$%^&!) and be at least 8 characters long.');
                return false;
            }

            if (newPassword !== confirmPassword) {
                alert('New password and confirm password do not match.');
                return false;
            }

            return true;
        }
    </script>
    </body></html>