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
    background-color: #f0f0f0;
    margin: 0;
    padding: 0;
}

form {
    width: 300px;
    margin: 0 auto;
    padding: 40px;
    background-color: #fff;
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
    <!-- Your HTML form for changing the password -->
    <form method="POST">
        <label for="old_password">Old Password:</label>
        <input type="password" name="old_password" required><br>

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" required><br>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password" required><br>

        <input type="submit" value="Change Password">
    </form>
</body>
</html>
