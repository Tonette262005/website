<?php
session_start();
include 'db.php'; 


if (isset($_POST['add_to_cart'])) {

    $username = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest';  
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $total_price = $price * $quantity;  
    $order_date = date('Y-m-d H:i:s'); 


    $stmt = $conn->prepare("INSERT INTO cart (username, product_name, price, quantity, total_price, order_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiis", $username, $product_name, $price, $quantity, $total_price, $order_date);


    if ($stmt->execute()) {

        header("Location: Udashboard.php"); 
    } else {

        echo "Error: " . $stmt->error;
    }


    $stmt->close();
    $conn->close();
}
?>
