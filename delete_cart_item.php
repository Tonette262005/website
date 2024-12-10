<?php
session_start();
include 'db.php'; 


if (!isset($_SESSION['name'])) {
    echo "Please log in to delete items.";
    exit;
}


if (isset($_GET['id'])) {
    $item_id = $_GET['id'];
    $username = $_SESSION['name']; 

    
    $query = $conn->prepare("DELETE FROM cart WHERE id = ? AND username = ?");
    $query->bind_param("is", $item_id, $username);
    
    if ($query->execute()) {
        
        header("Location: user_cart.php"); 
        exit(); 
    } else {
        
        echo "Failed to delete the item. Please try again.";
    }

  
    $query->close();
    $conn->close();
} else {
    echo "Item ID is required.";
}
?>
