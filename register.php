<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('You are successfully registered!');</script>";
        echo "<script>window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Registration failed Your email is alredy registered ');</script>";
        // echo "<script>window.location.href = 'register.php';</script>";
        echo "Error: " . $conn->error; 
        // $errorMessage = $conn->error;  
       

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
   
    <div>
        <h1>TODO Application App</h1>
    <h2>Register</h2>
    <form action="register.php"id="registrationForm" method="POST" onsubmit="return validateForm()">
        <label>Username</label>
        <input type="text" id="username" name="username" required>
        <span class="error" id="usernameError"></span><br>
        <label>Email</label>
        <input type="email"id="email" name="email" required>
        <span class="error" id="emailError"></span><br>
        <label>Password</label>
        <input type="password"id="password" name="password" required>
        <span class="error" id="passwordError"></span><br>
        <button type="submit">Register</button>

      <span>if you are already Register <a href="login.php">Login here</a></span> 
    </form>

    </div>
    <script>
        // Form validation function
        function validateForm() {
            let isValid = true; // To track if the form is valid

       
            let username = document.getElementById("username");
            let email = document.getElementById("email").value;
            let password = document.getElementById("password");

          
          
            document.getElementById("usernameError").innerText = "";
            document.getElementById("emailError").innerText = "";
            document.getElementById("passwordError").innerText = "";

            
            const usernamePattern = /^[A-Za-z]+$/;
            if (!usernamePattern.test(username.value)) {
                document.getElementById("usernameError").innerText = "Username must only contain letters A-Z (no numbers allowed).";
                isValid = false;
            }

          
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
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

            username.onclick=function(){
                usernameError.style.display="none"
                
            }
            return isValid; // Return true if the form is valid, false otherwise
        }
    </script>
</body>
</html>




