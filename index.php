<!doctype html>

<html lang="en">
<head>
	<title>405 Toys & Games</title>
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
  	        echo "<li role='presentation' class='active'><a href='index.php'>Home</a></li>";
                echo "<li role='presentation'><a href='manage_inventory.php'>Products</a></li>";
                echo "<li role='presentation'><a href='cart.php'>View Cart</a></li>";
	        echo "<li role='presentation'><a href='custorders.php'>View Orders</a></li>";
            }
            else if ($user_role === "manager") {
                echo "<li role='presentation' class='active'><a href='index.php'>Home</a></li>";
                echo "<li role='presentation'><a href='manage_inventory.php'>Products</a></li>";
                echo "<li role='presentation'><a href='allorders.php'>View All Orders</a></li>";
                echo "<li role='presentation'><a href='sales_stats.php'>Sales Statistics</a></li>";
            }
            else if($user_role === "staff"){
                echo "<li role='presentation' class='active'><a href='index.php'>Home</a></li>";
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

       <form method = 'post' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>'>
         <div class='form-group col-sm-10'>
         <h2>Search</h2>
            <input type='search' name = 'search' class='form-control' id='SearchCriteria' placeholder='Search by Keywords'>
	  <input class='btn btn-default' type='submit' value='Search'>
	  </div>
	  </form>
<?php
            //         $search = "";
            if($_SERVER["REQUEST_METHOD"] == "POST"){
               $alert = "Must be logged in to purchase.";
               if(!empty($_POST["search"])){
                  $search = test_input($_POST["search"]);
                  $param = "%{$search}%";
                  $statement = $conn->prepare("SELECT prod_id, name, price, quantity, promo_discount FROM items WHERE name LIKE ?");
                  $statement->bind_param("s", $param);
                  $statement->execute();
                  $statement->store_result();
                  $statement->bind_result($prod_id, $name, $price, $quantity, $promo);
                  if($statement->num_rows() > 0){
                     echo "<table class = 'table table-striped table-hover' >";
                     echo "<tbody>";
                     echo "<tr>";
                     echo "<td><b>Product ID</td></b>";
                     echo "<td><b>Product Name</td></b>";
                     echo "<td><b>Price</td></b>";
//                     echo "<td><b>Discounted Price</td></b>";
                     //echo "<td><b>Discount</td></b>";
                     echo "<td><b>Quantity In Stock</b></td>";
                     if ($user_role === "customer"){
                        echo "<td><b>Select Quantity:</b></td>";
                        echo "<td><b>Add to Cart?</b></td>";
                     }
                     else if($user_role === "manager" || $user_role === "staff"){
                        echo "<td><b>Insert Quantity to Add</b></td>";
                        if($user_role === "manager"){
                             echo "<td><b>Insert Promotion Rate (Percent)</b></td>";
                        }
                        echo "<td><b>Make all updates?</b></td>";
                     }
                     echo "</tbody>";
                     echo "</tr>";
                     echo "<tbody>";

                     while($statement->fetch()){
                         if($user_role === "customer"){
                            echo "<form method = 'post' action = 'cart_update.php'>";
                         }
                         else if($user_role === "manager" || $user_role === "staff"){
                            echo "<form method = 'post' action = 'update_inventory.php'>";
                         }

                        $buying_price = number_format(ROUND($price - ($price * ($promo / 100)),2),2,".",",");
                        if($promo > 0){
                           echo "<tr>";
                           echo "<td class='col-sm-1'>{$prod_id}</td>";
                           echo "<td class='span1'>{$name}</td>";
                           echo "<td class='col-sm-1'><strike style='color:red'><span style='color:black'>{$price}</span></strike>\n{$buying_price}\n{$promo}%\noff</td>";
                           //echo "<td class='col-sm-1'>{$promo}%</td>";
                           echo "<td class='col-sm-1'>{$quantity}</td>";
                        }
                        else{
                           echo "<tr>";
                           echo "<td class='col-sm-1'>{$prod_id}</td>";
                           echo "<td class='span1'>{$name}</td>";
                           echo "<td class='col-sm-1'>{$price}</td>";
//                           echo "<td class='col-sm-1'>{$promo}%</td>";
                           echo "<td class='col-sm-1'>{$quantity}</td>";
                        }

                           echo "<form method = 'post' action = 'cart_update.php'>";
                        if(($quantity > 0)&&($user_role === "customer")){
                           echo "<td class='span1'><div><select class = 'form-control col-sm-1' name = 'product_quantity'></div>";
                           for($i = 1; $i <= $quantity && $i <= $quantity; $i += 1){
                              echo "<option value = '{$i}' > {$i}</option>";
                           }
                           echo "</select></td>";
                        }
                        else if(($quantity === 0) || ($user_role === "customer")){
                           echo "<td class='span1'>Out of stock.</td>";
                        }
                        else if($user_role === "manager" || $user_role === "staff"){
                           echo "<td class='col-sm-1'><input type='text' name='name' class='form-control' id='quantityUpdate' placeholder='+'/></td>";
                        }
                        echo "<input type = 'hidden' value = '{$prod_id}' name = 'product_id'>";

                        if($user_email != ""){
                           if(($quantity > 0)&&($user_role ==="customer")){
                               echo "<td class='span1'><input type = 'submit' class = 'add_product' value = 'Add to Cart'></td>";
                           }
                           else if(($quantity < 0) && ($user_role ==="customer")){
                               echo "<td class='span1'>Out of stock.</td>";
                           }
                           else if($user_role === "manager" || $user_role === "staff"){
                             if($user_role === "manager"){
                                echo "<td class='col-sm-1'><input type='text' name='name' class='form-control' id='promotion' placeholder='%'/></td>";
                                                                                                                          }
                             echo "<td class='col-sm-1'><input type = 'submit' class= 'form-control' value = 'Update'></td>";
                           }
                           echo "</form>";
                        }
                        else{
                          //echo "<td><input type = 'submit' class = 'add_product' value = 'Add to Cart' onclick = 'alert({$alert})'></td>";
                        }
                        echo "</tr>";
                     }
                        echo "</tbody>";
                  }

                  else{
                     echo "<table class = 'table table-striped'>";
                     echo "<tbody>";
                     echo "<tr>";
                     echo "<td>No results found.</td>";
                     echo "</tbody>";
                     echo "</tr>";
                  }
                  $statement->close();
               }
         }

   ?>
	</div>
</body>
</html>
