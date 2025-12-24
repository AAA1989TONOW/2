<?php
$db = require_once(__DIR__ . '/../../Database/database.php');

try {
    $type = $_GET['type'] ?? 'all';
    $params = [];
    $sql = "SELECT * FROM events WHERE status != 'draft'";
    
    if ($type !== 'all') {
        $sql .= " AND event_type = ?";
        $params[] = $type;
    }
    
    $sql .= " ORDER BY start_date ASC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($events)) {
        echo '
        <div class="col-12 text-center py-5">
            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
            <h4>No events found</h4>
            <p class="text-muted">New events will be added soon. Please check back later!</p>
        </div>';
    } else {
        foreach ($events as $event) {
            $type_color = 'primary';
            switch($event['event_type']) {
                case 'conference': $type_color = 'primary'; break;
                case 'competition': $type_color = 'warning'; break;
                case 'webinar': $type_color = 'info'; break;
                case 'workshop': $type_color = 'success'; break;
            }
            
            echo '
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm event-card">
                    <img src="https://via.placeholder.com/400x200/2c3e50/ffffff?text=' . urlencode($event['title']) . '" class="card-img-top" alt="' . htmlspecialchars($event['title']) . '">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-' . $type_color . '">' . ucfirst($event['event_type']) . '</span>
                            <span class="text-primary fw-bold">$' . number_format($event['registration_fee'], 2) . '</span>
                        </div>
                        <h5 class="card-title fw-bold">' . htmlspecialchars($event['title']) . '</h5>
                        <p class="card-text text-muted small mb-3">' . htmlspecialchars(substr($event['description'], 0, 100)) . '...</p>
                        <div class="mb-3">
                            <small class="text-muted d-block"><i class="fas fa-calendar-alt me-2"></i>' . date('M d, Y', strtotime($event['start_date'])) . '</small>
                            <small class="text-muted d-block"><i class="fas fa-map-marker-alt me-2"></i>' . htmlspecialchars($event['venue']) . '</small>
                        </div>
                        <a href="System_user.php?action=register&event_id=' . $event['event_id'] . '" class="btn btn-outline-' . $type_color . ' w-100">Register Now</a>
                    </div>
                </div>
            </div>';
        }
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo "Error loading events.";
}
?>
