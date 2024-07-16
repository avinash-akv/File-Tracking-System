<?php
include 'db_connect.php';
$id_ = $_GET['id'];
$qry = $conn->query("SELECT * FROM users where id = '$id_'")->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
include 'new_staff.php';
?>