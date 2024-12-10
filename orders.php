<?php
include 'db.php'; 


$result = $conn->query("SELECT * FROM orders_admin"); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>Manage Orders</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Name</th>
            <th>Address</th>
            <th>Payment Method</th>
            <th>Product Name</th>
            <th>Product Quantity</th>
            <th>Total Price</th>
            <th>Order Date</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr data-id='{$row['id']}'>
                    <td>{$row['id']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['address']}</td>
                    <td>{$row['payment_method']}</td>
                    <td>{$row['product_name']}</td>
                    <td>{$row['product_quantity']}</td>
                    <td>{$row['total_price']}</td>
                    <td>{$row['order_date']}</td>
                </tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
