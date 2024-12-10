<?php
include 'db.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];


    $sql = "SELECT * FROM Admins WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        session_start();
        $_SESSION['admin'] = $email; 
        header("Location: Adashboard.php");
        exit;
    } else {
       
        header("Location: adminlogin.php?error=invalid_credentials");
        exit; 
    }
}
?>
