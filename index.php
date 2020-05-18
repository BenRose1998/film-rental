<?php
require_once('connect.php');
$header = "Home Page";
include('header.php');

if (isset($_SESSION['staff_id'])){
  redirect('admin.php');
}

$error = null;
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
    $sql = 'SELECT * FROM staff
    WHERE staff_email = ?
    LIMIT ?';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, 1]);
    // Saves result in object
    $user = $stmt->fetch();

    // Only checks for password if a record is found in database
    if($user){
      // Inputted password is checked against password stored in database
      if(password_verify($password, $user->staff_password)){
        // If password is correct information on user is stored in the session
        $_SESSION['staff_id'] = $user->staff_id;
        $_SESSION['first_name'] = $user->staff_first_name;
        $_SESSION['last_name'] = $user->staff_last_name;
        $_SESSION['email'] = $email;
        redirect('admin.php');
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

<!-- Main Body -->
  <!-- Container so everything is wrapped up -->
  <link rel="stylesheet" href="css/index.css">
  <div class="container" id="index-main">
    <form class="form-signin text-center" action="index.php" method="POST">
    <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
    <label for="inputEmail" class="sr-only">Email address</label>
    <input type="email" id="email" name="email" class="form-control" placeholder="Email address" required autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    <a class="btn btn-lg btn-secondary btn-block" href="register.php" role="button">Register</a>
    </form>
  <!-- End of main body div -->
  </div>

<?php
// added footer to bottom of page
include('footer.php');
?>
