<?php
require_once '../includes/auth.php';
require_once '../includes/data.php';
requireLogin();
$user = currentUser();
$wishlist = getUserWishlist($conn, (int) $user['id']);
$pageTitle = 'Your User Report';
$heroImage = $wishlist[0]['banner_url'] ?? 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?auto=format&fit=crop&w=1600&q=80';
$basePath = '../';
include '../includes/header.php';
?>
<section class="metrics-grid scroll-animated">
    <article class="metric-card">
        <p class="eyebrow">Profile</p>
        <strong><?php echo escape($user['name']); ?></strong>
        <p class="stat-label">Username: <?php echo escape($user['username']); ?></p>
    </article>
    <article class="metric-card">
        <p class="eyebrow">Email</p>
        <strong><?php echo escape($user['email']); ?></strong>
        <p class="stat-label">Role: <?php echo escape($user['role']); ?></p>
    </article>
    <article class="metric-card">
        <p class="eyebrow">Wishlist Count</p>
        <strong><?php echo count($wishlist); ?></strong>
        <p class="stat-label">Saved movies in your account.</p>
    </article>
</section>
<section class="dashboard-panel scroll-animated">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Saved Movies</p>
            <h2>Your Watchlist Summary</h2>
        </div>
    </div>
    <?php if ($wishlist): ?>
        <div class="report-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Movie</th>
                        <th>Category</th>
                        <th>Rating</th>
                        <th>Year</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($wishlist as $movie): ?>
                        <tr>
                            <td><?php echo escape($movie['title']); ?></td>
                            <td><?php echo escape($movie['category_title']); ?></td>
                            <td><?php echo escape((string) $movie['rating']); ?></td>
                            <td><?php echo escape((string) $movie['release_year']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">You have not added any wishlist movies yet.</div>
    <?php endif; ?>
</section>
<?php include '../includes/footer.php'; ?>
