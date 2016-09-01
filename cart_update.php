<?php
   include_once("./config.php");
   session_start();
   if(!isset ($_SESSION['email']) || empty($_SESSION['email'])){
      header("Location:../OJ");
   }
   $email = $_SESSION['email'];
   $product_ID = test_input($_POST['product_id']);
   $product_quantity = test_input($_POST['product_quantity']);
   $statement = $conn->prepare("SELECT max(order_num) FROM orders WHERE user_email=?");
   $statement->bind_param("s", $email);
   $statement->execute();
   $statement->bind_result($ordernum);
   $statement->fetch();
   $statement->close();

   $statement1 = $conn->prepare("SELECT id FROM order_details WHERE order_num=? AND prod_id=?");
   $statement1->bind_param("ii", $ordernum, $product_ID);
   $statement1->execute();
   $statement1->bind_result($orderdetailsid);
   $statement1->fetch();
   $statement1->close();
   debug_to_console([$product_ID, $product_quantity, $ordernum, $orderdetailsID]);

   if($orderdetailsid){
      $statement2 = $conn->prepare("UPDATE order_details SET quantity=? WHERE id=?");
      $statement2->bind_param("ii", $product_quantity, $orderdetailsid);
      $statement2->execute();
      $statement2->close();
   }
   else{
      $statement2 = $conn->prepare("INSERT INTO order_details(prod_id, order_num, quantity) VALUES(?,?,?)");
      $statement2->bind_param("iii", $product_ID, $ordernum, $product_quantity);
      $statement2->execute();
      $statement2->close();
   }
   header("Location:./cart.php");

?>


