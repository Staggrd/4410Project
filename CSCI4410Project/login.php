<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"]; //takes password and username from login form

    $stmt = $conn->prepare("SELECT user_id, password, role FROM users WHERE username = ?"); //get user_id, password and role from username
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hashed_password, $role);
        $stmt->fetch();
        if (password_verify($password, $hashed_password) && $role == "Student") {
            $_SESSION["user_id"] = $id;
            header("Location: dashboardStudent.php");
            exit;
        }
        else if (password_verify($password, $hashed_password) && $role == "Faculty") {
            $_SESSION["user_id"] = $id;
            header("Location: dashboardFaculty.php");
            exit;
        }
        else if (password_verify($password, $hashed_password) && $role == "Admin") {
            $_SESSION["user_id"] = $id;
            header("Location: dashboardAdmin.php");
            exit;
        }
    }
    echo "Invalid username or password.";
}
?>

<link rel="stylesheet" href="style.css">
<form method="post">
    <h2>Login</h2>
    Username: <input name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form><br>
<a href='register.php'>Register</a>