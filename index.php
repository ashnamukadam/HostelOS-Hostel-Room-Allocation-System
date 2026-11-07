<?php
require_once 'includes.php';
page_header('Overview','index.php');
$students=(int)$pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$hostels=(int)$pdo->query("SELECT COUNT(*) FROM hostels")->fetchColumn();
$rooms=(int)$pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
$active=(int)$pdo->query("SELECT COUNT(*) FROM allocations WHERE status='Active'")->fetchColumn();
$vacant=(int)$pdo->query("SELECT COALESCE(SUM(capacity-occupied),0) FROM (SELECT r.room_id,r.capacity,COUNT(a.allocation_id) occupied FROM rooms r LEFT JOIN allocations a ON a.room_id=r.room_id AND a.status='Active' GROUP BY r.room_id,r.capacity) x")->fetchColumn();
$recent=$pdo->query("SELECT a.allocation_id,s.student_name,h.hostel_name,r.room_number,a.allocation_date,a.status FROM allocations a JOIN students s ON s.student_id=a.student_id JOIN rooms r ON r.room_id=a.room_id JOIN hostels h ON h.hostel_id=r.hostel_id ORDER BY a.allocation_id DESC LIMIT 7")->fetchAll();
$occ=$pdo->query("SELECT h.hostel_name,r.room_number,r.capacity,COUNT(a.allocation_id) occupied FROM rooms r JOIN hostels h ON h.hostel_id=r.hostel_id LEFT JOIN allocations a ON a.room_id=r.room_id AND a.status='Active' GROUP BY r.room_id,h.hostel_name,r.room_number,r.capacity ORDER BY h.hostel_name,r.room_number")->fetchAll();
?>
<section class="hero"><span class="eyebrow">HOSTELOS · CAMPUS RESIDENCE MANAGEMENT</span><h2>A smarter way to manage campus living.</h2><p>One central workspace for student records, hostel inventory and room allocation.</p></section>
<div class="grid">
<div class="metric"><span class="metric-icon">♙</span><div class="label">Total students</div><div class="value"><?= $students ?></div><div class="hint">Registered student records</div></div>
<div class="metric"><span class="metric-icon">⌂</span><div class="label">Hostels</div><div class="value"><?= $hostels ?></div><div class="hint">Accommodation locations</div></div>
<div class="metric"><span class="metric-icon">▦</span><div class="label">Rooms</div><div class="value"><?= $rooms ?></div><div class="hint">Registered rooms</div></div>
<div class="metric"><span class="metric-icon">✧</span><div class="label">Vacant beds</div><div class="value"><?= $vacant ?></div><div class="hint"><?= $active ?> active allocations</div></div>
</div>
<div class="columns">
<section class="panel"><div class="panel-head"><h3>Room occupancy</h3><span class="badge">Live data</span></div>
<div class="table-wrap"><table><thead><tr><th>Room</th><th>Occupied</th><th>Capacity</th><th>Availability</th></tr></thead><tbody>
<?php foreach($occ as $r): $free=(int)$r['capacity']-(int)$r['occupied']; ?><tr><td><?=e($r['hostel_name'].' · '.$r['room_number'])?></td><td><?= (int)$r['occupied']?></td><td><?= (int)$r['capacity']?></td><td><span class="badge <?=$free===0?'warn':''?>"><?=$free?> free</span></td></tr><?php endforeach; ?>
</tbody></table></div></section>
<section class="panel"><div class="panel-head"><h3>Recent allocations</h3><a class="btn small secondary" href="allocations.php">View all</a></div>
<div class="table-wrap"><table><thead><tr><th>Student</th><th>Room</th><th>Status</th></tr></thead><tbody>
<?php foreach($recent as $r): ?><tr><td><?=e($r['student_name'])?></td><td><?=e($r['room_number'])?></td><td><span class="badge <?=$r['status']==='Active'?'':'gray'?>"><?=e($r['status'])?></span></td></tr><?php endforeach; ?>
<?php if(!$recent): ?><tr><td colspan="3" class="empty">No allocations yet.</td></tr><?php endif; ?>
</tbody></table></div></section></div>
<?php page_footer(); ?>
