<!DOCTYPE html>
<html lang="en">
<?php
    session_start();
    $_SESSION['message'] = "";
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chats</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* \u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550
           DARK THEME (default)
        \u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550 */
        :root {
            --wa-green:          #25D366;
            --wa-dark-green:     #128C7E;
            --wa-teal:           #075E54;
            --wa-sidebar-bg:     #111B21;
            --wa-sidebar-hover:  #202C33;
            --wa-chat-bg:        #0B141A;
            --wa-panel-bg:       #202C33;
            --wa-input-bg:       #2A3942;
            --wa-border:         #2A3942;
            --wa-text-primary:   #E9EDEF;
            --wa-text-secondary: #8696A0;
            --wa-msg-out:        #005C4B;
            --wa-msg-in:         #202C33;
            --wa-icon:           #AEBAC1;
            --wa-shadow:         rgba(0,0,0,0.4);
        }

        /* \u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550
           LIGHT THEME
        \u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550\u2550 */
        body.light {
            --wa-sidebar-bg:     #FFFFFF;
            --wa-sidebar-hover:  #F0F2F5;
            --wa-chat-bg:        #EAE6DF;
            --wa-panel-bg:       #F0F2F5;
            --wa-input-bg:       #FFFFFF;
            --wa-border:         #E9EDEF;
            --wa-text-primary:   #111B21;
            --wa-text-secondary: #667781;
            --wa-msg-out:        #D9FDD3;
            --wa-msg-in:         #FFFFFF;
            --wa-icon:           #54656F;
            --wa-shadow:         rgba(0,0,0,0.1);
        }
        body.light .msg-out        { color: #111B21; }
        body.light .msg-in         { color: #111B21; }
        body.light .msg-out .msg-time { color: #667781; }
        body.light #ch1            { color: #111B21; }

        html, body {
            height: 100%; width: 100%;
            overflow: hidden;
            font-family: 'Nunito', sans-serif;
            background: var(--wa-chat-bg);
            transition: background 0.3s;
        }

        /* \u2500\u2500 Layout \u2500\u2500 */
        .app-shell { display: flex; height: 100vh; width: 100vw; }

        /* \u2500\u2500 LEFT SIDEBAR \u2500\u2500 */
        #lf {
            width: 30%; max-width: 360px; min-width: 260px;
            background: var(--wa-sidebar-bg);
            border-right: 1px solid var(--wa-border);
            display: flex; flex-direction: column;
            overflow: hidden;
            transition: background 0.3s, border-color 0.3s;
        }

        .sidebar-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 16px;
            background: var(--wa-panel-bg);
            height: 60px; flex-shrink: 0;
            transition: background 0.3s;
        }
        .sidebar-header .brand {
            font-size: 1.15rem; font-weight: 700;
            color: var(--wa-text-primary); letter-spacing: 0.3px;
        }
        .sidebar-header .icons { display: flex; gap: 14px; align-items: center; }
        .sidebar-header .icons svg {
            width: 20px; height: 20px; fill: var(--wa-icon);
            cursor: pointer; transition: fill 0.15s; flex-shrink: 0;
        }
        .sidebar-header .icons svg:hover { fill: var(--wa-text-primary); }

        /* \u2500\u2500 Theme toggle button \u2500\u2500 */
        #theme-toggle {
            background: var(--wa-input-bg);
            border: none; cursor: pointer;
            width: 42px; height: 24px;
            border-radius: 12px;
            position: relative;
            flex-shrink: 0;
            transition: background 0.3s;
            outline: none;
        }
        #theme-toggle::after {
            content: '';
            position: absolute;
            top: 3px; left: 3px;
            width: 18px; height: 18px;
            border-radius: 50%;
            background: var(--wa-green);
            transition: transform 0.3s, background 0.3s;
        }
        body.light #theme-toggle::after {
            transform: translateX(18px);
        }
        /* Sun / Moon icons inside toggle */
        #theme-toggle .t-icon {
            position: absolute;
            top: 50%; transform: translateY(-50%);
            font-size: 11px; line-height: 1;
            pointer-events: none;
            transition: opacity 0.2s;
        }
        #theme-toggle .t-moon { left: 5px; opacity: 1; }
        #theme-toggle .t-sun  { right: 4px; opacity: 0.5; }
        body.light #theme-toggle .t-moon { opacity: 0.5; }
        body.light #theme-toggle .t-sun  { opacity: 1; }

        /* Search bar */
        .sidebar-search {
            padding: 8px 12px;
            background: var(--wa-sidebar-bg);
            flex-shrink: 0;
            transition: background 0.3s;
        }
        .sidebar-search input {
            width: 100%; background: var(--wa-panel-bg);
            border: none; border-radius: 8px;
            padding: 8px 14px 8px 36px;
            color: var(--wa-text-primary);
            font-family: 'Nunito', sans-serif; font-size: 0.85rem; outline: none;
            transition: background 0.3s, color 0.3s;
        }
        .sidebar-search input::placeholder { color: var(--wa-text-secondary); }
        .search-wrap { position: relative; }
        .search-wrap svg {
            position: absolute; left: 10px; top: 50%; transform: translateY(-50%);
            width: 16px; height: 16px; fill: var(--wa-text-secondary); pointer-events: none;
        }

        /* Contact items \u2014 styled by Updateleft.php output */
        .contact-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 16px; cursor: pointer;
            border-bottom: 1px solid rgba(128,128,128,0.1);
            transition: background 0.15s;
        }
        .contact-item:hover { background: var(--wa-sidebar-hover); }
        .contact-avatar {
            width: 46px; height: 46px; border-radius: 50%;
            background: var(--wa-dark-green);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; flex-shrink: 0; overflow: hidden;
        }
        .contact-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .contact-info { flex: 1; min-width: 0; }
        .contact-name {
            font-size: 0.92rem; font-weight: 600; color: var(--wa-text-primary);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .contact-preview {
            font-size: 0.78rem; color: var(--wa-text-secondary);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;
        }

        /* \u2500\u2500 RIGHT CHAT PANEL \u2500\u2500 */
        .chat-panel {
            flex: 1; display: flex; flex-direction: column;
            background: var(--wa-chat-bg);
            position: relative; overflow: hidden;
            transition: background 0.3s;
        }
        .chat-panel::before {
            content: ''; position: absolute; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M10 10 Q15 5 20 10 Q25 15 30 10 Q35 5 40 10 Q45 15 50 10' stroke='%23182229' stroke-width='1.2' fill='none'/%3E%3C/svg%3E");
            opacity: 0.35; pointer-events: none; z-index: 0;
        }
        body.light .chat-panel::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Cpath d='M10 10 Q15 5 20 10 Q25 15 30 10 Q35 5 40 10 Q45 15 50 10' stroke='%23C8B8A2' stroke-width='1.2' fill='none'/%3E%3C/svg%3E");
            opacity: 0.5;
        }

        /* Chat header */
        #tptp {
            position: relative; z-index: 1;
            background: var(--wa-panel-bg); height: 60px;
            display: flex; align-items: center;
            padding: 0 16px; gap: 12px;
            border-bottom: 1px solid var(--wa-border);
            flex-shrink: 0;
            transition: background 0.3s, border-color 0.3s;
        }
        #tptp .chat-header-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: var(--wa-dark-green);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; overflow: hidden; flex-shrink: 0;
        }
        #tptp .chat-header-avatar img { width: 100%; height: 100%; object-fit: cover; }
        #tptp .chat-header-info { flex: 1; }
        #tptp .chat-header-info .chat-name { font-size: 0.95rem; font-weight: 700; color: var(--wa-text-primary); }
        #tptp .chat-header-info .chat-status { font-size: 0.75rem; color: var(--wa-green); }
        #tptp .header-actions { display: flex; gap: 20px; }
        #tptp .header-actions svg {
            width: 20px; height: 20px; fill: var(--wa-icon); cursor: pointer; transition: fill 0.15s;
        }
        #tptp .header-actions svg:hover { fill: var(--wa-text-primary); }

        /* \u2500\u2500 Message area \u2500\u2500 */
        #ch1 {
            position: relative; z-index: 1; flex: 1; overflow-y: auto;
            padding: 16px 6% 8px; display: flex; flex-direction: column; gap: 3px;
        }
        #ch1::-webkit-scrollbar { width: 5px; }
        #ch1::-webkit-scrollbar-thumb { background: var(--wa-border); border-radius: 4px; }

        .msg-out, .msg-in {
            max-width: 65%; padding: 7px 10px 6px; border-radius: 8px;
            font-size: 0.875rem; line-height: 1.45; position: relative; word-break: break-word;
            transition: background 0.3s;
        }
        .msg-out {
            background: var(--wa-msg-out); color: var(--wa-text-primary);
            align-self: flex-end; border-top-right-radius: 2px;
        }
        .msg-in {
            background: var(--wa-msg-in); color: var(--wa-text-primary);
            align-self: flex-start; border-top-left-radius: 2px;
        }
        .msg-time {
            font-size: 0.68rem; color: var(--wa-text-secondary);
            float: right; margin-left: 8px; margin-top: 3px;
        }
        .msg-out .msg-time { color: #8eada3; }

        .date-divider {
            align-self: center; background: var(--wa-panel-bg);
            color: var(--wa-text-secondary); font-size: 0.72rem;
            padding: 4px 12px; border-radius: 8px; margin: 8px 0;
        }

        /* \u2500\u2500 Bottom input bar \u2500\u2500 */
        #tpbt {
            position: relative; z-index: 1;
            background: var(--wa-panel-bg);
            border-top: 1px solid var(--wa-border);
            padding: 8px 12px; flex-shrink: 0;
            transition: background 0.3s, border-color 0.3s;
        }

        /* Emoji row */
        .emoji-row {
            display: flex; flex-wrap: wrap; gap: 2px;
            padding: 4px 0 6px;
            border-bottom: 1px solid var(--wa-border);
            margin-bottom: 8px;
        }
        .emoji-row button {
            background: none; border: none; cursor: pointer;
            font-size: 1.1rem; padding: 2px 3px; border-radius: 4px;
            transition: transform 0.1s, background 0.1s; line-height: 1;
        }
        .emoji-row button:hover { transform: scale(1.3); background: var(--wa-input-bg); }

        /* Input row */
        .input-row { display: flex; align-items: center; gap: 10px; }
        .input-row .icon-btn {
            background: none; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 50%;
            transition: background 0.15s; flex-shrink: 0;
        }
        .input-row .icon-btn:hover { background: var(--wa-input-bg); }
        .input-row .icon-btn svg { width: 22px; height: 22px; fill: var(--wa-icon); }

        #chat {
            flex: 1; background: var(--wa-input-bg);
            border: none; border-radius: 10px;
            padding: 10px 14px; color: var(--wa-text-primary);
            font-family: 'Nunito', sans-serif; font-size: 0.9rem;
            outline: none; resize: none;
            min-height: 44px; max-height: 120px; line-height: 1.4;
            transition: background 0.3s, color 0.3s;
        }
        #chat::placeholder { color: var(--wa-text-secondary); }

        #sen {
            width: 44px; height: 44px; border-radius: 50%;
            background: var(--wa-green); border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; transition: background 0.2s, transform 0.15s;
        }
        #sen:hover { background: var(--wa-dark-green); transform: scale(1.05); }
        #sen svg { width: 20px; height: 20px; fill: #fff; margin-left: 2px; }

        /* Upload / clear row */
        .upload-row {
            display: flex; align-items: center; gap: 10px;
            margin-top: 6px; padding-top: 6px;
        }
        .upload-row label {
            background: var(--wa-input-bg); color: var(--wa-text-secondary);
            font-size: 0.78rem; padding: 5px 12px; border-radius: 6px;
            cursor: pointer; transition: background 0.15s;
        }
        .upload-row label:hover { background: var(--wa-border); color: var(--wa-text-primary); }
        .upload-row button {
            background: var(--wa-dark-green); color: #fff;
            border: none; border-radius: 6px; padding: 5px 14px;
            font-family: 'Nunito', sans-serif; font-size: 0.78rem;
            cursor: pointer; transition: background 0.15s;
        }
        .upload-row button:hover { background: var(--wa-teal); }

        #clear-btn {
            background: none; border: none; color: #ef5350;
            font-size: 0.75rem; font-family: 'Nunito', sans-serif;
            cursor: pointer; margin-left: auto; padding: 4px 8px;
            border-radius: 4px; transition: background 0.15s;
        }
        #clear-btn:hover { background: rgba(239,83,80,0.1); }

        /* Hidden */
        #pop, #pop form { display: none; }

        /* Empty state */
        .empty-chat {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 14px; z-index: 1; position: relative;
        }
        .empty-chat svg { width: 80px; height: 80px; fill: #2a3942; }
        body.light .empty-chat svg { fill: #C8B8A2; }
        .empty-chat p { color: var(--wa-text-secondary); font-size: 0.9rem; }
        .empty-chat h3 { color: var(--wa-text-primary); font-size: 1.1rem; }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: var(--wa-border); border-radius: 4px; }
    </style>
</head>
<body>

<div id="pop">
    <form id='myForm' action='' method='post' enctype='multipart/form-data'>
        <input type='file' name='dp_to_upload' id='file_dp'>
    </form>
</div>

<div class="app-shell">

    <!-- \u2500\u2500 LEFT SIDEBAR \u2500\u2500 -->
    <div id="lf">
        <div class="sidebar-header">
            <span class="brand">\U0001f4ac Chats</span>
            <div class="icons">
                <!-- Theme toggle -->
                <button id="theme-toggle" title="Toggle light/dark theme">
                    <span class="t-icon t-moon">\U0001f319</span>
                    <span class="t-icon t-sun">\u2600\ufe0f</span>
                </button>
                <!-- New chat -->
                <svg viewBox="0 0 24 24" title="New chat"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
                <!-- Settings -->
                <svg viewBox="0 0 24 24" title="Settings"><path d="M19.14 12.94c.04-.3.06-.61.06-.94s-.02-.64-.07-.94l2.03-1.58a.49.49 0 0 0 .12-.61l-1.92-3.32a.488.488 0 0 0-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54a.484.484 0 0 0-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96a.476.476 0 0 0-.59.22L2.74 8.87a.47.47 0 0 0 .12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58a.49.49 0 0 0-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32a.47.47 0 0 0-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
            </div>
        </div>
        <div class="sidebar-search">
            <div class="search-wrap">
                <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                <input type="text" placeholder="Search or start new chat">
            </div>
        </div>
        <!-- contacts injected by Updateleft.php -->
    </div>

    <!-- \u2500\u2500 RIGHT CHAT PANEL \u2500\u2500 -->
    <div class="chat-panel">

        <!-- Chat header -->
        <div id="tptp">
            <div class="empty-chat" style="width:100%;flex-direction:row;justify-content:flex-start;gap:10px;padding:0;">
                <div class="chat-header-avatar">\U0001f4ac</div>
                <div class="chat-header-info">
                    <div class="chat-name" style="color:var(--wa-text-secondary);font-weight:500;">Select a chat</div>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div id="ch1">
            <div class="empty-chat" id="empty-state">
                <svg viewBox="0 0 303.083 303.083"><path d="M151.54 0C68.1 0 0 66.012 0 147.166c0 42.973 18.498 81.721 48.009 109.057L32.547 303.083l55.372-22.981c19.925 9.41 42.18 14.699 65.622 14.699 83.438 0 151.54-66.011 151.54-147.165S234.978 0 151.54 0z"/></svg>
                <h3>Open a conversation</h3>
                <p>Select a contact on the left to start chatting</p>
            </div>
        </div>

        <!-- Input bar -->
        <div id="tpbt">
            <?php
                $name = $_SESSION['message'];

                $person = [
                    1=>'\U0001f468',2=>'\U0001f469',3=>'\U0001f440',4=>'\U0001f44f',5=>'\U0001f47b',
                    6=>'\U0001f483',7=>'\U0001f481',8=>'\U0001f4a5',9=>'\U0001f602',10=>'\U0001f60f',
                    11=>'\U0001f60e',12=>'\U0001f610',13=>'\U0001f613',14=>'\U0001f63c',15=>'\U0001f912',
                    16=>'\U0001f6b6',17=>'\U0001f921',18=>'\U0001f918',19=>'\U0001f924',20=>'\U0001f922',
                    21=>'\U0001f92d',22=>'\U0001f927',23=>'\U0001f9df',24=>'\U0001f9db',25=>'\U0001f9dc',
                    26=>'\U0001f9de',27=>'\U0001f413',28=>'\U0001f31d',29=>'\U0001f525',30=>'\U0001f680'
                ];

                echo '<div class="emoji-row">';
                for ($i = 0; $i < 30; $i++) {
                    echo '<button onclick="EmojiConfig(\'' . $person[$i+1] . '\')">' . $person[$i+1] . '</button>';
                }
                echo '</div>';

                echo '<div class="input-row">';

                echo '<button class="icon-btn" onclick="document.getElementById(\'file_input\').click()" title="Attach file">';
                echo '<svg viewBox="0 0 24 24"><path d="M16.5 6v11.5c0 2.21-1.79 4-4 4s-4-1.79-4-4V5a2.5 2.5 0 0 1 5 0v10.5c0 .55-.45 1-1 1s-1-.45-1-1V6H10v9.5a2.5 2.5 0 0 0 5 0V5c0-2.21-1.79-4-4-4S7 2.79 7 5v12.5c0 3.04 2.46 5.5 5.5 5.5s5.5-2.46 5.5-5.5V6h-1.5z"/></svg>';
                echo '</button>';

                echo '<input type="text" id="chat" name="name" placeholder="Type a message\u2026" autocomplete="off">';

                echo '<button id="sen">';
                echo '<svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>';
                echo '</button>';

                echo '</div>';

                echo '<div class="upload-row">';
                echo "<form id='myForm' action='' method='post' enctype='multipart/form-data' style='display:none'>";
                echo "<input type='file' name='file_to_upload' id='file_input' onchange='UploadFile()'>";
                echo "</form>";
                echo "<label for='file_input'>\U0001f4ce Attach file</label>";
                echo "<button id='clear-btn' onclick='ClearChat()'>\U0001f5d1 Clear chat</button>";
                echo '</div>';
            ?>
        </div>

    </div><!-- /.chat-panel -->
</div><!-- /.app-shell -->

<script>
    /* \u2500\u2500 Theme toggle \u2500\u2500 */
    (function () {
        // Restore saved preference on load
        if (localStorage.getItem('theme') === 'light') {
            document.body.classList.add('light');
        }
    })();

    document.getElementById('theme-toggle').addEventListener('click', function () {
        var isLight = document.body.classList.toggle('light');
        localStorage.setItem('theme', isLight ? 'light' : 'dark');
    });

    /* \u2500\u2500 DP update \u2500\u2500 */
    function UpdateDP() {
        document.getElementById('file_dp').click();
        var fileEl = document.getElementById('file_dp');
        fileEl.onchange = function() {
            var file = fileEl.files[0];
            if (!file) return;
            var var1 = file.name;
            if (var1.includes(" ")) return alert('File name cannot contain spaces');
            var lastindex = var1.lastIndexOf('.');
            var exten = var1.substring(lastindex + 1).toLowerCase();
            if (exten === "jpg" || exten === "png") {
                UploadFile1(file);
                updateleft();
            } else {
                alert('File format unsupported');
            }
        };
    }

    var dataxprev = "";
    var chatprev = "";
    var lfprev = " ";

    /* \u2500\u2500 Open chat window \u2500\u2500 */
    function ChatWin(receiver) {
        document.getElementById('ch1').innerHTML = "";
        chatprev = "";
        document.getElementById('tptp').innerHTML =
            '<div class="chat-header-avatar">\U0001f464</div>' +
            '<div class="chat-header-info">' +
                '<div class="chat-name">' + receiver + '</div>' +
                '<div class="chat-status">online</div>' +
            '</div>' +
            '<div class="header-actions">' +
                '<svg viewBox="0 0 24 24" title="Video call"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg>' +
                '<svg viewBox="0 0 24 24" title="Voice call"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>' +
                '<svg viewBox="0 0 24 24" title="More options"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>' +
            '</div>';
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'MakeSession.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("Data=" + encodeURIComponent(receiver));
    }

    /* \u2500\u2500 Emoji \u2500\u2500 */
    function EmojiConfig(emoji) {
        var val = document.getElementById("chat").value;
        document.getElementById("chat").value = val + emoji;
        document.getElementById("chat").focus();
    }

    /* \u2500\u2500 Polling \u2500\u2500 */
    function updateChatLog() {
        fetch('UpdateChat.php')
            .then(res => res.text())
            .then(data => UpdateDiv(data));
    }

    function updateleft() {
        fetch('Updateleft.php')
            .then(res => res.text())
            .then(data => udpadechange(data));
    }

    function udpadechange(dataz) {
        if (dataz !== lfprev) {
            var lf = document.getElementById('lf');
            var existing = document.getElementById('lf-contacts');
            if (!existing) {
                existing = document.createElement('div');
                existing.id = 'lf-contacts';
                existing.style.cssText = 'flex:1;overflow-y:auto;min-height:0;';
                lf.appendChild(existing);
            }
            existing.innerHTML = dataz;
            lfprev = dataz;
        }
    }

    function UpdateDiv(datay) {
        if (datay !== chatprev) {
            var emptyState = document.getElementById('empty-state');
            if (emptyState) emptyState.remove();
            document.getElementById('ch1').innerHTML = datay;
            document.getElementById('ch1').scrollTop = document.getElementById('ch1').scrollHeight;
            chatprev = datay;
        }
    }

    function ClearChat() {
        fetch('clear_chat.php')
            .then(res => res.text())
            .then(data => alert(data));
    }

    /* \u2500\u2500 Notifications \u2500\u2500 */
    SeekPermission();
    function SeekPermission() {
        if (window.Notification) Notification.requestPermission();
    }
    function NotificationCheck() {
        fetch('Notify.php')
            .then(res => res.text())
            .then(data => Notify(data));
    }
    function Notify(datax) {
        if (window.Notification && datax !== "" && dataxprev !== datax) {
            new Notification("New message", { body: datax });
            dataxprev = datax;
        }
    }

    /* \u2500\u2500 Send message \u2500\u2500 */
    function UpdateMssg() {
        var val = document.getElementById("chat").value.trim();
        if (!val) return;
        document.getElementById("chat").value = "";
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'process.php', true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("data=" + encodeURIComponent(val));
    }

    /* \u2500\u2500 Chunked file upload \u2500\u2500 */
    async function UploadFile() {
        var file = document.getElementById('file_input').files[0];
        if (!file) return;
        var var1 = file.name;
        if (var1.includes(" ")) return alert('File name cannot contain spaces');
        const chunkSize = 5 * 1024 * 1024;
        const totalChunks = Math.ceil(file.size / chunkSize);
        for (let i = 0; i < totalChunks; i++) {
            const start = i * chunkSize;
            const end = Math.min(start + chunkSize, file.size);
            const blob = file.slice(start, end);
            const formData = new FormData();
            formData.append('chunk', blob);
            formData.append('fileName', file.name);
            formData.append('chunkIndex', i);
            formData.append('totalChunks', totalChunks);
            if (i === 0) showUploadPopup(file.name);
            const res = await fetch('upload_chunks.php', { method: 'POST', body: formData });
            await res.text();
            var pct = Math.ceil(((i + 1) / totalChunks) * 100);
            updateUploadPopup(pct, file.name);
        }
        completeUploadPopup();
    }

    async function UploadFile1(file) {
        if (!file) return alert('Please try again');
        if (file.name.includes(" ")) return alert('File name cannot contain spaces');
        const chunkSize = 5 * 1024 * 1024;
        const totalChunks = Math.ceil(file.size / chunkSize);
        for (let i = 0; i < totalChunks; i++) {
            const start = i * chunkSize;
            const end = Math.min(start + chunkSize, file.size);
            const blob = file.slice(start, end);
            const formData = new FormData();
            formData.append('chunk', blob);
            formData.append('fileName', file.name);
            formData.append('chunkIndex', i);
            formData.append('totalChunks', totalChunks);
            await fetch('UpdateDP.php', { method: 'POST', body: formData });
        }
    }

    /* \u2500\u2500 Event listeners \u2500\u2500 */
    document.getElementById('sen').addEventListener('click', UpdateMssg);
    document.getElementById('chat').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('sen').click();
        }
    });
    document.getElementById('chat').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    updateleft();
    setInterval(updateChatLog, 100);
    setInterval(NotificationCheck, 150);
    setInterval(updateleft, 500);

    /* \u2500\u2500 Upload Progress Popup \u2500\u2500 */
    function showUploadPopup(fileName) {
        document.getElementById('up-filename').textContent = fileName;
        document.getElementById('up-status').textContent = 'Preparing\u2026';
        document.getElementById('up-pct-text').textContent = '0%';
        document.getElementById('up-bar-fill').style.width = '0%';
        document.getElementById('up-bar-fill').classList.remove('up-complete');
        document.getElementById('up-cancel').style.display = 'inline-flex';
        document.getElementById('up-done').style.display = 'none';
        document.getElementById('upload-popup').style.display = 'flex';
    }
    function updateUploadPopup(pct, fileName) {
        document.getElementById('up-bar-fill').style.width = pct + '%';
        document.getElementById('up-pct-text').textContent = pct + '%';
        document.getElementById('up-status').textContent = 'Uploading ' + fileName + '\u2026';
    }
    function completeUploadPopup() {
        document.getElementById('up-bar-fill').style.width = '100%';
        document.getElementById('up-bar-fill').classList.add('up-complete');
        document.getElementById('up-pct-text').textContent = '100%';
        document.getElementById('up-status').textContent = 'Upload complete!';
        document.getElementById('up-cancel').style.display = 'none';
        document.getElementById('up-done').style.display = 'inline-flex';
        setTimeout(function() {
            document.getElementById('upload-popup').style.display = 'none';
        }, 2000);
    }
    function closeUploadPopup() {
        document.getElementById('upload-popup').style.display = 'none';
    }
