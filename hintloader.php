<?php
session_start();
include 'dbconnect.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$user_id = $request->user_id;
$response = array();
$cur_ques;
$cur_type;
if ($_SESSION["user_id"] == $user_id) {
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        $sql = "SELECT * FROM `users` WHERE `user_id` = '" . $user_id . "';";
        $result = mysqli_query($conn, $sql);
        $response["result"] = $result;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $cur_ques = $row["cur_ques"];
                $ques_details = "SELECT * FROM `hints` WHERE `id` = '" . $cur_ques . "';";
                $ques_res = mysqli_query($conn, $ques_details);
                if (mysqli_num_rows($ques_res) > 0) {
                    while ($row = mysqli_fetch_assoc($ques_res)) {
                        $response["cur_ques"] = $cur_ques;
                        $response["hint"] = $row["hint"];
                    }
                }
            }
        } else {
            $response["message"] = "You are not authorized bro!";
        }
    }
} else {
    $response["message"] = "go away dude!";
}
$conn->close();
echo json_encode($response);
