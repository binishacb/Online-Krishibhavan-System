<!DOCTYPE html>
<html lang="en">
<head>
    <title>Client Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            padding: 20px;
            justify-content: center;
            align-items: center;
            height: 100%;
            margin: 0;
        }

        #container {
            display: flex;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #sidebar {
            width: 0;
            padding: 20px;
            background-color: green;
            color: white;
            transition: width 0.3s ease-in-out;
            overflow: hidden;
            white-space: nowrap;
        }

        #sidebar:hover {
            width: 250px;
        }

        #sidebar h3 {
            margin-bottom: 20px;
            opacity: 0;
            transform: translateX(-20px);
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        #sidebar:hover h3 {
            opacity: 1;
            transform: translateX(0);
        }

        #sidebar ul {
            list-style: none;
            padding: 0;
        }

        #sidebar li {
            margin-bottom: 10px;
        }

        #sidebar li a {
            color: white;
            text-decoration: none;
            padding: 10px 0;
            display: block;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
        }

        #sidebar li a:hover {
            background-color: #005f6b;
        }

        #content {
            flex: 1;
            padding: 20px;
            background-color: white;
            border-radius: 0 10px 10px 0;
        }

        h2 {
            color: black;
            }

        #content:hover h2 {
            opacity: 1;
            transform: translateY(0);
        }

        h3 {
            color: #fff;
            margin-top: 20px;
             }

        #content:hover h3 {
            opacity: 1;
            transform: translateY(0);
        }

        p {
            margin-bottom: 10px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"], input[type="email"], textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #008cba;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        button[type="submit"]:hover {
            background-color: #005f6b;
        }
    </style>
</head>
<body>
<script>
        // Logout function
        function logout() {
            // Implement your logout logic here

            // Redirect to the login page
            window.location.replace("login.php");
        }

        // Disable caching to prevent back button from showing the logged-in page
        window.onload = function () {
            window.history.forward();
            document.onkeydown = function (e) {
                if (e.keyCode === 9) {
                    return false;
                }
            };
        }

        // Redirect to login page if the user tries to go back
        window.addEventListener('popstate', function (event) {
            window.location.replace("login.php");
        });
    </script>
    <div id="container">
        <div id="sidebar">
            <h3>Dashboard Menu</h3>
            <ul>
                <li><a href="#">Farmers</a></li>
                <li><a href="#">Add news</a></li>
                <li><a href="#">Update Profile</a></li>
                <li><a href="#">Add schemes</a></li>
                <li><a href="#">Weather updates</a></li>
            </ul>
             <!-- Logout button -->
             <button onclick="logout()" type="button">Logout</button>
        </div>
        <div id="content">
            <h2>Welcome to Admin</h2>
            
         <!-- Replace with actual progress -->
        </div>
    </div>
</body>
</html>