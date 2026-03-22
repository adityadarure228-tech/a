<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'movie_recommendation_portal';

$conn = mysqli_connect($host, $username, $password);
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$database`");
mysqli_select_db($conn, $database);
mysqli_set_charset($conn, 'utf8mb4');

$schema = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(120) NOT NULL,
        username VARCHAR(80) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(120) NOT NULL UNIQUE,
        role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        slug VARCHAR(60) NOT NULL UNIQUE,
        title VARCHAR(80) NOT NULL,
        accent_color VARCHAR(20) NOT NULL DEFAULT '#7b61ff',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS movies (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(160) NOT NULL,
        category_id INT NOT NULL,
        release_year INT NOT NULL,
        rating DECIMAL(3,1) NOT NULL DEFAULT 8.0,
        duration VARCHAR(30) NOT NULL,
        poster_url TEXT NOT NULL,
        banner_url TEXT NOT NULL,
        teaser_url TEXT NOT NULL,
        description TEXT NOT NULL,
        featured TINYINT(1) NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS wishlist (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        movie_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_user_movie (user_id, movie_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    "CREATE TABLE IF NOT EXISTS activity_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        actor_name VARCHAR(120) NOT NULL,
        actor_role VARCHAR(30) NOT NULL,
        action_text VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
];

foreach ($schema as $sql) {
    mysqli_query($conn, $sql);
}

$adminEmail = 'adi@gmail.com';
$adminPassword = '123';
$adminHash = password_hash($adminPassword, PASSWORD_DEFAULT);
$adminQuery = mysqli_query($conn, "SELECT id FROM users WHERE email='" . mysqli_real_escape_string($conn, $adminEmail) . "' LIMIT 1");
if (mysqli_num_rows($adminQuery) === 0) {
    $stmt = mysqli_prepare($conn, 'INSERT INTO users (name, username, password, email, role) VALUES (?, ?, ?, ?, ?)');
    $adminName = 'Adi Admin';
    $adminUsername = 'adiadmin';
    $role = 'admin';
    mysqli_stmt_bind_param($stmt, 'sssss', $adminName, $adminUsername, $adminHash, $adminEmail, $role);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

$categorySeeds = [
    ['action', 'Action', '#f8961e'],
    ['romantic', 'Romantic', '#ff70a6'],
    ['thriller', 'Thriller', '#00b4d8'],
    ['drama', 'Drama', '#7b61ff'],
    ['comedy', 'Comedy', '#43aa8b'],
    ['classic', 'Classic', '#9b1c31']
];

foreach ($categorySeeds as $seed) {
    [$slug, $title, $accent] = $seed;
    $check = mysqli_prepare($conn, 'SELECT id FROM categories WHERE slug = ?');
    mysqli_stmt_bind_param($check, 's', $slug);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);
    if (mysqli_stmt_num_rows($check) === 0) {
        $insert = mysqli_prepare($conn, 'INSERT INTO categories (slug, title, accent_color) VALUES (?, ?, ?)');
        mysqli_stmt_bind_param($insert, 'sss', $slug, $title, $accent);
        mysqli_stmt_execute($insert);
        mysqli_stmt_close($insert);
    }
    mysqli_stmt_close($check);
}

function bollyMovieSeeds(): array
{
    return [
        ['Pathaan', 'action', 2023, 7.8, '2h 26m', 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?auto=format&fit=crop&w=1400&q=80', '', 'An exiled field agent returns for a globe-spanning mission that mixes spectacle, emotion, and high-speed action.', 1],
        ['Rocky Aur Rani Kii Prem Kahaani', 'romantic', 2023, 7.1, '2h 48m', 'https://images.unsplash.com/photo-1517602302552-471fe67acf66?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1513106580091-1d82408b8cd6?auto=format&fit=crop&w=1400&q=80', '', 'A lively romance brings two very different families together in a colorful story about love, identity, and acceptance.', 1],
        ['Andhadhun', 'thriller', 2018, 8.2, '2h 19m', 'https://images.unsplash.com/photo-1478720568477-152d9b164e26?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1497015289639-54688650d173?auto=format&fit=crop&w=1400&q=80', '', 'A gifted pianist becomes trapped inside a twisting crime story after witnessing something he was never meant to see.', 1],
        ['Dangal', 'drama', 2016, 8.3, '2h 41m', 'https://images.unsplash.com/photo-1524985069026-dd778a71c7b4?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1502134249126-9f3755a50d78?auto=format&fit=crop&w=1400&q=80', '', 'A determined father trains his daughters to become world-class wrestlers in a story about discipline and ambition.', 0],
        ['Bhool Bhulaiyaa 2', 'comedy', 2022, 6.5, '2h 23m', 'https://images.unsplash.com/photo-1518998053901-5348d3961a04?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1478720568477-152d9b164e26?auto=format&fit=crop&w=1400&q=80', '', 'A fake spiritual healer is pulled into a haunted palace mystery where comedy and suspense collide.', 0],
        ['Sholay', 'classic', 1975, 8.1, '3h 24m', 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1518929458119-e5bf444c30f4?auto=format&fit=crop&w=1400&q=80', '', 'Two outlaws are hired to protect a village, creating one of the most iconic friendships in Indian cinema.', 0]
    ];
}

function seedMovies(mysqli $conn, array $movies): void
{
    foreach ($movies as $movie) {
        [$title, $slug, $year, $rating, $duration, $poster, $banner, $teaser, $description, $featured] = $movie;
        $categoryQuery = mysqli_prepare($conn, 'SELECT id FROM categories WHERE slug = ? LIMIT 1');
        mysqli_stmt_bind_param($categoryQuery, 's', $slug);
        mysqli_stmt_execute($categoryQuery);
        $result = mysqli_stmt_get_result($categoryQuery);
        $categoryId = (int) mysqli_fetch_assoc($result)['id'];
        mysqli_stmt_close($categoryQuery);

        $insertMovie = mysqli_prepare($conn, 'INSERT INTO movies (title, category_id, release_year, rating, duration, poster_url, banner_url, teaser_url, description, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        mysqli_stmt_bind_param($insertMovie, 'siidsssssi', $title, $categoryId, $year, $rating, $duration, $poster, $banner, $teaser, $description, $featured);
        mysqli_stmt_execute($insertMovie);
        mysqli_stmt_close($insertMovie);
    }
}

$movieCountResult = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM movies');
$movieCount = (int) mysqli_fetch_assoc($movieCountResult)['total'];
$legacySeedCheck = mysqli_query($conn, "SELECT id FROM movies WHERE title IN ('Neon Eclipse', 'Velvet Pulse', 'Shadow Protocol', 'Crimson Run') LIMIT 1");
$shouldRefreshSeeds = mysqli_num_rows($legacySeedCheck) > 0;

if ($movieCount === 0 || $shouldRefreshSeeds) {
    mysqli_query($conn, 'SET FOREIGN_KEY_CHECKS=0');
    mysqli_query($conn, 'TRUNCATE TABLE wishlist');
    mysqli_query($conn, 'TRUNCATE TABLE movies');
    mysqli_query($conn, 'SET FOREIGN_KEY_CHECKS=1');
    seedMovies($conn, bollyMovieSeeds());
}

function siteName(): string
{
    return 'Bolly Movies';
}

function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user']);
}

function isAdmin(): bool
{
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

function redirectTo(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function flashMessage(?string $message = null, string $type = 'success'): ?array
{
    if ($message !== null) {
        $_SESSION['flash'] = ['message' => $message, 'type' => $type];
        return null;
    }

    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function logActivity(mysqli $conn, string $actorName, string $actorRole, string $actionText): void
{
    $stmt = mysqli_prepare($conn, 'INSERT INTO activity_logs (actor_name, actor_role, action_text) VALUES (?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'sss', $actorName, $actorRole, $actionText);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function hasPlayableTeaser(array $movie): bool
{
    return trim((string) ($movie['teaser_url'] ?? '')) !== '';
}
?>
