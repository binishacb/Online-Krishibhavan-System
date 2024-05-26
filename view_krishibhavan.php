<?php
session_start();
include('dbconnection.php');
if (!isset($_SESSION['useremail'])) {
    header('Location: index.php'); 
    exit(); 
}

// Fetch Krishibhavan details from the database
$query = "SELECT * FROM krishi_bhavan";
$result = $con->query($query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Krishibhavan Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <?php include('navbar/navbar_admin.php'); ?>
    <div class="container mt-5">
        <h2 class="mb-4">Krishibhavan Details</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Serial No.</th>
                    <th>Krishibhavan Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Block</th>
                    <th>District</th>
                    
                   
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $serial = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$serial}</td>";
                        echo "<td>{$row['krishibhavan_name']}</td>";
                        echo "<td>{$row['email']}</td>";
                        echo "<td>{$row['mobile']}</td>";
                        // Fetch district and block names from their respective tables based on IDs
                        $block_query = "SELECT block_name,dis_name  FROM block b JOIN district d on b.dis_id = d.dis_id WHERE block_id = {$row['block_id']}";
                        $block_result = $con->query($block_query);
                        $row = $block_result->fetch_assoc();
                        //$block_name = ($block_result->num_rows > 0) ? $block_result->fetch_assoc()['block_name'] : '';
                       // echo "<td>{$block_name}</td>";
                        echo "<td>{$row['block_name']}</td>";
                        echo "<td>{$row['dis_name']}</td>";
                      
                        echo "</tr>";
                        $serial++;
                    }
                } else {
                    echo "<tr><td colspan='7'>No Krishibhavan records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <br><br>
    <?php include('footer/footer.php'); ?>
</body>
</html>

<?php $con->close(); ?>
