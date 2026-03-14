<?php
if (isset($_GET['mute'])) {
    shell_exec("mpc volume 0");
} else {
    $step = (int)$_GET['step'];
    // Clamp step to safe range
    if ($step >= -20 && $step <= 20) {
        $sign = $step >= 0 ? '+' : '';
        shell_exec("mpc volume {$sign}{$step}");
    }
}
header("Location: http://" . $_SERVER['HTTP_HOST'] . "/");
exit;
