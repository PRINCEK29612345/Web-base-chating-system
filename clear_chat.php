<?php
    session_start();
    $sender = $_SESSION['name'];
    $receiver = $_SESSION['message'];
    $USER_NAME = "super";
    $PASSWD = "jamesBond@07";
    $SERVER = "localhost";
    $DBNAME = "RECORD";
    $dsn = 'mysql:host=localhost;dbname=' . $DBNAME;
    $pdo = new PDO($dsn, $USER_NAME, $PASSWD);
    //echo "Hi $sender";
    if(!empty($sender)){
        if($pdo){
            if($receiver != "TTLS") {
                $Q1 = "SELECT COUNT(*) FROM ".$sender." WHERE SENDER=:sender AND RECEIVER=:receiver";
                $stmt1 = $pdo->prepare($Q1);
                $params1 = array(
                    ':sender' => $sender,
                    ':receiver' => $receiver,
                );
                $stmt1->execute($params1);
                $rowCount1 = $stmt1->fetchColumn();

                $Q2 = "SELECT COUNT(*) FROM ".$sender." WHERE SENDER=:sender AND RECEIVER=:receiver";
                $stmt2 = $pdo->prepare($Q2);
                $params2 = array(
                    ':sender' => $receiver,
                    ':receiver' => $sender,
                );
                $stmt2->execute($params2);
                $rowCount2 = $stmt2->fetchColumn();

                if(!empty($rowCount1)) {

                    $Q = "DELETE FROM ".$sender." WHERE SENDER=:sender AND RECEIVER=:receiver";
                    $stmt = $pdo->prepare($Q);
                    $params = array(
                        ':sender' => $sender,
                        ':receiver' => $receiver,
                    );
                    $stmt->execute($params);
                    //if($stmt->execute($params)) {
                        //echo "All chat will be cleared";
                    //}
                }

                if(!empty($rowCount2)) {

                    $Q3 = "DELETE FROM ".$sender." WHERE SENDER=:sender AND RECEIVER=:receiver";
                    $stmt3 = $pdo->prepare($Q3);
                    $params3 = array(
                        ':sender' => $receiver,
                        ':receiver' => $sender,
                    );
                    $stmt3->execute($params3);
                    //if($stmt3->execute($params)) {
                        //echo "All chat will be cleared";
                    //}
                }
                if(!empty($rowCount2) || !empty($rowCount1)) {
                    echo "All chat will be cleared";
                }
                else {
                    echo "There is no chat to clear";
                }
            }
        elseif($receiver == "TTLS" && $sender == "PRINCE") {
            $Q4 = "SELECT COUNT(*) FROM TTLS";
            $stmt4 = $pdo->prepare($Q4);
            $stmt4->execute();
            $rowCount4 = $stmt4->fetchColumn();
            if(!empty($rowCount4)) {
                echo "All chat will be cleared";
                $Q5 = "DELETE FROM TTLS";
                $stmt5 = $pdo->prepare($Q5);
                $stmt5->execute();
            }
            else {
                echo "There is no chat to clear";
            }


        }
        else {
            echo "Only Admin Can Clear The Group Chats";
        }
        }
        
    }
?>
