<?php
include 'dbconnect.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$user_id = $request->user_id;
// $ques_id = $request->ques_id;
$ans = $request->ans;
$response = array();
$checkpoints = array(22,35, 58);
$switch = array(22, 29, 33, 40, 45, 52, 56, 62, 65);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else {
  $sql = "SELECT * FROM `users` WHERE `user_id` = '" . $user_id . "';";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $curr = $row["cur_ques"];
    //   if ($curr == 77) {
    //     $q = $row["cur_ques"] + $row["attempt"];
    //     $ques_sql = "SELECT * FROM `questions` WHERE `id` = '" . $q . "'";
    //   } else {
    //     $ques_sql = "SELECT * FROM `questions` WHERE `id` = '" . $row["cur_ques"] . "';";
    //   }
    //   if ($curr == 80) {
    //     $attempt = $row["attempt"];
    //     $attempt = $attempt + 1;
    //     $update = "UPDATE `users` SET `attempt` = " . $attempt . "  WHERE `user_id` = '" . $user_id . "';";
    //   }
      $branch = $row["path_choosen"];
      $pts = $row["points"];

      $q = $row["cur_ques"];
      $ques_sql = "SELECT * FROM `questions` WHERE `id` = '" . $q . "'";
      $ques_ans = mysqli_query($conn, $ques_sql);

      if (mysqli_num_rows($ques_ans) > 0) {
        while ($row1 = mysqli_fetch_assoc($ques_ans)) {
            // echo("question");
          $correct = $row1["ans"];
          $checkpoint = $row1["checkno"];
          $next = $row1["next"];

          if(in_array($curr, $switch)){
            if ($branch != 1){
                $next = $next + 1;
            }
        }

          if ($correct == $ans) {
            //   echo("correct ans");
            if (in_array($curr, $checkpoints)){
                // echo("checkpoint end");
                if($curr == 22){
                    if($pts>=500){
                        $pts += 500;
                        $response["checkpass"] = true;
                    }else{
                        $next = 1;
                        $response["checkpass"] = false;
                        $response["msg"] = "you need minimum 500 points to paas this checkpoint<br>you have to retry!"; 
                    }
                }elseif($curr == 35){
                    if($pts>=500){
                        $pts += 250;
                        $response["checkpass"] = true;
                    }else{
                        $next = 25;
                        $response["checkpass"] = false;
                        $response["msg"] = "you need minimum 500 points to paas this checkpoint<br>you have to retry!"; 
                    }
                }else{
                    if($pts>=300){
                        $pts += 100;
                        $response["checkpass"] = true;
                    }else{
                        $next = 36;
                        $response["checkpass"] = false;
                        $response["msg"] = "you need minimum 300 points to paas this checkpoint<br>you have to retry!"; 
                    }
                }
            }else{
                $response["checkpaas"] = false;
            }
            if ($row1['type'] != 2 && $row1['type'] != 5) {
              if($checkpoint == 4){
                  $pts += 500;
                  if($curr == 66 || $curr == 67){
                      $next = 71;
                  }
              }else{
                  $pts += 100;
              }



            //   if ($curr == 46) {
            //     $update = "UPDATE `users` SET `cur_ques` = '48', `points` = " . $pts . " WHERE `user_id` = '" . $user_id . "';";
            //   } else {
            //     $update = "UPDATE `users` SET `cur_ques` = " . $row1["next"] . ", `points` = " . $pts . " WHERE `user_id` = '" . $user_id . "';";
            //   }
            //   $response["qno"] = $curr;
            //   $response["msg"] = "somethin fucked";
            // } 

            // else {
            //   if ($curr == 46) {
            //     $response["qno"] = $curr;
            //     $update = "UPDATE `users` SET `cur_ques` = '47' WHERE `user_id` = '" . $user_id . "';";
            //   } else if ($curr == 19) {
            //     $response["qno"] = $curr;
            //     if ($branch == 0) {
            //       $update = "UPDATE `users` SET `cur_ques` = '28' WHERE `user_id` = '" . $user_id . "';";
            //     } else {
            //       $update = "UPDATE `users` SET `cur_ques` = '55' WHERE `user_id` = '" . $user_id . "';";
            //     }
            //   } else {
            //     $response["qno"] = $curr;
            //     $response["msg"] = "somethin fishy";
            //     $update = "UPDATE `users` SET `cur_ques` = " . $row1["next"] . " WHERE `user_id` = '" . $user_id . "';";
            //   }
            //   mysqli_query($conn, $update);
            // }
          } 
          $update = "UPDATE `users` SET `cur_ques` = $next, `points` = " . $pts . " WHERE `user_id` = '" . $user_id . "';";
          mysqli_query($conn, $update);

          $response["next"] = $next;
          $response["correct"] = "true";
          $response["curr_ques"] = $curr;

        }else {
            $response["correct"] = "false";
            $pts -= 100;
            $update = "UPDATE `users` SET `cur_ques` = $next, `points` = " . $pts . " WHERE `user_id` = '" . $user_id . "';";
            mysqli_query($conn, $update);  
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
