<?php
require_once 'includes/data.php';
$movieId = (int) ($_GET['id'] ?? 1);
$movie = getMovieById($conn, $movieId);
if (!$movie) {
    flashMessage('Movie not found.', 'danger');
    redirectTo('movies.php');
}
$relatedMovies = array_filter(getMovies($conn, $movie['category_slug']), fn($item) => (int) $item['id'] !== $movieId);
$pageTitle = $movie['title'];
$heroImage = $movie['banner_url'];
$hasTeaser = hasPlayableTeaser($movie);
include 'includes/header.php';
?>
<section class="detail-layout scroll-animated">
    <div>
        <article class="detail-hero">
            <div class="detail-hero__bg" style="background-image:url('<?php echo escape($movie['banner_url']); ?>')"></div>
            <div class="detail-hero__overlay"></div>
            <div class="detail-hero__content">
                <div class="badge-row">
                    <span class="movie-badge"><?php echo escape($movie['category_title']); ?></span>
                    <span class="movie-badge"><?php echo escape((string) $movie['release_year']); ?></span>
                    <span class="movie-badge">⭐ <?php echo escape((string) $movie['rating']); ?></span>
                    <span class="movie-badge"><?php echo escape($movie['duration']); ?></span>
                </div>
                <h2><?php echo escape($movie['title']); ?></h2>
                <p class="section-copy"><?php echo escape($movie['description']); ?></p>
                <div class="detail-actions">
                    <a class="primary-btn" href="teaser.php?id=<?php echo (int) $movie['id']; ?>"><?php echo $hasTeaser ? 'Watch Teaser' : 'View Teaser Status'; ?></a>
                    <?php if (isLoggedIn() && !isAdmin()): ?>
                        <a class="secondary-btn" href="wishlist.php?add=<?php echo (int) $movie['id']; ?>">Add to Wishlist</a>
                    <?php else: ?>
                        <a class="secondary-btn" href="login.php">Login for Wishlist</a>
                    <?php endif; ?>
                </div>
            </div>
        </article>
    </div>
    <aside class="side-reel glass-panel">
        <h3 class="mt-0">More In <?php echo escape($movie['category_title']); ?></h3>
        <?php foreach (array_slice($relatedMovies, 0, 4) as $related): ?>
            <a class="side-reel__item" href="movie-details.php?id=<?php echo (int) $related['id']; ?>">
                <img src="<?php echo escape($related['poster_url']); ?>" alt="<?php echo escape($related['title']); ?>">
                <span>
                    <strong><?php echo escape($related['title']); ?></strong>
                    <small class="meta-copy"><?php echo escape(substr($related['description'], 0, 70)); ?>...</small>
                </span>
            </a>
        <?php endforeach; ?>
    </aside>
</section>

<section class="teaser-frame glass-panel scroll-animated">
    <?php if ($hasTeaser): ?>
        <iframe src="<?php echo escape($movie['teaser_url']); ?>" title="<?php echo escape($movie['title']); ?> teaser" allowfullscreen></iframe>
    <?php else: ?>
        <div class="teaser-placeholder">
            <img src="<?php echo escape($movie['poster_url']); ?>" alt="<?php echo escape($movie['title']); ?> poster">
            <div>
                <p class="eyebrow">Teaser Update</p>
                <h3><?php echo escape($movie['title']); ?></h3>
                <p class="section-copy">A teaser has not been attached to this movie yet, which avoids showing a mismatched video under the wrong movie title.</p>
            </div>
        </div>
    <?php endif; ?>
</section>
<?php include 'includes/footer.php'; ?>
