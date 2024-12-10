<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Cart</title>
    <link rel="stylesheet" href="styyle.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <h3>
            <?php if (isset($_SESSION['name'])): ?>
                Hello, <?php echo htmlspecialchars($_SESSION['name']); ?>
            <?php else: ?>
                Your Cart
            <?php endif; ?>
        </h3>

        <ul>

            <li><a href="Udashboard.php"><i class="fas fa-arrow-left"></i> Back</a></li>  
            

            <li><a href="?page=cart"><i class="fas fa-shopping-cart"></i> Cart</a></li>
            

            <li><a href="?page=orders"><i class="fas fa-box"></i> Orders</a></li>
        </ul>
    </div>

    <div class="content">
        <?php

        $page = isset($_GET['page']) ? $_GET['page'] : 'cart';


        switch ($page) {
            case 'orders':
                include 'orderss.php';
                break;
            default:
                include 'cart.php';
        }
        ?>
    </div>
</body>
</html>
