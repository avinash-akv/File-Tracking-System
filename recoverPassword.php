<!DOCTYPE html>
<html lang="en">

<?php
session_start();
include('./db_connect.php');

if (isset($_SESSION['login_id'])) {
    header("location:index.php?page=home");
}

include 'header.php';

?>

<body class="hold-transition login-pages">
    <div class="container-fluid w-full h-6 p-4 fixed-top z-10" style="background-color: #fff;">
        <img src="assets/logo (1).png" alt="logo">
    </div>
    <div class="login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="#"><b>FTS-KNIT | recover password</b></a>
            </div>

            <div class="card">
                <div class="card-body login-card-body">
                    <form action="" id="send-otp">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="email" id="email" required placeholder="email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                 </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block" id="send_otp_btn">Send OTP</button>
                            </div>
                            <div class="col-8 resend-timer" style="display:none;color:black;">
                                resend in <span>10</span> s..
                            </div>
                        </div>
                    </form>
                    <br>
                    <!-- <form action="" id="verify-otp"> -->
                        <div class="input-group mb-3 otp-form">
                            <input type="number" class="form-control" name="otp" id="otp" required placeholder="enter otp">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row otp-form">
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block" id="verify_otp_btn" disabled>Verify OTP</button>
                            </div>
                        </div>
                    <!-- </form> -->
                </div>

                <div class="card-body login-card-body" >
                    <form action="" id="password-form" style="display: none;">

                        <div class="input-group mb-3">
                            <input type="password" class="form-control" id="_password" name="_password" required placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="confirm_password" id="confirm_password" required placeholder="Confirm Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Save</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Function to start the resend OTP timer
        function startResendOTPTimer(seconds) {
            var timerInterval = setInterval(function() {
                $('#send_otp_btn').prop('disabled',true);
                $('.resend-timer').show().find('span').text(seconds);
                seconds--;
                if (seconds < 0) {
                    clearInterval(timerInterval);
                    $('#send_otp_btn').prop('disabled',false);
                    $('.resend-timer').hide();
                }
            }, 1000);
        }

        $(document).ready(function() {
            //open correct forms if page is reloded accidently
            
            //verifying email address and sending otp on registered mail
            $('#send-otp').submit(function(e){
                e.preventDefault();
                start_load();
                if ($(this).find('.alert-danger').length > 0)
                    $(this).find('.alert-danger').remove();

                $.ajax({
                    url:'mailer.php?action=send_otp',
                    method:'POST',
                    data:$(this).serialize(),
                    error: err=>{
                        console.log(err); 
                        end_load();
                    },
                    success:function(resp){
                        if(resp == 0){
                            //otp sent
                            alert_toast("OTP sent successfully", 'success');
                            $('#verify_otp_btn').prop('disabled',false);
                            startResendOTPTimer(10);
                        }else if(resp == 1){
                            //email is not registered
                            $('#send-otp').prepend('<div class="alert alert-danger">Email is not registered.</div>');
                        }else if(resp == 2){
                            //new user
                            alert_toast('You first need to set your password',"error");
                            setTimeout(function(){
                                location.href = 'setPassword.php?email='+$('#email').val();
                            },2000)
                        }else{
                            //system error
                            $('#send-otp').prepend('<div class="alert alert-danger">Some error occured.</div>');
                        }
                        end_load();
                    }
                })
            })
            

            //verifying otp
            $('#verify_otp_btn').click(function(e){
                // e.preventDefault();
                start_load();
                if ($(this).find('.alert-danger').length > 0)
                    $(this).find('.alert-danger').remove();

                $.ajax({
                    url:'mailer.php?action=verify_otp',
                    method:'POST',
                    data: {otp : $("#otp").val()},
                    error:err=>{
                        console.log(err);
                        end_load();
                    },
                    success:function(resp){
                        // console.log(resp);
                        if(resp == 0){
                            //otp verified
                            alert_toast("OTP verified successfully", 'success');
                            $('#send-otp').css('display','none');
                            $('.otp-form').css('display','none');
                            $('#password-form').show();
                        }else{
                            //not -verifed
                            $('#send-otp').prepend('<div class="alert alert-danger">OTP not verified.</div>');
                        }
                        end_load();
                    }
                })
            })

            $('#password-form').submit(function(e) {
                e.preventDefault();
                start_load();
                if ($(this).find('.alert-danger').length > 0)
                    $(this).find('.alert-danger').remove();


                var password = document.getElementById("_password").value;
                var confirmPassword = document.getElementById("confirm_password").value;

                if (password !== confirmPassword) {
                    $('#password-form').prepend('<div class="alert alert-danger">Password fields do not match.</div>');
                    e.preventDefault(); // Prevent form submission
                    end_load();
                } else {
                    //save to database
                    $.ajax({
                        url: 'mailer.php?action=reset_password',
                        method: 'POST',
                        data: $(this).serialize(),
                        error: err => {
                            console.log(err)
                            end_load();
                        },
                        success: function(resp) {
                            if (resp == 0) {
                                alert_toast("Password successfully updated", 'success')
                                setTimeout(function() {
                                    location.reload()
                                }, 2000)
                                location.href = 'login.php';
                            } else {
                                $('#password-form').prepend('<div class="alert alert-danger">Some Error occured.</div>')
                                end_load();
                            }
                        }
                    })
                } //end else
            })
        })
    </script>
    <?php include 'footer.php' ?>

</body>

</html>