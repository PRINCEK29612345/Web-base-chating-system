<?php
session_start();
$sender = $_SESSION['name'];
$receiver = $_SESSION['message'];
//echo "<H1>hii</H1>";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!empty($_POST["data"])) {
        $idx = 0;
        $current_time = date('Y-m-d_H:i:s');
        $Date = date('Y-m-d');
        $Time = date('H:i');
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
                ':d' => $val,
                ':sender' => $sender,
                ':receiver' => $receiver,
                ':typex' => "msg",
                ':timex' => $Time,  
                ':date' => $Date,
                ':dir' => "NULL"
            );
            $stmt1->execute($params1);

            $Q3 = "INSERT INTO ".$receiver." (DATA,SENDER,RECEIVER,TYPE,TIME,DATE,DIR) VALUES (:d,:sender,:receiver,:typex,:timex,:date,:dir)";
            $stmt3 = $pdo->prepare($Q3);
            $params2 = array(
                ':d' => $val,
                ':sender' => $sender,
                ':receiver' => $receiver,
                ':typex' => "msg",
                ':timex' => $Time,
                ':date' => $Date,
                ':dir' => "NULL"
            );
            $stmt3->execute($params2);
        }
    }
}

?>