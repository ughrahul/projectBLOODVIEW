<?php
session_start(); // Start the session

include("connect.php"); // Include the database connection

// Initialize variables to store username and error message
$username = "";
$loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve user data from the database
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $hashedPassword = $user['password'];

        // Verify the entered password against the stored hashed password
        if (password_verify($password, $hashedPassword)) {
            // Authentication successful
            $_SESSION['username'] = $username; // Store username in session
            header("Location: homepage.php"); // Redirect to homepage
            exit();
        } else {
            $_SESSION['loginError'] = "Invalid Username or Password !!";
            header("Location: loginexample.php"); // Redirect back to the login page
            exit();
        }

    } else {
        $_SESSION['loginError'] = "Invalid Username or Password !!";
        header("Location: loginexample.php"); // Redirect back to the login page
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blood Bank Stock Management - Login</title>
</head>
<body>
       <div class="container">
        <h1><span class="title">BLOOD-VIEW </span><span class="emoji">🩸</span></h1>
        <h2>Login</h2>

        <?php
        if (!empty($_SESSION['registrationSuccess'])) {
            echo '<div class="success">' . $_SESSION['registrationSuccess'] . '</div>';
            unset($_SESSION['registrationSuccess']); // Clear the success message from the session
        }
        ?>

        <form action="loginexample.php" method="post">
            <label>Username:</label>
            <input type="text" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
            <label>Password:</label>
            <input type="password" name="password" required>

            <?php if (isset($_SESSION['loginError'])) { ?> <!-- Check if the error message is set -->
                <div class="error <?php echo isset($_SESSION['loginError']) ? 'blink' : ''; ?>" style="color: red; font-size: 20px; margin-top: 2px; margin-left: 50px;">
                    <?php echo $_SESSION['loginError']; ?>
                </div> <!-- Display the error message -->
                <?php unset($_SESSION['loginError']); } ?> <!-- Clear the error message from session after displaying -->

            <input type="submit" name="login" value="Login">
            <p>Don't have an account? <a href="registerexample.php">Register here</a></p>
        </form>
    </div>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.9);
            opacity: 0;
            animation: fadeIn 1s ease-in-out forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        h1 {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s; /* Add transform transition */
        }

        input[type="submit"]:hover {
            background-color: #45a049;
            transform: scale(1.05); /* Apply scale transformation on hover */
        }

        p {
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: blue;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .title {
            text-shadow: -1px -1px 0 rgba(0, 0, 0, 100);
            color: red;
        }

@keyframes blink {
    0% {
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    100% {
        opacity: 0;
    }
}

.blink {
    animation: blink 0.8s infinite;
}

.success {
        background-color: #99ff99; /* Light green background */
        color: #009900; /* Dark green text color */
        padding: 10px; /* Add padding for spacing */
        border-radius: 4px; /* Add rounded corners */
        font-size: 14px; /* Adjust font size */
        text-align: center; /* Center the text */
        margin-top: -10px; /* Adjust margin to position the message */
        animation: fadeInUp 0.5s ease-in-out; /* Add animation */
    }

       
    </style> 
</body>
</html>
