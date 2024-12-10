<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';


include 'db.php'; // Database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Homepage</title>
    <link rel="stylesheet" href="styyle.css">
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="?page=dashboard">Dashboard</a></li>
            <li><a href="?page=products">Products</a></li>
            <li><a href="?page=users">Users</a></li>
            <li><a href="?page=orders">Orders</a></li>
            <li><a href="index.php">Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <?php
        // Load the appropriate content based on the page parameter
        switch ($page) {
            case 'products':
                include 'products.php';
                break;
            case 'users':
                include 'users.php';
                break;
            case 'orders':
                include 'orders.php';
                break;
            default:
                include 'dashboard.php';
        }
        ?>
    </div>
</body>
</html>
