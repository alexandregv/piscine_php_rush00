<?php

function session_log_user($id, $first_name, $last_name, $mail, $admin)
{
	$_SESSION['id'] = $id;
	$_SESSION['first_name'] = $first_name;
	$_SESSION['last_name'] = $last_name;
	$_SESSION['mail'] = $mail;
	$_SESSION['admin'] = $admin;
}

?>