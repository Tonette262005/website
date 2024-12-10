<?php
include 'db.php';


$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <style>

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            max-width: 90%;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        h2 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-size: 16px;
            font-weight: bold;
        }

        .form-row {
            display: flex;
            gap: 15px;
            justify-content: space-between;
        }
        .form-row > div {
            flex: 1;
        }
        input[type="text"], input[type="number"], input[type="file"], select {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }
        button[type="submit"], button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button[type="submit"]:hover, button:hover {
            background-color: #45a049;
        }
        .closeBtn {
            background-color: #f44336;
            margin-top: 10px;
        }
        .closeBtn:hover {
            background-color: #e53935;
        }
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


        @media (max-width: 600px) {
            .form-row {
                flex-direction: column;
            }
            .form-row > div {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<h1>Manage Products</h1>
<button onclick="openAddProductModal()">Add Product</button>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Category</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr data-id='{$row['id']}'>
                    <td>{$row['id']}</td>
                    <td class='product-name'>{$row['name']}</td>
                    <td class='product-price'>{$row['price']}</td>
                    <td class='product-category'>{$row['category']}</td>
                    <td><img src='{$row['image_url']}' style='width:50px;height:50px;'></td>
                    <td>
                        <button onclick='openEditProductModal({$row['id']})'>Edit</button>
                        <button onclick='confirmDeleteProduct({$row['id']})'>Delete</button>
                    </td>
                </tr>";
        }
        ?>
    </tbody>
</table>


<div id="addProductModal" class="modal">
    <div class="modal-content">
        <h2>Add Product</h2>
        <form id="addProductForm" method="POST" action="process2.php" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            
            <div class="form-row">
                <div>
                    <label for="name">Product Name:</label>
                    <input type="text" name="name" id="name" required><br><br>
                </div>
                <div>
                    <label for="price">Price:</label>
                    <input type="number" name="price" id="price" required><br><br>
                </div>
            </div>
            
            <div class="form-row">
                <div>
                    <label for="category">Category:</label>
                    <select name="category" id="category" required>
                        <option value="" disabled selected>Select Category</option>
                        <option value="Casual">Casual</option>
                        <option value="Formal">Formal</option>
                        <option value="Sports">Sports</option>
                    </select><br><br>
                </div>
                <div>
                    <label for="image">Image:</label>
                    <input type="file" class="form-control" id="image" name="image" required><br><br>
                </div>
            </div>

            <button type="submit">Submit</button>
        </form>
        <button onclick="closeAddProductModal()" class="closeBtn">Close</button>
    </div>
</div>


<div id="editProductModal" class="modal">
    <div class="modal-content">
        <h2>Edit Product</h2>
        <form id="editProductForm" method="POST" action="process2.php" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="product_id" id="edit_product_id">
            
            <div class="form-row">
                <div>
                    <label for="edit_name">Product Name:</label>
                    <input type="text" name="name" id="edit_name" required><br><br>
                </div>
                <div>
                    <label for="edit_price">Price:</label>
                    <input type="number" name="price" id="edit_price" required><br><br>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label for="edit_category">Category:</label>
                    <select name="category" id="edit_category" required>
                        <option value="Casual">Casual</option>
                        <option value="Formal">Formal</option>
                        <option value="Sports">Sports</option>
                    </select><br><br>
                </div>
                <div>
                    <label for="edit_image">Image:</label>
                    <input type="file" name="image" id="edit_image"><br><br>
                </div>
            </div>

            <label for="edit_image_preview">Current Image:</label>
            <img id="edit_image_preview" src="" alt="Product Image" style="width: 100px; height: 100px; display:none;"><br><br>

            <button type="submit">Submit</button>
        </form>
        <button onclick="closeEditProductModal()" class="closeBtn">Close</button>
    </div>
</div>


<div id="deleteConfirmModal" class="modal">
    <div class="modal-content">
        <h2>Are you sure you want to delete this product?</h2>
        <p>This action cannot be undone.</p>
        <button onclick="deleteProduct()">Yes, Delete</button>
        <button onclick="closeDeleteModal()">Cancel</button>
    </div>
</div>

<script>
    let productIdToDelete = null;

    function confirmDeleteProduct(productId) {
        productIdToDelete = productId;
        document.getElementById("deleteConfirmModal").style.display = "flex";
    }

    function closeDeleteModal() {
        document.getElementById("deleteConfirmModal").style.display = "none";
        productIdToDelete = null;
    }

    function deleteProduct() {
        if (productIdToDelete !== null) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'process2.php';

            const inputAction = document.createElement('input');
            inputAction.type = 'hidden';
            inputAction.name = 'action';
            inputAction.value = 'delete';
            form.appendChild(inputAction);

            const inputProductId = document.createElement('input');
            inputProductId.type = 'hidden';
            inputProductId.name = 'product_id';
            inputProductId.value = productIdToDelete;
            form.appendChild(inputProductId);

            document.body.appendChild(form);
            form.submit();
        }
    }

    function openEditProductModal(productId) {
        const row = document.querySelector(`tr[data-id='${productId}']`);
        const name = row.querySelector(".product-name").textContent;
        const price = row.querySelector(".product-price").textContent;
        const category = row.querySelector(".product-category").textContent;
        const image = row.querySelector("img").src;


        document.getElementById("edit_product_id").value = productId;
        document.getElementById("edit_name").value = name;
        document.getElementById("edit_price").value = price;
        document.getElementById("edit_category").value = category;


        const imagePreview = document.getElementById("edit_image_preview");
        imagePreview.src = image;  
        imagePreview.style.display = "block"; 

        document.getElementById("editProductModal").style.display = "flex";
    }

    function closeEditProductModal() {
        document.getElementById("editProductModal").style.display = "none";
    }

    function openAddProductModal() {
        document.getElementById("addProductModal").style.display = "flex";
    }

    function closeAddProductModal() {
        document.getElementById("addProductModal").style.display = "none";
    }
</script>

</body>
</html>
