<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/data.php';
$categories = getCategories($conn);
$flash = flashMessage();
$user = currentUser();
$pageTitle = $pageTitle ?? siteName();
$heroImage = $heroImage ?? 'https://images.unsplash.com/photo-1518929458119-e5bf444c30f4?auto=format&fit=crop&w=1600&q=80';
$basePath = $basePath ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape($pageTitle); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Orbitron:wght@500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $basePath; ?>assets/css/style.css">
</head>
<body>
    <div class="app-shell">
        <div class="bg-blur bg-blur-top"></div>
        <div class="bg-blur bg-blur-bottom"></div>
        <header class="site-header glass-panel">
            <div class="brand-block">
                <a href="<?php echo $basePath; ?>index.php" class="brand-mark">
                    <span class="brand-mark__orb"></span>
                    <span>
                        <strong>CineVerse</strong>
                        <small>Movie Recommendation System</small>
                    </span>
                </a>
            </div>
            <nav class="primary-nav">
                <a href="<?php echo $basePath; ?>index.php">Home</a>
                <a href="<?php echo $basePath; ?>movies.php">Movies</a>
                <a href="<?php echo $basePath; ?>wishlist.php">Wishlist</a>
                <?php if ($user): ?>
                    <a href="<?php echo $basePath; ?>reports/user-report.php">Reports</a>
                <?php endif; ?>
            </nav>
            <div class="header-actions">
                <div class="dropdown">
                    <button class="pill-btn pill-btn--ghost">Login</button>
                    <div class="dropdown-menu glass-panel">
                        <a href="<?php echo $basePath; ?>login.php">User Login</a>
                        <a href="<?php echo $basePath; ?>register.php">Register</a>
                        <a href="<?php echo $basePath; ?>admin-login.php">Admin Login</a>
                    </div>
                </div>
                <?php if ($user): ?>
                    <span class="user-chip"><?php echo escape($user['name']); ?></span>
                    <a href="<?php echo $basePath; ?>logout.php" class="pill-btn">Logout</a>
                <?php endif; ?>
            </div>
        </header>

        <section class="hero-banner" style="--hero-image:url('<?php echo escape($heroImage); ?>')">
            <div class="hero-banner__overlay"></div>
            <div class="hero-banner__content">
                <p class="eyebrow">Unlimited Recommendations</p>
                <h1><?php echo escape($pageTitle); ?></h1>
                <p class="hero-copy">Discover premium sci-fi, horror, thriller, romantic, fantasy, and action movies with cinematic visuals, teaser previews, and a modern admin-ready dashboard.</p>
                <div class="category-pills">
                    <?php foreach ($categories as $category): ?>
                        <a href="<?php echo $basePath; ?>category.php?slug=<?php echo escape($category['slug']); ?>" class="category-pill" style="--accent:<?php echo escape($category['accent_color']); ?>">
                            <?php echo escape($category['title']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="hero-banner__art" id="floatingHeroArt">
                <div class="hero-cube hero-cube--one"></div>
                <div class="hero-cube hero-cube--two"></div>
                <div class="hero-cube hero-cube--three"></div>
            </div>
        </section>

        <main class="page-content">
            <?php if ($flash): ?>
                <div class="alert alert--<?php echo escape($flash['type']); ?>"><?php echo escape($flash['message']); ?></div>
            <?php endif; ?>
