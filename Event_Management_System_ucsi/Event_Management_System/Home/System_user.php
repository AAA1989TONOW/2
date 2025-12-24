<?php
session_start();
$db = require_once(__DIR__ . '/../Database/database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_type = $_SESSION['user_type'];

// Fetch user's registrations
$registrations = $db->prepare("
    SELECT r.*, e.title, e.event_type, e.start_date, e.venue
    FROM registrations r
    JOIN events e ON r.event_id = e.event_id
    WHERE r.user_id = ?
");
$registrations->execute([$user_id]);
$my_events = $registrations->fetchAll(PDO::FETCH_ASSOC);

// Handle Event Registration Action
if (isset($_GET['action']) && $_GET['action'] === 'register') {
    $event_id = $_GET['event_id'] ?? null;
    $event_type_req = $_GET['type'] ?? null;

    if (!$event_id && $event_type_req) {
        // Find the first event of this type
        $find = $db->prepare("SELECT event_id FROM events WHERE event_type = ? AND status='published' LIMIT 1");
        $find->execute([$event_type_req]);
        $event_id = $find->fetchColumn();
    }

    if ($event_id) {
        $check = $db->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
        $check->execute([$user_id, $event_id]);
        if ($check->rowCount() == 0) {
            $ev = $db->prepare("SELECT registration_fee, currency FROM events WHERE event_id = ?");
            $ev->execute([$event_id]);
            $ed = $ev->fetch();
            if ($ed) {
                $ins = $db->prepare("INSERT INTO registrations (user_id, event_id, registration_type, payment_status, payment_amount, payment_currency) VALUES (?,?,'attendee','pending',?,?)");
                $ins->execute([$user_id, $event_id, $ed['registration_fee'], $ed['currency']]);
                $_SESSION['msg'] = ["success", "Successfully registered for the event!"];
            }
        } else {
            $_SESSION['msg'] = ["warning", "You are already registered for this event."];
        }
    }
    header("Location: System_user.php");
    exit();
}

$page_title = "User Dashboard - ICSDI 2026";
ob_start();
?>

<?php if (isset($_SESSION['msg'])): ?>
    <div class="alert alert-<?php echo $_SESSION['msg'][0]; ?> alert-dismissible fade show mb-4" role="alert">
        <?php echo $_SESSION['msg'][1]; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['msg']); ?>
<?php endif; ?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="fw-bold">Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
            <p class="text-muted">Manage your event registrations and profile</p>
        </div>
        <div class="col-md-4 text-md-end">
            <span class="badge bg-primary fs-6 p-2">Standard User Account</span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush rounded">
                        <a href="System_user.php" class="list-group-item list-group-item-action active p-3">
                            <i class="fas fa-th-large me-2"></i>My Events
                        </a>
                        <a href="services.php" class="list-group-item list-group-item-action p-3">
                            <i class="fas fa-plus me-2"></i>Browse New Events
                        </a>
                        <a href="contact_us.php" class="list-group-item list-group-item-action p-3">
                            <i class="fas fa-question-circle me-2"></i>Support
                        </a>
                        <a href="logout.php" class="list-group-item list-group-item-action p-3 text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">My Registered Events</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($my_events)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5>No events registered yet</h5>
                            <p class="text-muted">Browse our events and secure your spot!</p>
                            <a href="services.php" class="btn btn-primary mt-2">Explore Events</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Event</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($my_events as $event): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold"><?php echo htmlspecialchars($event['title']); ?></div>
                                                <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i><?php echo htmlspecialchars($event['venue']); ?></small>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($event['start_date'])); ?></td>
                                            <td><span class="badge bg-secondary"><?php echo ucfirst($event['event_type']); ?></span></td>
                                            <td>
                                                <?php if ($event['payment_status'] == 'paid'): ?>
                                                    <span class="badge bg-success">Confirmed</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-outline-primary">View Details</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Profile Overview (Quick Look) -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="fas fa-user-circle me-2 text-primary"></i>Profile Info</h6>
                            <p class="mb-1"><small class="text-muted d-block">Full Name</small><?php echo htmlspecialchars($user_name); ?></p>
                            <p class="mb-0"><small class="text-muted d-block">Account Type</small><?php echo ucfirst($user_type); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3"><i class="fas fa-bell me-2 text-warning"></i>Notifications</h6>
                            <p class="text-muted small">No new notifications at this time.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'base.php';
?>
