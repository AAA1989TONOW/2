<footer class="bg-dark text-light pt-5 pb-4">
    <div class="container">
        <div class="row">
            <!-- Conference Information -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="text-uppercase fw-bold mb-3">
                    <i class="fas fa-globe-americas me-2"></i>ICSDI 2026
                </h5>
                <p class="mb-3">
                    Institute of Computer Science and Digital Innovation<br>
                    ucsi
                </p>
                <div class="d-flex">
                    <a href="#" class="text-light me-3">
                        <i class="fab fa-twitter fa-lg"></i>
                    </a>
                    <a href="#" class="text-light me-3">
                        <i class="fab fa-linkedin fa-lg"></i>
                    </a>
                    <a href="#" class="text-light me-3">
                        <i class="fab fa-facebook fa-lg"></i>
                    </a>
                    <a href="#" class="text-light">
                        <i class="fab fa-instagram fa-lg"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="text-uppercase fw-bold mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="index.php" class="text-light text-decoration-none">
                            <i class="fas fa-chevron-right me-1 small"></i> Home
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="about_us.php" class="text-light text-decoration-none">
                            <i class="fas fa-chevron-right me-1 small"></i> About
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="services.php" class="text-light text-decoration-none">
                            <i class="fas fa-chevron-right me-1 small"></i> Events
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="blogs.php" class="text-light text-decoration-none">
                            <i class="fas fa-chevron-right me-1 small"></i> News
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Event Types -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase fw-bold mb-3">Event Types</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="services.php?type=conference" class="text-light text-decoration-none">
                            <i class="fas fa-users me-2"></i>Main Conference
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="services.php?type=competition" class="text-light text-decoration-none">
                            <i class="fas fa-trophy me-2"></i>Competitions
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="services.php?type=webinar" class="text-light text-decoration-none">
                            <i class="fas fa-desktop me-2"></i>Webinars
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="services.php?type=workshop" class="text-light text-decoration-none">
                            <i class="fas fa-laptop-code me-2"></i>Workshops
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase fw-bold mb-3">Contact Info</h5>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        <span>ucsi<br></span>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-envelope me-2"></i>
                        <a href="mailto:info@icsdi2026.org" class="text-light text-decoration-none">
                            info@icsdi2026.org
                        </a>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-phone me-2"></i>
                        <a href="" class="text-light text-decoration-none">
                            +966 11 234 5678
                        </a>
                    </li>
                    <li class="mb-3">
                        <i class="fas fa-clock me-2"></i>
                        <span>Mon - Fri: 9:00 - 17:00</span>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="bg-light mb-4">

        <!-- Newsletter Subscription -->
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h6 class="text-uppercase fw-bold mb-2">Subscribe to our newsletter</h6>
                <p class="mb-0 small">Get the latest updates about ICSDI 2026 events and announcements.</p>
            </div>
            <div class="col-md-6">
                <form class="d-flex" id="newsletterForm">
                    <input type="email" class="form-control me-2" placeholder="Your email address" required>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>

        <hr class="bg-light mt-4 mb-4">

        <!-- Bottom Footer -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 small">
                    &copy; 2024 ICSDI Conference. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="privacy.php" class="text-light text-decoration-none small me-3">Privacy Policy</a>
                <a href="terms.php" class="text-light text-decoration-none small me-3">Terms of Service</a>
                <a href="contact_us.php" class="text-light text-decoration-none small">Support</a>
            </div>
        </div>
    </div>
</footer>

<!-- JavaScript for interactive elements -->
<script>
$(document).ready(function() {
    // Newsletter form submission
    $('#newsletterForm').on('submit', function(e) {
        e.preventDefault();
        const email = $(this).find('input[type="email"]').val();
        
        // Simulate API call
        $.ajax({
            url: 'api/newsletter_subscribe.php',
            method: 'POST',
            data: { email: email },
            success: function(response) {
                alert('Thank you for subscribing to our newsletter!');
                $('#newsletterForm')[0].reset();
            },
            error: function() {
                alert('Subscription failed. Please try again.');
            }
        });
    });

    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(event) {
        const target = $(this.getAttribute('href'));
        if (target.length) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 70
            }, 1000);
        }
    });

    // Add active class to current page in navigation
    const currentPage = window.location.pathname.split('/').pop();
    $('.navbar-nav .nav-link').each(function() {
        const linkPage = $(this).attr('href');
        if (linkPage === currentPage) {
            $(this).addClass('active');
            $(this).append('<span class="visually-hidden">(current)</span>');
        }
    });
});
</script>