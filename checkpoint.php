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
                $pts = $row["points"] - ($qno - 1) * 10 - 5;
                $update = "UPDATE `users` SET `cur_ques` = " . 12 . ", `points` = " . $pts . "  WHERE `user_id` = '" . $user_id . "';";
            } else if (($curr > 23 && $curr < 32)) {
                $pts = $row["points"] - ($qno - 6) * 10 - 5;
                $update = "UPDATE `users` SET `cur_ques` = " . 26 . ", `points` = " . $pts . "  WHERE `user_id` = '" . $user_id . "';";
            } else if (($curr > 57 && $curr < 66)) {
                $pts = $row["points"] - ($qno - 6) * 10 - 5;
                $update = "UPDATE `users` SET `cur_ques` = " . 58 . ", `points` = " . $pts . "  WHERE `user_id` = '" . $user_id . "';";
            } else if (($curr > 32 && $curr < 38)) {
                $pts = $row["points"] - ($qno - 10) * 10 - 5;
                $update = "UPDATE `users` SET `cur_ques` = " . 34 . ", `points` = " . $pts . "  WHERE `user_id` = '" . $user_id . "';";
            } else if (($curr > 66 && $curr < 71)) {
                $pts = $row["points"] - ($qno - 10) * 10 - 5;
                $update = "UPDATE `users` SET `cur_ques` = " . 67 . ", `points` = " . $pts . "  WHERE `user_id` = '" . $user_id . "';";
            } else if ($curr == 46) {
                $pts = 0;
                $update = "UPDATE `users` SET `cur_ques` = " . 52 . ", `points` = " . $pts . "  WHERE `user_id` = '" . $user_id . "';";
            }
            mysqli_query($conn, $update);
            $response["qno"] = $qno;
            $response["message"] = "eureka bitch";
        }
    } else {
        $response["message"] = "womp womp womp";
    }
    echo json_encode($response);
}
$conn->close();
