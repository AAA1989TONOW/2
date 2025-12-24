<?php
// Mock news data since we don't have a blogs/news table in the database.php yet
// In a real scenario, you'd fetch this from a 'news' or 'blogs' table.

$news_items = [
    [
        'title' => 'ICSDI 2026 Call for Papers',
        'date' => 'Dec 22, 2025',
        'category' => 'Announcement',
        'excerpt' => 'Submit your latest research in sustainable development and innovation for the 2026 conference.',
        'image' => 'https://via.placeholder.com/400x250/2c3e50/ffffff?text=Call+for+Papers'
    ],
    [
        'title' => 'Keynote Speakers Announced',
        'date' => 'Dec 15, 2025',
        'category' => 'Event',
        'excerpt' => 'Join us in welcoming world-renowned experts who will be sharing insights on renewable energy.',
        'image' => 'https://via.placeholder.com/400x250/3498db/ffffff?text=Keynote+Speakers'
    ],
    [
        'title' => 'Early Bird Registration Open',
        'date' => 'Dec 10, 2025',
        'category' => 'Registration',
        'excerpt' => 'Register before January 30th to take advantage of our discounted early bird rates.',
        'image' => 'https://via.placeholder.com/400x250/27ae60/ffffff?text=Registration+Open'
    ]
];

foreach ($news_items as $news) {
    echo '
    <div class="col-lg-4 col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <img src="' . $news['image'] . '" class="card-img-top" alt="' . htmlspecialchars($news['title']) . '">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="badge bg-light text-primary">' . $news['category'] . '</span>
                    <small class="text-muted">' . $news['date'] . '</small>
                </div>
                <h5 class="card-title fw-bold">' . htmlspecialchars($news['title']) . '</h5>
                <p class="card-text text-muted">' . htmlspecialchars($news['excerpt']) . '</p>
                <a href="blogs.php" class="btn btn-link text-primary p-0 fw-bold text-decoration-none">Read More <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>';
}
?>
