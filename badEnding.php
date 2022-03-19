<?php
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
            $next = 68;
            $update = "UPDATE `users` SET `cur_ques` = $next WHERE `user_id` = '" . $user_id . "';";
            mysqli_query($conn, $update);
            $response["message"] = "question number updated";
        }
    }
    echo json_encode($response);
}
$conn->close();
