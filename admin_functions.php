<?php
require_once('connect.php');

  function displayFilms($pdo){
    echo "<div class='container' id='tab_content'>";
    echo "<h3>Films Table</h3>";

    // Query
    // Pulls records from films table and appends data from relevant tables
    $sql = 'SELECT film.*, category.category_name, rating.rating_name
    FROM film
    INNER JOIN category ON film.category_id = category.category_id
    INNER JOIN rating ON film.rating_id = rating.rating_id
    ORDER BY film.film_id ASC';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Saves all results in object
    $films = $stmt->fetchAll();

    ?>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th>Film ID</th>
          <th>Film Title</th>
          <th>Category</th>
          <th>Rating</th>
          <th>Price per day</th>
        </tr>
      </thead>
      <tbody>
      <?php
      // Checks if there is at least 1 result from database query
      if($films) {
        // For each loop to display all filmss
        foreach($films as $film){
          // Result is displayed using HTML elements
          echo "<tr>";
          echo "<td>". $film->film_id ."</td>";
          echo "<td>". $film->film_title ."</td>";
          echo "<td>". $film->category_name ."</td>";
          echo "<td>". $film->rating_name ."</td>";
          echo "<td>£". $film->film_rental_price_per_day ."</td>";
          echo "</tr>";
        }
      }
      echo "</tbody>";
      echo "</table>";
      echo "<a href='create_film.php' class='btn btn-primary btn-block' role='button'>New film</a>";
      echo "</div>";
    }

  function displayRentals($pdo){
    echo "<div class='container' id='tab_content'>";
    echo "<h3 class='table_title'>Rentals Table</h3>";

    // Query
    // Pulls records from rentals table and appends data from relevant tables
    $sql = 'SELECT rental.rental_id, rental.rental_date, rental.rental_return_date, film.film_title, customer.customer_title, customer.customer_first_name, customer.customer_last_name, staff.staff_first_name, staff.staff_last_name, 
    @Rental_Duration := DATEDIFF(rental.rental_return_date, rental.rental_date) AS "rental_duration",
    @Rental_Duration * film.film_rental_price_per_day AS "rental_price"
    FROM rental
    INNER JOIN stock ON rental.stock_id = stock.stock_id
    INNER JOIN film ON stock.film_id = film.film_id
    INNER JOIN customer ON rental.customer_id = customer.customer_id
    INNER JOIN staff ON rental.staff_id = staff.staff_id;';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Saves all results in object
    $rentals = $stmt->fetchAll();

    ?>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th>Rental ID</th>
          <th>Film Title</th>
          <th>Rental Date</th>
          <th>Return Date</th>
          <th>Customer</th>
          <th>Staff</th>
          <th>Rental Duration</th>
          <th>Total Price</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody>
      <?php
      // Checks if there is at least 1 result from database query
      if($rentals) {
        // For each loop to display all rentals
        foreach($rentals as $rental){
          // Result is displayed using HTML elements
          echo "<tr>";
          echo "<td>". $rental->rental_id ."</td>";
          echo "<td>". $rental->film_title ."</td>";
          echo "<td>". $rental->rental_date ."</td>";
          echo "<td>". $rental->rental_return_date ."</td>";
          echo "<td>". $rental->customer_title . " " . $rental->customer_first_name . " " . $rental->customer_last_name ."</td>";
          echo "<td>". $rental->staff_first_name . " " . $rental->staff_last_name ."</td>";
          echo "<td>". $rental->rental_duration ." Days</td>";
          echo "<td>£". number_format($rental->rental_price, 2, '.', '') ."</td>";
          echo "<td><a href='admin.php?delete=". $rental->rental_id ."' class='btn btn-danger' role='button'>X</a></td>";
          echo "</tr>";
        }
      }
      echo "</tbody>";
      echo "</table>";
      echo "</div>";
    }

  function displayStock($pdo){
    echo "<div class='container' id='tab_content'>";
    echo "<h3>Stock Table (without current rentals)</h3>";
  
    // Query
    // Pulls records from stock table that aren't currently in the rentals table
    $sql = 'SELECT stock.stock_id, film.film_title, category.category_name, rating.rating_name, supplier.supplier_name
    FROM stock
    INNER JOIN film ON stock.film_id = film.film_id
    INNER JOIN supplier ON stock.supplier_id = supplier.supplier_id
    INNER JOIN category ON film.category_id = category.category_id
    INNER JOIN rating ON film.rating_id = rating.rating_id
    WHERE NOT EXISTS (SELECT stock_id FROM rental WHERE stock.stock_id = rental.stock_id)';
  
    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Saves all results in object
    $stocks = $stmt->fetchAll();
  
    ?>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th>Stock ID</th>
          <th>Film Title</th>
          <th>Category</th>
          <th>Rating</th>
          <th>Supplier</th>
        </tr>
      </thead>
      <tbody>
      <?php
      // Checks if there is at least 1 result from database query
      if($stocks) {
        // For each loop to display all results
        foreach($stocks as $stock){
          // Result is displayed using HTML elements
          echo "<tr>";
          echo "<td>". $stock->stock_id ."</td>";
          echo "<td>". $stock->film_title ."</td>";
          echo "<td>". $stock->category_name ."</td>";
          echo "<td>". $stock->rating_name ."</td>";
          echo "<td>". $stock->supplier_name ."</td>";
          echo "</tr>";
        }
      }
      echo "</tbody>";
      echo "</table>";
      echo "</div>";
    }

  function displayStaff($pdo){
    echo "<div class='container' id='tab_content'>";
    echo "<h3>Staff Table</h3>";
  
    // Query
    // Pulls records from staff table
    $sql = "SELECT staff_id, staff_first_name, staff_last_name, CONCAT_WS(', ', staff_street_number, staff_street, staff_town, staff_county, staff_postcode) AS 'staff_address', staff_email, staff_mobile, staff_start_date, staff_position
    FROM staff";
  
    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Saves all results in object
    $staff_members = $stmt->fetchAll();
  
    ?>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th>Staff ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Position</th>
          <th>Address</th>
          <th>Email</th>
          <th>Mobile</th>
          <th>Start Date</th>
        </tr>
      </thead>
      <tbody>
      <?php
      // Checks if there is at least 1 result from database query
      if($staff_members) {
        // For each loop to display all staff
        foreach($staff_members as $staff_member){
          // Result is displayed using HTML elements
          echo "<tr>";
          echo "<td>". $staff_member->staff_id ."</td>";
          echo "<td>". $staff_member->staff_first_name ."</td>";
          echo "<td>". $staff_member->staff_last_name ."</td>";
          echo "<td>". $staff_member->staff_position ."</td>";
          echo "<td>". $staff_member->staff_address ."</td>";
          echo "<td>". $staff_member->staff_email ."</td>";
          echo "<td>". $staff_member->staff_mobile ."</td>";
          echo "<td>". $staff_member->staff_start_date ."</td>";
          echo "</tr>";
        }
      }
      echo "</tbody>";
      echo "</table>";
      echo "</div>";
    }

  function displayAllContacts($pdo){
    echo "<div class='container' id='tab_content'>";
    echo "<h3>All Contacts Table</h3>";
  
    // Query
    // Pulls records from contacts table
    $sql = 'SELECT * FROM view_all_contacts';
  
    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Saves all results in object
    $contacts = $stmt->fetchAll();
  
    ?>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th>Contact Type</th>
          <th>Name</th>
          <th>Address</th>
          <th>Email</th>
          <th>Mobile</th>
        </tr>
      </thead>
      <tbody>
      <?php
      // Checks if there is at least 1 result from database query
      if($contacts) {
        // For each loop to display all contacts
        foreach($contacts as $contact){
          // Result is displayed using HTML elements
          echo "<tr>";
          echo "<td>". $contact->contact_type ."</td>";
          echo "<td>". $contact->name ."</td>";
          echo "<td>". $contact->address ."</td>";
          echo "<td>". $contact->email ."</td>";
          echo "<td>". $contact->mobile ."</td>";
          echo "</tr>";
        }
      }
      echo "</tbody>";
      echo "</table>";
      echo "</div>";
    }

  function displayOverview($pdo){
    echo "<div class='container' id='tab_content'>";
    echo "<h3>Overview</h3>";
    echo "<div class='row' id='overview'>";
    // echo "<div class='card-deck'>";
  
    // Current Stock Query
    // Pulls count of records from stock table
    $sql = 'SELECT COUNT(stock_id) AS "count" FROM stock';
  
    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Saves all results in object
    $result = $stmt->fetch();
  
    ?>
    <div class="col-sm">
    <div class="card bg-light text-center" style="max-width: 18rem;">
      <div class="card-header"><h4>Current Stock</h4></div>
      <div class="card-body">
      <?php
      // Checks if there is at least 1 result from database query
      if($result) {
        // Result is displayed using HTML elements
        echo '<h4 class="card-content">'. $result->count .'</h4>';
      }
      echo "</div>";
      echo "</div>";
      echo "</div>";

    ////////////////////////////////////////////////////////////////////////////////

    // Current Rental Query
    // Pulls count of records from rental table
    $sql = 'SELECT COUNT(rental_id) AS "count" FROM rental';
  
    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Saves all results in object
    $result = $stmt->fetch();
  
    ?>
    <div class="col-sm">
    <div class="card bg-light text-center" style="max-width: 18rem;">
      <div class="card-header"><h4>Current Rentals</h4></div>
      <div class="card-body">
      <?php
      // Checks if there is at least 1 result from database query
      if($result) {
        // Result is displayed using HTML elements
        echo '<h4 class="card-content">'. $result->count .'</h4>';
      }
      echo "</div>";
      echo "</div>";
      echo "</div>";

    ////////////////////////////////////////////////////////////////////////////////

    // Current Avg Rental Duration Query
    // Pulls the average rental duration from the rental table
    $sql = 'SELECT AVG(DATEDIFF(rental.rental_return_date, rental.rental_date)) AS "average" FROM rental';
  
    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Saves all results in object
    $result = $stmt->fetch();
  
    ?>
    <div class="col-sm">
    <div class="card bg-light text-center" style="max-width: 18rem;">
      <div class="card-header"><h5>Average Rental Duration</h5></div>
      <div class="card-body">
      <?php
      // Checks if there is at least 1 result from database query
      if($result) {
        // Result is displayed using HTML elements
        echo '<h4 class="card-content">'. $result->average .' Days</h4>';
      }
      echo "</div>";
      echo "</div>";
      echo "</div>";


      echo "</div>";
      echo "</div>";
      echo "</div>";
      echo "</div>";
    }

  function deleteRental($rental_id, $pdo){
    // Query
    // If a record in the rental table is found matching the given rental id then it is deleted.
    // This is limited to one result as there should only be one rental per rental id.
    $sql = 'DELETE FROM rental
    WHERE rental_id = ?
    LIMIT ?';
  
    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$rental_id, 1]);
  
    // Redirects user back to Rentals table in the admin area
    redirect('admin.php?view=rentals');
  }

  function displayCustomers($pdo){
    echo "<div class='container' id='tab_content'>";
    echo "<h3>Customers Table</h3>";
  
    // Query
    // Pulls records from customers table
    $sql = "SELECT *, CONCAT_WS(', ', customer_street_number, customer_street, customer_town, customer_county, customer_postcode) AS 'customer_address'
    FROM customer";
  
    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    // Saves all results in object
    $customers = $stmt->fetchAll();
  
    ?>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th>Customer ID</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Address</th>
          <th>Email</th>
          <th>Mobile</th>
          <th>Rentals</th>
        </tr>
      </thead>
      <tbody>
      <?php
      // Checks if there is at least 1 result from database query
      if($customers) {
        // For each loop to display all customers
        foreach($customers as $customer){
          // Result is displayed using HTML elements
          echo "<tr>";
          echo "<td>". $customer->customer_id ."</td>";
          echo "<td>". $customer->customer_first_name ."</td>";
          echo "<td>". $customer->customer_last_name ."</td>";
          echo "<td>". $customer->customer_address ."</td>";
          echo "<td>". $customer->customer_email ."</td>";
          echo "<td>". $customer->customer_mobile ."</td>";
          echo "<td><a href='admin.php?view_films_by_cust=". $customer->customer_id ."' class='btn btn-info' role='button'>View Rentals</a></td>";
          echo "</tr>";
        }
      }
      echo "</tbody>";
      echo "</table>";
      echo "</div>";
    }
  
  function displayRentalsByCustomer($customer_id, $pdo){
    echo "<div class='container' id='tab_content'>";
    echo "<td><a href='admin.php?view=customers' class='btn btn-info' role='button'>Back</a></td>";
    echo "<h3 class='table_title'>". $customer_id . "'s Rentals</h3>";

    // Query
    $sql = 'SELECT * FROM film
    INNER JOIN category ON film.category_id = category.category_id
    INNER JOIN rating ON film.rating_id = rating.rating_id
    WHERE film_id IN (
        SELECT film_id
        FROM stock
        WHERE stock_id IN (
            SELECT stock_id
            FROM rental
            WHERE customer_id = ?
        )
    );';

    // Prepare and execute statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$customer_id]);
    // Saves all results in object
    $films = $stmt->fetchAll();

    ?>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th>Film ID</th>
          <th>Film Title</th>
          <th>Category</th>
          <th>Rating</th>
        </tr>
      </thead>
      <tbody>
      <?php
      // Checks if there is at least 1 result from database query
      if($films) {
        // For each loop to display all films
        foreach($films as $film){
          // Result is displayed using HTML elements
          echo "<tr>";
          echo "<td>". $film->film_id ."</td>";
          echo "<td>". $film->film_title ."</td>";
          echo "<td>". $film->category_name ."</td>";
          echo "<td>". $film->rating_name ."</td>";
          echo "</tr>";
        }
      }else{
        echo "<h4>This customer has no films currently outstanding.</h4>";
      }
      echo "</tbody>";
      echo "</table>";
      echo "</div>";
  }


?>