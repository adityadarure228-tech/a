<?php
require_once __DIR__ . '/../db.php';

function getCategories(mysqli $conn): array
{
    $result = mysqli_query($conn, 'SELECT * FROM categories ORDER BY title ASC');
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getFeaturedMovies(mysqli $conn, int $limit = 4): array
{
    $result = mysqli_query($conn, 'SELECT m.*, c.title AS category_title, c.slug AS category_slug, c.accent_color FROM movies m JOIN categories c ON c.id = m.category_id WHERE m.featured = 1 ORDER BY m.created_at DESC LIMIT ' . $limit);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getMovies(mysqli $conn, ?string $categorySlug = null): array
{
    if ($categorySlug) {
        $stmt = mysqli_prepare($conn, 'SELECT m.*, c.title AS category_title, c.slug AS category_slug, c.accent_color FROM movies m JOIN categories c ON c.id = m.category_id WHERE c.slug = ? ORDER BY m.release_year DESC, m.rating DESC');
        mysqli_stmt_bind_param($stmt, 's', $categorySlug);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $movies = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $movies;
    }

    $result = mysqli_query($conn, 'SELECT m.*, c.title AS category_title, c.slug AS category_slug, c.accent_color FROM movies m JOIN categories c ON c.id = m.category_id ORDER BY m.featured DESC, m.release_year DESC, m.rating DESC');
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getMovieById(mysqli $conn, int $movieId): ?array
{
    $stmt = mysqli_prepare($conn, 'SELECT m.*, c.title AS category_title, c.slug AS category_slug, c.accent_color FROM movies m JOIN categories c ON c.id = m.category_id WHERE m.id = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'i', $movieId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $movie = mysqli_fetch_assoc($result) ?: null;
    mysqli_stmt_close($stmt);
    return $movie;
}

function getUserWishlist(mysqli $conn, int $userId): array
{
    $stmt = mysqli_prepare($conn, 'SELECT w.id AS wishlist_id, m.*, c.title AS category_title, c.slug AS category_slug, c.accent_color FROM wishlist w JOIN movies m ON m.id = w.movie_id JOIN categories c ON c.id = m.category_id WHERE w.user_id = ? ORDER BY w.created_at DESC');
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $items;
}

function getDashboardStats(mysqli $conn): array
{
    $stats = [];
    $queries = [
        'movies' => 'SELECT COUNT(*) AS total FROM movies',
        'users' => "SELECT COUNT(*) AS total FROM users WHERE role = 'user'",
        'wishlist' => 'SELECT COUNT(*) AS total FROM wishlist',
        'categories' => 'SELECT COUNT(*) AS total FROM categories'
    ];

    foreach ($queries as $key => $query) {
        $result = mysqli_query($conn, $query);
        $stats[$key] = (int) mysqli_fetch_assoc($result)['total'];
    }

    return $stats;
}
?>
