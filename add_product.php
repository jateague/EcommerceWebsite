<?php
   include_once("./config.php");
   session_start();
   $user_role = $user_email = "";
   if(isset($_SESSION['email']) && !empty($_SESSION['email'])){
      $user_role = $_SESSION['user_role'];
      $user_email = $_SESSION['email'];
   }
   if($user_role === "manager" || $user_role === "staff"){
      $name = $price = $quantity = "";
      if($_SERVER["REQUEST_METHOD"] == "POST"){
         if(!empty($_POST["name"])){
            $name = test_input($_POST["name"]);
         }
         if(!empty($_POST["price"])){
            $price = test_input($_POST["price"]);
            if(preg_match("/^[0-9]{1,8}.[0-9]{2}$/", $price))
               $price = test_input($_POST["price"]);
         }
         if(!empty($_POST["quantity"])){
            $quantity = test_input($_POST["quantity"]);
            if(preg_match("/^[0-9]{5}$/", $quantity))
               $quantity = test_input($_POST["quantity"]);
         }
         $statement = $conn->prepare("INSERT INTO items (name, price, quantity) VALUES (?,?,?)");
         $statement->bind_param("sdi", $name, $price, $quantity);
         $statement->execute();
         $statement->close();
         }
   }
   header("Location:./manage_inventory.php");
?>
