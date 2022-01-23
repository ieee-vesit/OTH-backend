<?php
session_start();
include 'dbconnect.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$user_id = $request->user_id;
$cur_trust = $request->trust;
$response = array();
$cur_ques;
//$trust = 1;



if ($_SESSION["user_id"] == $user_id) {

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        $sql = "SELECT * FROM `users` WHERE `user_id` = '" . $user_id . "';";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $cur_ques = $row["cur_ques"];
                //$trust = $row["trust"];
                $pts = $row["points"] - 3;
                $update = "UPDATE `users` SET `points` = " . $pts . "  WHERE `user_id` = '" . $user_id . "';";
                mysqli_query($conn, $update);
                $ques_details = "SELECT * FROM `questiongiver` WHERE `qno` = '" . $cur_ques . "';";
                $ques_res = mysqli_query($conn, $ques_details);
                if (mysqli_num_rows($ques_res) > 0) {
                    while ($row = mysqli_fetch_assoc($ques_res)) {
                        $response["cur_ques"] = $cur_ques;
                        $response["info"] = $row["info"];
                        if ($row["trust"] == $cur_trust) {
                            $val = 1;
                            $response["trust"] = $val;
                        } else {
                            $val = 0;
                            $response["trust"] = $val;
                        }
                    }
                }
            }
        } else {
            $response["message"] = "You are not authorized bro!";
        }
    }
} else {
    $response["message"] = "fuck off dude!";
}
$conn->close();
echo json_encode($response);
