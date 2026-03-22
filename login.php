<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = mysqli_prepare($conn, 'SELECT * FROM users WHERE email = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($user && $user['role'] === 'user' && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        logActivity($conn, $user['name'], $user['role'], 'Logged in to the movie portal');
        flashMessage('Welcome back, ' . $user['name'] . '!');
        redirectTo('index.php');
    }

    flashMessage('Invalid user login credentials.', 'danger');
    redirectTo('login.php');
}

$pageTitle = 'User Login';
$heroImage = 'https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?auto=format&fit=crop&w=1600&q=80';
include 'includes/header.php';
?>
<section class="form-card scroll-animated">
    <p class="eyebrow">Member Access</p>
    <h2>User Login</h2>
    <p class="form-hint">Login to save Bollywood movies in your wishlist and access your account pages.</p>
    <form method="post">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="button-row login-actions">
            <button class="primary-btn" type="submit">Login</button>
            <a href="register.php" class="secondary-btn">Register</a>
            <a href="admin-login.php" class="secondary-btn">Admin Login</a>
        </div>
    </form>
</section>
<?php include 'includes/footer.php'; ?>
