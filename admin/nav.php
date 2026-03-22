<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/data.php';
requireAdmin();
?>
<nav class="admin-nav glass-panel">
    <a href="dashboard.php">Dashboard</a>
    <a href="movies.php">Manage Movies</a>
    <a href="../reports/users-report.php">User Report</a>
    <a href="../reports/movies-report.php">Movie Report</a>
    <a href="../reports/activity-report.php">Activity Report</a>
    <a href="../logout.php">Logout</a>
</nav>
