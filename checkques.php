<?php
include 'dbconnect.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$user_id = $request->user_id;
$ans = $request->ans;
$hintused = $request->hintused;
$response = array();
$checkpoints = array(22, 35, 58);
$switch = array(22, 29, 33, 40, 45, 52, 56, 62, 60);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else {
  $sql = "SELECT * FROM `users` WHERE `user_id` = '" . $user_id . "';";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

      $curr = $row["cur_ques"];
      $branch = $row["path_choosen"];
      $pts = $row["points"];
      $q = $row["cur_ques"];
      $ques_sql = "SELECT * FROM `questions` WHERE `id` = '" . $q . "'";
      $ques_ans = mysqli_query($conn, $ques_sql);

      if (mysqli_num_rows($ques_ans) > 0) {

        while ($row1 = mysqli_fetch_assoc($ques_ans)) {

          $correct = $row1["ans"];
          $checkpoint = $row1["checkno"];
          $next = $row1["next"];

          if (in_array($curr, $switch)) {
            if ($branch != 1) {
              $next = $next + 1;
            }
          }

          if ($correct == $ans) {

            if (in_array($curr, $checkpoints)) {
              if ($curr == 22) {
                $pts += 99;
                $response["checkpass"] = true;
              } elseif ($curr == 35) {
                $pts += 200;
                $response["checkpass"] = true;
              } else {
                $pts += 100;
                $response["checkpass"] = true;
              }
            } else {
              $response["checkpass"] = false;
            }


            if ($row1['type'] != 2 && $row1['type'] != 5) {
              if ($checkpoint == 4) {
                if ($curr == 63 || $curr == 64) {
                  if ($hintused) {
                    $pts = $pts + 150;
                  } else {
                    $pts = $pts + 250;
                  }
                }
                if ($curr == 66 || $curr == 67) {
                  if ($hintused) {
                    $pts = $pts + 250;
                  } else {
                    $pts = $pts + 500;
                  }
                  $next = 71;
                }
              } else {
                if ($hintused) {
                  if ($curr < 20) {
                    $pts += 25;
                  } elseif ($curr > 20 && $curr < 60) {
                    $pts += 50;
                  } elseif ($curr > 61 && $curr < 65) {
                    $pts += 150;
                  } else {
                    $pts += 50;
                  }
                } else {
                  if ($curr < 20) {
                    $pts += 50;
                  } elseif ($curr > 20 && $curr < 60) {
                    $pts += 100;
                  } elseif ($curr > 61 && $curr < 65) {
                    $pts += 250;
                  } else {
                    $pts = $pts;
                  }
                }
              }
            }

            $update = "UPDATE `users` SET `cur_ques` = $next, `points` = " . $pts . " WHERE `user_id` = '" . $user_id . "';";
            mysqli_query($conn, $update);

            $response["next"] = $next;
            $response["correct"] = "true";
            $response["curr_ques"] = $curr;
          } else {
            $pts = $pts - 5;
            if ($pts < 0) {
              $pts = 0;
            }
            $update = "UPDATE `users` SET  `points` = " . $pts . " WHERE `user_id` = '" . $user_id . "';";
            mysqli_query($conn, $update);
            $response["curr_ques"] = $curr;
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
