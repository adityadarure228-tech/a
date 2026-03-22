<?php
require_once '../includes/auth.php';
require_once '../includes/data.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $releaseYear = (int) ($_POST['release_year'] ?? date('Y'));
    $rating = (float) ($_POST['rating'] ?? 8.0);
    $duration = trim($_POST['duration'] ?? '2h 00m');
    $poster = trim($_POST['poster_url'] ?? '');
    $banner = trim($_POST['banner_url'] ?? '');
    $teaser = trim($_POST['teaser_url'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $featured = isset($_POST['featured']) ? 1 : 0;

    if ($title && $categoryId && $poster && $banner && $teaser && $description) {
        $stmt = mysqli_prepare($conn, 'INSERT INTO movies (title, category_id, release_year, rating, duration, poster_url, banner_url, teaser_url, description, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'siidsssssi', $title, $categoryId, $releaseYear, $rating, $duration, $poster, $banner, $teaser, $description, $featured);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        logActivity($conn, currentUser()['name'], 'admin', 'Added movie: ' . $title);
        flashMessage('New movie added successfully.');
        redirectTo('movies.php');
    }

    flashMessage('Please fill every movie field.', 'danger');
    redirectTo('movies.php');
}

$categories = getCategories($conn);
$movies = getMovies($conn);
$pageTitle = 'Admin Movie Manager';
$heroImage = $movies[0]['banner_url'] ?? 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=1600&q=80';
$basePath = '../';
include '../includes/header.php';
include 'nav.php';
?>
<section class="report-layout scroll-animated">
    <div class="form-card">
        <p class="eyebrow">Admin Form</p>
        <h2>Add Movie</h2>
        <form method="post">
            <div class="form-grid">
                <div class="form-group">
                    <label for="title">Movie Title</label>
                    <input type="text" name="title" id="title" required>
                </div>
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select name="category_id" id="category_id" required>
                        <option value="">Select category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo (int) $category['id']; ?>"><?php echo escape($category['title']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="release_year">Release Year</label>
                    <input type="number" name="release_year" id="release_year" value="2026" required>
                </div>
                <div class="form-group">
                    <label for="rating">Rating</label>
                    <input type="number" step="0.1" name="rating" id="rating" value="8.4" required>
                </div>
                <div class="form-group">
                    <label for="duration">Duration</label>
                    <input type="text" name="duration" id="duration" value="1h 58m" required>
                </div>
                <div class="form-group">
                    <label for="teaser_url">Teaser URL (YouTube embed)</label>
                    <input type="url" name="teaser_url" id="teaser_url" required>
                </div>
                <div class="form-group">
                    <label for="poster_url">Poster Image URL</label>
                    <input type="url" name="poster_url" id="poster_url" required>
                </div>
                <div class="form-group">
                    <label for="banner_url">Banner Image URL</label>
                    <input type="url" name="banner_url" id="banner_url" required>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" required></textarea>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="featured" value="1"> Mark as featured</label>
            </div>
            <button class="primary-btn" type="submit">Add Movie</button>
        </form>
    </div>
    <aside class="highlight-card glass-panel">
        <p class="eyebrow">Admin Credentials</p>
        <h2>Quick Login</h2>
        <p class="section-copy">Email: <strong>adi@gmail.com</strong><br>Password: <strong>123</strong></p>
        <p class="section-copy">All images and movie teasers can use online URLs, so the portal works without local image uploads.</p>
    </aside>
</section>

<section class="dashboard-panel scroll-animated">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Current Catalog</p>
            <h2>Existing Movies</h2>
        </div>
    </div>
    <div class="movie-grid">
        <?php foreach ($movies as $movie): ?>
            <article class="movie-card">
                <div class="movie-card__poster" style="background-image:url('<?php echo escape($movie['poster_url']); ?>')"></div>
                <div class="movie-card__gradient"></div>
                <div class="movie-card__content">
                    <div class="badge-row">
                        <span class="movie-badge"><?php echo escape($movie['category_title']); ?></span>
                        <span class="movie-badge">⭐ <?php echo escape((string) $movie['rating']); ?></span>
                    </div>
                    <h3><?php echo escape($movie['title']); ?></h3>
                    <p class="card-copy"><?php echo escape(substr($movie['description'], 0, 90)); ?>...</p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php include '../includes/footer.php'; ?>
