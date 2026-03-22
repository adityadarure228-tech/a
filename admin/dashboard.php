<?php
require_once '../includes/auth.php';
require_once '../includes/data.php';
requireAdmin();
$stats = getDashboardStats($conn);
$latestMovies = array_slice(getMovies($conn), 0, 6);
$pageTitle = 'Admin Dashboard';
$heroImage = $latestMovies[0]['banner_url'] ?? 'https://images.unsplash.com/photo-1524985069026-dd778a71c7b4?auto=format&fit=crop&w=1600&q=80';
$basePath = '../';
include '../includes/header.php';
include 'nav.php';
?>
<section class="metrics-grid scroll-animated">
    <article class="metric-card">
        <p class="eyebrow">Total Movies</p>
        <strong><?php echo $stats['movies']; ?></strong>
        <p class="stat-label">Movies available in all categories.</p>
    </article>
    <article class="metric-card">
        <p class="eyebrow">Registered Users</p>
        <strong><?php echo $stats['users']; ?></strong>
        <p class="stat-label">Users who can maintain wishlists.</p>
    </article>
    <article class="metric-card">
        <p class="eyebrow">Wishlist Entries</p>
        <strong><?php echo $stats['wishlist']; ?></strong>
        <p class="stat-label">Saved selections across all users.</p>
    </article>
    <article class="metric-card">
        <p class="eyebrow">Active Categories</p>
        <strong><?php echo $stats['categories']; ?></strong>
        <p class="stat-label">Buttons available in the website header.</p>
    </article>
</section>

<section class="dashboard-panel scroll-animated">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Quick Actions</p>
            <h2>Manage Platform Content</h2>
        </div>
    </div>
    <div class="link-grid">
        <a class="quick-link feature-card" href="movies.php">
            <h3>Add or review movies</h3>
            <p class="card-copy">Open the movie management area to seed fresh recommendations.</p>
        </a>
        <a class="quick-link feature-card" href="../reports/users-report.php">
            <h3>Inspect user records</h3>
            <p class="card-copy">View names, usernames, emails, and registration timestamps.</p>
        </a>
        <a class="quick-link feature-card" href="../reports/activity-report.php">
            <h3>Audit recent actions</h3>
            <p class="card-copy">Track admin and user interactions inside the portal.</p>
        </a>
    </div>
</section>

<section class="dashboard-panel scroll-animated">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Newest Titles</p>
            <h2>Recently Available Movies</h2>
        </div>
    </div>
    <div class="movie-grid">
        <?php foreach ($latestMovies as $movie): ?>
            <article class="movie-card">
                <div class="movie-card__poster" style="background-image:url('<?php echo escape($movie['poster_url']); ?>')"></div>
                <div class="movie-card__gradient"></div>
                <div class="movie-card__content">
                    <div class="badge-row">
                        <span class="movie-badge"><?php echo escape($movie['category_title']); ?></span>
                        <span class="movie-badge"><?php echo escape((string) $movie['release_year']); ?></span>
                    </div>
                    <h3><?php echo escape($movie['title']); ?></h3>
                    <a class="primary-btn" href="../movie-details.php?id=<?php echo (int) $movie['id']; ?>">Preview</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php include '../includes/footer.php'; ?>
