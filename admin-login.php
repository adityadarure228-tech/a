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

    if ($user && $user['role'] === 'admin' && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        logActivity($conn, $user['name'], $user['role'], 'Logged in to admin panel');
        flashMessage('Admin login successful.');
        redirectTo('admin/dashboard.php');
    }

    flashMessage('Invalid admin credentials. Please contact the owner for access.', 'danger');
    redirectTo('admin-login.php');
}

$pageTitle = 'Admin Login';
$heroImage = 'https://images.unsplash.com/photo-1518929458119-e5bf444c30f4?auto=format&fit=crop&w=1600&q=80';
include 'includes/header.php';
?>
<section class="form-card scroll-animated">
    <p class="eyebrow">Administrator Access</p>
    <h2>Admin Login</h2>
    <p class="form-hint">Only authorized administrators should sign in here to add or delete movies from the MySQL catalog.</p>
    <form method="post">
        <div class="form-group">
            <label for="email">Admin Email</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="button-row login-actions">
            <button class="primary-btn" type="submit">Login as Admin</button>
            <a href="login.php" class="secondary-btn">User Login</a>
        </div>
    </form>
</section>
<?php include 'includes/footer.php'; ?>
