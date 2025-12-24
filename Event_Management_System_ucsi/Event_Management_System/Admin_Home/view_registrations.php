<?php
include('base.php');
$db = require_once(__DIR__ . '/../Database/database.php');

$registrations = $db->query("
    SELECT r.*, 
           CONCAT(u.first_name, ' ', u.last_name) as user_name,
           u.email as user_email,
           e.title as event_name
    FROM registrations r 
    JOIN users u ON r.user_id = u.user_id 
    JOIN events e ON r.event_id = e.event_id 
    ORDER BY r.registration_date DESC
")->fetchAll();
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background-color:#121212; color:white; }
.container { padding-top:30px; }
.card { background:#1e1e2f; border-radius:12px; }
.check-in-badge { font-size:0.8em; }
</style>

<div class="container">
    <h1 class="mb-4"><i class="bi bi-people"></i> All Registrations</h1>
    
    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Event</th>
                        <th>Type</th>
                        <th>Payment Status</th>
                        <th>Amount</th>
                        <th>Registration Date</th>
                        <th>Check-in</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($registrations as $reg): ?>
                    <tr>
                        <td><?=htmlspecialchars($reg['user_name'])?></td>
                        <td><?=htmlspecialchars($reg['user_email'])?></td>
                        <td><?=htmlspecialchars($reg['event_name'])?></td>
                        <td><span class="badge bg-info"><?=ucfirst($reg['registration_type'])?></span></td>
                        <td>
                            <?php 
                            $paymentClass = [
                                'pending' => 'bg-warning',
                                'paid' => 'bg-success',
                                'failed' => 'bg-danger',
                                'refunded' => 'bg-secondary'
                            ];
                            ?>
                            <span class="badge <?=$paymentClass[$reg['payment_status']]?>">
                                <?=ucfirst($reg['payment_status'])?>
                            </span>
                        </td>
                        <td>
                            <?php if($reg['payment_amount']): ?>
                                $<?=number_format($reg['payment_amount'], 2)?>
                            <?php else: ?>
                                <span class="text-muted">Free</span>
                            <?php endif; ?>
                        </td>
                        <td><?=date('M j, Y', strtotime($reg['registration_date']))?></td>
                        <td>
                            <?php if($reg['checked_in']): ?>
                                <span class="badge bg-success check-in-badge">
                                    <i class="bi bi-check-circle"></i> Checked In
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary check-in-badge">Not Checked In</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>