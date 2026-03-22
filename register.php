<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name && $username && $password && $email) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'user';
        $stmt = mysqli_prepare($conn, 'INSERT INTO users (name, username, password, email, role) VALUES (?, ?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'sssss', $name, $username, $hash, $email, $role);
        if (mysqli_stmt_execute($stmt)) {
            logActivity($conn, $name, 'user', 'Registered a new account');
            flashMessage('Registration complete. Please login.');
            mysqli_stmt_close($stmt);
            redirectTo('login.php');
        }
        mysqli_stmt_close($stmt);
    }

    flashMessage('Registration failed. Email or username may already exist.', 'danger');
    redirectTo('register.php');
}

$pageTitle = 'Register New User';
$heroImage = 'https://images.unsplash.com/photo-1497032628192-86f99bcd76bc?auto=format&fit=crop&w=1600&q=80';
include 'includes/header.php';
?>
<section class="form-card scroll-animated">
    <p class="eyebrow">Create Account</p>
    <h2>Register</h2>
    <p class="form-hint">Provide your name, username, password, and email to start building your personal movie wishlist.</p>
    <form method="post">
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>
        </div>
        <div class="button-row">
            <button class="primary-btn" type="submit">Register</button>
            <a href="login.php" class="secondary-btn">Back to Login</a>
        </div>
    </form>
</section>
<?php include 'includes/footer.php'; ?>
