<?php
$page_title = "ICSDI Institute of Computer Science and Digital Innovation";
$hide_header = false;
ob_start();
?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">ICSDI 2026</h1>
                <h2 class="h3 mb-4">Institute of Computer Science and Digital Innovation</h2>
                <p class="lead mb-4">Join leading researchers, practitioners, and innovators from around the world to discuss sustainable development challenges and solutions.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="register.php?action=register" class="btn btn-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Register Now
                    </a>
                    <a href="about_us.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-info-circle me-2"></i>Learn More
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-image">
                    <i class="fas fa-globe-americas display-1 text-warning"></i>
                    <div class="mt-4">
                        <p class="h5"><i class="fas fa-map-marker-alt me-2"></i>ucsi</p>
                        <p class="h5"><i class="fas fa-calendar-alt me-2"></i>March 15-17, 2026</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Conference Schedule Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col">
                <h2 class="fw-bold">Conference Schedule</h2>
                <p class="lead">Plan your visit with our detailed session timeline</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="schedule-timeline">
                    <?php
                    $db = require_once(__DIR__ . '/../Database/database.php');
                    $sessions = $db->query("
                        SELECT s.*, e.title as event_title 
                        FROM sessions s 
                        JOIN events e ON s.event_id = e.event_id 
                        ORDER BY s.start_time ASC 
                        LIMIT 6
                    ")->fetchAll(PDO::FETCH_ASSOC);

                    if (empty($sessions)) {
                        echo '<div class="text-center py-4"><p class="text-muted">Schedule will be announced soon. Stay tuned!</p></div>';
                    } else {
                        foreach ($sessions as $session) {
                            $time = date('H:i', strtotime($session['start_time'])) . ' - ' . date('H:i', strtotime($session['end_time']));
                            $date = date('M d, Y', strtotime($session['start_time']));
                    ?>
                            <div class="schedule-item d-flex mb-4">
                                <div class="schedule-time pe-4 text-end border-end border-primary border-3" style="min-width: 150px;">
                                    <h5 class="fw-bold text-primary mb-0"><?php echo $time; ?></h5>
                                    <small class="text-muted"><?php echo $date; ?></small>
                                </div>
                                <div class="schedule-content ps-4">
                                    <span class="badge bg-primary mb-2"><?php echo ucfirst($session['session_type']); ?></span>
                                    <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($session['title']); ?></h5>
                                    <p class="text-muted mb-0 small"><i class="fas fa-map-marker-alt me-1 text-danger"></i><?php echo htmlspecialchars($session['room']); ?> | <i class="fas fa-calendar-alt me-1 text-info"></i><?php echo htmlspecialchars($session['event_title']); ?></p>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.schedule-item {
    transition: transform 0.3s ease;
}
.schedule-item:hover {
    transform: translateX(10px);
}
.schedule-time {
    border-right: 3px solid #0d6efd !important;
}
</style>

<!-- Event Types -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col">
                <h2 class="fw-bold">Event Types</h2>
                <p class="lead">Multiple ways to participate and engage</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card event-card h-100 border-0 shadow-hover">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Conference</h5>
                        <p class="card-text">Main academic conference with paper presentations and keynotes.</p>
                        <a href="services.php?type=conference" class="btn btn-outline-primary">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card event-card h-100 border-0 shadow-hover">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-trophy fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Competitions</h5>
                        <p class="card-text">Innovation challenges and student competitions.</p>
                        <a href="services.php?type=competition" class="btn btn-outline-warning">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card event-card h-100 border-0 shadow-hover">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-desktop fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Webinars</h5>
                        <p class="card-text">Virtual sessions and online workshops.</p>
                        <a href="services.php?type=webinar" class="btn btn-outline-info">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card event-card h-100 border-0 shadow-hover">
                    <div class="card-body text-center p-4">
                        <i class="fas fa-laptop-code fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Workshops</h5>
                        <p class="card-text">Hands-on training and skill development sessions.</p>
                        <a href="services.php?type=workshop" class="btn btn-outline-success">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Countdown Timer -->
<section class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col">
                <h3 class="fw-bold mb-4">Conference Starts In</h3>
                <div id="countdown" class="d-flex justify-content-center gap-3 flex-wrap">
                    <div class="countdown-item">
                        <div class="countdown-number" id="days">00</div>
                        <div class="countdown-label">Days</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-number" id="hours">00</div>
                        <div class="countdown-label">Hours</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-number" id="minutes">00</div>
                        <div class="countdown-label">Minutes</div>
                    </div>
                    <div class="countdown-item">
                        <div class="countdown-number" id="seconds">00</div>
                        <div class="countdown-label">Seconds</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest News -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold">Latest News & Updates</h2>
                <p class="lead">Stay informed about conference developments</p>
            </div>
            <div class="col-auto">
                <a href="blogs.php" class="btn btn-primary">View All News</a>
            </div>
        </div>
        <div class="row g-4" id="latest-news">
            <!-- News items will be loaded via AJAX -->
        </div>
    </div>
</section>

<!-- Registration CTA -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Ready to Join ICSDI 2026?</h2>
        <p class="lead mb-4">Register now to secure your spot at the premier sustainable development conference</p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="index.php?action=register" class="btn btn-light btn-lg">
                <i class="fas fa-user-plus me-2"></i>Register as Attendee
            </a>
            <a href="login.php?action=register&type=author" class="btn btn-outline-light btn-lg">
                <i class="fas fa-file-upload me-2"></i>Submit Paper
            </a>
        </div>
    </div>
</section>

<style>
.hero-section {
    background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
}

.icon-wrapper {
    transition: transform 0.3s ease;
}

.card:hover .icon-wrapper {
    transform: scale(1.1);
}

.event-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.event-card:hover {
    transform: translateY(-5px);
}

.shadow-hover {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.event-card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.countdown-item {
    text-align: center;
    padding: 20px;
    min-width: 100px;
}

.countdown-number {
    font-size: 2.5rem;
    font-weight: bold;
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 5px;
}

.countdown-label {
    font-size: 0.9rem;
    opacity: 0.8;
}
</style>

<script>
$(document).ready(function() {
    // Countdown timer
    function updateCountdown() {
        const conferenceDate = new Date('2026-03-15T09:00:00').getTime();
        const now = new Date().getTime();
        const distance = conferenceDate - now;

        if (distance > 0) {
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            $('#days').text(days.toString().padStart(2, '0'));
            $('#hours').text(hours.toString().padStart(2, '0'));
            $('#minutes').text(minutes.toString().padStart(2, '0'));
            $('#seconds').text(seconds.toString().padStart(2, '0'));
        }
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();

    // Load latest news
    $.ajax({
        url: 'api/get_latest_news.php',
        method: 'GET',
        success: function(response) {
            $('#latest-news').html(response);
        },
        error: function() {
            $('#latest-news').html(`
                <div class="col-12 text-center">
                    <p>Unable to load news. Please check back later.</p>
                </div>
            `);
        }
    });
});
</script>

<?php
$content = ob_get_clean();
include 'base.php';
?>