<?php

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $paymentMethod = $_POST['payment-method'];
    $selectedProducts = json_decode($_POST['selected-products'], true);


    $orderDate = date('Y-m-d H:i:s'); 


    $orderQuery = $conn->prepare("INSERT INTO orders (username, name, address, payment_method, product_name, product_quantity, total_price, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $orderAdminQuery = $conn->prepare("INSERT INTO orders_admin (username, name, address, payment_method, product_name, product_quantity, total_price, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($selectedProducts as $product) {
        $productName = $product['product'];
        $productQuantity = $product['quantity'];
        $totalPrice = $product['total'];


        $orderQuery->bind_param("ssssssis", $username, $name, $address, $paymentMethod, $productName, $productQuantity, $totalPrice, $orderDate);
        $orderQuery->execute();


        $orderAdminQuery->bind_param("ssssssis", $username, $name, $address, $paymentMethod, $productName, $productQuantity, $totalPrice, $orderDate);
        $orderAdminQuery->execute();
    }


    if ($orderQuery->affected_rows > 0 && $orderAdminQuery->affected_rows > 0) {

        header("Location: user_cart.php");
        exit(); 
    } else {
        echo "There was an error processing your order. Please try again.";
    }


    $orderQuery->close();
    $orderAdminQuery->close();
}

$conn->close(); 
?>
