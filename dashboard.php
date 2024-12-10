<?php

include 'db.php'; 


$total_users_query = "SELECT COUNT(*) as total FROM users";
$total_products_query = "SELECT COUNT(*) as total FROM products";
$total_revenue_query = "SELECT SUM(total_price) as total_revenue FROM orders";

$total_users = $conn->query($total_users_query)->fetch_assoc()['total'];
$total_products = $conn->query($total_products_query)->fetch_assoc()['total'];
$total_revenue = $conn->query($total_revenue_query)->fetch_assoc()['total_revenue']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Add Font Awesome link for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
 
        .dashboard-cards {
            display: flex;
            gap: 20px;
        }
        .card {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card i {
            font-size: 40px; /* Icon size */
            color: white    ; /* Icon color */
            margin-bottom: 10px;
        }
        .card h3 {
            margin: 10px 0;
            font-size: 40px;
        }
        .card p {
            font-size: 40px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="dashboard-cards">
    <!-- Total Users Card -->
    <div class="card">
        <i class="fas fa-users"></i> 
        <h3>Total Users</h3>
        <p><?= $total_users; ?></p>
    </div>
    
    
    <div class="card">
        <i class="fas fa-box"></i> 
        <h3>Total Products</h3>
        <p><?= $total_products; ?></p>
    </div>
    
    
    <div class="card">
        <i class="fas fa-dollar-sign"></i> 
        <h3>Total Revenue</h3>
        <p>$<?= number_format($total_revenue, 2); ?></p> 
    </div>
</div>

</body>
</html>

<?php

$conn->close();
?>
