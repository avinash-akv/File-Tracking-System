<!DOCTYPE html>
<html lang="en">

<?php
session_start();
include('./db_connect.php');
$qry = $conn->query("select id from users where email='" . $_GET['email'] . "' and active = 1")->fetch_assoc();
$user_id = $qry['id'];


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
                <a href="#"><b>FTS-KNIT | Reset Password</b></a>
            </div>

            <div class="card">
                <div class="card-body login-card-body">
                    <form action="" id="password-form">

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="old_password" id="old_password" required placeholder="old password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id ?>">
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
        $(document).ready(function() {
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
                            url: 'ajax.php?action=save_password',
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
                                } else if (resp == 2) {
                                    $('#password-form').prepend('<div class="alert alert-danger">Old Password is not correct.</div>')
                                    end_load();
                                } else {
                                    $('#password-form').prepend('<div class="alert alert-danger">Some Error occured.</div>')
                                    end_load();
                                }
                            }
                        })
                    }//end else
                })
            })
    </script>
    <?php include 'footer.php' ?>

</body>

</html>