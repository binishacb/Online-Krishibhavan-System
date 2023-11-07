<?php
// Start or resume the session
session_start();
include('dbconnection.php');

?>
<!DOCTYPE html>
<html lang="en">
	<head>
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

        /* Add styles for the search bar */
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
        function searchFarmers() {
            var input, filter, table, tr, td, i, firstName, lastName;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("farmersTable");
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
    if (!isset($_SESSION['useremail'])) {
        header('Location: index.php'); // Redirect to index.php
        exit(); // Stop further execution of the current script
    }
    ?>

	<div class="container">
		<h2 style="font: 30px 'Akaya Telivigala', cursive;font-weight: 900">Farmers</h2>
        <table id="farmersTable" class="table">
<div class="search-container">
          
            <input type="text" id="searchInput" class="search-box" placeholder="Search..." onkeyup="searchFarmers()">
            <button class="search-button">Search</button>
        </div>
	<thead>
		<tr style="font: 20px 'Akaya Telivigala', cursive;font-weight: 900">
		
		<th>First Name</th>
		
		<th>Last Name</th>

		<th>Email</th>
	
		<th>Mobile</th>
		
		<!--<th>Action</th> -->
	</tr>
	</thead>
	<tbody>	
		<?php
		$sql = "SELECT f.log_id, f.firstname,f.lastname, l.email, f.phone_no  FROM farmer AS f INNER JOIN login AS l ON f.log_id = l.log_id";
		//execute the query
		$result = $con->query($sql);
			if ($result->num_rows > 0) {
				//output data of each row
				while ($row = $result->fetch_assoc()) {
		?>

					<tr>
					
					<td><?php echo $row['firstname']; ?></td>

                    <td><?php echo $row['lastname']; ?></td>
					
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