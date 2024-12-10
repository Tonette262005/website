<?php
session_start();
include 'db.php';


$category_filter = "";
if (isset($_GET['category'])) {
    $category_filter = $_GET['category'];
}


if ($category_filter) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE category = ?");
    $stmt->bind_param("s", $category_filter);
} else {
    $stmt = $conn->prepare("SELECT * FROM products");
}
$stmt->execute();
$result = $stmt->get_result();

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];


    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {

        $_SESSION['cart'][$product_id] = 1;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Store</title>
    <link rel="stylesheet" href="styyyle.css">
</head>
<body>

<div class="header">
    <div class="profile-menu">
        <div class="profile-icon">
            <img src="https://cdn-icons-png.flaticon.com/512/147/147144.png" alt="Profile" />
            <h4>
                <?php if (isset($_SESSION['name'])): ?>
                    <?php echo $_SESSION['name']; ?>
                <?php else: ?>
                    Guest
                <?php endif; ?>
            </h4>
        </div>
    </div>

    <div class="nav-menu">
        <a href="user_cart.php">
            <button class="nav-btn">Cart / Orders</button>
        </a>
        <a href="index.php">
            <button class="nav-btn">Logout</button>
        </a>
    </div>
</div>

<div class="filters">
    <a href="?category=formal"><button class="filter-btn">Formal</button></a>
    <a href="?category=casual"><button class="filter-btn">Casual</button></a>
    <a href="?category=sports"><button class="filter-btn">Sport</button></a>
    <a href="?"><button class="filter-btn">All</button></a>
</div>

<div class="product-grid">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="product-card">
            <img src="<?php echo $row['image_url']; ?>" alt="Shoe Image">
            <div class="name"><?php echo $row['name']; ?></div>
            <div class="price">Php<?php echo $row['price']; ?></div>
            <button class="buy-btn" 
                    onclick="showProductModal(
                        '<?php echo $row['image_url']; ?>', 
                        '<?php echo $row['name']; ?>', 
                        '<?php echo $row['price']; ?>', 
                        '<?php echo $row['id']; ?>'
                    )">
                Buy Product
            </button>
        </div>
    <?php endwhile; ?>
</div>

<div class="modal" id="product-modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        

        <div class="modal-username">
            <h4>
                <?php 

                echo isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest'; 
                ?>
            </h4>
        </div>
        
        <img id="modal-product-image" src="" alt="Product Image" style="width: 100%; border-radius: 10px;">
        <h3 id="modal-product-name"></h3>
        <p id="modal-product-price"></p>
        <form id="modal-add-to-cart-form" method="post" action="process3.php">
            <input type="hidden" id="modal-product-id" name="product_id" value="">
            <input type="hidden" id="modal-product-name-hidden" name="product_name" value="">
            <input type="hidden" id="modal-product-price-hidden" name="price" value="">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" value="1" style="width: 50px;">
            <button type="submit" class="buy-btn" name="add_to_cart">Add to Cart</button>
        </form>
    </div>
</div>




<script>

    function showProductModal(imageUrl, name, price, id) {

        document.getElementById('modal-product-image').src = imageUrl;
        document.getElementById('modal-product-name').textContent = name;
        document.getElementById('modal-product-price').textContent = "Php " + price;
        document.getElementById('modal-product-id').value = id;
        document.getElementById('modal-product-name-hidden').value = name;
        document.getElementById('modal-product-price-hidden').value = price;


        document.getElementById('product-modal').style.display = 'flex';
    }


    function closeModal() {
        document.getElementById('product-modal').style.display = 'none';
    }


    window.onclick = function(event) {

        var productModal = document.getElementById('product-modal');
        if (event.target === productModal) {
            closeModal();
        }
    }
</script>

</body>
</html>
