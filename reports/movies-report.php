<?php
require_once '../includes/auth.php';
require_once '../includes/data.php';
requireAdmin();
$movies = getMovies($conn);
$pageTitle = 'Movies Report';
$heroImage = 'https://images.unsplash.com/photo-1517602302552-471fe67acf66?auto=format&fit=crop&w=1600&q=80';
$basePath = '../';
include '../includes/header.php';
include '../admin/nav.php';
?>
<section class="dashboard-panel scroll-animated">
    <div class="report-header section-heading">
        <div>
            <p class="eyebrow">Movie Analytics</p>
            <h2>Movies Inventory Report</h2>
            <p class="table-subtitle">Track movie titles, categories, years, ratings, duration, and featured status.</p>
        </div>
    </div>
    <div class="report-table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Year</th>
                    <th>Rating</th>
                    <th>Duration</th>
                    <th>Featured</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movies as $movie): ?>
                    <tr>
                        <td><?php echo escape($movie['title']); ?></td>
                        <td><?php echo escape($movie['category_title']); ?></td>
                        <td><?php echo escape((string) $movie['release_year']); ?></td>
                        <td><?php echo escape((string) $movie['rating']); ?></td>
                        <td><?php echo escape($movie['duration']); ?></td>
                        <td><?php echo $movie['featured'] ? 'Yes' : 'No'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include '../includes/footer.php'; ?>
