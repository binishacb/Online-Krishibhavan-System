<?php
session_start();
$page_title="registration";
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Agrocompanion</title>

	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/formhack.css">
<style>
    body{
    background-image:url('farmer.jpg');
    background-size: cover;
    }
	
</style>
</head>
</html>


	<div class="container">
		<div class ="alert">
			<?php
			if(isset($_SESSION['status']))
			{
				echo "<h4>".$_SESSION['status']."<h4>";
				unset($_SESSION['status']);
				echo "Registration successfull";
			}
			?>
		</div>
		<form action="code.php" method="POST" class="registration" id="registration">
			<h1>Registration Form</h1>

			<label for="username">
				<span>Username</span>

				<input type="text" name="username" id="username" minlength="3" required>

				<ul class="input-requirements">
					<li>At least 3 characters long</li>
					<li>Must only contain letters and numbers (no special characters)</li>
				</ul>
			</label>
			<label for="name">
				<span>Name</span>

				<input type="text" name="name" id="name" minlength="3" required>

				<ul class="input-requirements">
					<li>At least 3 characters long</li>
					<li>Must only contain letters and numbers (no special characters)</li>
				</ul>
			</label>
            <label for="email id">
                <span>Email</span>
                <input type="text" name="email" id="email" required>
                <ul class="input-requirements">
                    <li>please enter a valid email id in the format("username@gmail.com")</li>
                   

                </ul>
            </label>
            <label for="phone">
                <span>Phone</span>
                <input type="text" name="phone_no" id="phone_no" required>
                <ul class="input-requirements">
                    <li>phone number must contain 10 numbers</li>
                    <li>phone number should not contain special characters and letters</li>
                </ul>

            </label>
			<label for="password">
				<span>Password</span>

				<input type="password" name="password" id="password" maxlength="100" minlength="8" required>

				<ul class="input-requirements">
					<li>At least 8 characters long (and less than 100 characters)</li>
					<li>Contains at least 1 number</li>
					<li>Contains at least 1 lowercase letter</li>
					<li>Contains at least 1 uppercase letter</li>
					<li>Contains a special character (e.g. @ !)</li>
				</ul>
			</label>

			<label for="password_repeat">
				<span>Confirm Password</span>
				<input type="password" id="password_repeat" maxlength="100" minlength="8" required>
			</label>

			<br>
			<center>
			<input type="submit" name="register_btn" id="register_btn"><br>
			<p>Already a user?&nbsp;<a href="login.php">sign in</a></p></center>
		</form>
	</div>
    <script src="script.js"></script>

