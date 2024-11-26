<?php
include '../database/db_connect.php';

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Prepare and execute
  $stmt = $conn->prepare("SELECT password FROM userdata WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($db_password);
    $stmt->fetch();

    if ($password === $db_password) {
      $message = "Login successful";
      $toastClass = "bg-success";
      // Start the session and redirect to the dashboard or home page
      session_start();
      $_SESSION['email'] = $email;
      header("Location: dashboard.php");
      exit();
    } else {
      $message = "Incorrect password";
      $toastClass = "bg-danger";
    }
  } else {
    $message = "Email not found";
    $toastClass = "bg-warning";
  }

  $stmt->close();
  $conn->close();
}
?>

<!DOCTYPE html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Sign In | Worker ID Management</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

  <!-- Boxicons -->
  <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="../assets/css/demo.css" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="../assets/vendor/libs/pace/minimal.css">

  <!-- Page CSS -->
  <link rel="stylesheet" href="../assets/vendor/css/pages/page-auth.css" />

  <!-- Helpers -->
  <script src="../assets/vendor/js/helpers.js"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="../assets/js/config.js"></script>
</head>

<body>
  <!-- Content -->
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Sign In Card -->
        <div class="card px-sm-6 px-0">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-6">
              <a href="login.php" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">TIP</span>
                <span class="app-brand-text demo text-heading">Worker ID</span>
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-1">Sign In</h4>
            <p class="mb-6">Enter your details to login your account.</p>

            <!-- ALert -->
            <?php if ($message): ?>
              <div id="alert-container" class="alert alert-danger text-center" role="alert" aria-live="assertive" aria-atomic="true">
                <span id="alert" class="text-danger">
                  <?php echo $message; ?>
                </span>
              </div>
            <?php endif; ?>
            <!-- /Alert -->

            <!-- Form -->
            <form id="formAuthentication" class="mb-6" method="POST">
              <!-- Username -->
              <div class="mb-6">
                <label for="username" class="form-label">Username:</label>
                <div class="input-group input-group-merge">
                  <span class="input-group-text"><i class="bx bx-user-circle"></i></span>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required autofocus />
                </div>
              </div>
              <!-- /Username -->

              <!-- Password -->
              <div class="mb-6 form-password-toggle">
                <label for="password" class="form-label">Password:</label>
                <div class="input-group input-group-merge">
                  <span class="input-group-text"><i class="bx bx-lock"></i></span>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required autofocus aria-describedby="password" />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide toggle-icon" id="toggle-icon"></i></span>
                </div>
              </div>
              <!-- /Password -->

              <!-- Remember Me & Forgot Password -->
              <div class="mb-8">
                <div class="d-flex justify-content-between mt-8">
                  <div class="form-check mb-0 ms-2">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me">Remember Me</label>
                  </div>
                  <a href="forgot-password.php">
                    <span>Forgot Password?</span>
                  </a>
                </div>
              </div>
              <!-- /Remember Me & Forgot Password -->

              <!-- Submit -->
              <div class="mb-6">
                <button class="btn btn-primary d-grid w-100" type="submit">Submit</button>
              </div>
              <!-- /Submit -->

            </form>
            <!-- /Form -->
            <p class="text-center">
              <span>Don't have an account?</span>
              <a href="register.php">
                <span>Sign up now!</span>
              </a>
            </p>
          </div>
        </div>
        <!-- Sign In Card -->
      </div>
    </div>
  </div>
  <!-- /Content -->

  <!-- Core JS -->
  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="../assets/vendor/js/menu.js"></script>

  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="../assets/vendor/libs/pace/pace.js"></script>

  <!-- Main JS -->
  <script src="../assets/js/main.js"></script>

  <!-- Page JS -->
  <script>
    function ShowAlert(message) {
      const alertContainer = document.getElementById('alert-container');
      const alertText = document.getElementById('alert');

      alertText.textContent = message;
      alertContainer.style.display = 'block';
    }

    function validateForm() {
      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value;
      if (!username || !password) {
        ShowAlert("All fields are required!");
        return false;
      }

      const alertContainer = document.getElementById('alert-container');
      if (alertContainer) alertContainer.style.display = 'none';
      return true;
    }

    document.getElementById('formAuthentication').addEventListener('submit', function(event) {
      if (!validateForm()) {
        event.preventDefault();
      }
    });

    const loginForm = document.getElementById('registration-form');
    if (loginForm) {
      loginForm.reset();
    }

    const togglePassword = document.querySelectorAll('.toggle-icon');
    togglePassword.forEach(function(icon) {
      icon.addEventListener('click', function() {
        icon.classList.toggle('bx-hide');
        icon.classList.toggle('bx-show');
      });
    });
  </script>
</body>

</html>