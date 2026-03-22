<?php
require_once '../includes/auth.php';
require_once '../includes/data.php';
requireAdmin();
$usersResult = mysqli_query($conn, "SELECT name, username, email, role, created_at FROM users ORDER BY created_at DESC");
$users = mysqli_fetch_all($usersResult, MYSQLI_ASSOC);
$pageTitle = 'Users Report';
$heroImage = 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1600&q=80';
$basePath = '../';
include '../includes/header.php';
include '../admin/nav.php';
?>
<section class="dashboard-panel scroll-animated">
    <div class="report-header section-heading">
        <div>
            <p class="eyebrow">User Analytics</p>
            <h2>User Details Report</h2>
            <p class="table-subtitle">Includes registered names, usernames, emails, roles, and signup timestamps.</p>
        </div>
    </div>
    <div class="report-table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo escape($user['name']); ?></td>
                        <td><?php echo escape($user['username']); ?></td>
                        <td><?php echo escape($user['email']); ?></td>
                        <td><?php echo escape($user['role']); ?></td>
                        <td><?php echo escape($user['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include '../includes/footer.php'; ?>
