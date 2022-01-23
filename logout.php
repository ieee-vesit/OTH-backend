<?php
	session_start();
	$postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  $user_id = $request->user_id;
	session_unset();
	session_destroy();
	$response = [];
	echo json_encode($response);
?>
