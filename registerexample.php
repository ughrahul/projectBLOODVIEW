<?php
session_start(); // Start the session

include("connect.php"); // Include the database connection

error_reporting(E_ALL);
ini_set('display_errors', 1);

$registrationError = "";
$registrationSuccess = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($con, $_POST['confirm_password']);

    if (isset($_POST['register'])) {
        // Check if passwords match
        if ($password !== $confirmPassword) {
            $_SESSION['registrationError'] = "Passwords do not match.";
        } else {
            // Password strength checks
            if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
                $_SESSION['registrationError'] = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special symbol.";
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Check if the email already exists
                $checkQuery = "SELECT username FROM users WHERE username = '$username'";
                $checkResult = mysqli_query($con, $checkQuery);

                if (mysqli_num_rows($checkResult) > 0) {
                    $_SESSION['registrationError'] = "Email already exists. Please choose a different email.";
                } else {
                    // Insert the new user data
                    $insertQuery = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
                    $insertResult = mysqli_query($con, $insertQuery);

                    if ($insertResult) {
                        $_SESSION['registrationSuccess'] = "Registration successful.";
                        
                        header("Location: loginexample.php");
                        exit();

                    } else {
                        $_SESSION['registrationError'] = "Unable to register. Please try again later.";
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>BloodView - REGISTER</title>
    
</head>
<body>
    <div class="container">
        <h1><span class="title">BLOOD-VIEW </span><span class="emoji">🩸</span></h1>
        <h2>Register</h2>

        <?php if (!empty($_SESSION['registrationError'])) { ?>
            <div class="error"><?php echo $_SESSION['registrationError']; ?></div>
            <?php unset($_SESSION['registrationError']); ?>
        <?php } ?>

        <?php if (!empty($registrationSuccess)) { ?>
            <div class="success"><?php echo $registrationSuccess; ?></div>
        <?php } ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label><B>Email:</B></label>
            <input type="text" name="email" required><br><br>
            <label><B>Password:</B></label>
            <input type="password" name="password" required>
          <span class="password-guidelines" id="password-strength">
    <ul>
        <li class="requirement" id="length">8 characters minimum</li>
        <li class="requirement" id="uppercase">At least 1 uppercase letter</li>
        <li class="requirement" id="lowercase">At least 1 lowercase letter</li>
        <li class="requirement" id="number">At least 1 number</li>
        <li class="requirement" id="special">At least 1 special symbol</li>
    </ul>

</span>
    

            <label><B>Confirm Password:</B></label>
            <input type="password" name="confirm_password" required><br><br>

            <button type="submit" name="register">Register</button>
            <p>Already have an account? <a href="loginexample.php">Login here</a></p>
        </form>
    </div>
</body>
</html>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
    }

    .container {
        max-width: 350px;
        margin: 0 auto;
        padding: 15px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 1.5);
        opacity: 0;
        animation: fadeIn 1s ease-in-out forwards;
    }
    @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
        .title {
        text-shadow: -1px -1px 0 rgba(0, 0, 0, 50);
        color: red;;
        }
        .emoji {
        animation: dropAnimation 2s ease-in-out infinite;
        display: inline-block;
    }

        /* Button Hover Animation */
        input[type="submit"]:hover,
        button[type="submit"]:hover {
            background-color: #45a049;
            transform: scale(1.05);
            transition: background-color 0.3s, transform 0.3s;
        }


        input[type="text"]:focus ~ label,
        input[type="password"]:focus ~ label {
            transform: translateY(-20px);
        }

    h1 {
        color: red;
        text-align: center;
        margin-bottom: 30px;
    }

    h2 {
        text-align: center;
    }

    label {
        font-weight: bold;
    }

    input[type="text"],
    input[type="password"] {
        display: block;
        width: 100%;
        padding: 12px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    /* Adjust margin and padding for the "Register" button */
button[type="submit"] {
    width: 100%;
    padding: 15px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 2px; /* Adjust the margin-top as needed */
}

/* Add margin to the "Confirm Password" input field */
input[name="confirm_password"] {
    margin-bottom: 1px; /* Add margin to separate from the button */
    margin-top: 1px;
}

/* Add margin to the "Already have an account?" link */
p {
    text-align: center;
    margin-top: 3px; /* Adjust the margin-top as needed */
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
    .password-guidelines {
    font-size: 14px;
    color: #777;
    line-height: 1.4;
    display: block;
    margin-top: 0.5px;
}

/* Error and Success Message Styles */
    .error,
    .success {
        padding: 10px;
        border-radius: 4px;
        font-size: 14px;
        margin-top: -10px;
        text-align: center;
        animation: fadeInUp 0.5s ease-in-out;
    }

    .error {
        background-color: #ff9999; /* Light red */
        color: #cc0000; /* Dark red */
    }

    

    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(10px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .password-guidelines {
    font-size: 14px;
    color: #555; /* Dark gray color */
    margin-top: 10px; /* Adjust this value for spacing */
}

.password-guidelines::before {
    content: "Password Requirements:";
    font-weight: bold;
    display: block;
    margin-bottom: 6px;
}

.password-guidelines ul {
    list-style: disc;
    margin-left: 20px;
    padding-left: 0;
}

.password-guidelines li {
    margin-bottom: 6px;
}


    
</style>
