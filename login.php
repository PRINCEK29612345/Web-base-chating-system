<!DOCTYPE html>
<html lang="en">
<?php session_start(); ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login \u2014 ChatApp</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green:      #25D366;
            --dark-green: #128C7E;
            --teal:       #075E54;
            --bg:         #0B141A;
            --panel:      #1F2C34;
            --input-bg:   #2A3942;
            --border:     #2A3942;
            --text:       #E9EDEF;
            --muted:      #8696A0;
            --error:      #ef5350;
        }

        html, body {
            height: 100%; width: 100%;
            font-family: 'Nunito', sans-serif;
            background: var(--bg);
            overflow: hidden;
        }

        /* \u2500\u2500 Animated background bubbles \u2500\u2500 */
        .bg-bubbles {
            position: fixed; inset: 0; z-index: 0;
            overflow: hidden; pointer-events: none;
        }
        .bg-bubbles span {
            position: absolute; bottom: -150px;
            border-radius: 50%;
            background: rgba(37,211,102,0.07);
            animation: rise linear infinite;
        }
        .bg-bubbles span:nth-child(1)  { width:80px;  height:80px;  left:10%; animation-duration:12s; animation-delay:0s; }
        .bg-bubbles span:nth-child(2)  { width:30px;  height:30px;  left:20%; animation-duration:8s;  animation-delay:2s; }
        .bg-bubbles span:nth-child(3)  { width:50px;  height:50px;  left:35%; animation-duration:14s; animation-delay:1s; }
        .bg-bubbles span:nth-child(4)  { width:100px; height:100px; left:50%; animation-duration:18s; animation-delay:0s; }
        .bg-bubbles span:nth-child(5)  { width:40px;  height:40px;  left:65%; animation-duration:9s;  animation-delay:3s; }
        .bg-bubbles span:nth-child(6)  { width:60px;  height:60px;  left:75%; animation-duration:11s; animation-delay:1.5s; }
        .bg-bubbles span:nth-child(7)  { width:25px;  height:25px;  left:85%; animation-duration:7s;  animation-delay:4s; }
        @keyframes rise {
            0%   { transform: translateY(0)   scale(1);   opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 0.6; }
            100% { transform: translateY(-110vh) scale(1.15); opacity: 0; }
        }

        /* \u2500\u2500 Centered card \u2500\u2500 */
        .page {
            position: relative; z-index: 1;
            height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }

        .card {
            width: 100%; max-width: 420px;
            background: var(--panel);
            border-radius: 18px;
            padding: 44px 40px 36px;
            box-shadow: 0 24px 80px rgba(0,0,0,0.6);
            border: 1px solid rgba(37,211,102,0.12);
            animation: cardIn 0.5s cubic-bezier(.22,.68,0,1.2) both;
        }
        @keyframes cardIn {
            from { opacity:0; transform: translateY(32px) scale(0.96); }
            to   { opacity:1; transform: translateY(0)    scale(1); }
        }

        /* \u2500\u2500 Logo area \u2500\u2500 */
        .logo {
            display: flex; flex-direction: column;
            align-items: center; gap: 10px;
            margin-bottom: 36px;
        }
        .logo-icon {
            width: 68px; height: 68px; border-radius: 50%;
            background: linear-gradient(135deg, var(--dark-green), var(--green));
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem;
            box-shadow: 0 8px 28px rgba(37,211,102,0.35);
            animation: pulse 2.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 8px 28px rgba(37,211,102,0.35); }
            50%       { box-shadow: 0 8px 40px rgba(37,211,102,0.6); }
        }
        .logo h1 {
            font-size: 1.5rem; font-weight: 800;
            color: var(--text); letter-spacing: 0.3px;
        }
        .logo p {
            font-size: 0.8rem; color: var(--muted);
            margin-top: -6px;
        }

        /* \u2500\u2500 Fields \u2500\u2500 */
        .field {
            margin-bottom: 18px;
            animation: fieldIn 0.5s ease both;
        }
        .field:nth-child(1) { animation-delay: 0.15s; }
        .field:nth-child(2) { animation-delay: 0.25s; }
        @keyframes fieldIn {
            from { opacity:0; transform: translateX(-12px); }
            to   { opacity:1; transform: translateX(0); }
        }

        .field label {
            display: block;
            font-size: 0.75rem; font-weight: 700;
            color: var(--muted);
            letter-spacing: 0.08em; text-transform: uppercase;
            margin-bottom: 7px;
        }

        .input-wrap {
            position: relative;
        }
        .input-wrap .icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            font-size: 1rem; pointer-events: none;
            opacity: 0.6;
        }
        .input-wrap input {
            width: 100%;
            background: var(--input-bg);
            border: 1.5px solid transparent;
            border-radius: 10px;
            padding: 12px 14px 12px 42px;
            color: var(--text);
            font-family: 'Nunito', sans-serif;
            font-size: 0.92rem;
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        }
        .input-wrap input::placeholder { color: var(--muted); }
        .input-wrap input:focus {
            border-color: var(--green);
            background: #2f424d;
            box-shadow: 0 0 0 3px rgba(37,211,102,0.12);
        }

        /* Toggle password visibility */
        .eye-btn {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            font-size: 1rem; color: var(--muted);
            transition: color 0.15s; padding: 2px;
        }
        .eye-btn:hover { color: var(--green); }

        /* \u2500\u2500 Login button \u2500\u2500 */
        .btn-login {
            width: 100%; margin-top: 10px;
            padding: 13px;
            background: linear-gradient(135deg, var(--dark-green), var(--green));
            border: none; border-radius: 10px;
            color: #fff; font-family: 'Nunito', sans-serif;
            font-size: 0.95rem; font-weight: 800;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s, filter 0.15s;
            box-shadow: 0 6px 20px rgba(37,211,102,0.35);
            animation: fieldIn 0.5s 0.35s ease both;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(37,211,102,0.5);
            filter: brightness(1.08);
        }
        .btn-login:active { transform: translateY(0); }

        /* \u2500\u2500 Error / success message \u2500\u2500 */
        .msg-box {
            margin-top: 20px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            text-align: center;
            animation: cardIn 0.3s ease;
        }
        .msg-error {
            background: rgba(239,83,80,0.15);
            border: 1px solid rgba(239,83,80,0.3);
            color: var(--error);
        }
        .msg-success {
            background: rgba(37,211,102,0.12);
            border: 1px solid rgba(37,211,102,0.3);
            color: var(--green);
        }

        /* \u2500\u2500 Footer watermark \u2500\u2500 */
        .watermark {
            position: fixed; bottom: 12px; right: 16px;
            font-size: 0.72rem; color: rgba(134,150,160,0.5);
            font-family: 'Nunito', sans-serif;
            pointer-events: none; user-select: none;
        }
        .watermark span { color: var(--green); font-weight: 700; }
    </style>
