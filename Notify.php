<?php
session_start();
$sender = $_SESSION['name'];
if(!empty($sender)) {
    $USER_NAME = "super";
    $PASSWD = "jamesBond@07";
    $SERVER = "localhost";
    $DBNAME = "RECORD";
    $dsn = 'mysql:host=localhost;dbname=' . $DBNAME;
    $pdo = new PDO($dsn, $USER_NAME, $PASSWD);
    //echo "<br>";
    if($pdo){
        //echo "connected";
        $Q2 = "SELECT MAX(ID) FROM ".$sender;
        $stmt2 = $pdo->prepare($Q2);
        $stmt2->execute();
        $val = $stmt2->fetchColumn();
        //echo $val;
        $Q2 = "SELECT * FROM ".$sender." WHERE ID=:id";
        $stmt2 = $pdo->prepare($Q2);
        $params2 = array(
            ':id' => $val,
        );
        $stmt2->execute($params2);
        $result = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            //echo "hi".$row['RECEIVER'];
            //echo "hi".$result['RECEIVER'];
            if($row['RECEIVER'] == $sender) {
                echo $row['SENDER']." : ".$row['DATA']."   ";
                //echo "<BR>";
            }
            else {
                echo "";
            }
        }

        /*$Q4 = "SELECT MAX(ID) FROM TTLS";
        $stmt4 = $pdo->prepare($Q4);
        $stmt4->execute();
        $val4 = $stmt4->fetchColumn();

        $Q5 = "SELECT * FROM TTLS WHERE ID=:id";
        $stmt5 = $pdo->prepare($Q5);
        $params5 = array(
            ':id' => $val4,
        );
        $stmt5->execute($params5);
        $result5 = $stmt5->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result5 as $row5) {
            //echo "hi".$row['RECEIVER'];
            //echo "hi".$result['RECEIVER'];
            if($row5['SENDER'] != $sender ) {
                echo "TTLS : ".$row5['SENDER']." : ".$row5['DATA'];
            }
            else {
                echo "";
            }
        }*/





    }
}
