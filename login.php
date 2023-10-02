<?php
session_start();
include('dbcon.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Form</title>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="login.css">
	<link rel="stylesheet" href="css/formhack.css">
</head>
<body>

    <div class="container">
     <?php
     if(isset($_SESSION['status']))
     {
        ?>
        <div class = "alert alert-success">
            <h5><?=$_SESSION['status'];?></h5>
        </div>
        <?php
        unset($_SESSION['status']);
     }
     ?>
      <form class="login" id="login" action="">
      <h1>Login Form</h1>
      <label for="uname"><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="uname" required>

      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="psw" required><br><br>
      <center>
      <button type="submit" class="loginbtn">Login</button><br></center>
      <label>
        <div >
        <input type="checkbox" checked="checked" class ="remembercheck" name="remember"> Remember me
      </label>
      <button type="button" class="cancelbtn">Cancel</button>
      <a href="#">Forgot password?</a></div>
    </div>
  </div>
</form>
</div>

</body>
</html>
