<?php
  include 'dbconnect.php';
  $response=[];
  $send=array();
  if ($conn->connect_error)
  {
    die("Connection failed: " . $conn->connect_error);
  }
  else {
    $sql = "SELECT `points`, `nickname` FROM `users` ORDER BY `points` DESC , `timestamp` ASC LIMIT 0 , 10";
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result) > 0){
      while ($row = mysqli_fetch_assoc($result)) {
        array_push($response,$row);
      }
    }
    $send["value"]=$response;
    echo json_encode($response);
  }
  $conn->close();
?>
