<?php
    // Start a session (if not already started)
    session_start();
    include('dbconnection.php');
    include('navbar/navbar_farmer.php');
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
   
    // Check if the farmer is logged in and their ID is stored in the session
    if (isset($_SESSION['useremail'])) {
        // Retrieve the farmer's email from the session
        $farmeremail = $_SESSION['useremail'];
        // Fetch farmer details from the database
        $sql = "SELECT f.log_id, f.firstname,f.lastname, l.email, f.phone_no, f.dob FROM farmer AS f INNER JOIN login AS l ON f.log_id = l.log_id WHERE l.email = '$farmeremail'";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $firstname = $row["firstname"];
            $lastname = $row["lastname"];
            // $email = $row["email"];
            $phone_no = $row["phone_no"];
            $dob = $row["dob"];
        } else {
            echo "<script>alert('No farmer found with email: ' . $farmeremail)</script>";
        }
    } else {
        echo "<script>alert('Farmer not logged in.');</script>";
        header('location:login.php');
        exit;
    }
    
    // Check if the form is submitted for updating the profile
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $newFirstName = $_POST['new_firstname'];
        $newLastName = $_POST['new_lastname'];
        $newPhone = $_POST['new_phone'];
        $newDob = $_POST['new_dob'];
        //$newEmail = $_POST['new_email'];
        // Update the profile in the database
        $updateSql = "UPDATE farmer AS f INNER JOIN login AS l ON f.log_id = l.log_id SET f.firstname = '$newFirstName',f.lastname = '$newLastName', f.phone_no = '$newPhone', f.dob = '$newDob' WHERE l.email = '$farmeremail'";
        if ($con->query($updateSql) == TRUE) {
            echo "<script>alert('Profile updated successfully');</script>";
            // $_SESSION['useremail'] = $newEmail; 
          // Update the session email
            header('Location:profile_farmer.php'); 
            exit;
        } else {
            echo "Error updating profile: " . $con->error;
        }
    }
    
?>
    <div class="container">
        <div class="profile-container">
            <h2>Edit Profile:</h2>
            <form method="POST">
                <div class="profile-details">
                    <label for="new_name">First Name:</label>
                    <input type="text" name="new_firstname" value="<?php echo $firstname; ?>">
                </div>
                <div class="profile-details">
                    <label for="new_name">Last Name:</label>
                    <input type="text" name="new_lastname" value="<?php echo $lastname; ?>">
                </div>
                <div class="profile-details">
                    <label for="new_phone">Phone Number:</label>
                    <input type="text" name="new_phone" value="<?php echo $phone_no; ?>">
                </div>
                <div class="profile-details">
                    <label for="new_dob">Date of Birth:</label>
                    <input type="text" name="new_dob" value="<?php echo $dob; ?>">
                </div>
                <button type="submit" class="edit-button">Save Changes</button>
            </form>
        </div>
    </div><br><br><br><br><br><br><br><br><br>
<?php
    include('footer/footer.php');
?>
</body>
</html>

