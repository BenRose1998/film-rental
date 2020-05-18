<?php
require_once('connect.php');
$header = "New Film";
include('header.php');

$error = null;

// Checks if the form has been submitted
if (isset($_POST) && !empty($_POST)) {
  // Trims white space and stores user inputs as variables to be used later
  $film_title = trim($_POST['film_title']);
  $category = trim($_POST['category']);
  $rating = trim($_POST['rating']);
  $price = trim($_POST['price']);
  // Checks if inputs are empty, if so sends an error
  if(empty($film_title) || empty($category) || empty($rating) || empty($price)){
    $error = "Please fill in all information";
  }else{
    // Random 6 digit film_id
    $film_id = mt_rand(100000, 999999);

    // Query
    // User's data is inserted into the database
    $sql = 'INSERT INTO film VALUES (:film_id, :category, :rating, :film_title, :price)';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['film_id' => $film_id, 'category' => $category, 'rating' => $rating, 'film_title' => $film_title, 'price' => $price]);

    // Redirects user to login page
    redirect('admin.php?view=films');
    }
  }

?>
<div class="container" id="main">

  <!-- If an error is sent it is displayed -->
  <?php if($error != null){
    echo "<h3 class='error'>" . $error . "</h3>";
  }; 
  
    // Categories Query
    // Pulls records from stock table that aren't currently in the rentals table
    $sql = 'SELECT * FROM category';
  
    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Saves all results in object
    $categories = $stmt->fetchAll();

    // Ratings Query
    // Pulls records from stock table that aren't currently in the rentals table
    $sql = 'SELECT * FROM rating';
  
    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Saves all results in object
    $ratings = $stmt->fetchAll();
  
  ?>
  <td><a href='admin.php?view=films' class='btn btn-info' role='button'>Back</a></td>
  <h3>New Film</h3>
  <form action="create_film.php" method="POST">
    <div class="form-group">
      <label>Film Title</label>
      <input type="text" class="form-control" name="film_title" id="film_title" placeholder="Film Title">
    </div>

    <div class="form-group">
      <label>Category</label>
      <select id="category" class="form-control" name="category">
        <option selected>Choose...</option>
      <?php 
          if($categories) {
            // For each loop to display all results
            foreach($categories as $category){
              echo "<option value='". $category->category_id ."'>". $category->category_name ."</option>";
            }
          }
        ?>
        </select>
    </div>

    <div class="form-group">
      <label>Rating</label>
      <select id="rating" class="form-control" name="rating">
        <option selected>Choose...</option>
        <?php 
          if($ratings) {
            // For each loop to display all results
            foreach($ratings as $rating){
              echo "<option value='". $rating->rating_id ."'>". $rating->rating_name ."</option>";
            }
          }
        ?>
      </select>
    </div>
    <div class="form-group">
      <label for="password">Rental price per day</label>
      <input type="text" class="form-control" name="price" id="price" placeholder="Price">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
  <?php
  // added footer to bottom of page
  include('footer.php');
  ?>
