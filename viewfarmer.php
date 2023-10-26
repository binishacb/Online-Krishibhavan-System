<?php
// Start or resume the session
session_start();
include('dbconnection.php');
include('navbar/navbar_admin.php');
if (!isset($_SESSION['useremail'])) {
    header('Location: index.php'); // Redirect to index.php
    exit(); // Stop further execution of the current script
}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Farmers</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="bootstrap\css\bootstrap.min.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="bootstrap\js\bootstrap.min.js"></script>
		<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-layers.min.js"></script>
		<script src="js/init.js"></script>
		<!-- <noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-xlarge.css" />
		</noscript> -->
		<link rel="stylesheet" href="indexfooter.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
	</head>
	
<body>

	<div class="container">
		<h2 style="font: 30px 'Akaya Telivigala', cursive;font-weight: 900">Farmers</h2>
<table class="table">
	<thead>
		<tr style="font: 20px 'Akaya Telivigala', cursive;font-weight: 900">
		<th>ID</th>
		<th>Full Name</th>
		
		<th>Email</th>
	
		<th>Mobile</th>
		
		<!--<th>Action</th> -->
	</tr>
	</thead>
	<tbody>	
		<?php
		$sql = "SELECT f.log_id, f.name, l.email, f.phone_no  FROM farmer AS f INNER JOIN login AS l ON f.log_id = l.log_id";
		//execute the query
		$result = $con->query($sql);
			if ($result->num_rows > 0) {
				//output data of each row
				while ($row = $result->fetch_assoc()) {
		?>

					<tr>
					<td><?php echo $row['log_id'];?></td>
					<td><?php echo $row['name']; ?></td>
					
					<td><?php echo $row['email']; ?></td>
					
					<td><?php echo $row['phone_no']; ?></td>
					
				
					</tr>	
					
		<?php		}
			}
			
		?>
	        	
	</tbody>
</table>
	</div>	<br><br><br>
	<?php
	include('footer/footer.php')
?>
</body>
</html>