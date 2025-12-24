<?php
$page_title = "News & Blogs - ICSDI 2026";
ob_start();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <!-- Page Header -->
            <div class="row mb-5">
                <div class="col">
                    <h1 class="fw-bold">News & Blogs</h1>
                    <p class="lead">Stay updated with the latest news, announcements, and insights from ICSDI 2026</p>
                </div>
                <div class="col-auto">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-filter me-2"></i>Filter by Category
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="blogs.php">All News</a></li>
                            <li><a class="dropdown-item" href="blogs.php?category=announcement">Announcements</a></li>
                            <li><a class="dropdown-item" href="blogs.php?category=update">Updates</a></li>
                            <li><a class="dropdown-item" href="blogs.php?category=insight">Insights</a></li>
                            <li><a class="dropdown-item" href="blogs.php?category=speaker">Speaker Spotlights</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Featured Post -->
            <div class="card featured-post mb-5 border-0 shadow-lg">
                <div class="row g-0">
                    <div class="col-md-6">
                        <img src="https://via.placeholder.com/400x300/2c3e50/ffffff?text=Featured+News" class="img-fluid rounded-start h-100 object-fit-cover" alt="Featured News">
                    </div>
                    <div class="col-md-6">
                        <div class="card-body p-4 d-flex flex-column h-100">
                            <span class="badge bg-primary mb-2 align-self-start">Featured</span>
                            <h3 class="card-title fw-bold">Call for Papers Now Open</h3>
                            <p class="card-text flex-grow-1">We are excited to announce that the submission portal for ICSDI 2026 is now open. Researchers and practitioners are invited to submit their papers on sustainable development and innovation.</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="fas fa-calendar me-1"></i>December 1, 2024</small>
                                <a href="blog-detail.php?id=1" class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Blog Posts Grid -->
            <div class="row g-4" id="blog-posts">
                <!-- Blog posts will be loaded here -->
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-5">
                <button id="load-more" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-spinner fa-spin d-none me-2"></i>
                    Load More Articles
                </button>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Search Widget -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Search News</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search articles..." id="blog-search">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Categories Widget -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Categories</h5>
                    <div class="list-group list-group-flush">
                        <a href="blogs.php?category=announcement" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Announcements
                            <span class="badge bg-primary rounded-pill">12</span>
                        </a>
                        <a href="blogs.php?category=update" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Updates
                            <span class="badge bg-primary rounded-pill">8</span>
                        </a>
                        <a href="blogs.php?category=insight" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Insights
                            <span class="badge bg-primary rounded-pill">15</span>
                        </a>
                        <a href="blogs.php?category=speaker" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            Speaker Spotlights
                            <span class="badge bg-primary rounded-pill">6</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Posts Widget -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Recent Posts</h5>
                    <div id="recent-posts">
                        <!-- Recent posts will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Newsletter Subscription -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Stay Updated</h5>
                    <p class="card-text">Subscribe to our newsletter for the latest conference news and updates.</p>
                    <form id="sidebar-newsletter">
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Your email address" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane me-2"></i>Subscribe
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.featured-post {
    transition: transform 0.3s ease;
}

.featured-post:hover {
    transform: translateY(-5px);
}

.blog-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.blog-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.object-fit-cover {
    object-fit: cover;
}

.recent-post-item {
    border-bottom: 1px solid #eee;
    padding: 10px 0;
}

.recent-post-item:last-child {
    border-bottom: none;
}
</style>

<script>
$(document).ready(function() {
    let currentPage = 1;
    let isLoading = false;

    // Load initial blog posts
    loadBlogPosts();

    // Load recent posts
    loadRecentPosts();

    // Load more posts on button click
    $('#load-more').on('click', function() {
        if (!isLoading) {
            currentPage++;
            loadBlogPosts(currentPage);
        }
    });

    // Search functionality
    $('#blog-search').on('input', function() {
        const searchTerm = $(this).val();
        if (searchTerm.length >= 3 || searchTerm.length === 0) {
            currentPage = 1;
            loadBlogPosts(1, searchTerm);
        }
    });

    function loadBlogPosts(page = 1, search = '') {
        isLoading = true;
        const $loadBtn = $('#load-more');
        const $spinner = $loadBtn.find('.fa-spinner');
        
        $spinner.removeClass('d-none');
        $loadBtn.prop('disabled', true);

        $.ajax({
            url: 'api/get_blog_posts.php',
            method: 'GET',
            data: {
                page: page,
                search: search,
                category: getUrlParameter('category')
            },
            success: function(response) {
                if (page === 1) {
                    $('#blog-posts').html(response);
                } else {
                    $('#blog-posts').append(response);
                }

                // Hide load more button if no more posts
                if (response.trim() === '') {
                    $loadBtn.hide();
                }
            },
            error: function() {
                $('#blog-posts').html(`
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h4>Unable to load blog posts</h4>
                        <p>Please try again later.</p>
                    </div>
                `);
            },
            complete: function() {
                isLoading = false;
                $spinner.addClass('d-none');
                $loadBtn.prop('disabled', false);
            }
        });
    }

    function loadRecentPosts() {
        $.ajax({
            url: 'api/get_recent_posts.php',
            method: 'GET',
            success: function(response) {
                $('#recent-posts').html(response);
            }
        });
    }

    function getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Sidebar newsletter subscription
    $('#sidebar-newsletter').on('submit', function(e) {
        e.preventDefault();
        const email = $(this).find('input[type="email"]').val();
        
        $.ajax({
            url: 'api/newsletter_subscribe.php',
            method: 'POST',
            data: { email: email },
            success: function() {
                alert('Thank you for subscribing!');
                $('#sidebar-newsletter')[0].reset();
            },
            error: function() {
                alert('Subscription failed. Please try again.');
            }
        });
    });
});
</script>

<?php
$content = ob_get_clean();
include 'base.php';
?>