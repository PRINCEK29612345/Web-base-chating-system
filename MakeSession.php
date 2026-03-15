<?php
    session_start();
    $receiver = $_POST["Data"];
    $_SESSION['message'] = $receiver;
    
?>