<?php
  include 'dbconnect.php';
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  $user_id = $request->user_id;
  $ques_no = $request->ques_no;
  $ques_shift = $request->ques_shift;
  $response = array();

  if ($conn->connect_error)
  {
      die("Connection failed: " . $conn->connect_error);
  }
  else{
    $sql = 'SELECT * FROM `users` WHERE `user_id` = "'.$user_id.'";';
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result) > 0){
      while($row = mysqli_fetch_assoc($result)){
        if($ques_no == $row['cur_ques']){

          $check = "SELECT * FROM `split` WHERE `split_id` = ".$ques_no.";";
          $checkres = mysqli_query($conn,$check);
          if(mysqli_num_rows($checkres) > 0){
            $makesure = false;
            while($rows = mysqli_fetch_assoc($checkres)){
              if($ques_shift == $rows["next_quest"]){
                $update = 'UPDATE `users` SET `cur_ques`='.$ques_shift.', `path_choosen`='.$ques_shift.' WHERE `user_id`="'.$user_id.'";';
                mysqli_query($conn,$update);
                $response["success"]=1;
                $makesure = true;
                break;
              }
            }
            if(!$makesure){
              $response["success"]=0;
            }
          }
          else {
            $response["success"]=0;
          }
        }
        else{
          $response["success"]=0;
        }
      }
    }
  }
  echo json_encode($response);
  $conn->close();
 ?>
