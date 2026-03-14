<?php
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Radio Player</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&family=Source+Sans+3:wght@400;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:        #111316;
            --surface:   #1c1f24;
            --border:    #2e323a;
            --accent:    #e8472a;
            --accent2:   #f0a500;
            --text:      #eaeaea;
            --muted:     #7a7f8a;
            --play-btn:  #e8472a;
            --del-btn:   #2e323a;
            --danger:    #7a1f1f;
            --vol-btn:   #1e3a2f;
            --vol-color: #2ecc71;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Source Sans 3', sans-serif;
            font-size: 17px;
            min-height: 100vh;
            padding: 0 0 40px;
        }

        /* ── Header ── */
        header {
            background: var(--surface);
            border-bottom: 2px solid var(--accent);
            padding: 18px 20px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        header .logo {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        header .logo svg { width: 18px; height: 18px; fill: #fff; }
        header h1 {
            font-family: 'Oswald', sans-serif;
            font-size: 1.4rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--text);
        }
        header h1 span { color: var(--accent); }

        /* ── Now Playing Banner ── */
        .now-playing {
            background: linear-gradient(135deg, #1a2a1a, #1c1f24);
            border: 1px solid var(--vol-color);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .now-playing .pulse-dot {
            width: 12px; height: 12px;
            background: var(--vol-color);
            border-radius: 50%;
            flex-shrink: 0;
            animation: pulse 1.4s infinite;
        }
        @keyframes pulse {
            0%   { box-shadow: 0 0 0 0 rgba(46,204,113,0.6); }
            70%  { box-shadow: 0 0 0 8px rgba(46,204,113,0); }
            100% { box-shadow: 0 0 0 0 rgba(46,204,113,0); }
        }
        .now-playing .np-text { flex: 1; min-width: 0; }
        .now-playing .np-label {
            font-family: 'Oswald', sans-serif;
            font-size: 0.7rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--vol-color);
            margin-bottom: 3px;
        }
        .now-playing .np-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .now-playing.idle {
            border-color: var(--border);
        }
        .now-playing.idle .pulse-dot {
            background: var(--muted);
            animation: none;
        }
        .now-playing.idle .np-label { color: var(--muted); }
        .now-playing.idle .np-title { color: var(--muted); }

        /* ── Volume Control ── */
        .volume-section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
        }
        .volume-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .volume-label {
            font-family: 'Oswald', sans-serif;
            font-size: 0.75rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--muted);
        }
        .volume-value {
            font-family: 'Oswald', sans-serif;
            font-size: 1.1rem;
            color: var(--vol-color);
        }
        .volume-bar-wrap {
            background: var(--bg);
            border-radius: 6px;
            height: 6px;
            margin-bottom: 14px;
            overflow: hidden;
        }
        .volume-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--vol-color), var(--accent2));
            border-radius: 6px;
            transition: width 0.3s;
        }
        .volume-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
        }
        .btn-vol {
            background: var(--vol-btn);
            color: var(--vol-color);
            border: 1px solid rgba(46,204,113,0.2);
            border-radius: 8px;
            font-family: 'Oswald', sans-serif;
            font-size: 1rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            min-height: 52px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            transition: opacity .15s, transform .1s;
            -webkit-tap-highlight-color: transparent;
            width: 100%;
        }
        .btn-vol:active { opacity: 0.8; transform: scale(0.97); }
        .btn-vol svg { width: 18px; height: 18px; }
        .btn-vol.mute {
            background: #2a1f1f;
            color: var(--accent);
            border-color: rgba(232,71,42,0.2);
        }

        /* ── Main layout ── */
        main { padding: 20px 16px; max-width: 600px; margin: 0 auto; }

        /* ── Section label ── */
        .section-label {
            font-family: 'Oswald', sans-serif;
            font-size: 0.75rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 12px;
        }

        /* ── Station card ── */
        .station-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 14px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .station-info {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .station-num {
            font-family: 'Oswald', sans-serif;
            font-size: 1.4rem;
            color: var(--accent);
            min-width: 32px;
            line-height: 1;
            padding-top: 2px;
        }
        .station-url {
            font-size: 0.9rem;
            color: var(--text);
            word-break: break-all;
            line-height: 1.5;
        }
        .station-actions {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            border-radius: 8px;
            font-family: 'Oswald', sans-serif;
            font-size: 1rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            cursor: pointer;
            padding: 14px 18px;
            min-height: 52px;
            transition: opacity .15s, transform .1s;
            -webkit-tap-highlight-color: transparent;
            width: 100%;
        }
        .btn:active { opacity: 0.8; transform: scale(0.97); }
        .btn svg { width: 18px; height: 18px; flex-shrink: 0; }

        .btn-play  { background: var(--play-btn); color: #fff; }
        .btn-del   { background: var(--del-btn);  color: var(--muted); min-width: 52px; width: auto; }
        .btn-add   { background: var(--accent2);  color: #111; }
        .btn-power { background: var(--danger);   color: #fff; }

        /* ── Add URL form ── */
        .add-section {
            margin-top: 28px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px 16px;
        }
        .add-section .section-label { margin-bottom: 14px; }
        .input-row {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .url-input {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-family: 'Source Sans 3', sans-serif;
            font-size: 0.95rem;
            padding: 14px 14px;
            width: 100%;
            min-height: 52px;
            outline: none;
            transition: border-color .2s;
        }
        .url-input:focus { border-color: var(--accent2); }
        .url-input::placeholder { color: var(--muted); }

        /* ── Power section ── */
        .power-section { margin-top: 20px; }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 28px 0;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 3a9 9 0 100 18A9 9 0 0012 3zm0 16a7 7 0 110-14 7 7 0 010 14zm0-10a3 3 0 100 6 3 3 0 000-6z"/>
        </svg>
    </div>
    <h1>RPi <span>Radio</span></h1>
</header>

<main>

<?php
$output     = shell_exec("mpc playlist");
$arr        = array_values(array_filter(array_map('trim', explode("\n", $output))));
$count      = count($arr);

// Now playing
$current    = trim(shell_exec("mpc current"));

// Volume: mpc volume returns e.g. "volume: 80%   repeat: off..."
$statusRaw  = trim(shell_exec("mpc status"));
$volume     = 50; // default fallback
if (preg_match('/volume:\s*(\d+)%/', $statusRaw, $m)) {
    $volume = (int)$m[1];
}
?>

<!-- Now Playing -->
<div class="now-playing <?= empty($current) ? 'idle' : '' ?>">
    <div class="pulse-dot"></div>
    <div class="np-text">
        <div class="np-label"><?= empty($current) ? 'Nije aktivno' : 'Sada svira' ?></div>
        <div class="np-title"><?= empty($current) ? 'Ništa ne svira' : htmlspecialchars($current) ?></div>
    </div>
</div>

<!-- Volume Control -->
<div class="volume-section">
    <div class="volume-header">
        <span class="volume-label">Jačina zvuka</span>
        <span class="volume-value"><?= $volume ?>%</span>
    </div>
    <div class="volume-bar-wrap">
        <div class="volume-bar" style="width: <?= $volume ?>%"></div>
    </div>
    <div class="volume-buttons">
        <form action="/volume_command.php" method="get">
            <input type="hidden" name="step" value="-10">
            <button type="submit" class="btn-vol">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.5 12A4.5 4.5 0 0016 7.97V16a4.48 4.48 0 002.5-4zm-13-1v2h3l4 4V7l-4 4h-3zm6.5-.73v5.46l-2.17-2.17-.66-.56H5.5v-2h3.67l.66-.56L12 10.27z"/><path d="M3 9v6h4l5 5V4L7 9H3z"/></svg>
                -10%
            </button>
        </form>
        <form action="/volume_command.php" method="get">
            <input type="hidden" name="step" value="-5">
            <button type="submit" class="btn-vol">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.5 12A4.5 4.5 0 0016 7.97V16a4.48 4.48 0 002.5-4zM3 9v6h4l5 5V4L7 9H3z"/></svg>
                -5%
            </button>
        </form>
        <form action="/volume_command.php" method="get">
            <input type="hidden" name="step" value="5">
            <button type="submit" class="btn-vol">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3A4.5 4.5 0 0014 7.97V16c1.48-.73 2.5-2.25 2.5-4z"/></svg>
                +5%
            </button>
        </form>
        <form action="/volume_command.php" method="get">
            <input type="hidden" name="step" value="10">
            <button type="submit" class="btn-vol">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3A4.5 4.5 0 0014 7.97V16c1.48-.73 2.5-2.25 2.5-4zm-3-5.43v10.86C16.52 16.59 19 14.44 19 12S16.52 7.41 13.5 6.57z"/></svg>
                +10%
            </button>
        </form>
        <form action="/volume_command.php" method="get" style="grid-column: span 2;">
            <input type="hidden" name="mute" value="1">
            <button type="submit" class="btn-vol mute" style="width:100%">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16.5 12A4.5 4.5 0 0014 7.97V10.18l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06A8.99 8.99 0 0017.73 18l2 2L21 18.73 4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
                Mute
            </button>
        </form>
    </div>
</div>

<?php if ($count > 0): ?>
<p class="section-label">Playlist — <?= $count ?> stanica</p>

<?php for ($i = 0; $i < $count; $i++):
    $num = $i + 1;
?>
<div class="station-card">
    <div class="station-info">
        <div class="station-num"><?= $num ?></div>
        <div class="station-url"><?= htmlspecialchars($arr[$i]) ?></div>
    </div>
    <div class="station-actions">
        <form action="/execute_command.php" method="get">
            <input type="hidden" name="number" value="<?= $num ?>">
            <button type="submit" class="btn btn-play">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                Pusti Radio <?= $num ?>
            </button>
        </form>
        <form action="/clear_command.php" method="get">
            <input type="hidden" name="number" value="<?= $num ?>">
            <button type="submit" class="btn btn-del" title="Obrisi">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6 19a2 2 0 002 2h8a2 2 0 002-2V7H6v12zm3-9h2v7H9v-7zm4 0h2v7h-2v-7zM15.5 4l-1-1h-5l-1 1H5v2h14V4h-3.5z"/></svg>
            </button>
        </form>
    </div>
</div>
<?php endfor; ?>

<?php else: ?>
<p style="color:var(--muted); font-size:0.95rem; margin-bottom: 20px;">Playlist je prazna. Dodaj URL ispod.</p>
<?php endif; ?>

<hr class="divider">

<div class="add-section">
    <p class="section-label">Dodaj stanicu</p>
    <form action="/change_command.php" method="get">
        <input type="hidden" name="number" value="1">
        <div class="input-row">
            <input class="url-input" type="url" id="command1" name="command"
                   placeholder="https://stream.url/path">
            <button type="submit" class="btn btn-add">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 11h-6V5h-2v6H5v2h6v6h2v-6h6z"/></svg>
                Dodaj u Playlist
            </button>
        </div>
    </form>
</div>

<div class="power-section">
    <form action="/turnoff_command.php" method="get">
        <button type="submit" class="btn btn-power">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M13 3h-2v10h2V3zm4.83 2.17l-1.42 1.42A6.92 6.92 0 0119 12c0 3.87-3.13 7-7 7s-7-3.13-7-7a6.92 6.92 0 012.58-5.42L6.17 5.17A8.93 8.93 0 003 12a9 9 0 0018 0 8.93 8.93 0 00-3.17-6.83z"/></svg>
            Iskljuci Raspberry Pi
        </button>
    </form>
</div>

</main>
</body>
</html>
