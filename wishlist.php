<?php
require_once 'includes/auth.php';
require_once 'includes/data.php';
requireLogin();

$user = currentUser();
if (isset($_GET['add'])) {
    $movieId = (int) $_GET['add'];
    $stmt = mysqli_prepare($conn, 'INSERT IGNORE INTO wishlist (user_id, movie_id) VALUES (?, ?)');
    mysqli_stmt_bind_param($stmt, 'ii', $user['id'], $movieId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    logActivity($conn, $user['name'], $user['role'], 'Added movie ID ' . $movieId . ' to wishlist');
    flashMessage('Movie added to wishlist.');
    redirectTo('wishlist.php');
}

if (isset($_GET['remove'])) {
    $wishlistId = (int) $_GET['remove'];
    $stmt = mysqli_prepare($conn, 'DELETE FROM wishlist WHERE id = ? AND user_id = ?');
    mysqli_stmt_bind_param($stmt, 'ii', $wishlistId, $user['id']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    logActivity($conn, $user['name'], $user['role'], 'Removed wishlist item ID ' . $wishlistId);
    flashMessage('Movie removed from wishlist.', 'warning');
    redirectTo('wishlist.php');
}

$wishlist = getUserWishlist($conn, (int) $user['id']);
$pageTitle = 'Your Wishlist';
$heroImage = $wishlist[0]['banner_url'] ?? 'https://images.unsplash.com/photo-1511497584788-876760111969?auto=format&fit=crop&w=1600&q=80';
include 'includes/header.php';
?>
<section class="dashboard-panel scroll-animated">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Saved For Later</p>
            <h2>Your Wishlist</h2>
            <p class="section-copy">Your favorite movies appear here so you can revisit teaser clips and movie details instantly.</p>
        </div>
    </div>
    <?php if ($wishlist): ?>
        <div class="movie-grid">
            <?php foreach ($wishlist as $movie): ?>
                <article class="movie-card scroll-animated">
                    <div class="movie-card__poster" style="background-image:url('<?php echo escape($movie['poster_url']); ?>')"></div>
                    <div class="movie-card__gradient"></div>
                    <div class="movie-card__content">
                        <div class="badge-row">
                            <span class="movie-badge"><?php echo escape($movie['category_title']); ?></span>
                            <span class="movie-badge">⭐ <?php echo escape((string) $movie['rating']); ?></span>
                        </div>
                        <h3><?php echo escape($movie['title']); ?></h3>
                        <p class="card-copy"><?php echo escape(substr($movie['description'], 0, 120)); ?>...</p>
                        <div class="detail-actions">
                            <a class="primary-btn" href="movie-details.php?id=<?php echo (int) $movie['id']; ?>">Details</a>
                            <a class="secondary-btn" href="wishlist.php?remove=<?php echo (int) $movie['wishlist_id']; ?>">Remove</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">No saved movies yet. Browse the catalog and add a movie to your wishlist.</div>
    <?php endif; ?>
</section>
<?php include 'includes/footer.php'; ?>
