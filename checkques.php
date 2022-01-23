<?php
include 'dbconnect.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$user_id = $request->user_id;
// $ques_id = $request->ques_id;
$ans = $request->ans;
$response = array();

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else {
  $sql = "SELECT * FROM `users` WHERE `user_id` = '" . $user_id . "';";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $curr = $row["cur_ques"];
      $branch = $row["path_choosen"];
      if ($curr == 77) {
        $q = $row["cur_ques"] + $row["attempt"];
        $ques_sql = "SELECT * FROM `questions` WHERE `id` = '" . $q . "'";
      } else {
        $ques_sql = "SELECT * FROM `questions` WHERE `id` = '" . $row["cur_ques"] . "';";
      }
      if ($curr == 80) {
        $attempt = $row["attempt"];
        $attempt = $attempt + 1;
        $update = "UPDATE `users` SET `attempt` = " . $attempt . "  WHERE `user_id` = '" . $user_id . "';";
      }
      $ques_ans = mysqli_query($conn, $ques_sql);
      if (mysqli_num_rows($ques_ans) > 0) {
        while ($row1 = mysqli_fetch_assoc($ques_ans)) {
          $correct = $row1["ans"];
          if ($correct == $ans) {

            if ($row1['type'] != 2 && $row1['type'] != 3 && $row1['type'] != 4 && $row1['type'] != 5) {
              $pts = $row["points"] + 10;
              if ($curr == 46) {
                $response["qno"] = $curr;
                $update = "UPDATE `users` SET `cur_ques` = '48', `points` = " . $pts . " WHERE `user_id` = '" . $user_id . "';";
              } else {
                $update = "UPDATE `users` SET `cur_ques` = " . $row1["next"] . ", `points` = " . $pts . " WHERE `user_id` = '" . $user_id . "';";
              }
              mysqli_query($conn, $update);
              $response["qno"] = $curr;
              $response["msg"] = "somethin fucked";
            } else {
              if ($curr == 46) {
                $response["qno"] = $curr;
                $update = "UPDATE `users` SET `cur_ques` = '47' WHERE `user_id` = '" . $user_id . "';";
              } else if ($curr == 19) {
                $response["qno"] = $curr;
                if ($branch == 0) {
                  $update = "UPDATE `users` SET `cur_ques` = '28' WHERE `user_id` = '" . $user_id . "';";
                } else {
                  $update = "UPDATE `users` SET `cur_ques` = '55' WHERE `user_id` = '" . $user_id . "';";
                }
              } else {
                $response["qno"] = $curr;
                $response["msg"] = "somethin fishy";
                $update = "UPDATE `users` SET `cur_ques` = " . $row1["next"] . " WHERE `user_id` = '" . $user_id . "';";
              }
              mysqli_query($conn, $update);
            }
            $response["correct"] = "true";
            $response["curr_ques"] = $curr;
          } else {
            if ($curr == 46) {
              $update = "UPDATE `users` SET `cur_ques` = '51' WHERE `user_id` = '" . $user_id . "';";
            }

            $response["correct"] = "false";
          }
        }
      }
    }
  } else {
    $response["message"] = "You are not authorized bro!";
  }

  echo json_encode($response);
}
$conn->close();
