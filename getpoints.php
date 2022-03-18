<?php
session_start();
include 'dbconnect.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$user_id = $request->user_id;
$response = array();
if ($_SESSION["user_id"] == $user_id) {
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        $sql = "SELECT * FROM `users` WHERE `user_id` = '" . $user_id . "';";
        $result = mysqli_query($conn, $sql);
        $response["result"] = $result;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $points = $result["points"];
                $response["points"] = $points;
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
