<!doctype html>

<html lang="en">
<head>
        <meta charset="utf-8">
        <title>405 Toys & Games - Manage Inventory</title>
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

            if($user_role != 'manager')
            {
               header('Location:../OJ');
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
                     echo "<li role='presentation'><a href='custorders.php'>View Orders</a></li>";
                  }
                  else if ($user_role === "manager") {
                     echo "<li role='presentation'><a href='index.php'>Home</a></li>";
                     echo "<li role='presentation'><a href='manage_inventory.php'>Products</a></li>";
                     echo "<li role='presentation'><a href='allorders.php'>View All Orders</a></li>";
                     echo "<li role='presentation' class='active'><a href='sales_stats.php'>Sales Statistics</a></li>";
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

        <h2> Sales Statistics </h2>
        <p> Please select the time frame in which you would like to view. </p>
<?php
         echo "<form method='post' action = '" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
         echo "<input type='submit' class='btn btn-default' name='date' value = 'Week'>";
         echo "<input type='submit' class='btn btn-default' name='date' value = 'Month'>";
         echo "<input type='submit' class='btn btn-default' name='date' value = 'Year'>";
         echo "<p> Below is the history of sales: </p>";
         echo "</form>";

         if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $date = test_input($_POST["date"]);
            $current_date = date("Y-m-d");
            $past_date = "";

            if ($date === "Week"){
               $past_date = date("Y-m-d", strtotime("-1 week"));
              // $past_date = strtotime("-1 week");
            }
            if ($date === "Month"){
               $past_date = date("Y-m-d", strtotime("-1 months"));
               //$past_date = strtotime("-1 months");
            }
            if ($date === "Year"){
               $past_date = date("Y-m-d", strtotime("-1 year"));
               //$past_date = strtotime("-1 year");
            }

            $statement1 = $conn->prepare("SELECT total, order_num FROM orders WHERE date_time BETWEEN ? AND ?");
            $statement1->bind_param("ss", $past_date, $current_date);
            $statement1->execute();
            $statement1->bind_result($total, $order);
            $statement1->fetch();
            $statement1->close();
         }

         if($order){

            $statement = $conn->prepare("SELECT I.name, SUM(D.quantity), SUM(D.buying_price * D.quantity) FROM order_details D, items I WHERE I.prod_id=D.prod_id GROUP BY I.prod_id");
            $statement->bind_param();
            $statement->execute();
            $statement->bind_result($product_name, $total_quantity,  $total_revenue);

            echo "<table class = 'table table-striped'>";
            echo "<tbody>";
            echo "<tr>";
            echo "<td><b>Product Name</b></td>";
            echo "<td><b>Total Quantity Purchased</b></td>";
            echo "<td><b>Total Revenue</b></td>";
            echo "</tbody>";
            echo "</tr>";
            echo "<tbody>";
            $overall_total = 0;
            while($statement->fetch()){
               if($total_revenue > 0)
               {

                  echo "<tr>";
                  echo "<td class='span1'>{$product_name}</td>";
                  echo "<td class='col-sm-1'>{$total_quantity}</td>";
                  echo "<td class='col-sm-1'>{$total_revenue}</td>";
                  echo "</tr>";
                  $overall_total += $total_revenue;
               }
            }
            echo "</tbody>";
            echo "</table>";
            $statement->close();
            $formatted_total = number_format($overall_total,2,".",",");
            echo "<h4>Total: $ {$formatted_total} </h4>";
         }

?>
        </div>
</body>
</html>

