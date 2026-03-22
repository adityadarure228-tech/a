<?php
require_once 'includes/data.php';
$movieId = (int) ($_GET['id'] ?? 1);
$movie = getMovieById($conn, $movieId);
if (!$movie) {
    flashMessage('Teaser not available.', 'danger');
    redirectTo('movies.php');
}
$pageTitle = 'Teaser: ' . $movie['title'];
$heroImage = $movie['banner_url'];
$hasTeaser = hasPlayableTeaser($movie);
include 'includes/header.php';
?>
<section class="report-layout scroll-animated">
    <div class="teaser-frame glass-panel">
        <?php if ($hasTeaser): ?>
            <iframe src="<?php echo escape($movie['teaser_url']); ?>?autoplay=1&amp;mute=1" title="<?php echo escape($movie['title']); ?> teaser clip" allowfullscreen></iframe>
        <?php else: ?>
            <div class="teaser-placeholder teaser-placeholder--full">
                <img src="<?php echo escape($movie['banner_url']); ?>" alt="<?php echo escape($movie['title']); ?> banner">
                <div>
                    <p class="eyebrow">Teaser Pending</p>
                    <h2><?php echo escape($movie['title']); ?></h2>
                    <p class="section-copy">This movie currently has no teaser URL saved, so the page shows the correct movie information instead of a mismatched video.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <aside class="highlight-card glass-panel">
        <p class="eyebrow">Now Playing</p>
        <h2><?php echo escape($movie['title']); ?></h2>
        <p class="section-copy"><?php echo escape($movie['description']); ?></p>
        <div class="detail-meta">
            <span class="stat-pill"><?php echo escape($movie['category_title']); ?></span>
            <span class="stat-pill">⭐ <?php echo escape((string) $movie['rating']); ?></span>
            <span class="stat-pill"><?php echo escape($movie['duration']); ?></span>
        </div>
        <div class="teaser-actions">
            <a href="movie-details.php?id=<?php echo (int) $movie['id']; ?>" class="primary-btn">Back to Details</a>
            <a href="movies.php" class="secondary-btn">More Movies</a>
        </div>
    </aside>
</section>
<?php include 'includes/footer.php'; ?>
