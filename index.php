<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "u_info"; // Make sure you have created this database in phpMyAdmin

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if this is an AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Register User (AJAX)
    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        // Get input data
        $user_name = $_POST['name'];
        $user_email = $_POST['email'];
        $user_password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

        // SQL query to insert user details
        $sql = "INSERT INTO user_details (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $user_name, $user_email, $user_password);

        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    // Log In User (AJAX)
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        // Get input data
        $login_email = $_POST['email'];
        $login_password = $_POST['password'];

        $sql = "SELECT * FROM user_details WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $login_email);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($login_password, $row['password'])) {
                echo "Login successful!";
            } else {
                echo "Incorrect password!";
            }
        } else {
            echo "No user found with this email!";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="styyle.css">
    <title>Registration and Login</title>
    
<style>

@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap');
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Montserrat',sans-serif;
}
body{
    background-color: #c9d6ff;
    background: linear-gradient(to right, #e2e2e2, #c9d6ff);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    height: 100vh;
}
.container {
    position: relative;
    display: flex;
    width: 100%;
    overflow: hidden;
    
    
    background-color: #fff;
    border-radius:30px;
    box-shadow: 0 5px 15px rgba(0,0,0,35);
    position: relative;
    overflow: hidden;
    width: 768px;
    max-width: 100%;
    min-height: 480px;
}
.container p{
    font-size: 14px;
    line-height: 20px;
    letter-spacing: 0.3px;
    margin: 20px;
}
.container span{
    font-size: 12px;
}
.container a{
    color: #333;
    font-size: 13px;
    text-decoration: none;
    margin: 15px 0 10px;
}
.container button{
    background-color: #512da8;
    color: #fff;
    font-size: 12px;
    padding: 10px 45px;
    border: 1px solid transparent;
    border-radius: 8px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-top: 10px;
    cursor: pointer;
}
.container button.hidden{
    background-color: transparent;
    border-color: #fff;
}
.container form{
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 0 40px;
    height: 100%;
}
.container input{
    background-color: #eee;
    border: none;
    margin: 8px 0;
    padding: 10px 15px;
    font-size: 13px;
    border-radius: 8px;
    width: 100%;
    outline: none;
}
.form-container{
    position: absolute;
    top: 0;
    height: 100%;
    transition: all 0.6s ease-in-out;

}
 .sign-in, .sign-up {
        width: 100%;
        height: 100%;
    }
.container .sign-in{
    left: 0;
    width: 50%;
    z-index: 2;
   
}
.container.active .sign-in{
    transform: translateX(0%);
    
}
.container:not(.active) .sign-in{
    display: block;
}
.container .sign-up {
    left: 0;
    width: 50%;
    opacity: 0;
    z-index: 1;
    display: block;
   
}
.container.active .sign-up{
    transform: translateX(100%);
    opacity: 10;
    z-index: 5;
    animation: move 0.6s;
}
@keyframes move{
    0%,
    49.99% {
        opacity: 0;
        z-index: 5;
    }
    50%,
    100%{
        opacity: 1;
        z-index: 5;
    }
}
.social-icons {
    margin: 20px 0;
}
.social-icons a{
    border: 1px solid #ccc;
    border-radius: 20%;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    margin: 0 3px;
    width: 40px;
    height: 40px;
}
.toggle-container{
    position: absolute;
    top: 0;
    width: 50%;
    left: 50%;
    height: 100%;
    overflow: hidden;
    transition: all 0.6s ease-in-out;
    border-radius: 150px 0 0 100px;
    z-index: 1000;
}
.container.active .toggle-container{
    transform: translateX(-100%);
    border-radius: 0 150px 100px 0;

}
.toggle{
    background-color: #512da8;
    height: 100%;
    background: linear-gradient(to right, #5c6bc0, #512da8);
    color: #fff;
    position:relative;
    left: -100%;
    height: 100%;
    width: 200%;
    transform: translateX(0);
    transition: all 0.5s ease-in-out;
}
.container.active .toggle{
    transform: translateX(50%);
}
.toggle-panel{
    position: absolute;
    width: 50%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 0 30px;
    text-align: center;
    top: 0;
    transform: translateX(0);
    transition: all 0.5s;
}

.toggle-left{
    transform: translateX(-200%);
}
.container.active .toggle-left{
    transform: translateX(0);
}


.toggle-right{
    right: 0;
    transform: translateX(200);
}
.container.active .toggle-right{
    transform: translateX(-200%);
}
</style>
<script src="script.js" defer></script>
</head>
<body>
  <div class="container" id="container">
        <!-- Sign-Up Form -->
        <div class="form-container sign-up" id="sign-up">
            <form method="POST" action="index.php" id="signup-form">
                <h1>Create Account</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use email for registration</span>
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="register">Sign Up</button>
            </form>
        </div>

        <!-- Sign-In Form -->
        <div class="form-container sign-in" id="sign-in">
            <form method="POST" action="index.php" id="signin-form">
                <h1>Sign In</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for sign in</span>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <a href="#">Forgot Password?</a>
                <button type="submit" name="login">Sign In</button>
            </form>
        </div>

        <!-- Toggle Panels -->
        <div class="toggle-container">
                <div class="toggle">
                     <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected, please sign in with your details.</p>
                    <button id="sign-in-btn">Sign In</button>
                     </div>

                     <div class="toggle-panel toggle-right">                   
                    <h1>No Account?</h1>
                    <p>Enter your details to create an account.</p>
                    <button id="sign-up-btn">Sign Up</button>
                     </div>
                </div>
            </div>
        </div>
    </div>
<script src="script.js"></script>
</body>
</html>
