<!doctype html>

<html lang="en">
<head>
        <meta charset="utf-8">
        <title>405 Toys & Games - View Orders</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/stylesheet.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
</head>

<body>
         <?php
         include_once("./config.php");
         session_start();
         $user_role = $user_email ="";
         if(isset($_SESSION['email']) && !empty($_SESSION['email']))
         {
            $user_role = $_SESSION['user_role'];
            $user_email = $_SESSION['email'];
         }

         ?>

        <div class = "container registration">
        <h1><b> Welcome to 405 Toys & Games!</b></h1>
        <?php
        if($user_email != ""){
             echo "<div style='text-align:right;'>";
             echo "<p> You are logged in.</p>";
             echo "<a style='text-align:right;' class='btn btn-default' href='./logout.php' role='button'>Log Out</a>";
             echo "</div>";
        }
        ?>

        <?php
        echo "<nav>";
        echo "<ul class='nav nav-tabs'>";
        if($user_email != ""){
           if ($user_role === "customer"){
              echo "<li role='presentation'><a href='index.php'>Home</a></li>";
              echo "<li role='presentation'><a href='manage_inventory.php'>Products</a></li>";
              echo "<li role='presentation'><a href='cart.php'>View Cart</a></li>";
              echo "<li role='presentation' class='active'><a href='custorders.php'>View Orders</a></li>";
           }
           else if ($user_role === "manager") {
              echo "<li role='presentation'><a href='index.php'>Home</a></li>";
              echo "<li role='presentation'><a href='manage_inventory.php'>Products</a></li>";
              echo "<li role='presentation'><a href='allorders.php'>View All Orders</a></li>";
              echo "<li role='presentation'><a href='sales_stats.php'>Sales Statistics</a></li>";
           }
           else if($user_role === "staff"){
              echo "<li role='presentation'><a href='index.php'>Home</a></li>";
              echo "<li role='presentation'><a href='manage_inventory.php'>Products</a></li>";
              echo "<li role='presentation'><a href='allorders.php'>View All Orders</a></li>";
           }
        }
        else{
           echo "<li role='presentation'><a href='registration.php'>Customer Registration</a></li>";
           echo "<li role='presentation'><a href='signin.php'>Returning User Sign In</a></li>";
           echo "<li role='presentation'><a href='manage_inventory.php'>Products</a></li>";
        }
         echo "</ul>";
         echo "</nav>";
          ?>

        <h2> View Orders </h2>


<?php

         $statement = $conn->prepare("SELECT O.order_num, O.date_time, O.total, O.status, U.first_name, U.last_name, U.address, U.city, U.zipcode, U.state FROM orders O INNER JOIN users U ON U.email=O.user_email WHERE O.order_num<>(SELECT MAX(O2.order_num) FROM orders O2 WHERE O2.user_email=?) AND O.user_email=? ORDER BY O.order_num DESC");
         $statement->bind_param("ss", $user_email, $user_email);
         $statement->execute();
         $statement->bind_result($order_num, $date, $total, $status, $first_name, $last_name, $address, $city, $zipcode, $state);
         $order_nums = array();
         $dates = array();
         $totals = array();
         $statuses = array();
         $first_names = array();
         $last_names = array();
         $addresses = array();
         $cities = array();
         $zipcodes = array();
         $states = array();
         while($statement->fetch()){
            array_push($order_nums, $order_num);
            array_push($dates, $date);
            array_push($totals, $total);
            array_push($statuses, $status);
            array_push($first_names, $first_name);
            array_push($last_names, $last_name);
            array_push($addresses, $address);
            array_push($cities, $city);
            array_push($zipcodes, $zipcode);
            array_push($states, $state);
         }
         $statement->close();
         $detail_statement = $conn->prepare("SELECT I.name, D.buying_price, D.quantity from order_details D INNER JOIN items I on I.prod_id=D.prod_id WHERE D.order_num=?");
         for($i = 0; $i < count($order_nums); $i++){
            $detail_statement->bind_param("i", $order_nums[$i]);
            $detail_statement->execute();
            $detail_statement->bind_result($name, $buying_price, $quantity);
            echo "<div class='panel panel-default'>";
            echo "<div class='panel-heading'>Order Number: {$order_nums[$i]}</div>";
            echo "<div class='panel-body'>";
            echo "<p>Order Placed: {$dates[$i]}</p>";
            echo "<p>Total: {$totals[$i]}</p>";
            echo "<p>Ship to: {$first_names[$i]} {$last_names[$i]}, {$addresses[$i]}, {$cities[$i]}, {$states[$i]} {$zipcodes[$i]}</p>";
            echo "<p>Status: {$statuses[$i]}</p>";
            echo "</div>";
            echo "<table class='table table-striped'>";
            echo "<thead>";
            echo "<td><b>Product Name<b></td>";
            echo "<td><b>Quantity<b></td>";
            echo "<td><b>Price<b></td>";
            echo "</thead>";
            echo "<tbody>";
            while($detail_statement->fetch()){
               echo "<tr>";
               echo "<td>{$name}</td>";
               echo "<td>{$quantity}</td>";
               echo "<td>{$buying_price}</td>";
               echo "</tr>";
         }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
         }
            $detail_statement->close();

         if (count($order_nums) === 0){
            echo "<p>No orders</p>";
         }
?>

         </div>

</body>
</html>

