<?php
require_once 'includes/data.php';
$slug = $_GET['slug'] ?? 'sci-fi';
$movies = getMovies($conn, $slug);
$categories = getCategories($conn);
$currentCategory = null;
foreach ($categories as $category) {
    if ($category['slug'] === $slug) {
        $currentCategory = $category;
        break;
    }
}
$pageTitle = ($currentCategory['title'] ?? 'Category') . ' Movies';
$heroImage = $movies[0]['banner_url'] ?? 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=1600&q=80';
include 'includes/header.php';
?>
<section class="dashboard-panel scroll-animated">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Category Focus</p>
            <h2><?php echo escape($pageTitle); ?></h2>
            <p class="section-copy">This filtered page highlights category buttons and movie teasers for the selected genre.</p>
        </div>
        <a class="secondary-btn" href="movies.php">Back to Catalog</a>
    </div>
    <?php if ($movies): ?>
        <div class="movie-grid">
            <?php foreach ($movies as $movie): ?>
                <article class="movie-card scroll-animated">
                    <div class="movie-card__poster" style="background-image:url('<?php echo escape($movie['poster_url']); ?>')"></div>
                    <div class="movie-card__gradient"></div>
                    <div class="movie-card__content">
                        <div class="badge-row">
                            <span class="movie-badge"><?php echo escape($movie['category_title']); ?></span>
                            <span class="movie-badge"><?php echo escape((string) $movie['duration']); ?></span>
                        </div>
                        <h3><?php echo escape($movie['title']); ?></h3>
                        <p class="card-copy"><?php echo escape(substr($movie['description'], 0, 100)); ?>...</p>
                        <div class="detail-actions">
                            <a class="primary-btn" href="movie-details.php?id=<?php echo (int) $movie['id']; ?>">Open</a>
                            <a class="secondary-btn" href="teaser.php?id=<?php echo (int) $movie['id']; ?>">Trailer</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">No movies found in this category yet. Use the admin panel to add more titles.</div>
    <?php endif; ?>
</section>
<?php include 'includes/footer.php'; ?>
