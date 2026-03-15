<?php
    session_start();
    $sender = $_SESSION['name'];
    $receiver = $_SESSION['message'];
    $targetDir = "uploads/";
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

            $Q1 = "INSERT INTO ".$sender." (DATA,SENDER,RECEIVER,TYPE,TIME,DATE,DIR) VALUES (:d,:sender,:receiver,:typex,:timex,:date,:dir)";
            $stmt1 = $pdo->prepare($Q1);
            $params1 = array(
                ':d' => $fileName,
                ':sender' => $sender,
                ':receiver' => $receiver,
                ':typex' => "file",
                ':timex' => $Time,
                ':date' => $Date,
                ':dir' => $targetDir

            );
            $stmt1->execute($params1);

            $Q2 = "INSERT INTO ".$receiver." (DATA,SENDER,RECEIVER,TYPE,TIME,DATE,DIR) VALUES (:d,:sender,:receiver,:typex,:timex,:date,:dir)";
            $stmt2 = $pdo->prepare($Q2);
            $params2 = array(
                ':d' => $fileName,
                ':sender' => $sender,
                ':receiver' => $receiver,
                ':typex' => "file",
                ':timex' => $Time,
                ':date' => $Date,
                ':dir' => $targetDir

            );
            $stmt2->execute($params2);
        }
    } else {
        echo "Chunk " . ($chunkIndex + 1) . " of " . $totalChunks . " received.";
    }
?>