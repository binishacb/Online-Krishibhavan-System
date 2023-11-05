<?php
session_start();
include('dbconnection.php');
include('navbar/navbar_farmer.php');
?>


<!DOCTYPE html>
<head><style>
    /* Style for the body */
body {
    font-family: Arial, sans-serif;
    background-color:#8FB06C;
    margin: 0;
    padding: 0;
}

/* Style for the profile container */
.profile-container {
    width: 50%; /* Increase the max-width to your desired size */
    margin: 0 auto;
    padding: 50px 40px; /* Adjust padding as needed */
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    border-radius: 5px;
    position: relative;
}
.container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

/* Style for the profile title */
.profile-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333;
}

/* Style for the profile details */
.profile-details {
    margin: 10px 0;
    padding: 10px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 5px;
}

/* Style for labels in the profile details */
.profile-details strong {
    font-weight: bold;
}

/* Style for the footer */
/* .footer {
    text-align: center;
    margin-top: 20px;
    color: #777;
} */

/* Style for links (if needed) */
a {
    text-decoration: none;
    color: #007bff;
}

a:hover {
    text-decoration: underline;
}
.edit-button {
    position: absolute;
    bottom: 20px;
    right: 20px;
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.edit-button:hover {
    background-color: white;
    
}
.change-password-button{
    position:absolute;
    bottom: 20px;
    left:20px;
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.change-password-button:hover{
    background-color: white;
}
</style></head>

<body>

 <div class="container">
        <div class="profile-container">
        <a href="editprofile_farmer.php" class="edit-button">Edit Profile</a>
        <a href="changepassword.php" class ="change-password-button">Change password</a>
            <?php
// Check if the farmer is logged in and their ID is stored in the session
if (isset($_SESSION['useremail'])) {
    
    // Retrieve the farmer's ID from the session
    $farmeremail = $_SESSION['useremail'];

    // Fetch farmer details from the database
   // $sql = "SELECT name,email, phone_no FROM farmer WHERE id = $farmerId";
   $sql = "SELECT f.log_id, f.firstname,f.lastname, l.email, f.phone_no,f.dob FROM farmer AS f INNER JOIN login AS l ON f.log_id = l.log_id WHERE l.email = '$farmeremail'";
   $result = $con->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<h2>Profile:</h2>";
            echo "<p><strong>First Name:</strong> " . $row["firstname"] . "</p>";
            echo "<p><strong>Last Name:</strong> " . $row["lastname"] . "</p>";
            echo "<p><strong>Email:</strong> " . $row["email"] . "</p>";
            echo "<p><strong>Phone Number:</strong> " . $row["phone_no"] . "</p>";
            echo "<p><strong>DOB:</strong> " . $row["dob"] . "</p>";
        }
    } else {
        echo "No farmer found with ID: " . $farmeremail;
    }

    // Close the database connection
    $con->close();
} else {
    echo "Farmer not logged in.";
}
?>
</div></div>

<?php
include('footer/footer.php');
?>
</body></html>