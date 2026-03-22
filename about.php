<?php
require_once 'includes/data.php';
$stats = getDashboardStats($conn);
$pageTitle = 'About CineVerse';
$heroImage = 'https://images.unsplash.com/photo-1502134249126-9f3755a50d78?auto=format&fit=crop&w=1600&q=80';
include 'includes/header.php';
?>
<section class="metrics-grid scroll-animated">
    <article class="metric-card"><p class="eyebrow">Movies</p><strong><?php echo $stats['movies']; ?></strong><p class="stat-label">Catalog titles seeded automatically.</p></article>
    <article class="metric-card"><p class="eyebrow">Users</p><strong><?php echo $stats['users']; ?></strong><p class="stat-label">Community members with accounts.</p></article>
    <article class="metric-card"><p class="eyebrow">Wishlist</p><strong><?php echo $stats['wishlist']; ?></strong><p class="stat-label">Wishlist saves across the platform.</p></article>
</section>
<section class="feature-grid scroll-animated">
    <article class="feature-card"><h3>Modern stack</h3><p class="card-copy">Built for XAMPP with PHP, MySQL, HTML, CSS, and JavaScript.</p></article>
    <article class="feature-card"><h3>Online imagery</h3><p class="card-copy">Every poster and hero image uses online URLs as requested.</p></article>
    <article class="feature-card"><h3>Rich navigation</h3><p class="card-copy">Includes user login, admin login, registration, reports, movie details, and wishlists.</p></article>
</section>
<?php include 'includes/footer.php'; ?>
