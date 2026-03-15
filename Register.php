<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register \u2014 ChatApp</title>
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
            min-height: 100%; width: 100%;
            font-family: 'Nunito', sans-serif;
            background: var(--bg);
            overflow-x: hidden;
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
        .bg-bubbles span:nth-child(1) { width:80px;  height:80px;  left:5%;  animation-duration:12s; animation-delay:0s; }
        .bg-bubbles span:nth-child(2) { width:30px;  height:30px;  left:18%; animation-duration:8s;  animation-delay:2s; }
        .bg-bubbles span:nth-child(3) { width:50px;  height:50px;  left:32%; animation-duration:14s; animation-delay:1s; }
        .bg-bubbles span:nth-child(4) { width:90px;  height:90px;  left:50%; animation-duration:18s; animation-delay:0s; }
        .bg-bubbles span:nth-child(5) { width:40px;  height:40px;  left:65%; animation-duration:9s;  animation-delay:3s; }
        .bg-bubbles span:nth-child(6) { width:60px;  height:60px;  left:78%; animation-duration:11s; animation-delay:1.5s; }
        .bg-bubbles span:nth-child(7) { width:20px;  height:20px;  left:90%; animation-duration:7s;  animation-delay:4s; }
        @keyframes rise {
            0%   { transform: translateY(0) scale(1);     opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 0.6; }
            100% { transform: translateY(-110vh) scale(1.15); opacity: 0; }
        }

        /* \u2500\u2500 Page wrapper \u2500\u2500 */
        .page {
            position: relative; z-index: 1;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 30px 16px;
        }

        /* \u2500\u2500 Card \u2500\u2500 */
        .card {
            width: 100%; max-width: 460px;
            background: var(--panel);
            border-radius: 18px;
            padding: 40px 40px 32px;
            box-shadow: 0 24px 80px rgba(0,0,0,0.6);
            border: 1px solid rgba(37,211,102,0.12);
            animation: cardIn 0.5s cubic-bezier(.22,.68,0,1.2) both;
        }
        @keyframes cardIn {
            from { opacity:0; transform: translateY(32px) scale(0.96); }
            to   { opacity:1; transform: translateY(0)    scale(1); }
        }

        /* \u2500\u2500 Logo \u2500\u2500 */
        .logo {
            display: flex; flex-direction: column;
            align-items: center; gap: 10px;
            margin-bottom: 32px;
        }
        .logo-icon {
            width: 64px; height: 64px; border-radius: 50%;
            background: linear-gradient(135deg, var(--dark-green), var(--green));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.9rem;
            box-shadow: 0 8px 28px rgba(37,211,102,0.35);
            animation: pulse 2.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0%,100% { box-shadow: 0 8px 28px rgba(37,211,102,0.35); }
            50%      { box-shadow: 0 8px 40px rgba(37,211,102,0.6); }
        }
        .logo h1 { font-size: 1.45rem; font-weight: 800; color: var(--text); }
        .logo p   { font-size: 0.8rem; color: var(--muted); margin-top: -6px; }

        /* \u2500\u2500 Two-column grid for some fields \u2500\u2500 */
        .fields-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 16px;
        }
        .fields-grid .field { grid-column: span 1; }
        .fields-grid .field.full { grid-column: span 2; }

        /* \u2500\u2500 Field \u2500\u2500 */
        .field {
            margin-bottom: 16px;
            animation: fieldIn 0.5s ease both;
        }
        .field:nth-child(1) { animation-delay: 0.10s; }
        .field:nth-child(2) { animation-delay: 0.17s; }
        .field:nth-child(3) { animation-delay: 0.24s; }
        .field:nth-child(4) { animation-delay: 0.31s; }
        .field:nth-child(5) { animation-delay: 0.38s; }
        @keyframes fieldIn {
            from { opacity:0; transform: translateX(-10px); }
            to   { opacity:1; transform: translateX(0); }
        }

        .field label {
            display: block;
            font-size: 0.72rem; font-weight: 700;
            color: var(--muted);
            letter-spacing: 0.08em; text-transform: uppercase;
            margin-bottom: 7px;
        }

        .input-wrap { position: relative; }
        .input-wrap .icon {
            position: absolute; left: 13px; top: 50%;
            transform: translateY(-50%);
            font-size: 0.95rem; pointer-events: none; opacity: 0.6;
        }
        .input-wrap input {
            width: 100%;
            background: var(--input-bg);
            border: 1.5px solid transparent;
            border-radius: 10px;
            padding: 11px 14px 11px 40px;
            color: var(--text);
            font-family: 'Nunito', sans-serif;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        }
        .input-wrap input::placeholder { color: var(--muted); }
        .input-wrap input:focus {
            border-color: var(--green);
            background: #2f424d;
            box-shadow: 0 0 0 3px rgba(37,211,102,0.12);
        }
        /* Date input calendar icon color fix */
        .input-wrap input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0.6);
            cursor: pointer;
        }

        /* Eye toggle */
        .eye-btn {
            position: absolute; right: 10px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            font-size: 0.95rem; color: var(--muted);
            transition: color 0.15s; padding: 2px;
        }
        .eye-btn:hover { color: var(--green); }

        /* Password strength bar */
        .strength-bar {
            height: 3px; border-radius: 2px;
            background: var(--input-bg);
            margin-top: 5px; overflow: hidden;
        }
        .strength-fill {
            height: 100%; width: 0%;
            border-radius: 2px;
            transition: width 0.3s, background 0.3s;
        }

        /* \u2500\u2500 Register button \u2500\u2500 */
        .btn-register {
            width: 100%; margin-top: 6px;
            padding: 13px;
            background: linear-gradient(135deg, var(--dark-green), var(--green));
            border: none; border-radius: 10px;
            color: #fff; font-family: 'Nunito', sans-serif;
            font-size: 0.95rem; font-weight: 800;
            letter-spacing: 0.5px; cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s, filter 0.15s;
            box-shadow: 0 6px 20px rgba(37,211,102,0.35);
            animation: fieldIn 0.5s 0.45s ease both;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(37,211,102,0.5);
            filter: brightness(1.08);
        }
        .btn-register:active { transform: translateY(0); }

        /* \u2500\u2500 Login link \u2500\u2500 */
        .login-link {
            text-align: center;
            margin-top: 18px;
            font-size: 0.82rem;
            color: var(--muted);
            animation: fieldIn 0.5s 0.5s ease both;
        }
        .login-link a {
            color: var(--green); font-weight: 700;
            text-decoration: none;
            transition: color 0.15s;
        }
        .login-link a:hover { color: #fff; }

        /* \u2500\u2500 Message boxes \u2500\u2500 */
        .msg-box {
            margin-top: 16px;
            padding: 11px 16px;
            border-radius: 8px;
            font-size: 0.83rem; font-weight: 600;
            animation: cardIn 0.3s ease;
        }
        .msg-error {
            background: rgba(239,83,80,0.13);
            border: 1px solid rgba(239,83,80,0.3);
            color: var(--error);
        }
        .msg-success {
            background: rgba(37,211,102,0.1);
            border: 1px solid rgba(37,211,102,0.3);
            color: var(--green);
        }
        .msg-box ul { padding-left: 18px; }
        .msg-box ul li { margin-top: 3px; }

        /* \u2500\u2500 Watermark \u2500\u2500 */
        .watermark {
            position: fixed; bottom: 12px; right: 16px;
            font-size: 0.72rem; color: rgba(134,150,160,0.5);
            font-family: 'Nunito', sans-serif;
            pointer-events: none; user-select: none; z-index: 99;
        }
        .watermark span { color: var(--green); font-weight: 700; }
    </style>
</head>
<body>

<!-- Floating bubbles -->
<div class="bg-bubbles">
    <span></span><span></span><span></span><span></span>
    <span></span><span></span><span></span>
</div>

<div class="page">
    <div class="card">

        <!-- Logo -->
        <div class="logo">
            <div class="logo-icon">\u2728</div>
            <h1>Create Account</h1>
            <p>Join ChatApp today</p>
        </div>

        <!-- Form -->
        <form action="" method="post" id="regForm">
            <div class="fields-grid">

                <!-- Username -->
                <div class="field full">
                    <label for="name">Username</label>
                    <div class="input-wrap">
                        <span class="icon">\U0001f464</span>
                        <input type="text" id="name" name="name"
                               placeholder="Choose a username"
                               autocomplete="username">
                    </div>
                </div>

                <!-- Date of Birth -->
                <div class="field full">
                    <label for="dob">Date of Birth</label>
                    <div class="input-wrap">
                        <span class="icon">\U0001f382</span>
                        <input type="date" id="dob" name="dob">
                    </div>
                </div>

                <!-- Code -->
                <div class="field full">
                    <label for="code">Invite Code</label>
                    <div class="input-wrap">
                        <span class="icon">\U0001f511</span>
                        <input type="text" id="code" name="code"
                               placeholder="Enter invite code">
                    </div>
                </div>

                <!-- Password -->
                <div class="field">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <span class="icon">\U0001f512</span>
                        <input type="password" id="password" name="password"
                               placeholder="Create password"
                               oninput="checkStrength(this.value)">
                        <button type="button" class="eye-btn" id="eyeBtn1"
                                onclick="togglePw('password','eyeBtn1')">\U0001f441</button>
                    </div>
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="field">
                    <label for="Confirmpass">Confirm</label>
                    <div class="input-wrap">
                        <span class="icon">\u2705</span>
                        <input type="password" id="Confirmpass" name="Confirmpass"
                               placeholder="Repeat password">
                        <button type="button" class="eye-btn" id="eyeBtn2"
                                onclick="togglePw('Confirmpass','eyeBtn2')">\U0001f441</button>
                    </div>
                </div>

            </div><!-- /.fields-grid -->

            <button type="submit" name="clicked" class="btn-register">
                Create Account \u2192
            </button>

        </form>

        <!-- PHP logic -->
        <?php
        $USER_NAME = "super";
        $PASSWD    = "jamesBond@07";
        $SERVER    = "localhost";
        $DBNAME    = "RECORD";
        $pdo = new PDO('mysql:host=' . $SERVER . ';dbname=' . $DBNAME, $USER_NAME, $PASSWD);

        if (isset($_POST['clicked'])) {
            $name      = trim($_POST['name']);
            $dob       = $_POST['dob'];
            $passwd    = $_POST['password'];
            $cnfpsswd  = $_POST['Confirmpass'];
            $errors    = [];

            if (empty($name))     $errors[] = "Username can't be empty";
            if (empty($dob))      $errors[] = "Date of birth can't be empty";
            if (empty($passwd))   $errors[] = "Password can't be empty";
            if (empty($cnfpsswd)) $errors[] = "Please confirm your password";
            if (!empty($passwd) && !empty($cnfpsswd) && $passwd !== $cnfpsswd)
                                  $errors[] = "Passwords do not match";

            if (empty($errors)) {
                $stmt3 = $pdo->query("SELECT Uname FROM USERS");
                while ($row = $stmt3->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['Uname'] === $name) {
                        $errors[] = "Username already exists \u2014 try another";
                        break;
                    }
                }
            }

            if (!empty($errors)) {
                echo "<div class='msg-box msg-error'><ul>";
                foreach ($errors as $e) echo "<li>\u26a0\ufe0f $e</li>";
                echo "</ul></div>";
            } else {
                $stmt2 = $pdo->prepare("SELECT MAX(ID) FROM USERS");
                $stmt2->execute();
                $val = $stmt2->fetchColumn();
                $val = empty($val) ? 1 : $val + 1;

                $stmt1 = $pdo->prepare(
                    "INSERT INTO USERS (ID,Uname,DOB,PSSWD) VALUES (:id,:name,:dob,:password)"
                );
                $stmt1->execute([':id'=>$val, ':name'=>$name, ':dob'=>$dob, ':password'=>$passwd]);

                $pdo->exec("CREATE TABLE `$name`(
                    ID INT AUTO_INCREMENT PRIMARY KEY,
                    DATA VARCHAR(1000),
                    SENDER VARCHAR(20),
                    RECEIVER VARCHAR(20),
                    TYPE VARCHAR(10),
                    TIME VARCHAR(50),
                    DATE VARCHAR(20),
                    DIR VARCHAR(10)
                )");

                echo "<div class='msg-box msg-success'>\U0001f389 Account created! Redirecting to login\u2026</div>";
                echo "<script>setTimeout(function(){ window.location.href='login.php'; }, 1200);</script>";
            }
        }
        ?>

        <!-- Login link -->
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>

    </div>
</div>

<!-- Watermark -->
<div class="watermark">made by <span>@ Prince KP</span></div>

<script>
    function togglePw(fieldId, btnId) {
        var inp = document.getElementById(fieldId);
        var btn = document.getElementById(btnId);
        if (inp.type === 'password') {
            inp.type = 'text';
            btn.textContent = '\U0001f648';
        } else {
            inp.type = 'password';
            btn.textContent = '\U0001f441';
        }
    }

    function checkStrength(val) {
        var fill = document.getElementById('strengthFill');
        var score = 0;
        if (val.length >= 6)                    score++;
        if (val.length >= 10)                   score++;
        if (/[A-Z]/.test(val))                  score++;
        if (/[0-9]/.test(val))                  score++;
        if (/[^A-Za-z0-9]/.test(val))           score++;

        var pct   = (score / 5) * 100;
        var color = score <= 1 ? '#ef5350'
                  : score <= 3 ? '#FFA726'
                  : '#25D366';
        fill.style.width      = pct + '%';
        fill.style.background = color;
    }
</script>
</body>
</html>
