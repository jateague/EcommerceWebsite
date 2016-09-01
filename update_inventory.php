<?php
   include_once("./config.php");
   session_start();
   if(!isset($_SESSION['email']) || empty($_SESSION['email']))
      header("Location:../OJ");

   if($user_role != 'manager')
   {
      header("Location:../OJ");
   }

   $user_email = $_SESSION['email'];
   $user_role = $_SESSION['user_role'];
   if($_SERVER["REQUEST_METHOD"] == "POST"){
      $prod_id = test_input($_POST['product_id']);
      $quantity = test_input($_POST['prod_quantity']);
      $promo = test_input($_POST['promo']);
      if($promo === "")
      {
         $promo = NULL;
      }
   }

   if($user_role === "staff"){
         $statement = $conn->prepare("UPDATE items SET quantity = (quantity + ?) WHERE prod_id = ?");
         $statement->bind_param("ii", $quantity, $prod_id);
         $statement->execute();
         $statement->fetch();
         $statement->close();
   }

   if($user_role === "manager"){
         $statement = $conn->prepare("UPDATE items SET quantity = (quantity + ?), promo_discount = IF( ? IS NULL, promo_discount, ?)  WHERE prod_id = ?");
         $statement->bind_param("iiii", $quantity, $promo, $promo, $prod_id);
         $statement->execute();
         $statement->fetch();
         $statement->close();
   }

   header("Location:./manage_inventory.php");
?>