</script>

<!-- \u2550\u2550 Windows-style Upload Progress Popup \u2550\u2550 -->
<div id="upload-popup" style="display:none;
     position:fixed; inset:0; z-index:99998;
     background:rgba(0,0,0,0.55);
     align-items:center; justify-content:center;">

    <div style="
        background:#1e2a32;
        border:1px solid #2d3b45;
        border-radius:10px;
        width:360px;
        box-shadow:0 20px 60px rgba(0,0,0,0.7);
        overflow:hidden;
        font-family:'Nunito',sans-serif;
        animation:upPopIn 0.2s ease;">

        <!-- Title bar -->
        <div style="
            background:#111b21;
            padding:10px 16px;
            display:flex; align-items:center; gap:10px;
            border-bottom:1px solid #2d3b45;">
            <span style="font-size:1rem;">\U0001f4e4</span>
            <span style="flex:1; font-size:0.88rem; font-weight:700; color:#E9EDEF;">
                Uploading File
            </span>
            <button onclick="closeUploadPopup()" style="
                background:none; border:none; cursor:pointer;
                color:#8696A0; font-size:1.1rem; line-height:1;
                padding:0 2px; transition:color 0.15s;"
                onmouseover="this.style.color='#ef5350'"
                onmouseout="this.style.color='#8696A0'">\u2715</button>
        </div>

        <!-- Body -->
        <div style="padding:20px 20px 18px;">

            <!-- File icon + name -->
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                <div style="
                    width:42px; height:42px; border-radius:8px;
                    background:#25D366; display:flex;
                    align-items:center; justify-content:center;
                    font-size:1.3rem; flex-shrink:0;">\U0001f4c4</div>
                <div style="flex:1; min-width:0;">
                    <div id="up-filename" style="
                        font-size:0.88rem; font-weight:700;
                        color:#E9EDEF;
                        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        filename.ext
                    </div>
                    <div id="up-status" style="
                        font-size:0.74rem; color:#8696A0; margin-top:3px;">
                        Preparing\u2026
                    </div>
                </div>
                <div id="up-pct-text" style="
                    font-size:0.88rem; font-weight:700;
                    color:#25D366; flex-shrink:0;">0%</div>
            </div>

            <!-- Progress track -->
            <div style="
                background:#2A3942; border-radius:6px;
                height:8px; overflow:hidden; margin-bottom:6px;">
                <div id="up-bar-fill" style="
                    height:100%; width:0%;
                    background: linear-gradient(90deg, #25D366, #128C7E);
                    border-radius:6px;
                    transition: width 0.3s ease;"></div>
            </div>

            <!-- Chunked dots animation -->
            <div style="
                display:flex; gap:4px; margin-bottom:18px; justify-content:center;">
                <span class="up-dot" style="animation-delay:0s"></span>
                <span class="up-dot" style="animation-delay:0.2s"></span>
                <span class="up-dot" style="animation-delay:0.4s"></span>
            </div>

            <!-- Buttons -->
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button id="up-cancel" onclick="closeUploadPopup()" style="
                    display:inline-flex; align-items:center; gap:6px;
                    background:#2A3942; border:none; border-radius:6px;
                    padding:7px 16px; color:#E9EDEF;
                    font-family:'Nunito',sans-serif; font-size:0.82rem;
                    cursor:pointer; transition:background 0.15s;"
                    onmouseover="this.style.background='#35464f'"
                    onmouseout="this.style.background='#2A3942'">
                    Cancel
                </button>
                <button id="up-done" onclick="closeUploadPopup()" style="
                    display:none; align-items:center; gap:6px;
                    background:#25D366; border:none; border-radius:6px;
                    padding:7px 16px; color:#fff;
                    font-family:'Nunito',sans-serif; font-size:0.82rem;
                    cursor:pointer;">
                    \u2713 Done
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes upPopIn {
    from { opacity:0; transform:scale(0.92) translateY(16px); }
    to   { opacity:1; transform:scale(1)    translateY(0); }
}
.up-dot {
    width:6px; height:6px; border-radius:50%;
    background:#25D366; display:inline-block;
    animation: upBounce 0.8s ease-in-out infinite;
}
@keyframes upBounce {
    0%,100% { transform:translateY(0);   opacity:0.4; }
    50%      { transform:translateY(-5px); opacity:1; }
}
#up-bar-fill.up-complete {
    background: linear-gradient(90deg, #25D366, #128C7E) !important;
    box-shadow: 0 0 8px rgba(37,211,102,0.5);
}
</style>

</body>
</html>