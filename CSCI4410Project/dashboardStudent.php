<link rel="stylesheet" href="style.css">
<?php
    session_start();
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }
    echo "<h2>Welcome! You are logged in to your student account.</h2>";
    echo "<a href='logout.php'>Logout</a>";
?>