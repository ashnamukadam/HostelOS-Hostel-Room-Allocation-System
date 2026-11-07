<?php
require_once 'includes.php';
page_header('Reports & insights','reports.php');
$vacant=$pdo->query("SELECT h.hostel_name,r.room_number,r.room_type,r.capacity,COUNT(a.allocation_id) occupied,r.capacity-COUNT(a.allocation_id) vacant_beds FROM rooms r JOIN hostels h ON h.hostel_id=r.hostel_id LEFT JOIN allocations a ON a.room_id=r.room_id AND a.status='Active' GROUP BY r.room_id,h.hostel_name,r.room_number,r.room_type,r.capacity HAVING vacant_beds>0 ORDER BY h.hostel_name,r.room_number")->fetchAll();
$joined=$pdo->query("SELECT s.student_name,s.course,h.hostel_name,r.room_number,a.allocation_date FROM students s INNER JOIN allocations a ON a.student_id=s.student_id INNER JOIN rooms r ON r.room_id=a.room_id INNER JOIN hostels h ON h.hostel_id=r.hostel_id WHERE a.status='Active' ORDER BY s.student_name")->fetchAll();
$summary=$pdo->query("SELECT h.hostel_name,COUNT(DISTINCT r.room_id) total_rooms,COALESCE(SUM(r.capacity),0) total_beds,COUNT(a.allocation_id) occupied_beds,COALESCE(SUM(r.capacity),0)-COUNT(a.allocation_id) vacant_beds FROM hostels h LEFT JOIN rooms r ON r.hostel_id=h.hostel_id LEFT JOIN allocations a ON a.room_id=r.room_id AND a.status='Active' GROUP BY h.hostel_id,h.hostel_name ORDER BY h.hostel_name")->fetchAll();
function report_table($rows,$headers){if(!$rows){echo '<div class="empty">No records to display.</div>';return;}echo '<div class="table-wrap"><table><thead><tr>';foreach($headers as $h)echo '<th>'.e($h).'</th>';echo '</tr></thead><tbody>';foreach($rows as $row){echo '<tr>';foreach(array_values($row) as $v)echo '<td>'.e($v??'—').'</td>';echo '</tr>';}echo '</tbody></table></div>';}
?>
<section class="hero"><span class="eyebrow">DATABASE QUERY OUTPUTS</span><h2>Reports that turn records into insight.</h2><p>These reports demonstrate SELECT, GROUP BY, HAVING and multi-table JOIN operations.</p></section>
<h3 class="section-title">1. Vacant rooms and available beds</h3><?php report_table($vacant,['Hostel','Room','Type','Capacity','Occupied','Vacant beds']);?>
<h3 class="section-title">2. Current student-room-hostel JOIN</h3><?php report_table($joined,['Student','Course','Hostel','Room','Allocation date']);?>
<h3 class="section-title">3. Hostel occupancy summary</h3><?php report_table($summary,['Hostel','Rooms','Total beds','Occupied beds','Vacant beds']);?>
<?php page_footer();?>
