<?php
// Start or resume the session
session_start();
include('dbconnection.php');
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
		<style>
			table {
            border: 5px solid green;
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 2px solid green;
        }

        th {
            background-color: lightgreen;
        }
			 .search-container {
            text-align: center;
            margin: 20px;
        }

        .search-box {
            width: 50%;
            padding: 10px;
            border: 2px solid green;
            border-radius: 5px;
        }

        .search-button {
            background-color: green;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
		</style>
		 <script>
        // JavaScript function to handle the search functionality
        function searchOfficers() {
            var input, filter, table, tr, td, i, firstName, lastName;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("officersTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                firstName = tr[i].getElementsByTagName("td")[0];
                lastName = tr[i].getElementsByTagName("td")[1];
                if (firstName && lastName) {
                    var name = (firstName.textContent + " " + lastName.textContent).toUpperCase();
                    if (name.indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
	</head>
	
<body>

<?php
include('navbar/navbar_admin.php');

?>
	<div class="container">
		<h2 style="font: 30px 'Akaya Telivigala', cursive;font-weight: 900">Officers</h2>
		<div class="search-container">
            <input type="text" id="searchInput" class="search-box" placeholder="Search..." onkeyup="searchOfficers()">
        </div>
		<table id="officersTable" class="table">
	<thead>
		<tr style="font: 20px 'Akaya Telivigala', cursive;font-weight: 900">
		
		<th>First Name</th>
		<th>Last Name</th>
		<th>Email</th>
	
		<th>Mobile</th>
		<th>Status</th>
		<!--<th>Action</th> -->
	</tr>
	</thead>
	<tbody>	
		<?php
		$sql = "SELECT o.log_id, o.firstname,o.lastname, l.email, o.phone_no ,o.status FROM officer AS o INNER JOIN login AS l ON o.log_id = l.log_id";
		//execute the query
		$result = $con->query($sql);
			if ($result->num_rows > 0) {
				//output data of each row
				while ($row = $result->fetch_assoc()) {
					$status = $row['status'];
					$statusText = ($status == 1) ? "Inactive" : "Active";
		?>

					<tr>
					
					<td><?php echo $row['firstname']; ?></td>

					<td><?php echo $row['lastname']; ?></td>
					
					<td><?php echo $row['email']; ?></td>
					
					<td><?php echo $row['phone_no']; ?></td>
					
					<td><?php echo $statusText; ?></td>
				
					</tr>	
					
		<?php		}
			}
			
		?>
	        	
	</tbody>
</table>
	</div>	<br><br><br><br><br><br><br><br><br><br><br><br>
	<?php
	include('footer/footer.php')
?>
</body>
</html>