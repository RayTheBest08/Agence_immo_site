<?php
require_once 'config.php';

function flash($message, $type = 'info') {
    $_SESSION['flash'] = ['msg' => $message, 'type' => $type];
}

function show_flash() {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        $class = $f['type'] === 'success' ? 'alert success' : ($f['type'] === 'danger' ? 'alert danger' : 'alert');
        echo "<div class='\$class'>" . htmlspecialchars($f['msg']) . "</div>";
        unset($_SESSION['flash']);
    }
}

function esc($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
