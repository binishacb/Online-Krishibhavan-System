<?php
// Start or resume the session
session_start();
include('dbconnection.php');
include('navbar/navbar_officer.php');
if (!isset($_SESSION['useremail'])) {
    header('Location: index.php'); // Redirect to index.php
    exit(); // Stop further execution of the current script
}
?>

<?php
// Fetch schemes from the database
$query = "SELECT * FROM schemes";
$result = $con->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schemeName = $row['scheme_name'];
        //$imageURL = $row['image_url'];
        $schemeID = $row['scheme_id'];

        // Display each scheme in a box with an image and a link to the details page
        echo "<div class='scheme-box'>";
       // echo "<img src='$imageURL' alt='$schemeName'>";
        echo "<h3><a href='scheme_details.php?id=$schemeID'>$schemeName</a></h3>";
        echo "</div>";
    }
} else {
    echo "No schemes available.";
}
?>
