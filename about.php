<?php
require_once 'includes/data.php';
$stats = getDashboardStats($conn);
$pageTitle = 'About Bolly Movies';
$heroImage = 'https://images.unsplash.com/photo-1502134249126-9f3755a50d78?auto=format&fit=crop&w=1600&q=80';
include 'includes/header.php';
?>
<section class="metrics-grid scroll-animated">
    <article class="metric-card"><p class="eyebrow">Movies</p><strong><?php echo $stats['movies']; ?></strong><p class="stat-label">Catalog titles stored in MySQL.</p></article>
    <article class="metric-card"><p class="eyebrow">Users</p><strong><?php echo $stats['users']; ?></strong><p class="stat-label">Community members with accounts.</p></article>
    <article class="metric-card"><p class="eyebrow">Wishlist</p><strong><?php echo $stats['wishlist']; ?></strong><p class="stat-label">Wishlist saves across the platform.</p></article>
</section>
<section class="feature-grid scroll-animated">
    <article class="feature-card"><h3>Built for MySQL</h3><p class="card-copy">The project stores users, categories, movies, wishlists, and activity logs in MySQL.</p></article>
    <article class="feature-card"><h3>Safer interface</h3><p class="card-copy">Sensitive credential hints were removed from public sections and replaced with owner contact details.</p></article>
    <article class="feature-card"><h3>Admin tools</h3><p class="card-copy">Admins can add and delete movie records while keeping the movie catalog consistent.</p></article>
</section>
<?php include 'includes/footer.php'; ?>
