<?php
require_once __DIR__ . '/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function redirect(string $url): never { header("Location: $url"); exit; }
function flash(string $message, string $type='success'): void { $_SESSION['flash'] = [$message,$type]; }
function show_flash(): void {
    if (!empty($_SESSION['flash'])) {
        [$m,$t] = $_SESSION['flash'];
        echo '<div class="alert '.e($t).'">'.e($m).'</div>';
        unset($_SESSION['flash']);
    }
}
function active_count(PDO $pdo, int $roomId): int {
    $s=$pdo->prepare("SELECT COUNT(*) FROM allocations WHERE room_id=? AND status='Active'");
    $s->execute([$roomId]); return (int)$s->fetchColumn();
}
function page_header(string $title, string $active): void {
    $items = [
      'index.php'=>['Overview','⌂'],
      'students.php'=>['Students','♙'],
      'hostels.php'=>['Hostels','⌂'],
      'rooms.php'=>['Rooms','▦'],
      'allocations.php'=>['Allocations','⇄'],
      'reports.php'=>['Reports','▤'],
    ];
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>'.e($title).' | HostelOS</title><link rel="stylesheet" href="assets/style.css"></head><body><div class="layout"><aside class="sidebar"><a class="brand" href="index.php"><span class="brand-icon">H</span><span>HostelOS<small>RESIDENCE MANAGEMENT</small></span></a><div class="nav-label">WORKSPACE</div><nav>';
    foreach($items as $href=>$item) {
        $is=($active===$href)?' active':'';
        echo '<a class="nav-link'.$is.'" href="'.e($href).'"><span class="nav-icon">'.$item[1].'</span>'.$item[0].'</a>';
    }
    echo '</nav><div class="sidebar-foot"><span class="online-dot"></span> Local server connected<br><small>Semester 3 · DBMS Project</small></div></aside><main class="main"><header class="topbar"><div><span class="eyebrow">CAMPUS RESIDENCE MANAGEMENT</span><h1>'.e($title).'</h1></div><div class="user-chip"><span class="avatar">A</span><span>Ashna Altaf Mukadam<small>Project Administrator</small></span></div></header>';
    show_flash();
}
function page_footer(): void { echo '<footer>HostelOS · Hostel Room Allocation System · Academic DBMS Project</footer></main></div></body></html>'; }
function post(string $key, string $default=''): string { return trim((string)($_POST[$key] ?? $default)); }
