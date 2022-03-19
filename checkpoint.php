<?php
session_start();
include 'dbconnect.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$user_id = $request->user_id;

$response = array();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    $sql = "SELECT * FROM `users` WHERE `user_id` = '" . $user_id . "';";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $curr = $row["cur_ques"];
            $ques_sql = "SELECT * FROM `questions` WHERE `id` = '" . $curr . "'";
            $result2 = mysqli_query($conn, $ques_sql);
            if (mysqli_num_rows($result2) > 0) {
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    $qno = $row2["qno"];
                }
            }
            if ($curr < 17) {
                //$pts = $row["points"] - ($qno - 1) * 10 - 5;
                $update = "UPDATE `users` SET `cur_ques` = " . 4 . ", `points` = " . $pts . "  WHERE `user_id` = '" . $user_id . "';";
            } else if (($curr > 22 && $curr < 36)) {
                //$pts = $row["points"] - ($qno - 6) * 10 - 5;
                $update = "UPDATE `users` SET `cur_ques` = " . 22 . ", `points` = " . $pts . "  WHERE `user_id` = '" . $user_id . "';";
            } else if (($curr > 36 && $curr < 59)) {
                //$pts = $row["points"] - ($qno - 6) * 10 - 5;
                $update = "UPDATE `users` SET `cur_ques` = " . 37 . ", `points` = " . $pts . "  WHERE `user_id` = '" . $user_id . "';";
            } else if (($curr > 62 && $curr < 68)) {
                $pts = $row["points"] - ($qno - 10) * 10 - 5;
                $update = "UPDATE `users` SET `cur_ques` = " . 6 . ", `points` = " . $pts . "  WHERE `user_id` = '" . $user_id . "';";
            } else if ($curr == 46) {
                $pts = 0;
                $update = "UPDATE `users` SET `cur_ques` = " . 52 . ", `points` = " . $pts . "  WHERE `user_id` = '" . $user_id . "';";
            }
            mysqli_query($conn, $update);
            $response["qno"] = $qno;
            $response["message"] = "eureka ";
        }
    } else {
        $response["message"] = "womp womp womp";
    }
    echo json_encode($response);
}
$conn->close();
