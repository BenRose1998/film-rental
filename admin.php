<?php
require_once('connect.php');
$header = "Admin Area";
include('header.php');

  // Checks if the user is not logged in and if so redirects them
  if (!isset($_SESSION['staff_id'])){
    redirect('index.php');
  }

  // Admin functions are loaded in and the page is loaded
  include('admin_functions.php');

  // If toggle user button is pressed and user isn't trying to change the administrator's employee status
  if (isset($_GET['toggle']) && $_GET['toggle'] != 1){
    // Toggle user function is called and sent the user id (casted value to int for security)
    toggleUser((int)$_GET['toggle'], $pdo);
  }

  // If delete film button is pressed
  if (isset($_GET['delete'])){
    // Delete rental function is called and sent rental id
    deleteRental($_GET['delete'], $pdo);
  }
  
  ?>
  <div class='container' id='main'>
    <h1><?php echo $header ?></h1>
    <ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link" href="admin.php?view=overview">Overview</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin.php?view=films">Films</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin.php?view=stock">Stock</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin.php?view=rentals">Rentals</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin.php?view=customers">Customers</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin.php?view=staff">Staff</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin.php?view=contacts">All Contacts</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Log out</a>
      </li>
    </ul>

  <?php

  // If view films button is pressed
  if (isset($_GET['view_films_by_cust'])){
    // Delete displayRentalsByCustomer function is called and sent customer id
    displayRentalsByCustomer($_GET['view_films_by_cust'], $pdo);
  }else{
    // If table is requested
    if (isset($_GET['view'])){
      // Calls necessary function depending on which table is requested
      switch ($_GET['view']){
        case "films":
          displayFilms($pdo);
          break;
        case "stock":
          displayStock($pdo);
          break;
        case "rentals":
          displayRentals($pdo);
          break;
        case "customers":
          displayCustomers($pdo);
          break;
        case "staff":
          displayStaff($pdo);
          break;
        case "contacts":
          displayAllContacts($pdo);
          break;
        default:
          displayOverview($pdo);
      }
    }else{
      displayOverview($pdo);
    }
  }
  ?>
</div>
<?php
// added footer to bottom of page
include('footer.php');
?>
