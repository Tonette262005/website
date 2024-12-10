<?php
include 'db.php'; 


$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            max-width: 90%;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
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
        input[type="text"], input[type="email"], input[type="password"] {
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

        @media (max-width: 768px) {
            .modal-content {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<h1>Manage Users</h1>
<button onclick="openAddUserModal()">Add User</button>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Password</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr data-id='{$row['id']}'>
                    <td>{$row['id']}</td>
                    <td class='user-name'>{$row['name']}</td>
                    <td class='user-email'>{$row['email']}</td>
                    <td class='user-password'>{$row['password']}</td>
                    <td>{$row['created_at']}</td>
                    <td>
                        <button onclick='openEditUserModal({$row['id']})'>Edit</button>
                        <button onclick='confirmDeleteUser({$row['id']})'>Delete</button>
                    </td>
                </tr>";
        }
        ?>
    </tbody>

</table>

<div id="addUserModal" class="modal">
    <div class="modal-content">
        <h2 id="modalTitle">Add User</h2>
        <form id="addUserForm" method="POST" action="process1.php">
            <input type="hidden" name="action" value="add"> <!-- Action for adding user -->
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required><br><br>
            
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br><br>
            
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br><br>
            
            <button type="submit">Submit</button>
        </form>
        <button onclick="closeAddUserModal()" class="closeBtn">Close</button>
    </div>
</div>





<div id="editUserModal" class="modal">
    <div class="modal-content">
        <h2 id="editModalTitle">Edit User</h2>
        <form id="editUserForm" method="POST" action="process1.php">
            <label for="edit_user_id">ID:</label>
            <input type="text" name="user_id" id="edit_user_id" readonly><br><br> 
            
            <label for="edit_name">Name:</label>
            <input type="text" name="name" id="edit_name" required><br><br>
            
            <label for="edit_email">Email:</label>
            <input type="email" name="email" id="edit_email" required><br><br>
            
            <label for="edit_password">Password:</label>
            <input type="password" name="password" id="edit_password" required><br><br>
            
            <button type="submit" name="action" value="edit">Submit</button>
        </form>
        <button onclick="closeEditUserModal()" class="closeBtn">Close</button>
    </div>
</div>


<div id="deleteConfirmModal" class="modal">
    <div class="modal-content">
        <h2>Are you sure you want to delete this user?</h2>
        <p>This action cannot be undone.</p>
        <button onclick="deleteUser()">Yes, Delete</button>
        <button onclick="closeDeleteModal()">Cancel</button>
    </div>
</div>

<script>

    let userIdToDelete = null;


    function confirmDeleteUser(userId) {
        userIdToDelete = userId;  
        document.getElementById("deleteConfirmModal").style.display = "flex"; 
    }


    function closeDeleteModal() {
        document.getElementById("deleteConfirmModal").style.display = "none"; 
        userIdToDelete = null; 
    }


    function deleteUser() {
        if (userIdToDelete !== null) {
 
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'process1.php'; 

            const inputAction = document.createElement('input');
            inputAction.type = 'hidden';
            inputAction.name = 'action';
            inputAction.value = 'delete';
            form.appendChild(inputAction);

            const inputUserId = document.createElement('input');
            inputUserId.type = 'hidden';
            inputUserId.name = 'user_id';
            inputUserId.value = userIdToDelete;
            form.appendChild(inputUserId);

            document.body.appendChild(form);
            form.submit(); 
        }
    }
</script>


<script>

    function openEditUserModal(userId) {
        const row = document.querySelector(`tr[data-id="${userId}"]`);
        const name = row.querySelector(".user-name").textContent;
        const email = row.querySelector(".user-email").textContent;
        const password = row.querySelector(".user-password").textContent;

        document.getElementById("edit_user_id").value = userId; 
        document.getElementById("edit_name").value = name;
        document.getElementById("edit_email").value = email;
        document.getElementById("edit_password").value = password;

        document.getElementById("editUserModal").style.display = "flex";
    }


    function closeEditUserModal() {
        document.getElementById("editUserModal").style.display = "none";
    }


    function openAddUserModal() {
        document.getElementById("addUserForm").reset();
        document.getElementById("modalTitle").textContent = "Add User";
        document.getElementById("addUserModal").style.display = "flex";
    }

    function closeAddUserModal() {
        document.getElementById("addUserModal").style.display = "none";
    }
</script>

</body>


</html>
