<?php
include 'db.php'; 

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $redirect_url = 'your_redirect_page.php';  
   
    if ($action == 'add') {

        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']); 


        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            
            header("Location: Adashboard.php");
            exit();  
        } else {
            echo "Error: " . $conn->error;
        }
    }


    if ($action == 'edit') {

        $user_id = (int)$_POST['user_id'];  
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']); 


        $sql = "UPDATE users SET name = '$name', email = '$email', password = '$password' WHERE id = $user_id";

        if ($conn->query($sql) === TRUE) {

            header("Location: Adashboard.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }


    if ($action == 'delete') {
        $user_id = (int)$_POST['user_id'];  


        $sql = "DELETE FROM users WHERE id = $user_id";

        if ($conn->query($sql) === TRUE) {

            header("Location: Adashboard.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}


$conn->close();
?>
