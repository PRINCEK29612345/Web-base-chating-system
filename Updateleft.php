<?php
session_start();
$sender = $_SESSION['name'];

$USER_NAME  = "super";
$PASSWD     = "jamesBond@07";
$SERVER     = "localhost";
$DBNAME     = "RECORD";
$DP_BASE    = "http://localhost/DP/";
$DP_DEFAULT = "http://localhost/chatbg/sea.jpg";

try {
    $pdo = new PDO('mysql:host='.$SERVER.';dbname='.$DBNAME, $USER_NAME, $PASSWD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo "<div style='color:red;padding:10px;'>DB Error: " . $e->getMessage() . "</div>";
    exit;
}

/* ── Fetch MY own DP ── */
$myDp = $DP_DEFAULT;
$smt  = $pdo->prepare("SELECT DP FROM USERS WHERE Uname = ?");
$smt->execute([$sender]);
$me   = $smt->fetch(PDO::FETCH_ASSOC);
if (!empty($me['DP'])) $myDp = $DP_BASE . $me['DP'];

/* ── Fetch ALL other users ── */
$stmt     = $pdo->prepare("SELECT Uname, DP FROM USERS WHERE Uname != ? ORDER BY Uname ASC");
$stmt->execute([$sender]);
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ══════════════════════════════
     DP PREVIEW MODAL
══════════════════════════════ -->
<div id="dp-modal"
     onclick="closeDpModal()"
     style="display:none;
            position:fixed;inset:0;z-index:9999;
            background:rgba(0,0,0,0.82);
            align-items:center;justify-content:center;
            flex-direction:column;gap:14px;">

    <!-- Close button -->
    <button onclick="closeDpModal()"
            style="position:absolute;top:18px;right:22px;
                   background:none;border:none;cursor:pointer;
                   color:#fff;font-size:1.6rem;line-height:1;opacity:0.8;">✕</button>

    <!-- Big photo -->
    <img id="dp-modal-img" src="" alt=""
         onclick="event.stopPropagation()"
         style="width:280px;height:280px;border-radius:50%;
                object-fit:cover;object-position:center;
                border:3px solid #25D366;
                box-shadow:0 8px 40px rgba(0,0,0,0.6);
                animation:dpPop 0.2s ease;">

    <!-- Name under photo -->
    <div id="dp-modal-name"
         onclick="event.stopPropagation()"
         style="color:#E9EDEF;font-size:1.1rem;font-weight:700;
                letter-spacing:0.3px;"></div>
</div>

<style>
@keyframes dpPop {
    from { transform: scale(0.8); opacity: 0; }
    to   { transform: scale(1);   opacity: 1; }
}
</style>

<script>
function previewDp(imgSrc, name) {
    document.getElementById('dp-modal-img').src  = imgSrc;
    document.getElementById('dp-modal-name').textContent = name;
    var modal = document.getElementById('dp-modal');
    modal.style.display = 'flex';
}
function closeDpModal() {
    document.getElementById('dp-modal').style.display = 'none';
    document.getElementById('dp-modal-img').src = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDpModal();
});
</script>

<!-- ── My profile row ── -->
<div style="display:flex;align-items:center;gap:10px;padding:10px 14px;
            border-bottom:1px solid var(--wa-border);
            background:var(--wa-panel-bg);">
    <div onclick="UpdateDP()"
         title="Change photo"
         style="width:46px;height:46px;border-radius:50%;
                background-image:url('<?php echo htmlspecialchars($myDp); ?>');
                background-size:cover;background-position:center;
                background-color:var(--wa-dark-green);
                border:2px solid var(--wa-green);
                flex-shrink:0;cursor:pointer;"></div>
    <div style="flex:1;min-width:0;">
        <div style="font-size:0.92rem;font-weight:700;
                    color:var(--wa-text-primary);
                    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            <?php echo htmlspecialchars($sender); ?>
        </div>
        <div style="font-size:0.72rem;color:var(--wa-green);margin-top:2px;">
            Your profile
        </div>
    </div>
</div>

<!-- ── Contact list ── -->
<?php if (empty($contacts)): ?>
    <div style="padding:20px;text-align:center;
                color:var(--wa-text-secondary);font-size:0.85rem;">
        No other users found
    </div>
<?php else: ?>
    <?php foreach ($contacts as $row):
        $dp   = (!empty($row['DP'])) ? $DP_BASE . $row['DP'] : $DP_DEFAULT;
        $name = htmlspecialchars($row['Uname']);
        $dpJs = addslashes($dp);
    ?>
    <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;
                cursor:pointer;border-bottom:1px solid rgba(128,128,128,0.1);"
         onmouseover="this.style.background='var(--wa-sidebar-hover)'"
         onmouseout="this.style.background=''"
         onclick="ChatWin('<?php echo $name; ?>')">

        <!-- Circle DP — click previews, does NOT open chat -->
        <div title="View photo"
             onclick="event.stopPropagation(); previewDp('<?php echo $dpJs; ?>', '<?php echo $name; ?>')"
             style="width:46px;height:46px;border-radius:50%;
                    background-image:url('<?php echo htmlspecialchars($dp); ?>');
                    background-size:cover;background-position:center;
                    background-color:var(--wa-dark-green);
                    flex-shrink:0;cursor:zoom-in;
                    transition:transform 0.15s,box-shadow 0.15s;"
             onmouseover="this.style.transform='scale(1.08)';this.style.boxShadow='0 2px 12px rgba(0,0,0,0.4)'"
             onmouseout="this.style.transform='';this.style.boxShadow=''">
        </div>

        <!-- Name + subtitle — pointer-events:none so only outer div fires ChatWin -->
        <div style="flex:1;min-width:0;pointer-events:none;">
            <div style="font-size:0.92rem;font-weight:600;
                        color:var(--wa-text-primary);
                        white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                <?php echo $name; ?>
            </div>
            <div style="font-size:0.78rem;color:var(--wa-text-secondary);margin-top:2px;">
                Tap to chat
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

