<?php
require_once 'includes/data.php';
$movies = getMovies($conn);
$pageTitle = 'All Bolly Movies';
$heroImage = $movies[0]['banner_url'] ?? null;
include 'includes/header.php';
?>
<section class="dashboard-panel scroll-animated">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Movie Library</p>
            <h2>Explore Every Category</h2>
            <p class="section-copy">Browse movies loaded from MySQL and open the correct detail or teaser page for each title.</p>
        </div>
    </div>
    <div class="movie-grid">
        <?php foreach ($movies as $movie): ?>
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
                        <a href="movie-details.php?id=<?php echo (int) $movie['id']; ?>" class="primary-btn">Details</a>
                        <a href="teaser.php?id=<?php echo (int) $movie['id']; ?>" class="secondary-btn">Teaser</a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
