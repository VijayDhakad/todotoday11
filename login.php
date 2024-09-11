<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
     echo "<script>alert('You are Logged in Enter the Dashboard');</script>";
        // header("Location: dashboard.php");
        echo "<script>window.location.href = 'dashboard.php';</script>";
    } else {
    
    echo "<script>alert('Invalid credentials! Please try again.');</script>";
   
    echo "<script>window.location.href = 'login.php';</script>";
        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div>
       <h1>TODO Application App</h1>
     <h2>Login</h2>
    <form action="login.php" method="POST" id="myForm" onclick="validateForm()">
        <label>Email</label>
        <input type="email" name="email" required>
        <span id="emailError" style="color:red;"></span><br>
        <label>Password</label>
        <input type="password" name="password" required>
        <span id="passwordError" style="color:red;"></span><br>
        <button type="submit">Login</button>
        <span>if you are not Register <a href="register.php">SignUp here</a></span> 
    </form>
</div>
<script src="tasks.js"></script>




<script>
        // Form validation function
        function validateForm() {
            let isValid = true; // To track if the form is valid

       
            let email = document.getElementById("email");
            let password = document.getElementById("password");

          
            document.getElementById("emailError").innerText = "";
            document.getElementById("passwordError").innerText = "";

          
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email.value)) {
                document.getElementById("emailError").innerText = "Invalid email format.";
                isValid = false;
            }

            // Password validation:
            // - Must start with a capital letter
            // - Must contain at least one special character
            // - Must contain at least one number
            const passwordPattern = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/;
            if (!passwordPattern.test(password.value)) {
                document.getElementById("passwordError").innerText = 
                    "Password must start with a capital letter, contain at least one number, one special character, and be at least 8 characters long.";
                isValid = false;
            }

            password.onclick=function(){
                passwordError.style.display="none"
                
            }

            email.onclick=function(){
                emailError.style.display="none"
                
            }
            return isValid; // Return true if the form is valid, false otherwise
        }
    </script>




</body>
</html>


<!-- <form >
   id="password" 
id="email"
    <label fo r="email">Email:</label>
    <input type="email" name="email" required>
   

    <label for="password">Password:</label>
    <input type="password"  name="password" required>
   

    <button type="submit">Submit</button>
</form> -->

