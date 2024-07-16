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
        <a href="#"><b>File Tracking System</b></a>
      </div>
      <!-- /.login-logo -->
      <div class="card">
        <div class="card-body login-card-body">
          <form action="" id="login-form">

            <div class="input-group mb-3">
              <input type="email" class="form-control" id="email" name="email" required placeholder="Email">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" class="form-control" name="password" required placeholder="Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-8">
                <div class="icheck-primary">
                  <a href="recoverPassword.php">forget password?</a>
                </div>
              </div>
              <!-- /.col -->
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
              </div>
              <!-- /.col -->
            </div>
          </form>

        </div>
        <!-- /.login-card-body -->
      </div>
    </div>
  </div>
  <!-- /.login-box -->
  <div class="info-icon" style="position: fixed; bottom: 7px; right: 10px;color:#007bff;font-size:4rem">
    <a href="about-us.php"><i class="fas fa-info-circle"></i></a>
  </div>

  <script>
    $(document).ready(function() {
      $('#login-form').submit(function(e) {
        e.preventDefault()
        start_load()
        if ($(this).find('.alert-danger').length > 0)
          $(this).find('.alert-danger').remove();

        $.ajax({
          url: 'ajax.php?action=login',
          method: 'POST',
          data: $(this).serialize(),
          error: err => {
            console.log(err)
            end_load();

          },
          success: function(resp) {
            if (resp == 1) {
              location.href = 'index.php?page=home';
            } else if (resp == 0) {
              //new user login
              var email = $("#email").val();
              location.href = 'setPassword.php?email=' + email;
            } else {
              //error
              $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
              end_load();
            }
          }
        })
      })
    })
  </script>
  <?php include 'footer.php' ?>

</body>

</html>