<?php
include('base.php');
$db = require_once(__DIR__ . '/../Database/database.php');

/* ============================================================
   DASHBOARD METRICS
============================================================ */
$totalUsers = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalEvents = $db->query("SELECT COUNT(*) FROM events")->fetchColumn();
$totalSessions = $db->query("SELECT COUNT(*) FROM sessions")->fetchColumn();
$totalPapers = $db->query("SELECT COUNT(*) FROM papers")->fetchColumn();
$totalRegistrations = $db->query("SELECT COUNT(*) FROM registrations")->fetchColumn();
$totalPayments = $db->query("SELECT IFNULL(SUM(payment_amount),0) FROM registrations WHERE payment_status='paid'")->fetchColumn();
$pendingPayments = $db->query("SELECT COUNT(*) FROM registrations WHERE payment_status='pending'")->fetchColumn();
$completedEvents = $db->query("SELECT COUNT(*) FROM events WHERE LOWER(status)='completed'")->fetchColumn();

/* ============================================================
   RECENT REGISTRATIONS
============================================================ */
$recentRegs = $db->query("
    SELECT r.registration_id, u.first_name, u.last_name, e.title AS event_title, r.payment_status
    FROM registrations r
    JOIN users u ON r.user_id = u.user_id
    JOIN events e ON r.event_id = e.event_id
    ORDER BY r.registration_date DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

/* ============================================================
   TOP 5 EVENTS
============================================================ */
$topEvents = $db->query("
    SELECT e.title, COUNT(r.registration_id) AS reg_count
    FROM events e
    LEFT JOIN registrations r ON e.event_id = r.event_id
    GROUP BY e.event_id
    ORDER BY reg_count DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

/* ============================================================
   ALL EVENTS (SHOW ALL VARIANTS)
============================================================ */
$allEvents = $db->query("
    SELECT event_id, title, event_type, status, start_date, end_date, created_at
    FROM events
    ORDER BY created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background-color:#121212; color:#e0e0e0; font-family:Roboto,sans-serif; }
.container { padding-top:20px; padding-bottom:50px; }
.card { background:#1e1e2f; border-radius:12px; color:#e0e0e0; box-shadow:0 4px 20px rgba(0,0,0,0.6); transition:0.3s; }
.card:hover { transform:translateY(-6px); box-shadow:0 10px 30px rgba(0,0,0,0.8); }
.card-header { background:#1e1e2f; border-bottom:1px solid #333; font-weight:600; }
.dashboard-icon { font-size:2.5rem; opacity:0.3; }
.table thead { background:#1b1b2a; }
.table tbody tr:hover { background:#2a2a3d; }
canvas { background:#1e1e2f; border-radius:12px; padding:10px; }
</style>

<div class="container">
    <h1 class="mb-4"><i class="bi bi-speedometer2"></i> Admin Dashboard</h1>

    <!-- METRIC CARDS -->
    <div class="row g-4">
        <?php
        $cards = [
            ['Users',$totalUsers,'bi-people','#0d6efd'],
            ['Events',$totalEvents,'bi-calendar-event','#198754'],
            ['Sessions',$totalSessions,'bi-journal-text','#ffc107'],
            ['Payments',"$".number_format($totalPayments,2),'bi-cash-stack','#dc3545'],
            ['Papers',$totalPapers,'bi-file-earmark-text','#0dcaf0'],
            ['Registrations',$totalRegistrations,'bi-card-checklist','#6c757d'],
            ['Pending Payments',$pendingPayments,'bi-hourglass-split','#fd7e14'],
            ['Completed Events',$completedEvents,'bi-check-circle','#20c997']
        ];
        foreach($cards as $c): ?>
        <div class="col-lg-3 col-md-6">
            <div class="card" style="border-left:5px solid <?=$c[3]?>;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5><?=$c[0]?></h5>
                        <p class="fs-3"><?=$c[1]?></p>
                    </div>
                    <i class="bi <?=$c[2]?> dashboard-icon" style="color:<?=$c[3]?>"></i>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- CHARTS -->
    <div class="row mt-5 g-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">User Growth</div>
                <div class="card-body">
                    <canvas id="userChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Top 5 Event Registrations</div>
                <div class="card-body">
                    <canvas id="eventChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- RECENT REGISTRATIONS -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">Recent Registrations</div>
                <div class="card-body">
                    <table class="table table-hover table-dark align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Event</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentRegs as $r): ?>
                                <tr>
                                    <td><?=$r['registration_id']?></td>
                                    <td><?=$r['first_name'].' '.$r['last_name']?></td>
                                    <td><?=$r['event_title']?></td>
                                    <td><?=ucfirst($r['payment_status'])?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ALL EVENTS TABLE -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">All Event Records</div>
                <div class="card-body">
                    <table class="table table-hover table-dark align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($allEvents) > 0): ?>
                                <?php foreach($allEvents as $ev): ?>
                                    <tr>
                                        <td><?=$ev['event_id']?></td>
                                        <td><?=$ev['title']?></td>
                                        <td><?=ucfirst($ev['event_type'])?></td>
                                        <td><?=ucfirst($ev['status'])?></td>
                                        <td><?=$ev['start_date']?></td>
                                        <td><?=$ev['end_date']?></td>
                                        <td><?=$ev['created_at']?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="7" class="text-center text-danger">No event records found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CHART SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
Chart.defaults.color='#e0e0e0';
Chart.defaults.borderColor='#333';

// User Growth Chart
new Chart(document.getElementById('userChart'), {
    type:'line',
    data:{
        labels:['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets:[{
            label:'Users',
            data:[<?php for($m=1;$m<=12;$m++){ echo $db->query("SELECT COUNT(*) FROM users WHERE MONTH(created_at)=$m")->fetchColumn().",";} ?>],
            borderColor:'rgba(13,110,253,1)',
            backgroundColor:'rgba(13,110,253,0.3)',
            fill:true, tension:0.3
        }]
    }
});

// Event Chart
new Chart(document.getElementById('eventChart'), {
    type:'bar',
    data:{
        labels:[<?php foreach($topEvents as $e){ echo "'".$e['title']."',"; } ?>],
        datasets:[{
            label:'Registrations',
            data:[<?php foreach($topEvents as $e){ echo $e['reg_count'].","; } ?>],
            backgroundColor:'rgba(40,167,69,0.7)'
        }]
    }
});
</script>
