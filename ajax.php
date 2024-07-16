<?php

date_default_timezone_set("Asia/kolkata");

$action = $_GET['action'];

include 'admin_class.php';

$crud = new Action();
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}

if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}

if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'save_password'){
	$save = $crud->save_password();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}

if($action == 'save_dept'){
	$save = $crud->save_dept();
	file_put_contents("log.txt",$save);
	if($save)
		echo $save;
}
if($action == 'delete_dept'){
	$save = $crud->delete_dept();
	if($save)
		echo $save;
}

if($action == 'save_file'){
	$save = $crud->save_file();
	if($save)
		echo $save;
}
if($action == 'delete_file'){
	$save = $crud->delete_file();
	if($save)
		echo $save;
}
if($action == 'get_users'){
	$save = $crud->get_users();
	if($save)
		echo $save;
}
if($action == 'send_file'){
	$save = $crud->send_file();
	if($save)
		echo $save;
}
if($action == 'accept_file'){
	$save = $crud->accept_file();
	if($save)
		echo $save;
}
if($action == 'return_file'){
	$save = $crud->return_file();
	if($save)
		echo $save;
}
if($action == 'forward_file'){
	$save = $crud->forward_file();
	if($save)
		echo $save;
}

?>
