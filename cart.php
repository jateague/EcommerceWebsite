<!doctype html>

<html lang="en">
<head>
        <title>405 Toys & Games - View Cart</title>
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
                   echo "<li role='presentation' class='active'><a href='cart.php'>View Cart</a></li>";
                   echo "<li role='presentation'><a href='custorders.php'>View Orders</a></li>";
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

        <h2> View Cart </h2>
        <p> Below are the items in your cart: </p>
<?php
                      $statement = $conn->prepare("SELECT MAX(order_num) FROM orders WHERE user_email=?");
                      $statement->bind_param("s", $user_email);
                      $statement->execute();
                      $statement->bind_result($max_ID);
                      $statement->fetch();
                      $statement->close();

                      if($max_ID){
                        $statement1 = $conn->prepare("SELECT O.id, I.name, ROUND(I.price - (I.price * (I.promo_discount / 100)),2), O.quantity FROM order_details O INNER JOIN items I ON I.prod_id=O.prod_id WHERE O.order_num=?");
                        $statement1->bind_param("i", $max_ID);
                        $statement1->bind_result($orderdetailsID, $product_name, $product_price, $product_quantity);
                        $statement1->execute();

                        echo "<table class = 'table table-striped'>";
                        echo "<tbody>";
                        echo "<tr>";
                        echo "<td><b>Product Name</b></td>";
                        echo "<td><b>Price</b></td>";
                        echo "<td><b>Quantity Purchased</b></td>";
                        echo "<td><b>Remove from Cart?</b></td>";
                        echo "</tbody>";
                        echo "</tr>";
                        echo "<tbody>";
                        $total_price = 0;
                        while($statement1->fetch()){
                           echo "<form method='post' action='remove_item.php'>";
                           echo "<tr>";
                           echo "<td class='span1'>{$product_name}</td>";
                           echo "<td class='col-sm-1'>{$product_price}</td>";
                           echo "<td class='col-sm-1'>{$product_quantity}</td>";
                           echo "<td><input class='btn btn-default' type='submit' value='Remove'></td>";
                           echo "</tr>";
                           echo "<input type='hidden' value='{$orderdetailsID}' name='orderdetailsID'>";
                           echo "</form>";
                           $total_price += $product_price*$product_quantity;
                        }
                        echo "</tbody>";
                        echo "</table>";
                        $statement1->close();
                        $formatted_price = number_format($total_price,2,".",",");
                        echo "<h3>Total: $ {$formatted_price} </h3>";
                        echo "<form method = 'post' action = 'place_order.php'>";
                        echo "<input type = 'hidden' value = '{$max_ID}' name = 'maxID'>";
                        echo "<input class='btn btn-default' type='submit' value='Purchase'>";
                        echo "</form>";

                     }
   ?>
                        </div>

</body>
</html>

