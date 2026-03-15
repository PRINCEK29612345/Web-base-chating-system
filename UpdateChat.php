<?php
session_start();
$sender   = $_SESSION['name'];
$receiver = $_SESSION['message'];

$USER_NAME = "super";
$PASSWD    = "jamesBond@07";
$SERVER    = "localhost";
$DBNAME    = "RECORD";

$pdo = new PDO('mysql:host='.$SERVER.';dbname='.$DBNAME, $USER_NAME, $PASSWD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo '
<style>
/* ── Reset inside chat area ── */
#ch1 * { box-sizing: border-box; }

/* ── Date badge ── */
.wa-date {
    display: flex;
    justify-content: center;
    margin: 10px 0 6px;
}
.wa-date span {
    background: var(--wa-panel-bg, #202C33);
    color: var(--wa-text-secondary, #8696A0);
    font-size: 0.72rem;
    padding: 4px 12px;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.2);
}

/* ── Row wrappers ── */
.wa-row-out { display:flex; justify-content:flex-end;  align-items:flex-start; padding:2px 12px 2px 60px; }
.wa-row-in  { display:flex; justify-content:flex-start; align-items:flex-start; padding:2px 60px 2px 12px; }

/* ── Bubbles ── */
.wa-bubble-out,
.wa-bubble-in {
    position: relative;
    max-width: 65%;
    padding: 6px 8px 4px 8px;
    border-radius: 8px;
    font-size: 0.88rem;
    line-height: 1.45;
    word-break: break-word;
    white-space: pre-wrap;
    box-shadow: 0 1px 2px rgba(0,0,0,0.25);
    animation: bubbleIn 0.15s ease;
    display: inline-flex;
    flex-direction: column;
}
@keyframes bubbleIn {
    from { opacity:0; transform:translateY(6px); }
    to   { opacity:1; transform:translateY(0); }
}

.wa-bubble-out {
    background: var(--wa-msg-out, #005C4B);
    color: var(--wa-text-primary, #E9EDEF);
    border-top-right-radius: 2px;
}
.wa-bubble-in {
    background: var(--wa-msg-in, #202C33);
    color: var(--wa-text-primary, #E9EDEF);
    border-top-left-radius: 2px;
}

/* Tail on sent bubble */
.wa-bubble-out::before {
    content:"";
    position:absolute; top:0; right:-8px;
    border-width:0 0 8px 8px;
    border-style:solid;
    border-color:transparent transparent transparent var(--wa-msg-out, #005C4B);
}
/* Tail on received bubble */
.wa-bubble-in::before {
    content:"";
    position:absolute; top:0; left:-8px;
    border-width:0 8px 8px 0;
    border-style:solid;
    border-color:transparent var(--wa-msg-in, #202C33) transparent transparent;
}

/* Sender name inside bubble (group chat) */
.wa-sender-name {
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--wa-green, #25D366);
    margin-bottom: 3px;
}

/* ── Timestamp ── */
.wa-time {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 3px;
    font-size: 0.65rem;
    color: var(--wa-text-secondary, #8696A0);
    white-space: nowrap;
    margin-top: 2px;
    align-self: flex-end;
}
.wa-bubble-out .wa-time { color: #8eada3; }

/* Double tick */
.wa-tick { font-size: 0.75rem; color: var(--wa-green, #25D366); }

/* ── Image messages ── */
.wa-img-wrap {
    position: relative;
    cursor: pointer;
}
.wa-img-wrap img {
    display: block;
    max-width: 260px;
    max-height: 260px;
    width: 100%;
    border-radius: 6px;
    object-fit: cover;
    transition: filter 0.2s;
}
.wa-img-wrap:hover img { filter: brightness(0.88); }
.wa-img-zoom {
    position: absolute; inset:0;
    display:flex; align-items:center; justify-content:center;
    opacity:0; transition:opacity 0.2s;
    font-size:1.8rem;
}
.wa-img-wrap:hover .wa-img-zoom { opacity:1; }

/* ── File / doc card ── */
.wa-file-card {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(0,0,0,0.15);
    border-radius: 6px;
    padding: 8px 10px;
    min-width: 200px;
    max-width: 260px;
    text-decoration: none;
    transition: background 0.15s;
}
.wa-file-card:hover { background: rgba(0,0,0,0.28); }
.wa-file-icon {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: var(--wa-dark-green, #128C7E);
    display:flex; align-items:center; justify-content:center;
    font-size: 1.1rem; flex-shrink:0;
}
.wa-file-info { flex:1; min-width:0; }
.wa-file-name {
    font-size: 0.82rem; font-weight:600;
    color: var(--wa-text-primary, #E9EDEF);
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.wa-file-type {
    font-size: 0.7rem;
    color: var(--wa-text-secondary, #8696A0);
    margin-top:2px; text-transform:uppercase;
}

/* ── Full-screen image preview overlay ── */
#wa-img-overlay {
    display:none;
    position:fixed; inset:0; z-index:99999;
    background:rgba(0,0,0,0.92);
    align-items:center; justify-content:center;
    flex-direction:column; gap:14px;
}
#wa-img-overlay.open { display:flex; }
#wa-img-overlay img {
    max-width:90vw; max-height:80vh;
    border-radius:6px;
    box-shadow:0 8px 40px rgba(0,0,0,0.7);
    animation: dpPop 0.2s ease;
}
@keyframes dpPop {
    from { transform:scale(0.85); opacity:0; }
    to   { transform:scale(1);    opacity:1; }
}
#wa-img-overlay .ov-close {
    position:absolute; top:16px; right:20px;
    background:none; border:none; color:#fff;
    font-size:1.8rem; cursor:pointer; opacity:0.8;
}
#wa-img-overlay .ov-close:hover { opacity:1; }
#wa-img-overlay .ov-name {
    color:#E9EDEF; font-size:0.85rem;
}
</style>

<!-- Full-screen image preview overlay -->
<div id="wa-img-overlay" onclick="closeImgOverlay()">
    <button class="ov-close" onclick="closeImgOverlay()">✕</button>
    <img id="wa-overlay-img" src="" alt="" onclick="event.stopPropagation()">
    <div class="ov-name" id="wa-overlay-name"></div>
</div>

<script>
function openImgOverlay(src, name) {
    document.getElementById("wa-overlay-img").src  = src;
    document.getElementById("wa-overlay-name").textContent = name;
    document.getElementById("wa-img-overlay").classList.add("open");
}
function closeImgOverlay() {
    document.getElementById("wa-img-overlay").classList.remove("open");
    document.getElementById("wa-overlay-img").src = "";
}
document.addEventListener("keydown", function(e){
    if(e.key === "Escape") closeImgOverlay();
});
</script>
';

/* ── Helper: file-type icon ── */
function fileIcon($ext) {
    $ext = strtolower($ext);
    if (in_array($ext, ['pdf']))                          return '📄';
    if (in_array($ext, ['doc','docx']))                   return '📝';
    if (in_array($ext, ['xls','xlsx','csv']))             return '📊';
    if (in_array($ext, ['ppt','pptx']))                   return '📋';
    if (in_array($ext, ['zip','rar','7z','tar','gz']))    return '🗜️';
    if (in_array($ext, ['mp4','mkv','avi','mov','webm'])) return '🎬';
    if (in_array($ext, ['mp3','wav','ogg','aac']))        return '🎵';
    return '📁';
}

/* ── Helper: render one message row ── */
function renderMsg($msg, $tmt, $type, $dir, $isSent, $senderLabel = '') {
    $rowClass    = $isSent ? 'wa-row-out'    : 'wa-row-in';
    $bubbleClass = $isSent ? 'wa-bubble-out' : 'wa-bubble-in';
    $tick        = $isSent ? '<span class="wa-tick">✓✓</span>' : '';

    $content = '';

    if ($type === 'msg') {
        $content = '<span>' . htmlspecialchars($msg, ENT_QUOTES) . '</span>';
    } else {
        $link = htmlspecialchars($dir . $msg, ENT_QUOTES);
        $name = htmlspecialchars($msg, ENT_QUOTES);
        $ext  = strtolower(pathinfo($msg, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
            /* Image bubble */
            $content = '
                <div class="wa-img-wrap" onclick="openImgOverlay(\'' . $link . '\',\'' . $name . '\')">
                    <img src="' . $link . '" alt="' . $name . '" loading="lazy">
                    <div class="wa-img-zoom">🔍</div>
                </div>';
        } else {
            /* File / doc card */
            $icon = fileIcon($ext);
            $content = '
                <a class="wa-file-card" href="' . $link . '" target="_blank" download onclick="event.stopPropagation()">
                    <div class="wa-file-icon">' . $icon . '</div>
                    <div class="wa-file-info">
                        <div class="wa-file-name">' . $name . '</div>
                        <div class="wa-file-type">' . strtoupper($ext) . ' file</div>
                    </div>
                </a>';
        }
    }

    $nameLabel = $senderLabel
        ? '<div class="wa-sender-name">' . htmlspecialchars($senderLabel, ENT_QUOTES) . '</div>'
        : '';

    echo "
    <div class='{$rowClass}'>
        <div class='{$bubbleClass}'>
            {$nameLabel}
            {$content}
            <div class='wa-time'>{$tmt} {$tick}</div>
        </div>
    </div>";
}

/* ══════════════════════
   MAIN QUERY + RENDER
══════════════════════ */
if (!empty($receiver) && !empty($sender)) {

    /* ── Private chat ── */
    if ($receiver !== 'GROUP') {
        $stmt = $pdo->prepare("SELECT * FROM `{$sender}` ORDER BY ID ASC");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $lastDate = '';
        foreach ($rows as $row) {
            /* Date divider */
            $dateStr = date('d M Y', strtotime($row['TIME']));
            if ($dateStr !== $lastDate) {
                $today     = date('d M Y');
                $yesterday = date('d M Y', strtotime('-1 day'));
                $label     = ($dateStr === $today) ? 'Today'
                           : (($dateStr === $yesterday) ? 'Yesterday' : $dateStr);
                echo "<div class='wa-date'><span>{$label}</span></div>";
                $lastDate = $dateStr;
            }

            $isSent = ($row['SENDER'] === $sender && $row['RECEIVER'] === $receiver);
            $isRecv = ($row['SENDER'] === $receiver && $row['RECEIVER'] === $sender);

            if ($isSent || $isRecv) {
                renderMsg(
                    $row['DATA'], $row['TIME'],
                    $row['TYPE'], $row['DIR'],
                    $isSent
                );
            }
        }

    /* ── Group chat (GROUP) ── */
    } else {
        $stmt3 = $pdo->prepare("SELECT * FROM GROUP ORDER BY ID ASC");
        $stmt3->execute();
        $rows3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

        $lastDate = '';
        foreach ($rows3 as $row) {
            $dateStr = date('d M Y', strtotime($row['TIME']));
            if ($dateStr !== $lastDate) {
                $today     = date('d M Y');
                $yesterday = date('d M Y', strtotime('-1 day'));
                $label     = ($dateStr === $today) ? 'Today'
                           : (($dateStr === $yesterday) ? 'Yesterday' : $dateStr);
                echo "<div class='wa-date'><span>{$label}</span></div>";
                $lastDate = $dateStr;
            }

            $isSent      = ($row['SENDER'] === $sender);
            $senderLabel = $isSent ? '' : $row['SENDER'];

            renderMsg(
                $row['DATA'], $row['TIME'],
                $row['TYPE'], $row['DIR'],
                $isSent,
                $senderLabel
            );
        }
    }
}
?>

