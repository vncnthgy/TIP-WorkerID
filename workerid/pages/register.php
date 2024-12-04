<?php
include '../database/db_connect.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST["name"]);
  $email = trim($_POST["email"]);
  $department = trim($_POST["department"]);
  $username = trim($_POST["username"]);
  $password = $_POST["password"];
  $confirmPassword = $_POST["cpassword"];

  if (empty($name) || empty($email) || empty($department) || empty($username) || empty($password) || empty($confirmPassword)) {
    $message = "All fields are required!";
  } elseif (strlen($username) < 6) {
    $message = "Username must be at least 6 characters long!";
  } elseif (strlen($password) < 8) {
    $message = "Password must be at least 8 characters long!";
  } elseif ($password !== $confirmPassword) {
    $message = "Passwords do not match!";
  } else {
    $queryCheck = $connection->prepare("SELECT email, username FROM users WHERE email = ? OR username = ?");
    $queryCheck->bind_param("ss", $email, $username);
    $queryCheck->execute();
    $queryCheck->store_result();

    if ($queryCheck->num_rows > 0) {
      $message = "Email or username already exists!";    
    } else {
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      $query = $connection->prepare("INSERT INTO users (name, email, department, username, password) VALUES (?, ?, ?, ?, ?)");
      $query->bind_param("sssss", $name, $email, $department, $username, $hashedPassword);

      if ($query->execute()) {
        echo '<script> alert("Successfully registered!"); window.location.href = "login.php"; </script>';
        exit();
      } else {
        $message = "Error: " . $query->error;
      }
      $query->close();
    }
    $queryCheck->close();
  }
  $connection->close();
}
?>

<!DOCTYPE html>

<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Sign Up | Worker ID Management</title>

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
        <!-- Sign Up Card -->
        <div class="card px-sm-6 px-0">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-6">
              <a href="register.php" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">TIP</span>
                <span class="app-brand-text demo text-heading">Worker ID</span>
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-1">Sign Up</h4>
            <p class="mb-6">Enter your details to register your account.</p>

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
              <!-- Name -->
              <div class="mb-6">
                <label for="name" class="form-label">Name:</label>
                <div class="input-group input-group-merge">
                  <span class="input-group-text"><i class="bx bx-user"></i></span>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required autofocus />
                </div>
              </div>
              <!-- /Name -->

              <!-- Email -->
              <div class="mb-6">
                <label for="email" class="form-label">Email:</label>
                <div class="input-group input-group-merge">
                  <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required autofocus />
                </div>
              </div>
              <!-- /Email -->

              <!-- Department -->
              <div class="mb-6">
                <label for="department" class="form-label">Department:</label>
                <div class="input-group input-group-merge">
                  <span class="input-group-text"><i class="bx bx-briefcase"></i></span>
                  <select type="text" class="form-select" id="department" name="department" required>
                    <option value="">Select your department</option>
                    <option value="1">ISD-IS1</option>
                    <option value="2">ISD-IS2</option>
                    <option value="3">ISD-IS3</option>
                    <option value="4">ISD-IS4</option>
                    <option value="5">ISD-IS5</option>
                  </select>
                </div>
              </div>
              <!-- /Department -->

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

              <!-- ConfirmPassword -->
              <div class="mb-6 form-password-toggle">
                <label for="cpassword" class="form-label">Confirm Password:</label>
                <div class="input-group input-group-merge">
                  <span class="input-group-text"><i class="bx bx-lock"></i></span>
                  <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Enter your password" required autofocus aria-describedby="password" />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide toggle-icon" id="toggle-icon"></i></span>
                </div>
              </div>
              <!-- /Confirm Password -->

              <!-- Submit -->
              <div class="mb-6">
                <button class="btn btn-primary d-grid w-100" type="submit">Submit</button>
              </div>
              <!-- /Submit -->

            </form>
            <!-- /Form -->
            <p class="text-center">
              <span>Already have an account?</span>
              <a href="login.php">
                <span>Sign in here!</span>
              </a>
            </p>
          </div>
        </div>
        <!-- /Sign Up Card -->
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
      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const department = document.getElementById('department').value.trim();
      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('cpassword').value;

      if (!name || !email || !department || !username || !password || !confirmPassword) {
        ShowAlert("All fields are required!");
        return false;
      }
      if (username.length < 6) {
        ShowAlert("Username must be at least 6 characters long!");
        return false;
      }
      if (password.length < 8) {
        ShowAlert("Password must be at least 8 characters long!");
        return false;
      }
      if (password !== confirmPassword) {
        ShowAlert("Passwords do not match!");
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

    const registrationForm = document.getElementById('registration-form');
    if (registrationForm) {
      registrationForm.reset();
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