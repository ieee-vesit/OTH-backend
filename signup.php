<?php
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Credentials:true");
// header("Content-Type: application/json", false);

// header('Content-Type: application/json');
// response.setHeader("Access-Control-Allow-Origin", "*");
// response.setHeader("Access-Control-Allow-Credentials", "false");
// response.setHeader("Access-Control-Allow-Methods", "GET,HEAD,OPTIONS,POST,PUT");
// response.setHeader("Access-Control-Allow-Headers", "Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
session_start();
include 'dbconnect.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$email = $request->email;
$user_id = $request->user_id;
$nickname = $request->nickname;
//$name=$request->name;
$response = array();
//$picture = $request->picture;
if (($email != null) || ($user_id  != null) || ($nickname != null)) {
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } else {
    $sql = "SELECT * FROM `users` WHERE `user_id` = '" . $user_id . "';";
    $points = 100;
    $insert = "INSERT INTO `users`(`email`, `user_id`, `nickname`,`points`) VALUES ('" . $email . "','" . $user_id . "','" . $nickname . "','" . $points . "');";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $_SESSION["user_id"] = $user_id;
        $response["completed"] = $row["completed"];
        $response["cur_ques"] = $row["cur_ques"];
        $response["user_id"] = $_SESSION["user_id"];
      }
    } else {
      if (mysqli_query($conn, $insert)) {
        $_SESSION["user_id"] = $user_id;
        $response["completed"] = 0;
        $response["cur_ques"] = -1;

        $response["user_id"] = $_SESSION["user_id"];
      } else {
        $response["completed"] = -1;
      }
    }
  }
  $conn->close();
} else {
  $response["message"] = "fuck off dude!";
}
echo json_encode($response);
