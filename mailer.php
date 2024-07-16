<?php
session_start();
ini_set('display_errors', 1); //display errors on screen 

require 'assets/PHPMailer/src/Exception.php';
require 'assets/PHPMailer/src/PHPMailer.php';
require 'assets/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
           
class MailerAction {
    private $db;
    
	public function __construct() {
   		include 'db_connect.php';
    	$this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	}

    function generateOTP() {
        return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    function send_otp() {
        extract($_POST);
        $user_id = "";

        //find user_id and email check
        $qry = $this->db->query("select id from users where email = '".$email."' and active = 1");
        if($qry->num_rows != 1){
            //email not registered 
            return 1;
        }else{
            //email is registered
            $qry = $qry->fetch_assoc();
            $user_id = $qry['id'];

            //check if first time login or not
            $qry = $this->db->query("select user_id from new_user where user_id = '".$user_id."'");
            if($qry->num_rows > 0){
                //new user
                //need to set his password first
                return 2;
            }else{
                //not a new user ---> prepare to send otp  ( handle otp resends)
                $otp = $this->generateOTP();


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
                $mail->Subject = 'Password Reset OTP'; 
                $mail->Body = "FTS - KNIT | Your OTP for password reset is: ".$otp;

                try{
                    if($mail->send() && $this->storeOTP($email,$otp)){
                        $_SESSION['recovery_email'] = $email;
                        return 0;
                    }else{
                        return 3;
                    }
                }catch(Exception $e){
                    return 3;
                }
            }
        }

        return 4;
    }

    function storeOTP($email, $otp) {
        $expiration = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        //check for otp resends
        $qry = $this->db->query("select * from password_reset where email = '".$email."'");
        if($qry->num_rows > 0){
            //otp resend
            $qry = $this->db->prepare("UPDATE password_reset SET otp = ? , expiration_time = ?"); 
            $qry->bind_param("ss", $otp, $expiration);
        }else{
            $qry = $this->db->prepare("INSERT INTO password_reset (email, otp, expiration_time) VALUES (?, ?, ?)");
            $qry->bind_param("sss", $email, $otp, $expiration);
        }

        return $qry->execute();
    }

    function verify_otp() {
        extract($_POST);
        $email =  $_SESSION['recovery_email'];

        $qry = $this->db->prepare("SELECT * FROM password_reset WHERE email = ? AND otp = ?");
        // $qry = $this->db->prepare("SELECT * FROM password_reset WHERE email = ? AND otp = ? AND expiration_time >= NOW()");
        $qry->bind_param("ss",$email, $otp);
        $qry->execute();
        $result = $qry->get_result();

        if($result->num_rows > 0){
            //otp matched
            $qry = $this->db->query("delete from password_reset WHERE email = '".$email."'");
            if($qry){
                return 0;
            }
            return 2;
        }else{
            return 101;
        }
    }

    function reset_password() {
        extract($_POST);
        $email = $_SESSION['recovery_email'];
        $password = md5($_password);
        $qry = $this->db->prepare("update users set password = ? where email = ? and active = 1");
        $qry->bind_param("ss",$password,$email);
        $qry->execute();
        
        if($qry->execute()){
            return 0;
        }
        return 1;
    }

   
}




$action = $_GET['action'];

$crud = new MailerAction();
if($action == 'send_otp'){
	$save = $crud->send_otp();
	if($save)
		echo $save;
}

if($action == 'verify_otp'){
	$save = $crud->verify_otp();
	if($save)
		echo $save;
}

if($action == 'reset_password'){
	$save = $crud->reset_password();
	if($save)
		echo $save;
}

