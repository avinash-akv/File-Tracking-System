<?php
session_start();
ini_set('display_errors', 1); //display errors on screen 
require 'assets/PHPMailer/src/Exception.php';
require 'assets/PHPMailer/src/PHPMailer.php';
require 'assets/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
// Enable error reporting through log file
// error_reporting(E_ALL);
// ini_set('display_errors', 0);
// $errorLogFile = 'log.txt'; 
// ini_set('error_log', $errorLogFile);

Class Action {
	private $db;

	public function __construct() {
   		include 'db_connect.php';
    	$this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	}

	//login and logout routines
	function login(){
		extract($_POST);

		//check if new user --> password reset required
		$qry = $this->db->query("SELECT id FROM users where email ='".$email."' and type = 2");
		if($qry->num_rows == 1){
			//staff exists
			//check if new user
			$arr = $qry->fetch_assoc();
			$id = $arr['id'];
			$qry = $this->db->query("SELECT user_id FROM new_user where user_id = '".$id."'");

			if($qry->num_rows > 0){
				//new user confirmed
				return 0;
			}

			//not a new user ---> continue login
		}
		
		//login
		$qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where email = '".$email."' and password = '".md5($password)."' and active = 1 ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password'){
					$_SESSION['login_'.$key] = $value;
				}
			}
			return 1; //authentication successful
		}else{
			return 2; //failed to authenticate
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	
	//department management routines
	function save_dept(){
		$id = $_POST['id'];
		$name = $_POST['name'];
		$contact = $_POST['contact'];
		$email = $_POST['email'];

		// Specify the path to your text file
		$filePath = 'log2.txt';
		file_put_contents($filePath, "heolo" . PHP_EOL, FILE_APPEND);
		

		//update or new
		$check = $this->db->query("SELECT id FROM departments WHERE id = '$id' and active = 1");

		if($check->num_rows > 0){
			//already exists - update
			$save = $this->db->prepare("UPDATE departments SET name=?, contact=?, email=? WHERE id=?");
			$save->bind_param("ssss", $name, $contact, $email, $id);
		}else{
			//new entry
			$save = $this->db->prepare("INSERT INTO departments (id, name, contact, email) VALUES (?, ?, ?, ?)");
			$save->bind_param("ssss", $id, $name, $contact, $email);
		}

		if($save->execute()){
			return 1;
		}
	}
	function delete_dept(){
		extract($_POST);
		$delete = $this->db->query("UPDATE departments SET active = 0 where id = '$id'");
		if($delete){
			return 1;
		}
	}

	//staff(user) management routines
	function save_user(){
		$id = $_POST['id'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$password = $id.'#'. rand(10000,99999);   //initial random password
		$email = $_POST['email'];
		$dept_id = $_POST['dept_id'];

		//update or new
		$check = $this->db->query("SELECT id FROM users WHERE id = '$id'");
		$email_check = $this->db->query("SELECT email FROM users WHERE email = '$email' and active = 1");
		if($check->num_rows > 0){
			//already exists - update
			$save = $this->db->prepare("UPDATE users SET firstname=?,lastname=?, email=?,dept_id=? WHERE id=?");
			$save->bind_param("sssss", $firstname, $lastname, $email,$dept_id, $id);
		}else{
			//new entry
			if($email_check->num_rows > 0){
				//email already registered
				return 2;
			}
			$save = $this->db->prepare("INSERT INTO users (id, firstname,lastname, email,password,dept_id) VALUES (?, ?, ?, ?, ?, ?)");
			$save->bind_param("ssssss", $id, $firstname,$lastname, $email,$password,$dept_id);
		}

		if($save->execute()){
			$save = $this->db->prepare("INSERT INTO new_user (user_id) VALUES (?)");
			$save->bind_param("s",$id);
			if($save->execute()){
				//send password to email
				try{
					$mail = new PHPMailer(true);
					$mail->isSMTP();
					$mail->Host = 'smtp.gmail.com';
					$mail->SMTPAuth = true;
					$mail->Port = 465;
					$mail->Username = 'bhanupratap.gkpcc@gmail.com';  //sender
					$mail->Password = 'qwck iixk yoxv avxg';
					$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
					$mail->setFrom('bhanupratap.gkpcc@gmail.com'); //sender
					$mail->addAddress($email); //receiver
					$mail->isHTML(true);
					$mail->Subject = 'FTS KNIT | new user first time login password'; 
					$mail->Body = "Your password for first time login on FTS-Knit, Sultanpr is : ".$password."
						Thank You!
						File Tracking System - K.N.I.T Sultanpur";
	 
					$mail->send();
				}catch(Exception $e){
					error_log('Error: ' . $e->getMessage());
					return 1;
				}
				return 1;
			}
		}
	}
	function save_password(){
		$id = $_POST['user_id'];
		$old_pass = $_POST['old_password'];
		$password = md5($_POST['_password']);

		$save = $this->db->query("select password from users where id='".$id."' and password ='".$old_pass."'");
		if($save->num_rows == 1){
			//old password matched
			//save new
			$save = $this->db->prepare("update users set password =? where id=?");
			$save->bind_param("ss",$password,$id);
			if($save->execute()){
				$save = $this->db->query("delete from new_user where user_id='".$id."'");
				if($save){
					return 0;
				}
				//error
				return 1;
			}
			//errorr 
			return 1;
		}else{
			//wrong old passowrd
			return 2;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("UPDATE users SET active = 0 where id = '$id'");
		if($delete)
			return 1;
	}
	
	//file management routines
	function save_file(){
		$id = $_SESSION['login_id'];
		$file_id = $_POST['file_id'];
		$file_name = $_POST['file_name'];
		$remarks =$_POST['remarks'];
		// error_log(print_r($_POST, true));
		//update or new
		$check = $this->db->query("SELECT id FROM files WHERE id = '$file_id'");

		if($check->num_rows > 0){
			//already exists - update
			$save = $this->db->prepare("UPDATE files SET file_name=?,file_desc=? WHERE id=?");
			$save->bind_param("sss", $file_name, $file_desc, $file_id);
		}else{
			//new entry
			$save = $this->db->prepare("INSERT INTO files (id,file_name,creator_id,file_desc) VALUES (?, ?, ?, ?)");
			$save->bind_param("ssss", $file_id, $file_name,$id, $remarks);
		}

		if($save->execute()){
			return 1;
		}
	}
	function delete_file(){
		extract($_POST);
		$delete = $this->db->query("UPDATE files SET active = 0 where id = '$id'");
		if($delete){
			return 1;
		}
	}
	

	function get_users(){
		if (isset($_POST['dept_id'])) {
			$deptId = $_POST['dept_id'];
		
			$usersQuery = $this->db->query("SELECT id, CONCAT(firstname,' ',lastname) as name FROM users WHERE dept_id = '".$deptId."' and active=1");
			
			// Build options for the user dropdown
			$options = '<option value=""></option>';
			while ($userRow = $usersQuery->fetch_assoc()) {
				$options .= '<option value="' . $userRow['id'] . '">' .$userRow['id']." | ". $userRow['name'] . '</option>';
			}
		
			echo $options;
		}
	}

	function send_file(){
		$id = $_SESSION['login_id'];
		$file_id = $_POST['file_id'];
		$sender_notes = $_POST['remarks'];
		$user_id =$_POST['user_id'];
		$status = 1;
		
		//generate unique reference number
		$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$ref_no = '';
		// Loop until a unique code is generated
		do {
			$ref_no = substr(str_shuffle($chars), 0, 10);
			$ref_no = 'SND-'.$ref_no;
			$chk = $this->db->query("SELECT * FROM transactions WHERE reference_number = '$ref_no'")->num_rows;
		} while ($chk > 0);
		

		$save = $this->db->prepare("INSERT INTO transactions (reference_number,sender_id,recipient_id,file_id,sender_notes,status) VALUES (?,?,?,?,?,?)");
		$save->bind_param("sssssi",$ref_no,$id, $user_id, $file_id,$sender_notes, $status);
		
		if($save->execute()){
			try{
				$qry = $this->db->query("SELECT email FROM users WHERE id = '".$user_id."' AND active = 1");
				$result = $qry->fetch_assoc();
				// Extract the email from the result array
				$email = $result['email'];

				$mail = new PHPMailer(true);
				$mail->isSMTP();
				$mail->Host = 'smtp.gmail.com';
				$mail->SMTPAuth = true;
				$mail->Port = 465;
				$mail->Username = 'bhanupratap.gkpcc@gmail.com';  //sender
				$mail->Password = 'qwck iixk yoxv avxg';
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
				$mail->setFrom('bhanupratap.gkpcc@gmail.com'); //sender
				$mail->addAddress($email); //receiver
				$mail->isHTML(true);
				$mail->Subject = 'FTS KNIT | incoming file'; 
				$mail->Body = "Hello! \n
					File ID : ".$file_id."\n
					From : ".$id."\n
					Has been sent to you , It will reach to you within 3 days. We kindly request your attention to this matter and ask that you take any necessary actions on the file within the designated time frame. 
					\n
					Thank You!\n
					File Tracking System - K.N.I.T Sultanpur";
 
				$mail->send();
			}catch(Exception $e){
				error_log('Error: ' . $e->getMessage());
				return 1;
			}
			return 1;
		}
	}

	function accept_file(){
		extract($_POST);
		
		$update = $this->db->query("UPDATE transactions SET receiver_notes = '$rec_note',status = 2,receiver_timestamp = current_timestamp() WHERE reference_number = '$ref'");
		
		if($update){
			return 1;
		}
	}
	function forward_file(){
		extract($_POST);

		$update = $this->db->query("UPDATE transactions SET receiver_notes = '$rec',status = 2,receiver_timestamp = current_timestamp() WHERE reference_number = '$ref'");
		
		if($update){
			//create a new transaction for forwarded file
			$details = $this->db->query("SELECT * FROM transactions WHERE reference_number = '$ref'")->fetch_assoc(); //key-value pair
			$status = 1;
			
			//generate unique reference number
			$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$ref_no = '';
			//Loop until a unique code is generated
			do {
				$ref_no = substr(str_shuffle($chars), 0, 10);
				$ref_no = 'FWD-'.$ref_no;
				$chk = $this->db->query("SELECT * FROM transactions WHERE reference_number = '$ref_no'")->num_rows;
			} while ($chk > 0);

			$new_trans = $this->db->prepare("INSERT INTO `transactions` (`reference_number`,`sender_id`,`recipient_id`,`file_id`,`sender_notes`,`status`) VALUES (?,?,?,?,?,?)");
			$new_trans->bind_param("sssssi",$ref_no,$details['recipient_id'], $rec_id, $details['file_id'],$sen, $status);
			
		
			if($new_trans->execute()){
				return 1;
			}
		}
	}
	function return_file(){
		extract($_POST);

		$update = $this->db->query("UPDATE transactions SET receiver_notes = '$rec_note',status = 2,receiver_timestamp = current_timestamp() WHERE reference_number = '$ref'");
		if($update){
			//create a new transaction for returned file
			$details = $this->db->query("SELECT * FROM transactions WHERE reference_number = '$ref'")->fetch_assoc(); //key-value pair
			$status = 1;
			
			//generate unique reference number
			$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$ref_no =  '';
			// Loop until a unique code is generated
			do {
				$ref_no = substr(str_shuffle($chars), 0, 10);
				$ref_no = 'RET-'.$ref_no;
				$chk = $this->db->query("SELECT * FROM transactions WHERE reference_number = '$ref_no'")->num_rows;
			} while ($chk > 0);
	
			$new_trans = $this->db->prepare("INSERT INTO transactions (reference_number,sender_id,recipient_id,file_id,sender_notes,status) VALUES (?,?,?,?,?,?)");
			$new_trans->bind_param("sssssi",$ref_no,$details['recipient_id'], $details['sender_id'], $details['file_id'],$details['receiver_notes'], $status);
			
		
			if($new_trans->execute()){
				return 1;
			}
		}
	}

}