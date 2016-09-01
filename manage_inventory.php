<!doctype html>

<html lang="en">
<head>
        <title>405 Toys & Games - View All Products</title>
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
                      echo "<li role='presentation' class='active'><a href='manage_inventory.php'>Products</a></li>";
                      echo "<li role='presentation'><a href='cart.php'>View Cart</a></li>";
                      echo "<li role='presentation'><a href='custorders.php'>View Orders</a></li>";
                   }
                   else if ($user_role === "manager") {
                      echo "<li role='presentation'><a href='index.php'>Home</a></li>";
                      echo "<li role='presentation' class='active'><a href='manage_inventory.php'>Products</a></li>";
                      echo "<li role='presentation'><a href='allorders.php'>View All Orders</a></li>";
                      echo "<li role='presentation'><a href='sales_stats.php'>Sales Statistics</a></li>";
                   }
                   else if($user_role === "staff"){
                      echo "<li role='presentation'><a href='index.php'>Home</a></li>";
                      echo "<li role='presentation' class='active'><a href='manage_inventory.php'>Products</a></li>";
                      echo "<li role='presentation'><a href='allorders.php'>View All Orders</a></li>";
                   }
              }
              else{
                   echo "<li role='presentation'><a href='registration.php'>Customer Registration</a></li>";
                   echo "<li role='presentation'><a href='signin.php'>Returning User Sign In</a></li>";
                   echo "<li role='presentation' class='active'><a href='manage_inventory.php'>Products</a></li>";
              }
              echo "</ul>";
              echo "</nav>";
         ?>
        <h2> Products </h2>
        <p> Below is the list of inventory: </p>
<?php
              $statement = $conn->prepare("SELECT prod_id, name, price, quantity, promo_discount FROM items ORDER BY name");
              $statement->execute();
              $statement->store_result();
              $statement->bind_result($prod_id, $name, $price, $quantity, $promo);
              echo "<table class = 'table table-striped table-hover'>";
              echo "<tbody>";
              echo "<tr>";
              echo "<td><b>Product ID</b></td>";
              echo "<td><b>Product Name</b></td>";
              echo "<td><b>Price</b></td>";
//              echo "<td><b>Discounted Price</b></td>";
//              echo "<td><b>Discount</b></td>";
              echo "<td><b>Quantity In Stock</b></td>";
              if($user_role === "customer"){
                  echo "<td><b>Quantity to Purchase?</b></td>";
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
                 if($promo > 0)
                 {
                    echo "<tr>";
                    echo "<td class='col-sm-1'>{$prod_id}</td>";
                    echo "<td class='span1'>{$name}</td>";
                    echo "<td class='col-sm-1'><strike style='color:red'><span style='color:black'>{$price}</span></strike>\n{$buying_price}\n{$promo}%\noff</td>";
                    echo "<td class='col-sm-1'>{$quantity}</td>";
                 }
                 else
                 {
                    echo "<tr>";
                    echo "<td class='col-sm-1'>{$prod_id}</td>";
                    echo "<td class='span1'>{$name}</td>";
                    echo "<td class='col-sm-1'>{$price}</td>";
                    echo "<td class='col-sm-1'>{$quantity}</td>";
                 }
                 if(($quantity > 0)&&( $user_role === "customer")){
                     echo "<td class='col-sm-1'><select class = 'form-control' name = 'product_quantity'>";
                     for($i = 1; $i <= $quantity && $i <= $quantity; $i += 1){
                     echo "<option value = '{$i}' > {$i}</option>";
                    }
                     echo "</select></td>";
                 }
                 else if(($quantity === 0)||($user_role === "customer")){
                  echo "<td class='span1'>Out of stock.</td>";
                 }
                 else if($user_role === "manager" || $user_role === "staff"){
                    echo "<td class='col-sm-1'><input type='text' name='prod_quantity' class='form-control' id='quantityUpdate' placeholder='+'/></td>";
                  }
                 echo "<input type = 'hidden' value = '{$prod_id}' name = 'product_id'>";

                 if($user_email != ""){
                    if(($quantity > 0)&&($user_role === "customer")){
                     echo "<td class='span1'><input type = 'submit' class = 'add_product' value = 'Add to Cart'></td>";
                    }
                    else if(($quantity < 0) && ($user_role === "customer")){
                     echo "<td class='span1'>Out of stock.</td>";
                    }
                    else if($user_role === "manager" || $user_role === "staff"){
                       if($user_role === "manager"){
                          echo "<td class='col-sm-1'><input type='text' name='promo' class='form-control' id='promotion' placeholder='%'/></td>";
                       }
                       echo "<td class='col-sm-1'><input type = 'submit' class= 'form-control' value = 'Update'></td>";
                    }
                    echo "</form>";
                 }
                 else{
                    // $alert = "Not logged in.";
                    // echo "<td><input type = 'submit' class = 'add_product' value = 'Add to Cart' onclick = 'alert($alert)'></td>";
                 }
                 echo "</tr>";
              }
              echo "</tbody>";
              $statement->close();
?>
        </div>
<?php
              if($user_role === "manager" || $user_role === "staff"){
                 echo "<button class='btn btn-primary btn-lg' data-toggle='modal' data-target='#myModal'> Add product </button>";
                 echo "<div class='modal fade' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>";
                 echo "<div class='modal-dialog'>";

                 echo "<div class='modal-content'>";
                 echo "<div class='modal-header'>";
                 echo "<button type='button' class='close' data-dismiss='modal'>";
                 echo "<span aria-hidden='true'>&times;</span>";
                 echo "<span class='sr-only'>Close</span>";
                 echo "</button>";
                 echo "<h4 class='modal-title' id='myModalLabel'> Add Product </h4>";
                 echo  "</div>"; //header

                 echo "<form method='post' class='form-vertical' role='form' action='add_product.php'>";
                 echo "<div class='modal-body'>";

                 echo "<div class='row'>";
                 echo "<label  class='col-sm-2 control-label' form='inputEmail3'>Name</label>";
                 echo "<div class='col-sm-4'>";
                 echo "<input type='text' name='name' class='form-control' id='inputEmail3' placeholder='Product Name'/>";
                 echo "</div>";
                 echo "</div>";

                 echo "<div class='row'>";
                 echo "<label class='col-sm-2 control-label'for='inputPassword3'>Price</label>";
                 echo "<div class='col-sm-4'>";
                 echo "<input type='number' name='price' min='0' max='99999999' step='0.01' class='form-control'id='inputPrice3' placeholder='Price'/>";
                 echo "</div>";
                 echo "</div>";

                 echo "<div class='row'>";
                 echo "<label class='col-sm-2 control-label' for='inputPassword3'>Quantity</label>";
                 echo "<div class='col-sm-4'>";
                 echo "<input type='number' name='quantity' min='0' max='999999' step='1' class='form-control' id='exampleInputPassword3' placeholder='Quantity'/>";
                 echo "</div>";
                 echo "</div>";

                 echo "</div>";

                 echo "<div class='modal-footer'>";
                 echo "<button type='button' class='btn btn-default' data-dismiss='modal'> Close </button>";
                 echo "<input type='submit' class='btn btn-primary' value='Add Product'>";
                 echo "</div>";
                 echo "</div>";
                 echo "</div>";
                 echo "</div>";
              }
?>

</body>
</html>

