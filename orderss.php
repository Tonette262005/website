<?php

include 'db.php'; 

if (!isset($_SESSION['name'])) {
    echo "Please log in to view your cart.";
    exit;
}


$username = $_SESSION['name'];

if (isset($_POST['delete_all'])) {
    $deleteQuery = "DELETE FROM orders WHERE username = ?"; 
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("s", $username);
    
    if ($stmt->execute()) {
        ("Location: user_cart.php");
    } else {
        echo "Error deleting orders: " . $conn->error;
    }
    $stmt->close();
}


$query = $conn->prepare("SELECT * FROM orders WHERE username = ?"); 
$query->bind_param("s", $username); 
$query->execute();
$result = $query->get_result();

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
        
       
        #delete-all-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        #delete-all-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

<h1>Manage Orders for <?php echo htmlspecialchars($username); ?></h1>


<form method="POST" style="position: absolute; top: 20px; right: 20px;">
    <button type="submit" id="delete-all-btn" name="delete_all">Delete All</button>
</form>

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

<?php

$query->close();
$conn->close();
?>
