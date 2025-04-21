<?php
    $conn = new mysqli("localhost", "root", "", "librarydb");
    if($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
        
    }
?>