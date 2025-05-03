<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $role = $_POST["role"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Username already taken.";
    }

    else {
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, phone, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $password, $email, $phone, $role);

        try{
          $stmt->execute();
          echo "Registered successfully!";

        }
        catch (mysqli_sql_exception $e){
          echo "Error Registering" . $stmt->error;
        }

        $stmt = $conn->prepare("INSERT INTO users (username, password, email, phone, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $password, $email, $phone, $role);
    }
}

?>

<link rel="stylesheet" href="style.css">
<form method="post">
    <h2>Register</h2>
    Username: <input name="username" required><br>
    Password: <input type="password" name="password" required><br>
    Email: <input type="email" name="email" required><br>
    Phone: <input name="phone" required><br>
    Role: <select name="role" id="role">
        <option value="Student">Student</option>
        <option value="Faculty">Faculty</option>
        <option value="Admin">Admin</option>
    </select><br>
    <button type="submit">Register</button>
</form>
<br>
<a href='login.php'>Login</a>