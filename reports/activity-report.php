<?php
require_once '../includes/auth.php';
require_once '../includes/data.php';
requireAdmin();
$activityResult = mysqli_query($conn, 'SELECT actor_name, actor_role, action_text, created_at FROM activity_logs ORDER BY created_at DESC LIMIT 100');
$activities = mysqli_fetch_all($activityResult, MYSQLI_ASSOC);
$pageTitle = 'Activity Report';
$heroImage = 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?auto=format&fit=crop&w=1600&q=80';
$basePath = '../';
include '../includes/header.php';
include '../admin/nav.php';
?>
<section class="dashboard-panel scroll-animated">
    <div class="report-header section-heading">
        <div>
            <p class="eyebrow">Platform Timeline</p>
            <h2>Recent Activity Report</h2>
            <p class="table-subtitle">Monitors account registration, login events, wishlist changes, and movie administration actions.</p>
        </div>
    </div>
    <div class="report-table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Actor</th>
                    <th>Role</th>
                    <th>Action</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activities as $activity): ?>
                    <tr>
                        <td><?php echo escape($activity['actor_name']); ?></td>
                        <td><?php echo escape($activity['actor_role']); ?></td>
                        <td><?php echo escape($activity['action_text']); ?></td>
                        <td><?php echo escape($activity['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include '../includes/footer.php'; ?>
