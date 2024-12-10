<?php
include 'db.php'; 

if (!isset($_SESSION['name'])) {
    echo "Please log in to view your cart.";
    exit;
}


$username = $_SESSION['name'];


echo "<h1>Manage Cart for {$username}</h1>";


$query = $conn->prepare("SELECT id, username, product_name, price, quantity, total_price, order_date FROM cart WHERE username = ?");
$query->bind_param("s", $username);
$query->execute();
$result = $query->get_result();

echo '<form id="cart-form" action="checkout.php" method="POST">';
echo '<table border="1">
        <thead>
            <tr>
                <th>Select</th>
                <th>ID</th>
                <th>Username</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Order Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td><input type='checkbox' class='item-checkbox' data-total='{$row['total_price']}' data-product='{$row['product_name']}' data-price='{$row['price']}' data-quantity='{$row['quantity']}' data-order-date='{$row['order_date']}' name='selected_ids[]' value='{$row['id']}'></td>
                <td>{$row['id']}</td>
                <td>{$row['username']}</td>
                <td>{$row['product_name']}</td>
                <td>{$row['price']}</td>
                <td>{$row['quantity']}</td>
                <td>{$row['total_price']}</td>
                <td>{$row['order_date']}</td>
                <td><button type='button' class='delete-btn' data-id='{$row['id']}'>Delete</button></td>
              </tr>";
    }
} else {
    echo '<tr><td colspan="9">No data available in your cart.</td></tr>';
}

echo '</tbody></table>';
echo '<div>
        <h1>Total Price: <span id="total-price">0</span></h1>
        <button type="button" id="checkout-btn">Checkout</button>
      </div>';
echo '</form>';


echo '
<div id="checkout-modal" class="modal">
    <div class="modal-content">
        <span id="close-modal" class="close">&times;</span>
        <h2>Selected Products for Checkout</h2>
        
        <form id="checkout-form" action="checkout.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>
            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="2" cols="50" required></textarea><br><br>
            <label for="payment-method">Payment Method:</label>
            <input type="text" id="payment-method" name="payment-method" value="Cash on Delivery" readonly><br><br>
            
            <input type="hidden" name="username" id="hidden-username" value="' . htmlspecialchars($username) . '">
            <input type="hidden" name="selected-products" id="hidden-selected-products">
        </form>
        
        <table id="modal-items-table" border="1">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody id="modal-items">
                <!-- Selected items will appear here -->
            </tbody>
        </table>
        
        <div>
            <h3>Total: $<span id="modal-total-price">0</span></h3>
            <button type="submit" name="checkout" form="checkout-form">Confirm Checkout</button>
        </div>
    </div>
</div>
';    

echo '
<div id="deleteConfirmModal" class="modal">
    <div class="modal-content">
        <h2>Are you sure you want to delete this product?</h2>
        <p>This action cannot be undone.</p>
        <button id="confirm-delete-btn">Yes, Delete</button>
        <button id="cancel-delete-btn">Cancel</button>
    </div>
</div>

';  


$query->close();
$conn->close();
?>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    const totalPriceElement = document.getElementById('total-price');
    const modal = document.getElementById('checkout-modal');
    const closeModal = document.getElementById('close-modal');
    const checkoutBtn = document.getElementById('checkout-btn');
    const modalItems = document.getElementById('modal-items');
    const modalTotalPrice = document.getElementById('modal-total-price');
    const checkoutForm = document.getElementById('checkout-form');
    
    let total = 0;
    let selectedItems = [];


    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            total = 0;
            selectedItems = [];

            checkboxes.forEach(cb => {
                if (cb.checked) {
                    total += parseFloat(cb.getAttribute('data-total'));
                    selectedItems.push({
                        id: cb.value,
                        product: cb.getAttribute('data-product'),
                        price: cb.getAttribute('data-price'),
                        quantity: cb.getAttribute('data-quantity'),
                        total: cb.getAttribute('data-total'),
                        order_date: cb.getAttribute('data-order-date')
                    });
                }
            });

            totalPriceElement.textContent = total.toFixed(2);
        });
    });


    checkoutBtn.addEventListener('click', () => {
        if (selectedItems.length > 0) {
            modalItems.innerHTML = '';

            selectedItems.forEach(item => {
                const row = document.createElement('tr');

                const productCell = document.createElement('td');
                productCell.textContent = item.product;
                row.appendChild(productCell);

                const priceCell = document.createElement('td');
                priceCell.textContent = item.price;
                row.appendChild(priceCell);

                const quantityCell = document.createElement('td');
                quantityCell.textContent = item.quantity;
                row.appendChild(quantityCell);

                const totalCell = document.createElement('td');
                totalCell.textContent = item.total;
                row.appendChild(totalCell);

                const orderDateCell = document.createElement('td');
                orderDateCell.textContent = item.order_date;
                row.appendChild(orderDateCell);

                modalItems.appendChild(row);
            });

            modalTotalPrice.textContent = total.toFixed(2);
            modal.style.display = 'block';
        } else {
            alert('Please select at least one item to checkout.');
        }
    });


    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    checkoutForm.addEventListener('submit', function (e) {
        if (selectedItems.length === 0) {
            e.preventDefault();
            alert('Please select items to proceed with checkout.');
        } else {
            const selectedProductsData = selectedItems.map(item => {
                return {
                    product: item.product,
                    price: item.price,
                    quantity: item.quantity,
                    total: item.total,
                    order_date: item.order_date
                };
            });

            document.getElementById('hidden-selected-products').value = JSON.stringify(selectedProductsData);
        }
    });


    const deleteButtons = document.querySelectorAll('.delete-btn');
    const deleteConfirmModal = document.getElementById('deleteConfirmModal');
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
    let itemIdToDelete = null;

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {

            itemIdToDelete = this.getAttribute('data-id');


            deleteConfirmModal.style.display = 'block';
        });
    });


    confirmDeleteBtn.addEventListener('click', function () {
        if (itemIdToDelete) {

            window.location.href = `delete_cart_item.php?id=${itemIdToDelete}`;
        }
        deleteConfirmModal.style.display = 'none';
    });


    cancelDeleteBtn.addEventListener('click', function () {
        deleteConfirmModal.style.display = 'none';
    });


    window.addEventListener('click', function (event) {
        if (event.target === deleteConfirmModal) {
            deleteConfirmModal.style.display = 'none';
        }
    });
});

</script>
<style>
    


table {
    width: 100%;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid black;
}

th, td {
    padding: 8px;
    text-align: left;
}

h3 {
    margin-top: 20px;
}


#checkout-form label {
    font-weight: bold;
}

#checkout-form input, #checkout-form textarea {
    width: 100%;
    padding: 8px;
    margin: 5px 0 15px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
}

#checkout-form textarea {
    resize: vertical;
}

#checkout-form button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
}

#checkout-form button:hover {
    background-color: #45a049;
}


.modal {
    display: none; 
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4); 
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border: 1px solid #888;
    width: 60%;
    max-height: 100%;
    overflow-y: auto; 
    border-radius: 8px; 
    box-sizing: border-box;
}


.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    float: right;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

h3 {
    margin-top: 20px;
}
</style>