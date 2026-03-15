<?php
    session_start();
    $sender = $_SESSION['name'];
    $receiver = $_SESSION['message'];
    $targetDir = "DP/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    
    $fileName = $_POST['fileName'];
    $chunkIndex = intval($_POST['chunkIndex']);
    $totalChunks = intval($_POST['totalChunks']);
    $tempFile = $_FILES['chunk']['tmp_name'];
    $finalFile = $targetDir . basename($fileName);
    $partialFile = $targetDir . $fileName . '.part';
    $out = fopen($partialFile, $chunkIndex === 0 ? 'wb' : 'ab');
    $in = fopen($tempFile, 'rb');
    while ($buff = fread($in, 4096)) {
        fwrite($out, $buff);
    }
    fclose($in);
    fclose($out);
    if ($chunkIndex + 1 === $totalChunks) {
        rename($partialFile, $finalFile);
        //echo "All chunks received. File assembled as: " . htmlspecialchars($fileName);
        $current_time = date('Y-m-d_H:i:s');
        $Date = date('Y-m-d');
        $Time = date('H:i:s');
        $val =  $_POST["data"];
        $USER_NAME = "super";
        $PASSWD = "jamesBond@07";
        $SERVER = "localhost";
        $DBNAME = "RECORD";
        $dsn = 'mysql:host=localhost;dbname=' . $DBNAME;
        $pdo = new PDO($dsn, $USER_NAME, $PASSWD);
        echo "<br>";
        if($pdo){

            $Q1 = "UPDATE USERS SET DP = ? WHERE Uname = ?";
            $stmt1 = $pdo->prepare($Q1);
            $stmt1->bindParam(1,$fileName);
            $stmt1->bindParam(2,$sender);
            $stmt1->execute();
        }
    } else {
        echo "Chunk " . ($chunkIndex + 1) . " of " . $totalChunks . " received.";
    }
?>