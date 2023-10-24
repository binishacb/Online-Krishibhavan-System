<?php
include('dbconnection.php');

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Prepare a query to check if the email exists in the database
    $sql = "SELECT * FROM login WHERE email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo 'exists';
    } else {
        echo 'available';
    }
}

// Close the database connection
//$stmt->close();

?>
