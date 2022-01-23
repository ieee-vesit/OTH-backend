<?php
	session_start();
	$_SESSION["user_id"] = "100";
	echo $_SESSION["user_id"];
?>