</head>
<body>

<!-- Floating background bubbles -->
<div class="bg-bubbles">
    <span></span><span></span><span></span><span></span>
    <span></span><span></span><span></span>
</div>

<div class="page">
    <div class="card">

        <!-- Logo -->
        <div class="logo">
            <div class="logo-icon">\U0001f4ac</div>
            <h1>ChatApp</h1>
            <p>Sign in to continue</p>
        </div>

        <!-- Form -->
        <form action="" method="post" id="loginForm">

            <div class="field">
                <label for="name">Username</label>
                <div class="input-wrap">
                    <span class="icon">\U0001f464</span>
                    <input type="text" id="name" name="name"
                           placeholder="Enter your username"
                           autocomplete="username" required>
                </div>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <span class="icon">\U0001f512</span>
                    <input type="password" id="password" name="password"
                           placeholder="Enter your password"
                           autocomplete="current-password" required>
                    <button type="button" class="eye-btn" id="eyeBtn"
                            onclick="togglePw()" title="Show/hide password">\U0001f441</button>
                </div>
            </div>

            <button type="submit" name="clicked" class="btn-login">
                Login \u2192
            </button>

        </form>

        <?php
        if (isset($_POST['clicked'])) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $_SESSION['name'] = $_POST['name'];
            }
            $name     = $_POST['name'];
            $password = $_POST['password'];

            $USER_NAME = "super";
            $PASSWD    = "jamesBond@07";
            $SERVER    = "localhost";
            $DBNAME    = "RECORD";

            $pdo = new PDO('mysql:host=' . $SERVER . ';dbname=' . $DBNAME, $USER_NAME, $PASSWD);

            if ($pdo) {
                $Q1    = "SELECT Uname FROM USERS";
                $stmt1 = $pdo->query($Q1);
                $errflag = 0;

                while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['Uname'] == $name) {
                        $Q2    = "SELECT PSSWD FROM USERS WHERE Uname = :name";
                        $stmt2 = $pdo->prepare($Q2);
                        $stmt2->bindParam(':name', $name);
                        $stmt2->execute();
                        $psw = $stmt2->fetchColumn();
                        if (!empty($psw) && $psw == $password) {
                            $errflag = 1;
                            break;
                        }
                    }
                }

                if ($errflag == 0) {
                    echo "<div class='msg-box msg-error'>\u26a0\ufe0f Invalid username or password</div>";
                } else {
                    echo "<div class='msg-box msg-success'>\u2713 Login successful! Redirecting\u2026</div>";
                    echo "<script>setTimeout(function(){ window.location.href='chat.php'; }, 800);</script>";
                }
            }
        }
        ?>

    </div>
</div>

<!-- Watermark -->
<div class="watermark">made by <span>@ Prince KP</span></div>

<script>
    function togglePw() {
        var inp = document.getElementById('password');
        var btn = document.getElementById('eyeBtn');
        if (inp.type === 'password') {
            inp.type = 'text';
            btn.textContent = '\U0001f648';
        } else {
            inp.type = 'password';
            btn.textContent = '\U0001f441';
        }
    }

    // Shake animation on invalid submit
    document.getElementById('loginForm').addEventListener('submit', function() {
        document.querySelector('.btn-login').style.animation = 'none';
    });
</script>
</body>
</html>
