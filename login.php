<?php 
include 'db.php'; 
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

   
    $sql = "SELECT * FROM Users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        
        $user = $result->fetch_assoc();
        
        
        $_SESSION['name'] = $user['name']; 
        
        
        header("Location: Udashboard.php");
        exit;
    } else {
        
        header("Location: index.php?error=invalid");
        exit; 
    }
}
?>
