<?php
require_once 'includes/data.php';
$featuredMovies = getFeaturedMovies($conn, 4);
$allMovies = getMovies($conn);
$pageTitle = 'Bolly Movies';
$heroImage = $featuredMovies[0]['banner_url'] ?? 'https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?auto=format&fit=crop&w=1600&q=80';
include 'includes/header.php';
?>
<section class="scroll-animated">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Featured Collection</p>
            <h2>Handpicked Bollywood Favorites</h2>
            <p class="section-copy">The homepage now reads movies from MySQL so the catalog, details, and admin changes come from one database source.</p>
        </div>
        <button class="secondary-btn" data-scroll-right>Scroll Right Movies</button>
    </div>
    <div class="reel-track">
        <?php foreach ($featuredMovies as $movie): ?>
            <article class="reel-card">
                <p class="eyebrow"><?php echo escape($movie['category_title']); ?></p>
                <h3><?php echo escape($movie['title']); ?></h3>
                <p class="card-copy"><?php echo escape($movie['description']); ?></p>
                <div class="badge-row">
                    <span class="movie-badge"><?php echo escape((string) $movie['release_year']); ?></span>
                    <span class="movie-badge">⭐ <?php echo escape((string) $movie['rating']); ?></span>
                </div>
                <a class="primary-btn" href="movie-details.php?id=<?php echo (int) $movie['id']; ?>">View Movie</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="feature-grid scroll-animated">
    <article class="feature-card">
        <p class="eyebrow">MySQL Catalog</p>
        <h3>Movie data comes from the database</h3>
        <p class="card-copy">Movies, categories, wishlists, and activity logs are loaded from MySQL instead of a remote movie API.</p>
    </article>
    <article class="feature-card">
        <p class="eyebrow">User Access</p>
        <h3>Simple login and registration flow</h3>
        <p class="card-copy">The main login button now takes users directly to the login page where register and admin login actions are shown under the form.</p>
    </article>
    <article class="feature-card">
        <p class="eyebrow">Admin Control</p>
        <h3>Add and delete movies</h3>
        <p class="card-copy">Admins can create new movie entries, remove old ones, and keep the teaser section aligned with the correct movie card.</p>
    </article>
</section>

<section class="scroll-animated">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Trending Movies</p>
            <h2>Database-Driven Movie Cards</h2>
        </div>
        <a href="movies.php" class="primary-btn">Open Full Catalog</a>
    </div>
    <div class="movie-grid">
        <?php foreach ($allMovies as $movie): ?>
            <article class="movie-card scroll-animated">
                <div class="movie-card__poster" style="background-image:url('<?php echo escape($movie['poster_url']); ?>')"></div>
                <div class="movie-card__gradient"></div>
                <div class="movie-card__content">
                    <div class="badge-row">
                        <span class="movie-badge"><?php echo escape($movie['category_title']); ?></span>
                        <span class="movie-badge"><?php echo escape((string) $movie['release_year']); ?></span>
                    </div>
                    <h3><?php echo escape($movie['title']); ?></h3>
                    <p class="card-copy"><?php echo escape(substr($movie['description'], 0, 110)); ?>...</p>
                    <div class="detail-actions">
                        <a href="movie-details.php?id=<?php echo (int) $movie['id']; ?>" class="primary-btn">View</a>
                        <a href="teaser.php?id=<?php echo (int) $movie['id']; ?>" class="secondary-btn">Teaser</a>
                    </div>
                </div>
                <div class="movie-card__hover">
                    <p class="mt-0"><strong>Movie Preview</strong></p>
                    <p class="card-copy mb-0">Open the movie detail page to see the correct movie summary and teaser status.</p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
