<?php
require_once('connect.php');
$header = "Register";
include('header.php');

$error = null;

// Checks if the form has been submitted
if (isset($_POST) && !empty($_POST)) {
  // Trims white space and stores user inputs as variables to be used later
  $first_name = trim($_POST['first_name']);
  $last_name = trim($_POST['last_name']);
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);
  $password2 = trim($_POST['password2']);
  // Checks if inputs are empty, if so sends an error
  if(empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($password2)){
    $error = "Please fill in all information";
  }else{
    // Checks if passwords don't match and if so sends an error
    if($password != $password2){
      $error = "Passwords do not match";
    }else{
      $error = "Passwords do match";
      // If inputs aren't empty and passwords match the user's data is inserted into the database
      // Encrypts password
      $password = password_hash($password, PASSWORD_DEFAULT);

      // Random 6 digit staff_id
      $staff_id = mt_rand(100000, 999999);

      // Query
      // User's data is inserted into the database
      $sql = 'INSERT INTO staff (staff_id, staff_first_name, staff_last_name, staff_email, staff_password) VALUES (:staff_id, :first_name, :last_name, :email, :password)';

      // Prepare and execute statement
      $stmt = $pdo->prepare($sql);
      $stmt->execute(['staff_id' => $staff_id, 'first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'password' => $password]);

      // Redirects user to login page
      redirect('index.php');
    }
  }
}
?>
<div class="container" id="main">

  <td><a href='index.php' class='btn btn-info' role='button'>Back</a></td>
  <h3>Register</h3>

  <!-- If an error is sent it is displayed -->
  <?php if($error != null){
    echo "<h3 class='error'>" . $error . "</h3>";
  }; ?>

  <form action="register.php" method="POST">
    <div class="form-group">
      <label for="firstname">First name</label>
      <input type="text" class="form-control" name="first_name" id="first_name" aria-describedby="emailHelp" placeholder="First name">
    </div>
    <div class="form-group">
      <label for="last_name">Last name</label>
      <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last name">
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" class="form-control" name="email" id="email" placeholder="Email">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" class="form-control" name="password" id="password" placeholder="Password">
    </div>
    <div class="form-group">
      <label for="password2">Repeat Password</label>
      <input type="password" class="form-control" name="password2" id="password2" placeholder="Repeat Password">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
  <?php
  // added footer to bottom of page
  include('footer.php');
  ?>
