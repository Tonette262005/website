<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FOOTWAREHUB</title>
    <link rel="stylesheet" href="style.css">
    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>FOOTWAREHUB</h1>
            <nav>
                <a href="#" id="open-user-login">User Login</a>
                <a href="#" id="open-admin-login">Admin Login</a>
                <a href="#" id="open-user-registration">Register</a>
            </nav>
        </div>
    </header>
    <main>
        <section id="hero">
            <h2>Welcome to FOOTWAREHUB</h2>
            <p>Your one-stop shop for the latest and trendiest footwear!</p>
        </section>
    </main>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> FOOTWAREHUB. All rights reserved.</p>
    </footer>

    <!-- Modals -->
    <!-- User Login Modal -->
    <div id="user-login-modal" class="modal" role="dialog" aria-labelledby="user-login-title">
        <div class="modal-content">
            <span class="close" data-modal="user-login-modal">&times;</span>
            <h2 id="user-login-title">User Login</h2>
            <form action="login.php" method="POST">
                <label for="user-email">Email:</label>
                <input type="email" id="user-email" name="email" required>
                <label for="user-password">Password:</label>
                <div class="password-container">
                    <input type="password" id="user-password" name="password" required>
                    <i class="fa fa-eye toggle-password" data-target="user-password" style="cursor: pointer;"></i>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
    </div>

    <!-- User Registration Modal -->
    <div id="user-registration-modal" class="modal" role="dialog" aria-labelledby="user-registration-title">
        <div class="modal-content">
            <span class="close" data-modal="user-registration-modal">&times;</span>
            <h2 id="user-registration-title">User Registration</h2>
            <form action="register.php" method="POST">
                <label for="register-name">Name:</label>
                <input type="text" id="register-name" name="name" required>
                <label for="register-email">Email:</label>
                <input type="email" id="register-email" name="email" required>
                <label for="register-password">Password:</label>
                <div class="password-container">
                    <input type="password" id="register-password" name="password" required>
                    <i class="fa fa-eye toggle-password" data-target="register-password" style="cursor: pointer;"></i>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
        </div>
    </div>

    <!-- Admin Login Modal -->
    <div id="admin-login-modal" class="modal" role="dialog" aria-labelledby="admin-login-title">
        <div class="modal-content">
            <span class="close" data-modal="admin-login-modal">&times;</span>
            <h2 id="admin-login-title">Admin Login</h2>
            <form action="admin_login.php" method="POST">
                <label for="admin-email">Email:</label>
                <input type="email" id="admin-email" name="email" required>
                <label for="admin-password">Password:</label>
                <div class="password-container">
                    <input type="password" id="admin-password" name="password" required>
                    <i class="fa fa-eye toggle-password" data-target="admin-password" style="cursor: pointer;"></i>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
    </div>

    <script>
        // Modal functionality
        document.querySelectorAll('.close').forEach(closeButton => {
            closeButton.addEventListener('click', () => {
                const modalId = closeButton.getAttribute('data-modal');
                document.getElementById(modalId).style.display = 'none';
            });
        });

        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const modalId = link.getAttribute('id').replace('open-', '') + '-modal';
                document.getElementById(modalId).style.display = 'block';
            });
        });

        // Password toggle functionality
        document.querySelectorAll('.toggle-password').forEach(toggle => {
            toggle.addEventListener('click', () => {
                const targetId = toggle.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (input.type === 'password') {
                    input.type = 'text';
                    toggle.classList.remove('fa-eye');
                    toggle.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    toggle.classList.remove('fa-eye-slash');
                    toggle.classList.add('fa-eye');
                }
            });
        });
    </script>
</body>
</html>
