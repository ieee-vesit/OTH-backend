<?php
session_start();
include 'dbconnect.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$user_id = $request->user_id;
// $ques_id = $request->ques_id;
$response = array();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } else {
    $sql = "SELECT * FROM `users` WHERE `user_id` = '" . $user_id . "';";
    $result = mysqli_query($conn, $sql);
    

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          $curr = $row["cur_ques"];
          $points = $row["points"];

          if($curr<36){
            $curr += 1;
            $points -= 100;

            $update_sql = "UPDATE `users` SET `points` = ". $points . " , `cur_ques` = ". $curr . " WHERE `user_id` = '". $user_id . "';";

            $result2=mysqli_query($conn, $update_sql);
  
  
            $response["message"] = "skipped successfully";
            $response["result"] = $result2;
  
          }else{
              $response["result"] = false;
          }

          
        }
      }
        else{
          $response["message"] = "You are not authorized bro!";
        }
        echo json_encode($response);    
    }
$conn->close();
