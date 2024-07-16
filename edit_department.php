<?php
include 'db_connect.php';
$id_ = $_GET['id'];
$qry = $conn->query("SELECT * FROM departments where id = '$id_'")->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
include 'new_department.php';
?>