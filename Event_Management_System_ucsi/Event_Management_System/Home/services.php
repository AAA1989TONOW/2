<?php
$page_title = "Events & Services - ICSDI 2026";
$event_type = isset($_GET['type']) ? $_GET['type'] : 'all';
ob_start();
?>

<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col">
            <h1 class="fw-bold display-4">Events & Services</h1>
            <p class="lead">Explore various event types and services available at ICSDI 2026</p>
        </div>
    </div>

    <!-- Event Type Navigation -->
    <div class="row mb-5">
        <div class="col">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <ul class="nav nav-pills justify-content-center" id="eventTypeTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo $event_type === 'all' ? 'active' : ''; ?>" 
                                    data-bs-toggle="pill" data-bs-target="#all-events" type="button">
                                <i class="fas fa-th-large me-2"></i>All Events
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo $event_type === 'conference' ? 'active' : ''; ?>" 
                                    data-bs-toggle="pill" data-bs-target="#conference" type="button">
                                <i class="fas fa-users me-2"></i>Conference
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo $event_type === 'competition' ? 'active' : ''; ?>" 
                                    data-bs-toggle="pill" data-bs-target="#competition" type="button">
                                <i class="fas fa-trophy me-2"></i>Competitions
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo $event_type === 'webinar' ? 'active' : ''; ?>" 
                                    data-bs-toggle="pill" data-bs-target="#webinar" type="button">
                                <i class="fas fa-desktop me-2"></i>Webinars
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo $event_type === 'workshop' ? 'active' : ''; ?>" 
                                    data-bs-toggle="pill" data-bs-target="#workshop" type="button">
                                <i class="fas fa-laptop-code me-2"></i>Workshops
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Content -->
    <div class="tab-content" id="eventTypeTabsContent">
        <!-- All Events Tab -->
        <div class="tab-pane fade <?php echo $event_type === 'all' ? 'show active' : ''; ?>" id="all-events">
            <div class="row g-4" id="all-events-content">
                <!-- All events will be loaded here -->
            </div>
        </div>

        <!-- Conference Tab -->
        <div class="tab-pane fade <?php echo $event_type === 'conference' ? 'show active' : ''; ?>" id="conference">
            <div class="row g-4" id="conference-content">
                <!-- Content loaded via AJAX -->
            </div>
        </div>

        <!-- Competition Tab -->
        <div class="tab-pane fade <?php echo $event_type === 'competition' ? 'show active' : ''; ?>" id="competition">
            <div class="row g-4" id="competition-content">
                <!-- Content loaded via AJAX -->
            </div>
        </div>

        <!-- Webinar Tab -->
        <div class="tab-pane fade <?php echo $event_type === 'webinar' ? 'show active' : ''; ?>" id="webinar">
            <div class="row g-4" id="webinar-content">
                <!-- Content loaded via AJAX -->
            </div>
        </div>

        <!-- Workshop Tab -->
        <div class="tab-pane fade <?php echo $event_type === 'workshop' ? 'show active' : ''; ?>" id="workshop">
            <div class="row g-4" id="workshop-content">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>

    <!-- Dynamic Events will be loaded above -->
</div>

<style>
.object-fit-cover {
    object-fit: cover;
}

.nav-pills .nav-link {
    border-radius: 50px;
    padding: 12px 24px;
    margin: 0 5px;
    transition: all 0.3s ease;
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #2c3e50, #3498db);
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}

.price {
    font-size: 3rem;
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}
</style>

<script>
$(document).ready(function() {
    function loadEvents(type = 'all', containerId = '#all-events-content') {
        $.ajax({
            url: 'api/get_all_events.php',
            method: 'GET',
            data: { type: type },
            success: function(response) {
                $(containerId).html(response);
            },
            error: function() {
                $(containerId).html(`
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h4>Unable to load events</h4>
                        <p>Please try again later.</p>
                    </div>
                `);
            }
        });
    }

    // Initial load for all active tabs
    loadEvents('all', '#all-events-content');
    loadEvents('conference', '#conference-content');
    loadEvents('competition', '#competition-content');
    loadEvents('webinar', '#webinar-content');
    loadEvents('workshop', '#workshop-content');

    // Handle tab changes (optional: reload on click if needed)
    $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).data('bs-target');
        const type = target.replace('#', '');
        const container = target + '-content';
        // loadEvents(type, container); // Already loaded initially
    });
});
</script>

<?php
$content = ob_get_clean();
include 'base.php';
?>