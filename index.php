<?php
require_once 'includes/data.php';
$featuredMovies = getFeaturedMovies($conn, 4);
$allMovies = getMovies($conn);
$pageTitle = '3D Movie Recommendation Experience';
$heroImage = $featuredMovies[0]['banner_url'] ?? 'https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?auto=format&fit=crop&w=1600&q=80';
include 'includes/header.php';
?>
<section class="scroll-animated">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Featured Collection</p>
            <h2>Handpicked Movies For Every Mood</h2>
            <p class="section-copy">Browse powerful recommendation cards with teaser previews, animated gradients, and category shortcuts for horror, thriller, romantic, sci-fi, fantasy, and action.</p>
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
        <p class="eyebrow">Auto Setup</p>
        <h3>Database & tables created in db.php</h3>
        <p class="card-copy">Open the project in XAMPP, and the PHP layer automatically creates the database, user table, categories, movies, wishlist, and activity logs.</p>
    </article>
    <article class="feature-card">
        <p class="eyebrow">Smart Wishlist</p>
        <h3>Login, save, and revisit favorites</h3>
        <p class="card-copy">Users can register with name, username, password, and email, then save movies to a personalized wishlist page.</p>
    </article>
    <article class="feature-card">
        <p class="eyebrow">Admin Control</p>
        <h3>Dedicated panel for movie uploads</h3>
        <p class="card-copy">Admin credentials are pre-seeded so you can login quickly and add new movies, review user activity, and monitor reports.</p>
    </article>
</section>

<section class="scroll-animated">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Trending Movies</p>
            <h2>Card Layout With Hover Teasers</h2>
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
                    <p class="mt-0"><strong>Hover preview</strong></p>
                    <p class="card-copy mb-0">Watch the teaser or add this movie to your wishlist from the detail page.</p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php include 'includes/footer.php'; ?>
