<?php
include 'dbconnect.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$user_id = $request->user_id;
$response = [];
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else {
  $sql = 'UPDATE `users` SET `visited_satan`= 1 WHERE `user_id`="' . $user_id . '";';
  if (mysqli_query($conn, $sql)) {
    $response["success"] = 1;
  } else {
    $response["success"] = 0;
  }
  $response["cur_ques"] = 71;
  echo json_encode($response);
}
$conn->close();
