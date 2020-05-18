<?php
require_once('connect.php');
$header = "Login";
include('header.php');

$error = null;

// if (isset($_GET['error'])){
//   if ($_GET['error'] == 1){
//     $error = 'You must login to purchase a car';
//   }
// }

// Checks if the form has been submitted
if (isset($_POST) && !empty($_POST)) {
  // Stores user inputs as variables to be used later
  $email = $_POST['email'];
  $password = $_POST['password'];
  // Checks if inputs are empty, if so sends an error
  if (empty($email) || empty($password)){
    $error = "Please fill in all information";
  }else{
    // // If inputs aren't empty the user's data is pulled from the database

    // Query
    // User's data is pulled from user table and their job title from employee table if they are in it
    $sql = 'SELECT users.user_id, users.first_name, users.email, users.password, employees.job_id, jobs.job_title FROM users
    LEFT JOIN employees ON employees.user_id = users.user_id
    LEFT JOIN jobs ON jobs.job_id = employees.job_id
    WHERE users.email = ?
    LIMIT ?';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, 1]);
    // Saves result in object
    $user = $stmt->fetch();

    // Only checks for password if a record is found in database
    if($user){
      // Inputted password is checked against password stored in database
      if(password_verify($password, $user->password)){
        // If password is correct information on user is stored in the session
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['first_name'] = $user->first_name;
        $_SESSION['email'] = $email;
        // If the user is an employee this is stored in the session and user is redirected to admin area
        if($user->job_title != null){
          $_SESSION['employee'] = true;
          redirect('admin.php');
        }else{
          // If the user is not an employee this is stored in the session and user is redirected to home page
          $_SESSION['employee'] = false;
          redirect('index.php');
        }
      }else{
        // If the password inputted by user did not match then an error is sent
        $error = "Invalid password";
      }
    }else{
      // If no record found with that email then an error is sent
      $error = "Invalid email";
    }
  }
}
?>

<div class="container" id="main">

  <!-- If an error is sent it is displayed -->
  <?php if($error != null): ?>
    <h3 class='error'><?php echo $error; ?></h3>
  <?php endif; ?>

  <form action="login.php" method="POST" id="loginForm">
    <div class="input">
      <span>Email: </span><input type="email" name="email">
    </div>
    <div class="input">
      <span>Password: </span><input type="password" name="password">
    </div>
    <div class="input">
      <button type="submit" name="button">Submit</button>
      <a href='register.php' class="registerBut"><button type='button'>Register</button></a>
    </div>
  </form>

  <?php
  // added footer to bottom of page
  include('footer.php');
  ?>
