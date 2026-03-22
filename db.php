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
    ['horror', 'Horror', '#9b1c31'],
    ['thriller', 'Thriller', '#00b4d8'],
    ['romantic', 'Romantic', '#ff70a6'],
    ['sci-fi', 'Sci-Fi', '#7b61ff'],
    ['fantasy', 'Fantasy', '#43aa8b'],
    ['action', 'Action', '#f8961e']
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

$movieCountResult = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM movies');
$movieCount = (int) mysqli_fetch_assoc($movieCountResult)['total'];

if ($movieCount === 0) {
    $movies = [
        ['Neon Eclipse', 'sci-fi', 2025, 8.9, '2h 08m', 'https://images.unsplash.com/photo-1534447677768-be436bb09401?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1502134249126-9f3755a50d78?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/5PSNL1qE6VY', 'A renegade astronaut races through a collapsing wormhole to rewrite the fate of humanity with an AI companion who knows too much.', 1],
        ['Velvet Pulse', 'romantic', 2024, 8.2, '1h 49m', 'https://images.unsplash.com/photo-1517602302552-471fe67acf66?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1513106580091-1d82408b8cd6?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/8ugaeA-nMTc', 'A music producer and an architect fall in love while rebuilding a forgotten riverside theater full of memories.', 0],
        ['Midnight Howl', 'horror', 2023, 7.8, '1h 57m', 'https://images.unsplash.com/photo-1509347528160-9a9e33742cdb?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/smTK_AeAPHs', 'After moving into a forest observatory, a family uncovers a ritual that awakens every full moon.', 0],
        ['Shadow Protocol', 'thriller', 2025, 8.5, '2h 01m', 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1518929458119-e5bf444c30f4?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/gCcx85zbxz4', 'A forensic analyst decodes hidden messages in blockbuster trailers and stumbles onto a conspiracy targeting world leaders.', 1],
        ['Skyforge Realm', 'fantasy', 2024, 8.1, '2h 14m', 'https://images.unsplash.com/photo-1511497584788-876760111969?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/2SvwX3ux_-8', 'A swordsmith with forgotten magic must reunite broken kingdoms under a sky filled with floating citadels.', 0],
        ['Crimson Run', 'action', 2026, 8.4, '1h 54m', 'https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1524985069026-dd778a71c7b4?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/JfVOs4VSpmA', 'An elite courier crosses megacities at impossible speeds to stop a black-market weapon auction.', 1],
        ['Starlit Ruins', 'sci-fi', 2026, 8.0, '2h 05m', 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1519608487953-e999c86e7455?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/4UZrsTqkcW4', 'Explorers discover an ancient alien library buried beneath a desert city and awaken a celestial defense system.', 0],
        ['Electric Hearts', 'romantic', 2025, 7.9, '1h 52m', 'https://images.unsplash.com/photo-1497032628192-86f99bcd76bc?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1485846234645-a62644f84728?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/C0DPdy98e4c', 'Two coders competing in a futuristic hackathon keep finding secret love notes inside each other’s code.', 0],
        ['House of Echoes', 'horror', 2025, 7.7, '1h 43m', 'https://images.unsplash.com/photo-1518998053901-5348d3961a04?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1478720568477-152d9b164e26?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/hA6hldpSTF8', 'A sound designer discovers that every whisper in a cursed mansion predicts a future tragedy.', 0],
        ['Noir Circuit', 'thriller', 2026, 8.6, '2h 11m', 'https://images.unsplash.com/photo-1478720568477-152d9b164e26?auto=format&fit=crop&w=800&q=80', 'https://images.unsplash.com/photo-1497015289639-54688650d173?auto=format&fit=crop&w=1400&q=80', 'https://www.youtube.com/embed/7TavVZMewpY', 'A cybercrime journalist follows a string of smart-city blackouts to a sentient financial algorithm.', 1]
    ];

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

function siteName(): string
{
    return 'CineVerse Recommender';
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
?>
